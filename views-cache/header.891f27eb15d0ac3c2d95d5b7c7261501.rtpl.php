<?php if(!class_exists('Rain\Tpl')){exit;}?>

<!DOCTYPE HTML>
<!--
	Industrious by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>
		<title>ZWare Solutions</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="//assets.locaweb.com.br/locastyle/2.0.6/stylesheets/locastyle.css">
		<link rel="icon" href="\res/site/ico/favicon.ico">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="res/site/css/main.css" />
	</head>
	<body class="is-preload">            

		<!-- Header -->

			<header id="header">
					
				<a class="logo" href="/">ZWare</a>
				<nav>					

						<?php if( $local=='login' ){ ?>


							<a href="/cadastro">Cadastro </a>

						<?php } ?>



						<?php if( $local=='cadastro' ){ ?>

							
						<a href="/login"> Login </a>

						<?php } ?>


						<?php if( $local=='cadastro' ){ ?>

							
						<a href="/login"> Login </a>

						<?php } ?>



						<?php if( $nome_pessoa=='' ){ ?>

						
							<a href="/login"> Login </a>

							<?php if( $local!='cadastro' ){ ?>

							| <a href="/cadastro">Cadastro </a>
							<?php } ?>						
							
						<?php }else{ ?>

							
							<a href="#"><?php echo htmlspecialchars( $nome_pessoa, ENT_COMPAT, 'UTF-8', FALSE ); ?></a> <a href="/admin/logout">(sair) </a> | 
							<a href="#">Gerenciar listas </a>

							<?php if( $permissao=='MAS' ){ ?>

								| 
								<a href="/admin">Admin </a>
							<?php } ?>

						<?php } ?>

					
							 
				</nav>

			</header>		