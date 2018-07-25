<?php if(!class_exists('Rain\Tpl')){exit;}?>

<!-- Highlights -->
<section class="wrapper menor">
	<div class="inner">
		<header class="special">

			<!-- Abre alteracao liberada -->
			<?php if( $msg=='alteracaoLiberada' ){ ?>

			<h2>REDEFINA SUA SENHA</h2>
			<p>Digite a nova senha nos campos abaixo para alter&aacute;-la.</p>
			<?php } ?> <?php if( $msg=='alterada' ){ ?>

			<h2>RECUPERAR SENHA</h2>
			<?php } ?> <?php if( $msg=='inicial' ){ ?>

			<h2>RECUPERAR SENHA</h2>
			<p>Digite seu e-mail no campo abaixo que lhe enviaremos
				instru&ccedil;&otilde;es.</p>
			<?php } ?>



			<form method="post" action="/recuperarSenha">
				<div class="row gtr-uniform">
					<div class="col-4 col-12-small">
						<!-- Espa�ador -->

					</div>

					<div class="col-4 col-12-small">


						<!-- Caso possa ser feita a altera��o de senha -->
						<?php if( $msg=='alteracaoLiberada' ){ ?> <input type="password"
							name="senha" id="senha" value="" placeholder="Senha" required />
						<p></p>

						<input type="password" name="repetirSenha" id="repetirSenha"
							value="" placeholder="Repetir senha" /> <input type="hidden"
							name="idUsuario" value=<?php echo htmlspecialchars( $idUsuario, ENT_COMPAT, 'UTF-8', FALSE ); ?> />
						<p></p>

						<div class="col-12">
							<ul class="actions">
								<li><input type="reset" value="Cancelar" /></li>
								<li><input type="submit" value="Salvar" class="primary" /></li>
							</ul>
						</div>

						<script language='javascript' type='text/javascript'>
							var password = document.getElementById("senha"), confirm_password = document
									.getElementById("repetirSenha");

							function validatePassword() {
								if (password.value != confirm_password.value) {
									confirm_password
											.setCustomValidity("Senhas não conferem.");
								} else {
									confirm_password.setCustomValidity('');
								}
							}

							password.onchange = validatePassword;
							confirm_password.onkeyup = validatePassword;
						</script>

						<?php } ?> <?php if( $msg=='alterada' ){ ?>

						<div class="alert alert-success">
							<strong>Aviso!</strong> Sua senha foi alterada com sucesso. Agora voc&ecirc; pode us&aacute;-la para se conectar. Fa&ccedil;a <a href="/login">login</a> agora.
						</div>
						<?php } ?> 
						<?php if( $msg=='sucesso' ){ ?>

						
						<div class="alert alert-success">
							<strong>Aviso!</strong> Sucesso! Um email foi enviado a
							voc&ecirc; com um link de redefini&ccedil;&atilde;o de senha.
						</div>

						<?php } ?> <?php if( $msg=='invalidEmail' ){ ?>

						<div class="alert alert-danger">
							<strong>Aviso!</strong> Este e-mail n&atilde;o existe em nossos
							registros. <a href="/cadastro">Cadastrar-se?</a>.
						</div>
						<?php } ?> <?php if( $msg=='codigoExpirado' ){ ?>

						<div class="alert alert-danger">
							<strong>Aviso!</strong> Sua chave de recupera&ccedil;&atilde;o de
							senha expirou, gere uma nova clicando em <a
								href="/recuperarSenha">recuperar senha</a>.
						</div>
						<?php } ?> <?php if( $msg=='inicial' ){ ?> <input type="email" name="email"
							id="email" value="" placeholder="Email" required />
						<p></p>
						<input type="submit" value="Recuperar senha" class="primary" />

						<?php } ?>


					</div>
					<div class="col-4 col-12-xsmall">
						<!-- Espa�ador -->
					</div>
				</div>
			</form>


		</header>
	</div>
</section>
