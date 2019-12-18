<?php

	class usuarios{
		private $id;
		private $grupoAcesso;
		public $conexao;

		public function __construct($conn){
			$this->setConexao($conn);
		}

		private function setConexao($conn){
			$this->conexao = $conn;
		}
		public function getConexao(){
			return $this->conexao;
		}

		public function setID($id){
			$this->id = $id;
		}
		public function getID(){
			return $this->id;
		}
		public function setGrupoAcesso($grupoID){
			$this->grupoAcesso = $grupoID;
		}
		public function getGrupoAcesso($id_user){
			$this->setID($id_user);
			$id = $this->getID();

			$conn = $this->getConexao();

			$query = "SELECT GRUPOACESSO FROM USERS WHERE ID = '$id'";
			$exec_query = mysqli_query($conn, $query);
			$grupoID = mysqli_fetch_row($exec_query);

			$this->setGrupoAcesso($grupoID);

			return $grupoID;
		}

	}
?>
