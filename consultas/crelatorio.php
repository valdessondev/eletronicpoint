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
                    </tr>

                <?php } while ($registros = mysqli_fetch_assoc($exec_query));
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