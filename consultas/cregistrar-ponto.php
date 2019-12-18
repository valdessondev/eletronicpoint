<?php
require_once("../config/conn.php");

$codigo_session = $_SESSION['CODIGO_FUNC'];

$data_atual = date("Y-m-d");
$hora_atual = date("H:i:s");

//consulta A TABELA FUNCIONÁRIO recuperar as informações de determinado funcionário
$query_consulta = "SELECT DISTINCT
                        TC.NM_TIPOCONTRATO AS tipocontrato,
                        TC.QTDREGISTROS AS quantidadeDeBatidas,
                        TC.RELATORIOPAUSAATIVA AS controlarPausa,
                        F.NOME AS nomeFuncionario,
                        F.CODIGO AS codidoFuncionario,
                        F.EMPRESA AS codeempresa,
                        F.TIPO_FUNCIONARIO AS codetipocontrato,
                        E.NM_EMPRESA AS nmempresa,
                        FC.NM_FUNCAO AS funcao
                    FROM
                        FUNCIONARIO AS F
                    INNER JOIN  
                        TIPOCONTRATO AS TC
                    ON 
                        TC.TIPOCONTRATO_ID = F.TIPO_FUNCIONARIO
                    INNER JOIN  
                        EMPRESAS AS E
                    ON	
                        E.EMPRESA_ID = F.EMPRESA
                    INNER JOIN 
                        FUNCOES AS FC
                    ON	
                        FC.FUNCAO_ID = F.FUNCAO
                    WHERE
                        F.CODIGO = '$codigo_session'
                    LIMIT 1";

$executa_query_consulta = mysqli_query($conn,$query_consulta);
$funcionario = mysqli_fetch_assoc($executa_query_consulta);
//-------------------------------------------------------------------------------------------

//Consulta a TABELA PARAMETROS para buscar a quantidade máxima de pessoas em pausa para aquela empresa e aquele tipo
$quey_consulta_parametros = "SELECT DISTINCT 
                                P.QTD_MAX_PAUSA AS quantidadeMaximaEmPausa,
                                P.MINUTOS AS tempoParaVerificacao,
                                P.TMP_MIN_PAUSE AS tempoMinEntrePausas
                            FROM 
                                PARAMETROS AS P
                            WHERE 
                                P.PARAMETRO_ID= 1
                            LIMIT 1";

$executa_query_parametros = mysqli_query($conn,$quey_consulta_parametros);
$tb_parametros = mysqli_fetch_assoc($executa_query_parametros);
//Recuperando a quantidade máxima de pessoas em pausa
$qtd_max_parametro = $tb_parametros["quantidadeMaximaEmPausa"];
//Recuperando o tempo mínimo entre as pausa
$tmpMinEntrePausas = $tb_parametros["tempoMinEntrePausas"];

$hora_verificacao = date('H:i', strtotime('- '.$tb_parametros['tempoParaVerificacao'].'minute', strtotime(date('H:i'))));
//-------------------------------------------------------------

//Consulta na TABELA REGISTROS para inserir as batidas de ponto
$query_consulta_registros = "SELECT DISTINCT
                                R.HORA_ENTRADA,
                                R.HORA_SAIDA_INTERVALO,
                                R.HORA_RETORNO_INTERVALO,
                                R.HORA_SAIDA,
                                R.HORA_SAIDA_PAUSA,
                                R.HORA_VOLTA_PAUSA,
                                R.DESCRICAO_PAUSA_ATIVA AS descricaoPausaAtiva,
                                R.HORA_PAUSA_ATIVA AS horaPausaAtiva,
                                R.HORA_PAUSA_INATIVA AS horaPausaInativa
                            FROM 
                                REGISTROS AS R
                            WHERE 
                                DATA = '$data_atual' 
                            AND 
                                CODIGO = '$codigo_session'
                            ORDER BY 
                                R.ID DESC
                            LIMIT 1";
$executa_query_registros = mysqli_query($conn,$query_consulta_registros);
$tb_registro = mysqli_fetch_assoc($executa_query_registros);
//---------------------------------------------------------------

