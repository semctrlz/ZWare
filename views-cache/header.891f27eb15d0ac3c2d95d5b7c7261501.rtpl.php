<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE HTML>
<!--
	Industrious by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>
		<title>ZWare Solutions</title>
		<meta charset="utf-8" />
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
						<?php if( $nome_pessoa=='' ){ ?>
						
						<a href="/login"> Login </a> |
						<a href="#"> Cadastro </a>						
						
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