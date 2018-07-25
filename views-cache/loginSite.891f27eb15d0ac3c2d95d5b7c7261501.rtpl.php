<?php if(!class_exists('Rain\Tpl')){exit;}?>

		<!-- Highlights -->
		<section class="wrapper menor">	
				<div class="inner">
					<header class="special">
							

							<h2>LOGIN DE USU&Aacute;RIO</h2>
							<p>Agora voc&ecirc; pode fazer login com seu e-mail e senha.</p>		
						
							<form method="post" action="/login">
								<div class="row gtr-uniform">
									<div class="col-4 col-12-small">						
										<!-- Espaçador -->
			
									</div>
									<div class="col-4 col-12-small">														
										
											<input type="email" name="login" id="email" value="" placeholder="Email" required/><p></p>											
											<input type="password" name="senha" id="senha" value="" placeholder="Senha" required/><p></p>	

											<div class="col-12 col-12-small">	
											<input type="checkbox" id="permanecer-conectado" name="conectado" value="sim">
											<label for="permanecer-conectado">Lembrar-me neste computador</label>
											</div>											

											<input type="submit" value="Login" class="primary DISPLay: inline;" />

											<div class="col-12 col-12-small">
												<p></p>	
											Clique <A href="/recuperarSenha">aqui</A> se estiver com problemas para fazer login.
											</div>									
									</div>
									<div class="col-4 col-12-xsmall">
										<!-- Espaçador -->
									</div>
								</div>
							</form>									
						
						
					</header>			
			</div>
		</section>		

