<?php
require_once("../../config/conn.php");

$data_atual = date('Y-m-d');
$idUser = $_SESSION['USER_ID'];

//Identifica a empresa padrão do usuário
$query_parametros = "SELECT 	
						P.EMPPADRAO AS empresa_padrao_usuario,
						P.MINUTOS,
						E.NM_EMPRESA
					FROM 
						USERS AS U
					INNER JOIN 
						PARAMETROS AS P
					ON 
						U.PARAMETRO_ID = P.PARAMETRO_ID
					INNER JOIN 
						EMPRESAS AS E
					ON 
						P.EMPPADRAO = E.EMPRESA_ID
					WHERE 
						U.ID = '$idUser'
					LIMIT 1";
$exec_query_parametros = mysqli_query($conn,$query_parametros);
$registro_parametro = mysqli_fetch_array($exec_query_parametros);

$empresapadrao = $registro_parametro["empresa_padrao_usuario"];	
$qtdminmax =  $registro_parametro['MINUTOS'];

//Variavel utilizada para definir em quanto tempo a pausa esta se baseando
$hora_verificacao = date('H:i', strtotime('- '.$qtdminmax.'minute', strtotime(date('H:i'))));			
$query = 
	"SELECT DISTINCT
		TC.NM_TIPOCONTRATO AS tipocontrato,
		TC.RELATORIOPAUSAATIVA,
		F.NOME AS nomeFuncionario,
		R.DESCRICAO_PAUSA_ATIVA AS descricaoPausaAtiva,
		R.HORA_PAUSA_ATIVA AS horaPausaAtiva
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
		R.DATA = '$data_atual' AND F.EMPRESA = '$empresapadrao' AND R.PAUSA_ATIVA = '1'
    AND 
        R.HORA_PAUSA_ATIVA >= '$hora_verificacao' 
    AND 
        TC.RELATORIOPAUSAATIVA = '1'
	ORDER BY 1 ASC LIMIT 50";  

$exec_query = mysqli_query($conn,$query);
$total = mysqli_num_rows($exec_query);
$registros = mysqli_fetch_assoc($exec_query);
mysqli_close($conn);

//verifica o tempo transcorrido desde a pausa/intervalo--------
list($horas, $minutos) = explode(':', date('H:i'));
$qtd_minutos_atual = ($horas * 60) + $minutos;

//-------------------fim-----------------
?>

<!doctype html>
<html lang="pt-br">
    <head>

    </head>
    <body>
        <table class="table table-bordered table-hover table-condensed">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Tipo Contrato</th>					
                    <th scope="col">Nome</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Registro</th>
                    <th scope="col">Tempo<br>min</th>
                </tr>
            </thead>
            <tbody>
        
                <?php 
                if ($total > 0) {
                    do {
                        //Realizando os calculos do tempo em pausa
                        list($horas, $minutos) = explode(':',$registros['horaPausaAtiva']);
                        $qtd_minutos_registro = ($horas * 60) + $minutos;
                        $tempoEmPausa = $qtd_minutos_atual - $qtd_minutos_registro;
                        $descricaoPausa = $registros['descricaoPausaAtiva'];

                        if ($descricaoPausa == "INTERVALO" && $tempoEmPausa >= 15) $alert= 'class="alert alert-danger" role="alert"';
                        elseif (preg_match("/PAUSA/", $registros['descricaoPausaAtiva']) && $tempoEmPausa >= 10) $alert = 'class="alert alert-danger" role="alert"';
                        elseif (preg_match("/ALMOÇO/", $registros['descricaoPausaAtiva']) && $tempoEmPausa >= 60) $alert = 'class="alert alert-danger" role="alert"';
                        else  $alert = "";
                        //$alert = "";
                ?>
                    <tr <?=$alert?>>
                        <th id = "n"><?=$registros['tipocontrato']?></th>
                        <th id = "n"><?=$registros['nomeFuncionario']?></th>
                        <td id = "n"><?=$registros['descricaoPausaAtiva']?></td>
                        <td id = "n"><?=$registros['horaPausaAtiva']?></td>
                        <td id = "n"><?=$tempoEmPausa?></td>
                    </tr>
                <?php
                    } while ($registros = mysqli_fetch_assoc($exec_query));
                }else{
                    echo"<tr>
                            <td colspan='5'>
                                <div class='alert alert-danger' role='alert'>
                                <strong>Nenhum colaborador em pausa/intervalo neste momento...</strong>
                                </div>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </body>
</html>
<?php
mysqli_free_result($exec_query);
mysqli_free_result($exec_query_parametros);