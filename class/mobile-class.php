<?php

class mobile {
	public $vmobile;
	public $user_agents;
	
	
	public function get_user_agents($agents=array()){
		
		return $this->user_agents = $agents;
	}
	
	public function ismobile(){
		
		$user_agents = $this->get_user_agents(array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric"));
		
		$mobile = false;
		foreach($user_agents as $user_agent){

			if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== FALSE) {
				$mobile = TRUE;
				$modelo = $user_agent;
				break;
			}
		}
		return $mobile;
	}
}
//https://pt.stackoverflow.com/questions/121274/php-verificar-dispositivo-mobile
?>