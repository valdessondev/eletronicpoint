<?php 
mysqli_close($conn);
if (!isset($_SESSION)) @session_start();

unset( $_SESSION['USER_ID'] );
unset( $_SESSION['LOGIN'] );
unset( $_SESSION['SENHA'] );
unset( $_SESSION['USER_ATIVO'] );
//session_destroy();

unset( $_SESSION['TIME_REGISTER'] );
unset( $_SESSION['TIME_LIMITE'] );

//Destruindo informações da paginação
unset( $_SESSION["ano"] );
unset( $_SESSION["select_ano"] );
unset( $_SESSION["select_mes"] );
unset( $_SESSION["select_func"] );
unset( $_SESSION["select_func2"] );
unset( $_SESSION["select_empresa"] );
unset( $_SESSION["select_empresa2"] );
unset( $_SESSION["select_tipocontrato"] );
unset( $_SESSION["select_tipocontrato2"] );
unset( $_SESSION["data_inicial"] );
unset( $_SESSION["data_final"] );
unset( $_SESSION["select_funcao"] );
unset( $_SESSION["select_funcao2"] );
unset( $_SESSION["select_atrasos"] );
//-----------------

unset( $_SESSION["maxPaginaListaFunc"] );
unset( $_SESSION["maxPaginaRelatorioAdm"] );
unset( $_SESSION["maxPaginaRelatorioAdm2"] );
unset( $_SESSION["maxPaginaListaUsers"] );
unset( $_SESSION["maxPaginaListaFunctions"] );
unset( $_SESSION["maxPaginaListaCompany"] );
unset( $_SESSION["maxPaginaListaTipocontrato"] );
unset( $_SESSION["maxPaginaListaGroupAccess"] );
unset( $_SESSION["maxPaginaListaPermission"] );

unset( $_SESSION["situacaoCadastroFuncionario"] );
unset ( $_SESSION["situacaoCadastroUsuarios"] );
unset ( $_SESSION["situacaoCadastroFuncoes"] );
unset ( $_SESSION["situacaoCadastroCompany"] );
unset ( $_SESSION["situacaoCadastroTipocontrato"] );
unset ( $_SESSION["situacaoCadastroGroupAccess"] );
unset ( $_SESSION["situacaoCadastroPermission"] );

@header("location: index.php");

?>