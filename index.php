<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width"/> 
	<title>⛽ Pedir gás</title>
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css">
	<style type="text/css">
	    html,body{
	        height:100%; /* important to vertically align the container */
	        margin:0;
	        padding:0;
	    }

		body{			
			background: url('bg.jpg');
			background-size: cover;
		}

		.central-y{
			min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
			min-height: 100vh; /* These two lines are counted as one 🙂       */
			display: flex;
			align-items: center;
		}

		#janelaform{
			margin: 40px 0;
			background-color: #2ed573;
			padding: 20px;
			border-radius: 8px;
			color: #fff;
		}

		textarea{
			resize: none;
		}

		label{
			font-weight: 600;
		}

		.view{
			margin-top: 25px;
		}

	</style>
</head>
<body>
	<noscript>Para utilizar esse aplicativo, você deve utilizar um navegador com suporte a JavaScript!</noscript>
	<script src="jquery.min.js"></script>
	<script src="bootstrap.min.js"></script>
	<script src="jmask.js"></script>
	<div class="central-y">
		<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-md-8 col-sm-12">
				<div id="janelaform">
					<form>
						<p style="font-weight: 900; font-size: 1.3em">Para pedir seu gás via WhatsApp, preencha os campos corretamente</p>
						<div class="dropdown-divider" style="margin: 15px 0 20px 0;"></div>
						<div class="form-row">
							<div class="form-group col-md-3 col-sm-12">
								<label for="num">📞 Tel. do gás:</label>
								<input type="text" id="tel" class="form-control" placeholder="(00) 0000-0000" pattern="\d+">
							</div>
							<div class="form-group col-md-2 col-sm-12">
								<label for="cep">📍 CEP</label>
								<input type="text" id="cep" class="form-control" placeholder="00000000">
							</div>
							<div class="form-group col-md-7 col-sm-12">
								<label for="endereco">🛣 Endereço:</label>
								<input type="text" id="endereco" class="form-control" placeholder="Ex.: Rua Azul">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-5 col-sm-12">
								<label for="bairro">🏠󠁢󠁲󠁳󠁰󠁿 Bairro:</label>
								<input type="text" id="bairro" class="form-control" placeholder="Ex.: Jd. das Perolas">
							</div>
							<div class="form-group col-md-2 col-sm-12">
								<label for="num">🔢 Número:</label>
								<input type="text" id="num" class="form-control" placeholder="000" pattern="\d+">
							</div>
							<div class="form-group col-md-3 col-sm-12">
								<label for="pagto">💰 Pagamento: </label>
								<select class="form-control" id="pagto">
									<option value="Dinheiro">💵 Dinheiro</option>
									<option value="Débito">💳 Débito</option>
									<option value="Crédito">💳 Crédito</option>
								</select>
							</div>
							<div class="form-group col-md-2 col-sm-12">
								<label for="troco">💲 Troco (R$)</label>
								<input type="text" id="troco" class="form-control" placeholder="R$0,00">
							</div>
						</div>
						<a class="btn btn-primary btn-block" onclick="gerarMsg();">Gerar sua mensagem</a>
					</form>
					<form class="view">
						<div class="form-group">
							<label for="msg">👀 Sua mensagem ficará assim: </label>
							<textarea class="form-control" rows="4" placeholder="assim que você gerar sua mensagem, ela aparecerá aqui" id="msg"></textarea>
							<label><Br>Antes de enviar a mensagem, verifique se preencheu tudo corretamente e se a mensagem está correta. Caso sim, apenas confirme o envio no WhatsApp! ;)</label>
						</div>						
						<a class="btn btn-primary btn-block" onclick="enviar();">📨 Enviar!</a>
					</form>
				</div>	
			</div>
		</div>
	</div>
	</div>

	<script>
		$(document).ready(function(){
			function liberaTroco(){
				let pagtoSelecionado = $('#pagto').val();
				if(pagtoSelecionado != "Dinheiro"){
					$('#troco').prop('disabled', true)
					$('#troco').val(undefined)
				}else{
					$('#troco').prop('disabled', false)
				}
			}

			liberaTroco();

			$('#pagto').change(function(){
				liberaTroco();
			})

			$('#cep').blur(function(){
				let cep = $('#cep').val();
				$.getJSON('https://viacep.com.br/ws/'+cep+'/json/',function(preencher){
					if("erro" in preencher){
						$('#cep').addClass('is-invalid');
					}else{
						$('#cep').removeClass('is-invalid');
						$('#endereco').val(preencher.logradouro);
						$('#bairro').val(preencher.bairro);
					}	
				});
			})

			$('#troco').mask('000.000.000.000.000,00', {reverse: true});
			$('#cep').mask('00000000');
			$('#tel').mask('000000000000')
		});

		var mensagem

		function gerarMsg(){
			var Dados = {
				telefone: $('#tel').val(),
				bairro: $('#bairro').val(),
				endereco: $('#endereco').val(),
				numero: $('#num').val(),
				pagto: $('#pagto').val().toLowerCase(),
				troco: $('#troco').val(),
			}

			let d = new Date();
			let hrs = d.getHours();
			var cumprimento;

			if(hrs > 5 && hrs < 12){
				cumprimento = 'Bom dia'
			}else if(hrs > 12 && hrs < 19){
				cumprimento = 'Boa tarde'
			}else{
				cumprimento = 'Boa noite'
			}

			if(Dados.troco != ''){
				$('#msg').html(`${cumprimento}, gostaria que me enviassem um gás. O bairro é o ${Dados.bairro}, e o endereço é: ${Dados.endereco}, Nº ${Dados.numero}. Vou pagar no ${Dados.pagto} e precisarei de R$${Dados.troco} de troco, por favor. Desde já, agradeço 👍`);
			}else{
				$('#msg').html(`${cumprimento}, gostaria que me enviassem um gás, por favor. O bairro é o ${Dados.bairro}, e o endereço é: ${Dados.endereco}, Nº ${Dados.numero}. Vou pagar no ${Dados.pagto} e não será necessário troco. Desde já, agradeço 👍`);
			}
		}	

		function enviar(){
			mensagem = encodeURI($('#msg').val())
			let tel = $('#tel').val();
			window.location.href = "https://wa.me/55"+tel+"?text="+mensagem
		}
	</script>
	
</body>
</html>
