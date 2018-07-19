<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>AdminLTE 2 | Reset Password</title>

  <!-- Tell the browser to be responsive to screen width -->

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.7 -->

  <link rel="stylesheet" href="\res/admin/bower_components/bootstrap/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->

  <link rel="stylesheet" href="\res/admin/bower_components/font-awesome/css/font-awesome.min.css">

  <!-- Ionicons -->

  <link rel="stylesheet" href="\res/admin/bower_components/Ionicons/css/ionicons.min.css">

  <!-- Theme style -->

  <link rel="stylesheet" href="\res/admin/dist/css/AdminLTE.min.css">



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->

</head>

<body class="hold-transition lockscreen">

<!-- Automatic element centering -->

<div class="lockscreen-wrapper">

  <div class="lockscreen-logo">

    <a href="/res/admin/index2.html"><b>Z</b>Ware</a>

  </div>

  

   <div class="help-block text-center">

     Olá <?php echo htmlspecialchars( $name, ENT_COMPAT, 'UTF-8', FALSE ); ?>, digite uma nova senha:

    </div>



  <!-- START LOCK SCREEN ITEM -->

  <div class="lockscreen-item">



    <!-- lockscreen credentials (contains the form) -->

    <form  action="/admin/forgot/reset" method="post">

      <input type="hidden" name="code" value="<?php echo htmlspecialchars( $code, ENT_COMPAT, 'UTF-8', FALSE ); ?>">

      <div class="input-group">

        <input type="password" class="form-control" placeholder="Digite a nova senha" name="password" id="despassword"><p>
          <input type="password" class="form-control" placeholder="Digite a nova senha" id="repeatdespassword">

        <div class="input-group-btn">

          <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>

        </div>

      </div>

    </form>

    <!-- /.lockscreen credentials -->

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

  <!-- /.lockscreen-item -->

  

  <div class="lockscreen-footer text-center">

    Copyright &copy; 2014-2016 <b><a href="http://almsaeedstudio.com" class="text-black">Almsaeed Studio</a></b><br>

    All rights reserved

  </div>

</div>

<!-- /.center -->



<!-- jQuery 2.2.3 -->

<script src="\res/admin/bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->

<script src="\res/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</body>

</html>

