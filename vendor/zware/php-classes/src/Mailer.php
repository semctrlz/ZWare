<?php 

namespace ZWare;

use Rain\Tpl;

class Mailer {

const USERNAME = "contato@zware.com.br";
const PASSWORD = "dbadg975";
const NAMEFROM = "Zware";


private $mail;
    
    public function __construct($toAddress, $toName, $subject, $tplName, $data = array()){
        
        $config = array(
            "tpl_dir"   =>  $_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."email".DIRECTORY_SEPARATOR,
            "cache_dir" =>  $_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."views-cache".DIRECTORY_SEPARATOR,
            "debug"     =>  false
        );
        
        Tpl::configure($config);
        
        $tpl = new Tpl;
        
        foreach ($data as $key => $value)
        {
            $tpl->assign($key, $value);
        }
        
        $html = $tpl->draw($tplName,  true);
                       
        $this->mail = new \PHPMailer();
        
        //Diz para o PHPMailer usar o SMTP
        $this->mail->isSMTP();
        
        //Enable SMTP debugging
        //0 = off
        //1 = Client message
        //2 = Client and server message
        $this->mail->SMTPDebug = 0;

        $this->CharSet = 'UTF-8';
        
        //Requisita um HTML amigável para o debug
        $this->mail->Debugoutput = 'html';
        
  
        $this->mail->Host = 'email-ssl.com.br';
        $this->mail->Port = 587;
        $this->mail->SMTPSecure = 'tsl';        
        $this->mail->SMTPAuth = true;
       



        $this->mail->Username = Mailer::USERNAME;
        $this->mail->Password = Mailer::PASSWORD;
        
        $this->mail->setFrom(Mailer::USERNAME, Mailer::NAMEFROM);
        
        $this->mail->addAddress($toAddress, $toName);
        
        $this->mail->Subject = $subject;
        
        $this->mail->msgHTML($html);
        
        $this->mail->AltBody = 'This is a plain-text body';        
    }
    
    public function send(){
        
        return $this->mail->send();
    }
}

?>

