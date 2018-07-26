<?php


use ZWare\Page;
use ZWare\Model\User;

$app->get('/', function () {

    if ($_SERVER['REMOTE_ADDR'] != "127.0.0.1") {

        if (! isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
            if (! headers_sent()) {
                header("Status: 301 Moved Permanently");
                header(sprintf('Location: https://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']));
                exit();
            }
        }
    }
    // Verificar se Logado

    $user = new User();
    $user->loadCookie();
    $dados = $user->dadosUsuario();

    $page = new Page([
        "tipoHeader" => "header"
    ], $dados);

    $page->setTpl("index");
});

$app->get('/login(/)', function () {

    $dados = array();
    $dados["nome_pessoa"] = "";
    $dados["permissao"] = "";

    $page = new Page([
        "tipoHeader" => "header"
    ], $dados, "login");

    $page->setTpl("loginSite");
    exit();
});

$app->post('/login(/)', function () {

    if (isset($_POST["conectado"]) && $_POST["conectado"] == "sim") {
        $conexaoAutomatica = true;
    } else {
        $conexaoAutomatica = false;
    }

    User::login($_POST["login"], $_POST["senha"], $conexaoAutomatica);

    header("location: /");
    exit();
});

$app->get('/recuperarSenha(/)', function () {

    $msg = "inicial";
    $code = "";
    $idUsuario = "";

    if (isset($_GET["msg"])) {
        $msg = $_GET["msg"];
    }

    if (isset($_GET["code"])) {
        $code = $_GET["code"];

        if ($code != "") {
            // Verificar validade do codigo
            if (User::validaCodigoAlteracaoSenha($code)) {
                $idUsuario = User::idCodigoAlteracaoSenha($code);
                $msg = "alteracaoLiberada";
            } else {
                $msg = "codigoExpirado";
            }
        }
    }

    $dados = array(
        "msg" => $msg,
        "code" => $code,
        "nome_pessoa" => "",
        "idUsuario" => $idUsuario
    );

    $page = new Page([
        "tipoHeader" => "header"
    ], $dados, "recuperaSenha");

    $page->setTpl("recuperarSenha");
    exit();
});

$app->post('/recuperarSenha(/)', function () {

    $email = "";
    $senha = "";
    $id = "";

    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    } else {
        if (isset($_POST["senha"])) {
            $senha = $_POST["senha"];
        }

        if (isset($_POST["idUsuario"])) {
            $id = $_POST["idUsuario"];
        }

        if ($senha != "") {
            $User = new User();
            $User->alteraSenha($id, $senha);
            header("location: /recuperarSenha?msg=alterada");
            exit();
        }
    }

    if (User::existeEmailUsuario($email)) {
        header("location: /recuperarSenha?msg=sucesso");
    } else {
        header("location: /recuperarSenha?msg=invalidEmail");
    }

    exit();
});

$app->get('/cadastro(/)', function () {

    $user = new User();
    $dados = $user->dadosUsuario();

    // Erifica se existe os Get, caso não cria a variável mas a deixa vazia

    if (isset($_GET["erro"])) {
        $dados["erro"] = $_GET["erro"];
    } else {
        $dados["erro"] = "emailValido";
    }
    if (isset($_GET["nome"])) {
        $dados["nome"] = $_GET["nome"];
    } else {
        $dados["nome"] = "";
    }
    if (isset($_GET["sobrenome"])) {
        $dados["sobrenome"] = $_GET["sobrenome"];
    } else {
        $dados["sobrenome"] = "";
    }
    if (isset($_GET["celular"])) {
        $dados["celular"] = $_GET["celular"];
    } else {
        $dados["celular"] = "";
    }
    if (isset($_GET["fone"])) {
        $dados["fone"] = $_GET["fone"];
    } else {
        $dados["fone"] = "";
    }
    if (isset($_GET["sexo"])) {
        $dados["sexo"] = $_GET["sexo"];
    } else {
        $dados["sexo"] = "X";
    }
    if (isset($_GET["nascimento"])) {
        $dados["nascimento"] = date('Y-m-d', strtotime($_GET["nascimento"]));
    } else {
        $dados["nascimento"] = "";
    }

    $page = new Page([
        "tipoHeader" => "header"
    ], $dados, "cadastro");
    $page->setTpl("cadastro");
});

$app->post('/cadastro(/)', function () {

    $user = new User();
    $user->setData($_POST);

    $dados = $user->cadastraUsuario();

    if (isset($dados)) {

        header("location: /confirmacaoEnvioEmail");
    } else {

        $user->setData($_POST);
        header("location: /cadastro?erro=emailExistente&nome=" . $user->getnome() . "&sobrenome=" . $user->getsobrenome() . "&celular=" . $user->getcelular() . "&fone=" . $user->getfone() . "&sexo=" . $user->getsexo() . "&nascimento=" . $user->getnascimento());
    }
    exit();
});

$app->get('/confirmacaoEnvioEmail(/)', function () {
    $user = new User();
    $dados = $user->dadosUsuario();
    $page = new Page([
        "tipoHeader" => "header"
    ], $dados, "confirmacaoEmail");
    $page->setTpl("confirmacaoEnvioEmail");
});

$app->get('/validacaoEmail(/)', function () {
    $user = new User();
    $dados = array();

    if (isset($_GET["code"])) {
        $dados["code"] = $_GET["code"];
    } else {
        $dados["code"] = "erro";
    }

    $retorno = $user->verificarValidacaoEmail($dados["code"]);

    $dados = $user->dadosUsuario();

    if ($retorno == true) {
        $status = "sucesso";
    } else {
        $status = "fracasso";
    }

    $dados["status"] = $status;

    $page = new Page([
        "tipoHeader" => "header"
    ], $dados, "emailValidado");
    $page->setTpl("emailValidado");
});
                                    
                                    