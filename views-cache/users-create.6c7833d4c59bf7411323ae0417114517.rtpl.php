<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    Lista de Usuários

  </h1>

  <ol class="breadcrumb">

    <li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>

    <li><a href="/admin/users">Usuários</a></li>

    <li class="active"><a href="/admin/users/create">Cadastrar</a></li>

  </ol>

</section>



<!-- Main content -->

<section class="content">



  <div class="row">

  	<div class="col-md-12">

  		<div class="box box-success">

        <div class="box-header with-border">

          <h3 class="box-title">Novo Usuário</h3>

        </div>

        <!-- /.box-header -->

        <!-- form start -->

        <form role="form" action="/admin/users/create" method="post">

          <div class="box-body">

            <div class="form-group">

              <label for="desperson">Nome</label>

              <input type="text" required class="form-control" id="desperson" name="desperson" placeholder="Digite o nome">

            </div>

            <div class="form-group">

              <label for="deslogin">Login</label>

              <input type="text" required class="form-control" id="deslogin" name="deslogin" placeholder="Digite o login">

            </div>

            <div class="form-group">

              <label for="nrphone">Telefone</label>

              <input type="tel" class="form-control" id="nrphone" name="nrphone" placeholder="Digite o telefone">

            </div>

            <div class="form-group">

              <label for="desemail">E-mail</label>

              <input type="email" class="form-control" required id="desemail" name="desemail" placeholder="Digite o e-mail">

            </div>

            <div class="form-group">

              <label for="despassword">Senha</label>

              <input type="password" class="form-control" required id="despassword" name="despassword" placeholder="Digite a senha">

            </div>

            <div class="form-group">	

			  <label for="repeatdespassword">Repetir senha</label>	

              <input type="password" class="form-control" required id="repeatdespassword" placeholder="Repetir senha">

            </div>

            <div class="checkbox">

              <label>

                <input type="checkbox" name="inadmin" value="1"> Acesso de Administrador

              </label>

            </div>

          </div>

          <!-- /.box-body -->

          <div class="box-footer">

            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i>  Salvar</button>
            <a class="btn btn-danger pull-right" href="/admin/users"><i class="fa fa-close"></i>  Cancelar</a>

          </div>

        </form>

      </div>

  	</div>

  </div>


	<script language='javascript' type='text/javascript'>

              var password = document.getElementById("despassword")
				  , confirm_password = document.getElementById("repeatdespassword");

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




</section>

<!-- /.content -->

</div>

<!-- /.content-wrapper -->