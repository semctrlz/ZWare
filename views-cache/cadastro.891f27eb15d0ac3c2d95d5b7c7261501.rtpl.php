<?php if(!class_exists('Rain\Tpl')){exit;}?>		<!-- Highlights -->
		<section class="wrapper menor">	
				<div class="inner">
					<header class="special">
						<h2>CADASTRO DE USU&Aacute;RIO</h2>
						<p>Cadastre-se para receber novidades, e para poder utilizar nossos servi&ccedil;os</p>
					</header>
			<form method="post" action="/cadastro">
					<div class="row gtr-uniform">
						<div class="col-3 col-12-small">						
							<!-- Espaçador -->

						</div>
						<div class="col-6 col-12-small">														
								<div class="col-6 col-12-small">
									<div class="row"></div>
									<div class="col-4 col-12-small">
										<input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars( $nome, ENT_COMPAT, 'UTF-8', FALSE ); ?>" placeholder="Nome" required /><p></p>										

								<input type="text" name="sobrenome" id="sobrenome" value="<?php echo htmlspecialchars( $sobrenome, ENT_COMPAT, 'UTF-8', FALSE ); ?>" placeholder="Sobrenome" required />	<p></p>									
									
								<div class="row">
									
									<div class="col-12 col-12-small">
										
										<label for="Botões de radio">Com que g&ecirc;nero voc&ecirc; se identifica?</label>
									</div>

								<div class="col-4 col-12-small">
									<input type="radio" id="sexoM" name="sexo" value="M" <?php if( $sexo=='M' ){ ?> checked <?php } ?>>
									<label for="sexoM">Masculino</label>
								</div>
								<div class="col-4 col-12-small">
									<input type="radio" id="sexoF" name="sexo" value = "F" <?php if( $sexo=='F' ){ ?> checked <?php } ?>>
									<label for="sexoF">Feminino</label>
								</div>
								<div class="col-4 col-12-small">
									<input type="radio" id="sexoX" name="sexo" value = "X" <?php if( $sexo!='M' ){ ?><?php if( $sexo!='F' ){ ?>checked<?php } ?><?php } ?> >
									<label for="sexoX">Indefinido</label>
								</div>
								<br>
									</div>	
									
									<label for="Data nascimento">Data de nascimento</label>
								<input type="date" name="nascimento" value ="<?php echo htmlspecialchars( $nascimento, ENT_COMPAT, 'UTF-8', FALSE ); ?>" id="nascimento" placeholder="Data de nascimento"/><p></p>	
								
								<?php if( $erro=='emailExistente' ){ ?>

								<div class="alert alert-danger">
									<strong>Aviso!</strong> Já um cadastro com esse email, <a href="#">Recuperar sua senha?</a>.
								</div>
								<?php } ?>


								<input type="email" name="email" id="email" value="" placeholder="Email" required/><p></p>							

								<input type="tel" name="celular" class="cel-sp-mask" id="calular" value="<?php echo htmlspecialchars( $celular, ENT_COMPAT, 'UTF-8', FALSE ); ?>" placeholder="Celular (com DDD)" /><p></p>
								<input type="tel" name="fone" class="phone-ddd-mask" id="fone" value="<?php echo htmlspecialchars( $fone, ENT_COMPAT, 'UTF-8', FALSE ); ?>" placeholder="Telefone (com DDD)" /><p></p>
								
								<input type="password" name="senha" id="senha" value="" placeholder="Senha" required/><p></p>

								<input type="password" name="repetirSenha" id="repetirSenha" value="" placeholder="Repetir senha" /><p></p>

								<div class="col-12">
										<ul class="actions">
											<li><input type="reset" value="Cancelar" /></li>
											<li><input type="submit" value="Cadastrar" class="primary" /></li>
										</ul>
								</div>

								<!-- Verificar senhas iguais -->

								<script language='javascript' type='text/javascript'>

									var password = document.getElementById("senha")
										, confirm_password = document.getElementById("repetirSenha");
						
									  function validatePassword(){
										if(password.value != confirm_password.value) {
										  confirm_password.setCustomValidity("Senhas não conferem.");
										} else {
										  confirm_password.setCustomValidity('');
										}
									  }
						
									  password.onchange = validatePassword;
									  confirm_password.onkeyup = validatePassword;
						  </script>	
						</div>
						<div class="col-3 col-12-xsmall">
							<!-- Espaçador -->
						</div>
					</div>
				</form>

				

			</div>
		</section>