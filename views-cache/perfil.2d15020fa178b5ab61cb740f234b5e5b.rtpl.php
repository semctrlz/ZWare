<?php if(!class_exists('Rain\Tpl')){exit;}?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

	<!-- Content Header (Page header) -->

	<section class="content-header">

		<h1>

			Perfil <small>Gerencie aqui suas informa&ccedil;&otilde;es</small>

		</h1>

		<ol class="breadcrumb">

			<li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>

			<li><a href="/admin/perfil">Perfil</a></li>


		</ol>

	</section>



	<!-- Main content -->

	<section class="content container-fluid">

		<!-- Horizontal Form -->

		<div class="box box-info">
			<div class="box-header with-border ">
				<h3 class="box-title">Editar perfil</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->
			<form class="form-horizontal">
				<div class="box-body ">

					<div class="form-group">

						<label for="nome" class="col-sm-1 control-label">Nome</label>
						<div class="col-sm-3">
							<input type="text" class="col-sm-2 form-control" id="nome"
								placeholder="Nome" name="nome">
						</div>
						<label for="sobrenome" class="col-sm-1 control-label">Sobrenome</label>
						<div class="col-sm-3">
							<input type="text" class="col-sm-2 form-control" id="sobrenome"
								placeholder="Sobrenome" name="sobrenome">
						</div>
					</div>

					<div class="form-group">
						<label for="inputEmail3" class="col-sm-1 control-label">Email</label>
						<div class="col-sm-3">
							<input type="email" class="col-sm-2 form-control"
								id="inputEmail3" placeholder="Email">
						</div>
					</div>

					<div class="form-group">

						<label for="celular" class="col-sm-1 control-label">Celular</label>
						<div class="col-sm-3">
							<input type="text" class="col-sm-2 form-control cel-sp-mask"
								id="celular" name="celular" data-inputmask="'mask': ['(99)9999 99999']" data-mask>
						</div>
						<label for="fone" class="col-sm-1 control-label" >Fone</label>
						<div class="col-sm-3">
							<input type="text" class="col-sm-2 form-control" id="fone" name="fone"  data-inputmask="'mask': ['(99)9999 99999']" data-mask>
						</div>
					</div>				
					
					
				</div>
			</form>
		</div>
		<!--------------------------

        | Your Page Content Here |

        -------------------------->
	</section>

	<!-- /.content -->

</div>

<!-- /.content-wrapper -->