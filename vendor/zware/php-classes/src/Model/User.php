<?php
namespace ZWare\Model;

use ZWare\DB\Sql;
use ZWare\Model;
use ZWare\Mailer;

class User extends Model
{

    const SESSION = "User";

    const IV = 'Js2hS50bvoNDa51m';

    const CRIPTKEY = 'ALG5Vg68wmgose71';

    const METHOD = 'aes-256-cbc';

    public static function login($login, $senha)
    {
        $sql = new Sql();

        $results = $sql->select("select * from tb_usuarios u join tb_pessoas p on p.id_pessoa = u.pessoas_id_pessoa where u.login_usuario = :USUARIO or p.email_pessoa = :USUARIO", array(

            ":USUARIO" => $login
        ));

        if (count($results) === 0) {
            throw new \Exception("Usuário não encontrado ou senha inválida.");
        }

        $data = $results[0];

        if (password_verify($senha, $data["senha_usuario"]) === true) {
            $user = new User();

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getData();

            return $user;
        } else {
            throw new \Exception("Usuário não encontrado ou senha inválida.");
        }
    }

    public static function verifyLogin($inadmin = true)
    {
        if (! isset($_SESSION[User::SESSION]) || ! $_SESSION[User::SESSION] || 

        // Verifica se o usuario pode logar, caso sim, efetua o Login.

        ! (int) $_SESSION[user::SESSION]["id_usuario"] > 0) {

            header("Location: /admin/login");
            exit();
        }
    }

    public static function permissaoUsuarioMaster()
    {
        if (isset($_SESSION[User::SESSION]) && $_SESSION[User::SESSION] && (int) $_SESSION[user::SESSION]["id_usuario"] > 0) {

            $sql = new Sql();

            $results = $sql->select("select * from tb_permissoes where usuarios_id_usuario = :USUARIO and tipo_permissao = :PERMISSAO", array(

                ":USUARIO" => (int) $_SESSION[user::SESSION]["id_usuario"],
                ":PERMISSAO" => "MAS"
            ));

            if (count($results) === 0) {
                return false;
                exit();
            } else {
                return true;
                exit();
            }
        } else {
            return false;
            exit();
        }
    }

    public static function acessoAdmin()
    {
        if (isset($_SESSION[User::SESSION]) && $_SESSION[User::SESSION] && (int) $_SESSION[user::SESSION]["id_usuario"] > 0) {

            $sql = new Sql();

            $results = $sql->select("select * from tb_permissoes where usuarios_id_usuario = :USUARIO and tipo_permissao = :PERMISSAO", array(

                ":USUARIO" => (int) $_SESSION[user::SESSION]["id_usuario"],
                ":PERMISSAO" => "MAS"
            ));

            if (count($results) === 0) {
                header("Location: /");
                exit();
            } else {
                return true;
                exit();
            }
        } else {
            header("Location: /");
            exit();
        }
    }

    public static function logado()
    {
        if (isset($_SESSION[User::SESSION]) && $_SESSION[User::SESSION] && (int) $_SESSION[user::SESSION]["id_usuario"] > 0) {
            return true;
            exit();
        } else {
            return false;
            exit();
        }
    }

    public function dadosUsuario()
    {
        $sql = new Sql();

        if (isset($_SESSION[User::SESSION]) && $_SESSION[User::SESSION] && (int) $_SESSION[user::SESSION]["id_usuario"] > 0) {
            $results = $sql->select("call sp_retorna_dados_usuario(:id_usuario)", array(

                ":id_usuario" => (int) $_SESSION[user::SESSION]["id_usuario"]
            ));

            // Cria uma nova variável com o primeiro registro da consulta acima
            $NovaArray = $results[0];

            // Trata nome e sobrenome
            $NovaArray["nome_pessoa"] = utf8_encode(mb_convert_case($results[0]["nome_pessoa"], MB_CASE_TITLE, "ISO-8859-1"));
            $NovaArray["sobrenome_pessoa"] = utf8_encode(mb_convert_case($results[0]["sobrenome_pessoa"], MB_CASE_TITLE, "ISO-8859-1"));

            return $NovaArray;
        } else {
            $results = [
                "nome_pessoa" => ""
            ];
            return $results;
        }
    }

    public static function verifyLogado()
    {
        if (isset($_SESSION[User::SESSION]) && $_SESSION[User::SESSION] && (int) $_SESSION[user::SESSION]["id_usuario"] > 0) {

            if ((bool) $_SESSION[user::SESSION]["inadmin"] === true) {

                header("Location: /admin");

                exit();
            } else {

                header("Location: /");

                exit();
            }
        }
    }

    public static function formataNome($nome = string)
    {
        return utf8_encode(mb_convert_case($nome, MB_CASE_TITLE, "ISO-8859-1"));
    }

