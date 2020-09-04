<?php
require_once('../verifica_session_front.php');
include '../class/mobile-class.php';

$mes = (int) $_POST['vmes'];
$ano = (int) $_POST['vano'];
$codigo = (int) $_SESSION['CODIGO_FUNC'];

if(!isset($_SESSION['select_mes'])){
    $_SESSION['select_mes'] = $mes;

}elseif($_SESSION['select_mes'] != $mes){
    $mes = $_SESSION['select_mes'] = $mes;
}

$maxPaginaGeraRelatorioemployee = isset($_POST['vmaxPaginaGeraRelatorioemployee']) ? $_POST['vmaxPaginaGeraRelatorioemployee'] : "";
(int) $currentpage = isset($_POST['page']) ? $_POST['page'] : 1;

//Se o usuário digitar alguma letra na variavel da página ele apaga para evitar ataque
$currentpage = preg_replace("/[a-zA-ZÀ-ú]+/", "", $currentpage);

//Se estiver algum caractere especial, ele redireciona para a pagina 1 para evitar ataque
$currentpage = preg_match("/[-!\"#$%&'()*\+,\.\/:;<=>\?@\[\_{|}~]/", $currentpage) ? 1 : $currentpage;

//-----------Inicio Paginação----------------------

if (!isset($_SESSION["maxPaginaGeraRelatorioemployee"]) && isset($maxPaginaGeraRelatorioemployee)) {
    $_SESSION["maxPaginaGeraRelatorioemployee"] = $maxPaginaGeraRelatorioemployee;
} elseif (isset($maxPaginaGeraRelatorioemployee) && ($maxPaginaGeraRelatorioemployee != $_SESSION["maxPaginaGeraRelatorioemployee"])) {
    $_SESSION["maxPaginaGeraRelatorioemployee"] = $maxPaginaGeraRelatorioemployee;
    $currentpage = 1;
}


//Definir o número de itens por página
$itensPorPagina = (isset($_SESSION["maxPaginaGeraRelatorioemployee"]) && $_SESSION["maxPaginaGeraRelatorioemployee"] != "") ? (int) $_SESSION["maxPaginaGeraRelatorioemployee"] : 31;

//pegar a página atual
$paginaAtual = (int) $currentpage;

//---------Fim Páginação---------------------------

function intervaloy( $entrada, $saida, $entrada1,$saida1 , $retorna_segundos=null) {
       $entrada = ($entrada==''?'00:00':explode( ':', $entrada ));
       $saida   = ($saida==''?'00:00':explode( ':', $saida ));
       $entrada1 = ($entrada1==''?'00:00':explode( ':', $entrada1 ));
       $saida1   = ($saida1==''?'00:00':explode( ':', $saida1 ));
       
       $minutos = (( $saida[0] - $entrada[0] )+( $saida1[0] - $entrada1[0] )) * 60 + (($saida[1] - $entrada[1])+($saida1[1] - $entrada1[1]));
       if( $minutos < 0 ) $minutos += 24 * 60;
       $zeroH=(($minutos / 60)<10 ?'0':'');
       $zeroM=(($minutos % 60)<10 ?'0':'');
       if(is_null($retorna_segundos)){
       return sprintf( "$zeroH%d:$zeroM%d", $minutos / 60, $minutos % 60 );
       }else {
           $tv=sprintf( "$zeroH%d:$zeroM%d", $minutos / 60, $minutos % 60 );
               list($horas,$minutos) = explode(":",$tv);
    $calc = $horas * 3600 + $minutos * 60;
    return $calc;
       }
    }
        function segundos_em_tempo($segundos) {
     $horas = floor($segundos / 3600);
     $minutos = floor($segundos % 3600 / 60);
     $segundos = $segundos % 60;
     return sprintf("%d:%02d:%02d", $horas, $minutos, $segundos);
    }
?>
<!doctype html>
<html lang="pt-br">
<head>