//Consulta na TABELA REGISTROS para limitar a quantidade de pessoas em pausa----
$query = "SELECT DISTINCT
            *
            FROM
                REGISTROS AS R
            INNER JOIN 
                FUNCIONARIO AS F
            ON 
                R.CODIGO = F.CODIGO
            INNER JOIN 
                TIPOCONTRATO AS TC
            ON	
                TC.TIPOCONTRATO_ID = F.TIPO_FUNCIONARIO
            WHERE
                TC.TIPOCONTRATO_ID = '".$funcionario["codetipocontrato"]."'
            AND
                R.DATA = '$data_atual' 
            AND 
                F.EMPRESA = '".$funcionario["codeempresa"]."' 
            AND 
                R.PAUSA_ATIVA = '1'
            AND 
                HORA_PAUSA_ATIVA >= '$hora_verificacao'
            LIMIT 50 ";  
$exec_query = mysqli_query($conn,$query);
$total = mysqli_num_rows($exec_query);
mysqli_close($conn);

//verifica o tempo transcorrido desde a pausa/intervalo--------
list($horas, $minutos) = explode(':', date('H:i'));
$qtd_minutos_atual = ($horas * 60) + $minutos;

//Realizando os calculos do tempo em pausa
@list($horas, $minutos) = explode(':',$tb_registro['horaPausaAtiva']);
@$qtd_minutos_registro = ($horas * 60) + $minutos;
$tempoEmPausa = $qtd_minutos_atual - $qtd_minutos_registro;
$descricaoPausa = $tb_registro['descricaoPausaAtiva'];

//Realizando os calculos para verificar se o usuáro pode ou não bater a próxima pausa
@list($horas, $minutos) = explode(':',$tb_registro['horaPausaInativa']);
@$qtd_min_pausa_inativa = ($horas * 60) + $minutos;
$tempoDesdeAUltimaPausa = $qtd_minutos_atual - $qtd_min_pausa_inativa;

$data = new DateTime($tb_registro['horaPausaInativa']);	
$data->add(new DateInterval('PT'.$tb_parametros["tempoMinEntrePausas"].'M'));
$tbmPermitidoProxPausa = $data->format('H:i');
//-------------------fim-----------------

//---------------------------------------------------------------------

$botao_saida_primeira_pausa = '<button class="btn btn-lg btn-success btn-block" type="submit">Saida<br> Primeira Pausa</button>';
$botao_volta_primeira_pausa = '<button class="btn btn-lg btn-warning btn-block" type="submit">Volta<br> Primeira Pausa</button>';
$botao_saida_intervalo = '<button class="btn btn-lg btn-primary btn-block" type="submit">Saida<br> Intevalo</button>';
$botao_volta_intervalo = '<button class="btn btn-lg btn-danger btn-block" type="submit">Volta<br> do Intevalo</button>';
$botao_saida_ultima_pausa = '<button class="btn btn-lg btn-success btn-block" type="submit">Saida<br> Ultima Pausa</button>';
$botao_volta_ultima_pausa = '<button class="btn btn-lg btn-warning btn-block" type="submit">Volta<br> Ultima Pausa</button>';
$msg_limite_pausa = "<div class='alert alert-warning' role='alert' align='center'>
    <img src='images/alert.png' class='rounded mx-auto d-block' alt='Alerta'>
    Registro não permitido.<br>
    Maximo de pessoas em pausa/intervalo: $qtd_max_parametro
    </div>";
