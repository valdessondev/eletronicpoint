function openAjax(){
	var ajax;

try{
	ajax = new XMLHttpRequest();
}catch(erro){
	try{
		ajax = new ActiveXObject("Msxl2.XMLHTTP");
	}catch(ee){
		try{
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		}catch(e){
			ajax = false;
		}
	}
}
return ajax;	
}//instancia dinamicamente o objecto xmlhttp

function busca(){
	if(document.getElementById){
		var termo = document.getElementById('q').value;
		var exibeResultado = document.getElementById('resultado');
		var pagina = document.getElementById('pagina_search').value;
		
		if(termo !== "" && termo !== null && termo.length >= 2){
			var ajax = openAjax();
			
			ajax.open("GET", "search/"+pagina+".php?q="+termo+"&pagina="+pagina, true);
			ajax.onreadystatechange = function(){
				if(ajax.readyState == 1){
					exibeResultado.innerHTML = '<p>Carregando resultados...</p>';
				}
				
				if(ajax.readyState == 4){
					if(ajax.status == 200){
						var resultado = ajax.responseText;
						resultado = resultado.replace(/\+g/, "");
						resultado = unescape(resultado);
						
						if($('#q').val() !== ""){
							
							exibeResultado.innerHTML = resultado;
							$("#tabInclude").hide();
						}
						
					}else{
						exibeResultado.innerHTML = '<p>Houve algum erro na requisição.</p>';
					}
				}
			}
			ajax.send(null);
		}else if(termo == "" || termo == "null"){
			
			exibeResultado.innerHTML = null;
			$("#tabInclude").show();
		}
	}
}

//https://www.youtube.com/watch?v=0cLAJw8lkRY










