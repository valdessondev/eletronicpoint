<?php
	class anti_injection{

		private $str;

		public function anti_injection_exec($sql_text){

			$this->setStr($sql_text);
			
			$sql = $this->getStr();

			$sql = preg_replace($this->my_Sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "" ,$sql);
			$sql = trim($sql);
			$sql = strip_tags($sql);
			$sql = (get_magic_quotes_gpc()) ? $sql : addslashes($sql);

			return $sql;
		}
		
		public function anti_injection_login($sql_login){

			$sql = $this->anti_injection_exec($sql_login);
			$sql = preg_replace('/[^[:alpha:]_]/', '',$sql);

			return $sql;
		}

		private function my_Sql_regcase($str){

			$res = "";
		
			$chars = str_split($str);
			foreach($chars as $char){
				if(preg_match("/[A-Za-z]/", $char)){
					 $res .= "[".mb_strtoupper($char, 'UTF-8').mb_strtolower($char, 'UTF-8')."]";
				}else{
					$res .= $char;
				}
			 }
		
			 return $res;
		}

		private function setStr($str_receive){
			$this->str = $str_receive;
		}
		private function getStr(){
			return $this->str;
		}	

	}
?>