</head>
<body>
    <table class="table table-bordered table-hover table-condensed" style="width: 90%;">
        <thead class="thead-dark">
            <tr>
                <th scope="col" style="width:150px">Data</th>
                <th scope="col">S.P1</th>
                <th scope="col">V.P1</th>
                <th scope="col">S.I</th>
                <th scope="col">V.I</th>
                <th scope="col">S.P2</th>
                <th scope="col">V.P2</th>
                <th scope="col">Soma do dia</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query_total = "SELECT  
                                 R.ALTERADO,R.DATA,R.HORA_ENTRADA,
                                 R.HORA_RETORNO_INTERVALO,R.HORA_SAIDA,
                                 R.HORA_SAIDA_INTERVALO, R.HORA_SAIDA_PAUSA,
                                 R.HORA_VOLTA_PAUSA
                            FROM 
                                REGISTROS AS R
                            INNER JOIN
                                FUNCIONARIO AS F
                            ON
                                R.CODIGO = F.CODIGO
                            WHERE 
                                YEAR(DATA) = '$ano' AND MONTH(DATA) = '$mes'
                            AND 
                                F.CODIGO = '$codigo'";

            $exec_query_total = mysqli_query($conn, $query_total);
            $totalNumRows = mysqli_num_rows($exec_query_total);

            $totalPaginas = (int) ceil($totalNumRows / $itensPorPagina); //original

            //pegar a página atual 
            $paginaAtual = ($paginaAtual > $totalPaginas) ? $totalPaginas : $paginaAtual;

            //Se a pagina atual for ZERO então atribua 1 para não dar erro
            $paginaAtual  = ($paginaAtual < 1) ? 1 : $paginaAtual;

            $inicioExibir = ($itensPorPagina * $paginaAtual) - $itensPorPagina;

            $query = $query_total . " ORDER BY R.DATA ASC LIMIT $inicioExibir,$itensPorPagina";
            $exec_query = mysqli_query($conn, $query);
            $registros = mysqli_fetch_assoc($exec_query);
            mysqli_close($conn);
$soma_total=0;
            if ($totalNumRows > 0) {
                do {
                    ?>
                    <tr>
                        <th scope="row" style="width:150px"><?= date('d/m/Y', strtotime($registros['DATA']));
                            if ($registros['ALTERADO']) echo "*"; ?>
                        </th>
                        <td><?= $registros['HORA_ENTRADA'] ?></td>
                        <td><?= $registros['HORA_SAIDA_INTERVALO'] ?></td>
                        <td><?= $registros['HORA_RETORNO_INTERVALO'] ?></td>
                        <td><?= $registros['HORA_SAIDA'] ?></td>
                        <td><?= $registros['HORA_SAIDA_PAUSA'] ?></td>
                        <td><?= $registros['HORA_VOLTA_PAUSA'] ?></td>
                        <td><?php 
                        
                        echo intervaloy($registros['HORA_ENTRADA'], $registros['HORA_SAIDA_INTERVALO'], $registros['HORA_RETORNO_INTERVALO'], $registros['HORA_SAIDA']);
                        
                        $soma_total+=intervaloy($registros['HORA_ENTRADA'], $registros['HORA_SAIDA_INTERVALO'], $registros['HORA_RETORNO_INTERVALO'], $registros['HORA_SAIDA'],'segundos');
                        
                                ?></td>
                    </tr>

                <?php } while ($registros = mysqli_fetch_assoc($exec_query));
                
                ?>
                    <tr><td>Soma do Mês:</td><td colspan="7"><?php echo segundos_em_tempo($soma_total); ?></td></tr>  
                <?php
                } else { ?>
                <tr>
                    <td colspan="7">
                        <div class="alert alert-danger" role="alert">
                            Nenhum registro encontrado, selecione outro filtro..
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php include("../include/pagination.php"); ?>
</body>
</html>
<?php
mysqli_free_result($exec_query_total);
mysqli_free_result($exec_query);
?>
