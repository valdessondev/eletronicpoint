$(function() {
    $("#rel td").dblclick(function() {
        var conteudoOriginal = $(this).text();
        var id = $(this).attr("id");
        var data = $(this).attr("data");
        var colum = $(this).attr("colum1");

        data_atual = new Date(),
            dia = (data_atual.getDate() < 10) ? '0' + data_atual.getDate() : data_atual.getDate();
        mes = ((data_atual.getMonth() + 1) < 10) ? +data_atual.getMonth() + 1 : data_atual.getMonth() + 1,
            ano = data_atual.getFullYear();

        var data_verificacao = [ano, mes, dia].join('-');

        if (id != 'n' && data < data_verificacao) {

            $(this).addClass("celulaEmEdicao");
            $(this).html("<input type='time' value='" + conteudoOriginal + "'/>");
            $(this).children().first().focus();

            $(this).children().first().keypress(function(e) {
                if (e.which == 13) {

                    var novoConteudo = $(this).val();
                    var urlv = 'relatorios';

                    $(this).parent().text(novoConteudo);
                    $(this).parent().removeClass("celulaEmEdicao");

                    //Enviando as informações através de AJAX
                    $.ajax({
                        url: "salvar_dados.php",
                        type: "POST",
                        data: { vnovoConteudo: novoConteudo, vID: id, vdata: data, vcoluna: colum, vurl: urlv, vconteudooriginal: conteudoOriginal },
                        success: function(data) {
                            //document.getElementById('result').innerHTML = urlv;
                            location.reload();
                        },
                        error: function() {
                            document.getElementById('result').innerHTML = 'Ocorreu um erro';
                        },
                        dataType: 'html'
                    });
                }
            });

            $(this).children().first().blur(function() {
                $(this).parent().text(conteudoOriginal);
                $(this).parent().removeClass("celulaEmEdicao");
            });
        }
    });

    /**$("#formparametros").submit(function(){
    	$("#carregando").css("display", "inline");		
    	var serializeDados = $("#formparametros").serialize();
    	
    	$.ajax({
    		url:'salvar_dados.php',
    		type: 'POST',
    		data:serializeDados,
    		success: function(data) {
    							
    			$.post("parametros.php", {atualiza:"yes"},  function(){					
    				document.getElementById('result').innerHTML = "Salvo com sucesso";
    				$('#result').addClass("alert alert-success");					
    				$('#result').fadeIn(5000);					
    				
    			});				
    			$('#carregando').fadeOut(2500);
    		},
    		error: function(){
    			$('#result').addClass("alert alert-danger");
    			$('#result').show("slow")
    			document.getElementById('result').innerHTML = 'Ocorreu um erro';

    		},
    		dataType:'html'
    	});
    	return false;
    });**/

    $("#form-login").on('submit', function(e) {

        $("#carregando").css("display", "block");

    });

    $('.menu_link').on('click', function() {

        $("#carregando").css("display", "block");

    });

});

function registrationStatus(ID, urlv, acao) { //Função para desabilitar o funcionário

    var msg_desabled = "Deseja realmente desativar este registro?";
    var msg_active = "Deseja realmente reativar este registro?";
    var msg;

    if (acao == "desabled") { msg = msg_desabled; } else if (acao == "active") { msg = msg_active; }

    var confirmation = confirm(msg);

    if (confirmation) {

        $.ajax({
            url: "salvar_dados.php",
            type: "POST",
            data: { vID: ID, vurl: urlv, vacao: acao },

            success: function(data) {

                location.reload();

            },
            error: function() {
                alert("Ocorreu um erro, contate o administrador!");
            },
            dataType: 'html'
        });

    } else {
        return false;
        event.preventDefault();
    }
};


//https://pt.stackoverflow.com/questions/41476/como-enviar-v%C3%A1riaveis-php-usando-ajax
//https://www.devmedia.com.br/como-tornar-uma-tabela-html-editavel-com-jquery/26899
//https://forum.imasters.com.br/topic/559753-gravar-dados-no-banco-de-dados-com-ajax-sem-refresh-n%C3%A1-p%C3%A1gina/
//https://www.linhadecomando.com/jquery/jquery-excluindo-registro-janela-de-confirmacao