$botao_saida_primeira_pausa = '<button class="btn btn-lg btn-success btn-block" type="submit">Saida<br> Primeira Pausa</button>';
$botao_volta_primeira_pausa = '<button class="btn btn-lg btn-warning btn-block" type="submit">Volta<br> Primeira Pausa</button>';
$botao_saida_intervalo = '<button class="btn btn-lg btn-primary btn-block" type="submit">Saida<br> Intevalo</button>';
$botao_volta_intervalo = '<button class="btn btn-lg btn-danger btn-block" type="submit">Volta<br> do Intevalo</button>';
$botao_saida_ultima_pausa = '<button class="btn btn-lg btn-success btn-block" type="submit">Saida<br> Ultima Pausa</button>';
$botao_volta_ultima_pausa = '<button class="btn btn-lg btn-warning btn-block" type="submit">Volta<br> Ultima Pausa</button>';
$botao_inicio_expediente = '<button class="btn btn-lg btn-success btn-block" type="submit"> Entrada<br></button>';
$botao_saida_almoco = '<button class="btn btn-lg btn-warning btn-block" type="submit">Saida<br> Almoço</button>';
$botao_volta_almoco = '<button class="btn btn-lg btn-danger btn-block" type="submit">Volta<br> do Almoço</button>';
$botao_fim_expediente = '<button class="btn btn-lg btn-success btn-block" type="submit">Saida</button>';
$msg_limite_pausa = "<div class='alert alert-warning' role='alert' align='center'>
                <img src='images/alert.png' class='rounded mx-auto d-block' alt='Alerta'>
                Registro não permitido neste momento.<br>
                Maximo de pessoas em pausa/intervalo: $qtd_max_parametro
                </div>";
$msg_pausa_ativa = "<div class='alert alert-warning' role='alert' align='center'>
<img src='images/ampulheta.gif' class='rounded mx-auto d-block' alt='Alerta'>
Você está na(o) $descricaoPausa ha $tempoEmPausa Minuto(s)<br>
Aguarde a Finalização.
</div>";
$msg_tmp_min_pausas = "<div class='alert alert-warning' role='alert' align='center'>
                        <img src='images/alert.png' class='rounded mx-auto d-block' alt='Alerta'>
                        Registro não permitido neste momento.<br>
                        Ultimo registro: ".$tb_registro['horaPausaInativa']."<br>
                        Novo registro permitido a partir das $tbmPermitidoProxPausa
                        </div>";
?>

<!doctype html>
<html lang="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=edge, chrome=1">
    <head>
        <style type="text/css">
		.btn {
				width: 200px;
				height: 200px;
				border-style: solid;
                border-radius: 50%;
                -moz-border-radius: 50%; /* Implementação Firefox */
                -webkit-border-radius: 50%; /* ISafari e browsers que renderizam via webkit */
                box-shadow: #999 0px 2px 3px;
            }
            
        </style>
        
    </head>
    <body>
        <div>
            <?php 
            echo "<strong>Empresa: </strong>" . $funcionario['nmempresa'] . "<br>";
            echo "<strong>Funcionário: </strong>" . $funcionario['nomeFuncionario'] . "<br>";
            echo "<strong>Função: </strong>" .$funcionario['funcao']. "<br>";
            echo "<strong>Tipo: </strong>" .$funcionario['tipocontrato']. "<br>";
            echo "<strong>Código: </strong>" .$funcionario['codidoFuncionario']. "<br>";				  
            ?>
            <a href="relatorio.php">Relatorio de Registros</a>|
            <a href="sair.php">Sair</a>
        </div>
        <form class="form-signin" action="registrar.php" name="registra" method="post">
        
            <input type="hidden" name="codigo" value="<?php echo $codigo_session; ?>">

            <?php //Vai ser verificado se para aquele tipo de contrato a pausa é controlada ou não   

                if($funcionario["controlarPausa"] == 1){
                    $qtd_max_em_pausa = ((int)$tb_parametros['quantidadeMaximaEmPausa'] <= $total) ? 1: 0;
                }else{
                    $qtd_max_em_pausa = 0;
                }
                
                switch($funcionario['quantidadeDeBatidas']){
                    case 2: require_once("../include/bater_ponto_two.php");
                        echo "<input type='hidden' name='quantidadeDeBatidas' value='2'>";
                        break;
                        
                    case 4: require_once("../include/bater_ponto_four.php");
                        echo "<input type='hidden' name='quantidadeDeBatidas' value='4'>";
                        break;
                        
                    case 6: require_once("../include/bater_ponto_six.php");
                        echo "<input type='hidden' name='quantidadeDeBatidas' value='6'>";
                        break;
                }                   
            ?>       
        
        </form>
    </body>
</html>