    public static function retornaNome()
    {
        $nome = (string) $_SESSION[user::SESSION]["nome_pessoa"];
        return utf8_encode(mb_convert_case($nome, MB_CASE_TITLE, "ISO-8859-1"));
    }

    public static function logout()
    {
        $_SESSION[User::SESSION] = NULL;
    }

    public function cadastraUsuario()
    {

        // Verifica se o email de usuario ja existe, caso sim, retorna a pagina com um alerta
        $sql = new sql();

        // select pessoas_id_pessoa from tb_usuarios u join tb_pessoas p on u.pessoas_id_pessoa = p.id_pessoa where p.email_pessoa = 'email';

        $codigoParaConfirmacao = hash('ripemd160', $this->getemail() . time());
        $senhaSegura = User::passwordEncript($this->getsenha());

        $results = $sql->select("select pessoas_id_pessoa from tb_usuarios u join tb_pessoas p on u.pessoas_id_pessoa = p.id_pessoa where p.email_pessoa = :EMAIL;", array(

            ":EMAIL" => $this->getemail()
        ));

        if (count($results) > 0) {

            // Existe outro usuário cadastrado com este email
            $this->{"set" . "erro"}("emailExiste");
        } else {
            // Não existe outro usuario com este email

            // Cria usuario sem link de pessoa

            $sql->select("CALL sp_cadastra_usuario_comum(:HASH, :NOME, :SOBRENOME, :EMAIL, :CELULAR, :FONE, :SEXO, :NASCIMENTO, :SENHA);", array(
                ":HASH" => $codigoParaConfirmacao,
                ":NOME" => User::formataTexto($this->getnome(), true, true, true),
                ":SOBRENOME" => User::formataTexto($this->getsobrenome(), true, true, true),
                ":EMAIL" => $this->getemail(),
                ":CELULAR" => $this->getcelular(),
                ":FONE" => $this->getfone(),
                ":SEXO" => $this->getsexo(),
                ":NASCIMENTO" => $this->getnascimento(),
                ":SENHA" => $senhaSegura
            ));

            // Envia um email de confirmação

            $link = "http://zware.com.br/validacaoEmail?code=$codigoParaConfirmacao";

            $mailer = new Mailer($this->getemail(), $this->getnome(), "Verifique seu e-mail no site ZWare", "verificacao", array(
                "name" => User::formataNome($this->getnome()),
                "link" => $link
            ));

            $mailer->send();

            // Preenche array com os dados informados
            $dados = array(
                "nome_pessoa" => User::formataTexto($this->getnome(), true, true, true),
                "sobrenome_pessoa" => User::formataTexto($this->getsobrenome(), true, true, true),
                "email_pessoa" => $this->getemail(),
                "celular" => $this->getcelular(),
                "fone" => $this->getfone(),
                "erro" => "emailValido"
            );

            return $dados;
        }
    }

    public static function listAll()
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b ON a.idperson = b.idperson ORDER BY b.desperson");

