<?php

	class ckbAcesso{
		private $user_id;
		private $code_menu;

		public function ckbPermissao($userid,$codigoMenu,$conn){
			//Setando o ID do usuário
			$this->setUser_id($userid);
			//Setando o código do menu
			$this->setCode_Menu($codigoMenu);

			//Recuperando o ID do usuário
			$user = $this->getUser_id();
			//Recuperando o Código do menu
			$menu = $this->getCode_Menu();

			$query = 
				"SELECT DISTINCT 
					U.ID,
					U.LOGIN,
					GA.GRUPOACESSO_ID,
					P.PERMISSOES_ID,
					M.MENU_ID,
					M.NM_MENU
				FROM 
					USERS AS U
				INNER JOIN GRUPOACESSO AS GA
					ON U.GRUPOACESSO = GA.GRUPOACESSO_ID
				INNER JOIN PERMISSOES_GRUPOS AS PG
					ON GA.GRUPOACESSO_ID = PG.GRUPO_ID
				INNER JOIN PERMISSOES AS P
					ON PG.PERMISSOES_ID = P.PERMISSOES_ID
				INNER JOIN PERMISSOES_MENU AS PM
					ON P.PERMISSOES_ID = PM.PERMISSOES_ID
				INNER JOIN MENU AS M
					ON PM.MENU_ID = M.MENU_ID
				WHERE 
					U.ID = '$user' 
				AND 
					M.MENU_ID = '$menu'
				AND 
					P.PERMISSOES_ATIVA = 1
				LIMIT 1"; 


			$exec_query = mysqli_query($conn, $query);
			$total = mysqli_num_rows($exec_query);

			$acesso = (($total > 0) || ($menu==50)) ? true: false;

			//Retornando o resultado final
			return $acesso;
		}

		public function setUser_id($userid){
			$this->user_id = $userid; 
		}

		public function setCode_Menu($codeMenu){
			$this->code_menu = $codeMenu; 
		}

		public function getUser_id(){
			return $this->user_id; 
		}

		public function getCode_Menu(){
			return $this->code_menu; 
		}

	}
?>

		