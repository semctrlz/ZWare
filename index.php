<?php
session_start();

require_once ("vendor/autoload.php");

use Slim\Slim;
use ZWare\Page;
use ZWare\PageAdmin;
use ZWare\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function () {

    // Verificar se Logado

    $user = new User();
    $dados = $user->dadosUsuario();

    $page = new Page([
        "tipoHeader" => "header"
    ], $dados);
    $page->setTpl("index");
});

$app->get('/admin(/)', function () {

    User::acessoAdmin();
    User::verifyLogin();
    $page = new PageAdmin([], [

        "Nome" => User::retornaNome()
    ], "main");
    $page->setTpl("index");
});

$app->get('/admin/login(/)', function () {

    User::acessoAdmin();
    User::verifyLogado();

    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("login");
});

$app->post('/admin/login(/)', function () {

    User::acessoAdmin();
    User::login($_POST["login"], $_POST["senha"]);

    header("location: /admin");
    exit();
});

$app->get('/admin/logout(/)', function () {

    User::logout();
    header("location: /");
    exit();
});

$app->get('/admin/login(/)', function () {

    User::verifyLogado();

    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("login");
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

    User::login($_POST["login"], $_POST["senha"]);

    header("location: /");

    exit();
});

$app->get('/recuperarSenha(/)', function () {

    $page = new Page([
        "tipoHeader" => "header"
    ], array(), "login");

    $page->setTpl("recuperarSenha");
    exit();
});

$app->post('/login(/)', function () {

    User::login($_POST["login"], $_POST["senha"]);

    header("location: /");

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

// Admin

$app->get('/admin/users(/)', function () {

    User::acessoAdmin();
    User::verifyLogin();

    $page = new PageAdmin([], [

        "Nome" => User::retornaNome()
    ], "usuarios");

    $page->setTpl("users");
    exit();
});

$app->get('/admin/users/create(/)', function () {

    User::acessoAdmin();
    User::verifyLogin();

    $page = new PageAdmin([], [

        "Nome" => User::retornaNome()
    ], "usuarios");

    $page->setTpl("users-create");
    exit();
});

$app->get('/admin/users/:iduser/delete(/)', function ($iduser) {
    User::acessoAdmin();
    User::verifyLogin();

    $user = new User();
    $user->get((int) $iduser);

    $user->delete();

    header("Location: /admin/users");
    exit();
});

$app->get('/admin/users/:iduser(/)', function ($iduser) {

    User::acessoAdmin();
    User::verifyLogin();

    $page = new PageAdmin();

    $user = new User();

    $user->get((int) $iduser);

    $page->setTpl("users-update", array(
        "user" => $user->getData()
    ));
    exit();
});

$app->post('/admin/users/create(/)', function () {
    User::acessoAdmin();
    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $user->setData($_POST);

    $user->save();

    header("Location: /admin/users");
    exit();
});

$app->post('/admin/users/:iduser(/)', function ($iduser) {
    User::acessoAdmin();
    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $user->get((int) $iduser);
    $user->setData($_POST);
    $user->update();

    header("Location: /admin/users");
    exit();
});

$app->get('/admin/forgot(/)', function () {

    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("forgot");
});

$app->post('/admin/forgot(/)', function () {

    $user = User::getForgot($_POST["email"]);
    header("Location: /admin/forgot/sent");
    exit();
});

$app->get('/admin/forgot/sent(/)', function () {

    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("forgot-sent");
});

$app->get('/admin/forgot/reset(/)', function () {

    $user = User::validForgotDecrypt($_GET["code"]);

    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("forgot-reset", array(
        "name" => $user["desperson"],
        "code" => $_GET["code"]
    ));
});

$app->post('/admin/forgot/reset(/)', function () {

    $forgot = User::validForgotDecrypt($_POST["code"]);

    User::setForgotUsed($forgot["idrecovery"]);

    $user = new User();

    $user->get((int) $forgot["iduser"]);

    $user->setPassword($_POST["password"]);

    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("forgot-reset-success");
});

$app->run();

?>