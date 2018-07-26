<?php

use ZWare\PageAdmin;
use ZWare\Model\User;

$app->get('/admin(/)', function () {

    User::acessoAdmin();
    User::verifyLogin();

    $user = new User();
    $dados = $user->dadosUsuario();

    $page = new PageAdmin([], $dados, "main");
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

$app->get('/admin/users(/)', function () {

    User::acessoAdmin();
    User::verifyLogin();

    $user = new User();
    $dados = $user->dadosUsuario();

    $page = new PageAdmin([], $dados, "usuarios");

    $page->setTpl("users");
    exit();
});

$app->get('/admin/users/create(/)', function () {

    User::acessoAdmin();
    User::verifyLogin();
    $user = new User();
    $dados = $user->dadosUsuario();
    $page = new PageAdmin([], $dados, "usuarios");

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

$app->get('/admin/perfil(/)', function () {

    User::acessoAdmin();
    User::verifyLogin();

    $user = new User();

    $dados = $user->dadosUsuario();

    $page = new PageAdmin([], $dados, "perfil");

    $page->setTpl("perfil");
    exit();
});

$app->get('/admin/perfil/:operacao(/)', function ($op) {

    User::acessoAdmin();
    User::verifyLogin();

    $operacao = $op;

    $user = new User();

    $dados = $user->dadosUsuario();

    $dados["operacao"] = $operacao;

    $page = new PageAdmin();

    var_duump($dados);
    exit();

    $page->setTpl("users-update", $dados, "perfil");
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
                                                                        
                                                                        