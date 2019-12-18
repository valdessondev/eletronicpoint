<?php
	class array_ordem{

		public function organiza($array=array()){
			$qtd = count($array);
			$k = 1;
			$result = null;

			foreach($array as $valor){
				$v="";

				if($k < $qtd){
					$v = ", ";
				}

				$result.= $valor.$v;
				$k++;
			}
			return $result;
		}

	}
?>