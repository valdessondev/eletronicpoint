<?php 
	require_once("config/conn.php");
	if (!isset($_SESSION)) @session_start();

	mysqli_close($conn);	

	unset( $_SESSION['CODIGO_FUNC'] );
	unset( $_SESSION['FUNC_ATIVO'] );
	unset( $_SESSION['EMPRESA'] );
	unset( $_SESSION['TIPO_FUNCIONARIO'] );
	unset( $_SESSION['TURNO'] );

	unset( $_SESSION['TIME_REGISTER_FUNC'] );
	unset( $_SESSION['TIME_LIMITE_FUNC'] );

	//Destruindo informações da paginação
	unset( $_SESSION["ano"] );
	unset( $_SESSION["select_mes"] );
	unset( $_SESSION['select_ano'] );	
	unset( $_SESSION['maxPaginaRelatorioemployee'] );
	
	//session_destroy();	

	@header("location: index.php");

?>