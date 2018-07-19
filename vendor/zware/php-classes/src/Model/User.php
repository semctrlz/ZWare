<?php

namespace ZWare\Model;

use \ZWare\DB\Sql;

use \ZWare\Model;

use ZWare\Mailer;

class User extends Model {

    const SESSION = "User";

    const IV ='Js2hS50bvoNDa51m';

    const CRIPTKEY = 'ALG5Vg68wmgose71';

    const METHOD = 'aes-256-cbc';

    public static function login($login, $senha)
    {       
        $sql = new Sql();

        $results = $sql->select("select * from tb_usuarios u join tb_pessoas p on p.id_pessoa = u.pessoas_id_pessoa where u.login_usuario = :USUARIO or p.email_pessoa = :USUARIO", array(

           ":USUARIO"=>$login           
        ));
      
        
        if(count($results) === 0)

        {
            throw new \Exception("Usuário não encontrado ou senha inválida.");
        }

        $data = $results[0];

        if(password_verify($senha, $data["senha_usuario"]) === true)
        {
            $user = new User();

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getData();            

            return $user;
        }

        else

        {
            throw new \Exception("Usuário não encontrado ou senha inválida.");
        }

    }

    public static function verifyLogin($inadmin = true)

    {

        if(

            !isset($_SESSION[User::SESSION]) || 

            !$_SESSION[User::SESSION] || 

            !(int)$_SESSION[user::SESSION]["id_usuario"] > 0 /*|| 

            (bool)$_SESSION[user::SESSION]["inadmin"] != $inadmin
*/
            )

        {

            header("Location: /admin/login");

            exit;

        }

    }

    

    public static function verifyLogado()

    {

        if(

            isset($_SESSION[User::SESSION]) &&

            $_SESSION[User::SESSION] &&

            (int)$_SESSION[user::SESSION]["iduser"] > 0

            )

        {

            if((bool)$_SESSION[user::SESSION]["inadmin"] === true)

            {

                header("Location: /admin");

                exit;

            }

            else

            {

                header("Location: /");

                exit;

            }

        }        

    }

    

    public static function logout()

    {

        $_SESSION[User::SESSION] = NULL;

    }

    

    public static function listAll()

    {

        $sql = new Sql();

        

        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b ON a.idperson = b.idperson ORDER BY b.desperson");

        

        return $results;

    }

    

    public static function passwordEncript($password){

        $options = [

            'cost'=>12

            ];


        return password_hash($password, PASSWORD_BCRYPT, $options);

    }

        

    public function save(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_users_save(:DESPERSON, :DESLOGIN, :DESPASSWORD, :DESEMAIL, :NRPHONE, :INADMIN)", array(

            ":DESPERSON"=>$this->getdesperson(),

            ":DESLOGIN"=>$this->getdeslogin(),

            ":DESPASSWORD"=>User::passwordEncript($this->getdespassword()),

            ":DESEMAIL"=>$this->getdesemail(),

            ":NRPHONE"=>$this->getnrphone(),

            ":INADMIN"=>$this->getinadmin()

        ));

        

        $this->setData($results[0]);

    }

    

    public function get($iduser)

    {

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b ON a.idperson = b.idperson WHERE a.iduser = :IDUSER", array(

            ":IDUSER"=>$iduser

        ));

        

        $this->setData($results[0]);

        

    }

    

    public function update()

    {

        $sql = new Sql();

                        

        $results = $sql->select("CALL sp_usersupdate_save(:IDUSER, :DESPERSON, :DESLOGIN, :DESPASSWORD, :DESEMAIL, :NRPHONE, :INADMIN)", array(

            ":IDUSER"=>$this->getiduser(),

            ":DESPERSON"=>$this->getdesperson(),

            ":DESLOGIN"=>$this->getdeslogin(),

            ":DESPASSWORD"=>User::passwordEncript($this->getdespassword()),

            ":DESEMAIL"=>$this->getdesemail(),

            ":NRPHONE"=>$this->getnrphone(),

            ":INADMIN"=>$this->getinadmin()

        ));

        

        $this->setData($results[0]);

    }

    

    public function delete(){

        

        $sql = new Sql();

        

        $sql->query("CALL sp_users_delete(:IDUSER)", array(

           ":IDUSER"=>$this->getiduser()

        ));

    }

    

    public static function getForgot($email){

        

        $sql = new Sql();

        

        $results = $sql->select("SELECT * FROM tb_persons a INNER JOIN tb_users b ON a.idperson = b.idperson WHERE a.desemail = :EMAIL", array(

            ":EMAIL"=>$email

            ));

        

        if(count($results) === 0){

            throw new \Exception("Não foi possível recuperar a senha.");

        }

        else{

            $data = $results[0];

            

            $results2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:IDUSER, :DESIP)", array(

                ":IDUSER"=>$data["iduser"],

                ":DESIP"=>$_SERVER["REMOTE_ADDR"]

            ));

            

            if(count($results2) === 0){

                throw new \Exception("Não foi possível recuperar a senha.");

            }

            else{

                $dataRecovery = $results2[0];

                $code = base64_encode(openssl_encrypt($dataRecovery["idrecovery"], User::METHOD, User::CRIPTKEY, false, User::IV));                

                $link = "https://zware.websiteseguro.com/admin/forgot/reset?code=$code";

                

                $mailer = new Mailer($data["desemail"], $data["desperson"], "Redefinir senha do site ZWare", "forgot", array(

                    "name"=>$data["desperson"],

                    "link"=>$link

                ));

                

                $mailer->send();

                return $data;

                

            }

        }        

    }

    

    public static function validForgotDecrypt($code){

                       

        $idrecovery = openssl_decrypt(base64_decode($code), User::METHOD, User::CRIPTKEY, false,  User::IV);

        

        $sql = new Sql();

        

        $results = $sql->select("SELECT * FROM tb_userspasswordsrecoveries a

                    INNER JOIN tb_users b ON b.iduser = a.iduser

                    INNER JOIN tb_persons c ON b.idperson = c.idperson



                    WHERE a.idrecovery = :IDRECOVERY

                    AND a.dtrecovery is NULL

                    AND date_add(a.dtregister, interval 1 hour) >= NOW()", array(

                        ":IDRECOVERY"=>$idrecovery

                    ));

        

        if(count($results) === 0){

            throw new \Exception("Não foi possível recuperar a senha.");

        }

        else

        {

            return $results[0];

        }

    }

    

    public static function setForgotUsed($idrecovery)

        {

            $sql = new Sql();

            

            $sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :IDRECOVERY", array(

                ":IDRECOVERY"=>$idrecovery

            ));

        }

    

    public function setPassword($password){

               

        $sql = new Sql();

        $sql->query("UPDATE tb_users SET despassword = :PASSWORD WHERE iduser = :IDUSER", array(

            ":PASSWORD"=>User::passwordEncript($password),

            ":IDUSER"=>$this->getiduser()

        ));

        

    }

}





?>