        return $results;
    }

    public static function passwordEncript($password)
    {
        $options = [

            'cost' => 12
        ];

        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public function save()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_users_save(:DESPERSON, :DESLOGIN, :DESPASSWORD, :DESEMAIL, :NRPHONE, :INADMIN)", array(

            ":DESPERSON" => $this->getdesperson(),

            ":DESLOGIN" => $this->getdeslogin(),

            ":DESPASSWORD" => User::passwordEncript($this->getdespassword()),

            ":DESEMAIL" => $this->getdesemail(),

            ":NRPHONE" => $this->getnrphone(),

            ":INADMIN" => $this->getinadmin()
        ));

        $this->setData($results[0]);
    }

    // Retorna os dados do usuario através do id de usuário
    public function get($id_usuario)
    {
        $sql = new Sql();

        $results = $sql->select("select p.id_pessoa, p.nome_pessoa, p.sobrenome_pessoa, p.email_pessoa, p.data_cadastro, p.ativo, p.email_verificado, u.id_usuario, u.login_usuario from tb_pessoas p 
        join tb_usuarios u on u.pessoas_id_pessoa = p.id_pessoa where id_usuario = :id_usuario", array(

            ":id_usuario" => $id_usuario
        ));

        $this->setData($results[0]);
    }

    private function retornaDadosUsuario($id_usuario)
    {
        $sql = new Sql();

        $results = $sql->select("select p.id_pessoa, p.nome_pessoa, p.sobrenome_pessoa, p.email_pessoa, p.data_cadastro, p.ativo, p.email_verificado, u.id_usuario, u.login_usuario from tb_pessoas p 
        join tb_usuarios u on u.pessoas_id_pessoa = p.id_pessoa where id_usuario = :id_usuario", array(

            ":id_usuario" => $id_usuario
        ));

        $this->setData($results[0]);
    }

    public function update()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_usersupdate_save(:id_usuario, :DESPERSON, :DESLOGIN, :DESPASSWORD, :DESEMAIL, :NRPHONE, :INADMIN)", array(

            ":id_usuario" => $this->getid_usuario(),

            ":DESPERSON" => $this->getdesperson(),

            ":DESLOGIN" => $this->getdeslogin(),

            ":DESPASSWORD" => User::passwordEncript($this->getdespassword()),

            ":DESEMAIL" => $this->getdesemail(),

            ":NRPHONE" => $this->getnrphone(),

            ":INADMIN" => $this->getinadmin()
        ));

        $this->setData($results[0]);
    }

    public function delete()
    {
        $sql = new Sql();

        $sql->query("CALL sp_users_delete(:id_usuario)", array(

            ":id_usuario" => $this->getid_usuario()
        ));
    }

    public static function getForgot($email)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_persons a INNER JOIN tb_users b ON a.idperson = b.idperson WHERE a.desemail = :EMAIL", array(

            ":EMAIL" => $email
        ));

        if (count($results) === 0) {

            throw new \Exception("Não foi possível recuperar a senha.");
        } else {

            $data = $results[0];

            $results2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:id_usuario, :DESIP)", array(

                ":id_usuario" => $data["id_usuario"],

                ":DESIP" => $_SERVER["REMOTE_ADDR"]
            ));

            if (count($results2) === 0) {

                throw new \Exception("Não foi possível recuperar a senha.");
            } else {

                $dataRecovery = $results2[0];

                $code = base64_encode(openssl_encrypt($dataRecovery["idrecovery"], User::METHOD, User::CRIPTKEY, false, User::IV));

                $link = "https://zware.websiteseguro.com/admin/forgot/reset?code=$code";

                $mailer = new Mailer($data["desemail"], $data["desperson"], "Redefinir senha do site ZWare", "forgot", array(

                    "name" => $data["desperson"],

                    "link" => $link
                ));

                $mailer->send();

                return $data;
            }
        }
    }

    public static function validForgotDecrypt($code)
    {
        $idrecovery = openssl_decrypt(base64_decode($code), User::METHOD, User::CRIPTKEY, false, User::IV);

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_userspasswordsrecoveries a

                    INNER JOIN tb_users b ON b.id_usuario = a.id_usuario

                    INNER JOIN tb_persons c ON b.idperson = c.idperson



                    WHERE a.idrecovery = :IDRECOVERY

                    AND a.dtrecovery is NULL

                    AND date_add(a.dtregister, interval 1 hour) >= NOW()", array(

            ":IDRECOVERY" => $idrecovery
        ));

        if (count($results) === 0) {

            throw new \Exception("Não foi possível recuperar a senha.");
        } else {

            return $results[0];
        }
    }

    public static function setForgotUsed($idrecovery)
    {
        $sql = new Sql();

        $sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :IDRECOVERY", array(

            ":IDRECOVERY" => $idrecovery
        ));
    }

    public function setPassword($password)
    {
        $sql = new Sql();

        $sql->query("UPDATE tb_users SET despassword = :PASSWORD WHERE id_usuario = :id_usuario", array(

            ":PASSWORD" => User::passwordEncript($password),

            ":id_usuario" => $this->getid_usuario()
        ));
    }

    public function verificarValidacaoEmail($codigo = string)
    {
        $sql = new Sql();

        $sqlResult = $sql->select("SELECT nome_pessoa, sobrenome_pessoa, email_pessoa, celular, fone, sexo_pessoa, data_nascimento_pessoa, 
        senha FROM tb_confirmacao_email where data_envio > subdate(current_date(), interval 24 hour) and hash_usuario = :hashU;", array(
            ":hashU" => $codigo
        ));

        if (count($sqlResult) > 0) {

            $results = $sqlResult[0];

            $this->setData($results);

            $sqlResult = $sql->select("CALL sp_efetiva_cadastro(:NOME, :SOBRENOME, :EMAIL, :SEXO, :NASCIMENTO, :CELULAR, :FONE, :SENHA);", array(
                ":NOME" => User::formataTexto($this->getnome_pessoa(), true, true, true),
                ":SOBRENOME" => User::formataTexto($this->getsobrenome_pessoa(), true, true, true),
                ":EMAIL" => $this->getemail_pessoa(),
                ":SEXO" => $this->getsexo_pessoa(),
                ":NASCIMENTO" => $this->getdata_nascimento_pessoa(),
                ":CELULAR" => $this->getcelular(),
                ":FONE" => $this->getfone(),
                ":SENHA" => $this->getsenha()
            ));

            if (count($sqlResult) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

?>