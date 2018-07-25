<?php

namespace ZWare\Model;


use ZWare\DB\Sql;
use ZWare\Model;
use ZWare\Mailer;

<<<<<<< HEAD
ini_set ( 'default_charset', 'UTF-8' );
class User extends Model {
	const SESSION = "User";
	const IV = 'Js2hS50bvoNDa51m';
	const CRIPTKEY = 'ALG5Vg68wmgose71';
	const METHOD = 'aes-256-cbc';
	public static function login($login, $senha, $conectado = bool) {
		$sql = new Sql ();

		$results = $sql->select ( "select * from tb_usuarios u join tb_pessoas p on p.id_pessoa = u.pessoas_id_pessoa where u.login_usuario = :USUARIO or p.email_pessoa = :USUARIO", array (

				":USUARIO" => $login
		) );
=======
ini_set('default_charset', 'UTF-8');

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
>>>>>>> ada7e190bba4be36931f8d82080802f68cf30bec

		if (count ( $results ) === 0) {
			throw new \Exception ( "Usuário não encontrado ou senha inválida." );
		}

		$data = $results [0];

		if (password_verify ( $senha, $data ["senha_usuario"] ) === true) {
			$user = new User ();

			$user->setData ( $data );

			if ($conectado === true) {
				User::manterConectado ( $user->getid_usuario () );
			}

			$_SESSION [User::SESSION] = $user->getData ();

			return $user;
		} else {
			throw new \Exception ( "Usuário não encontrado ou senha inválida." );
		}
	}
	public static function manterConectado($id_usuario = int) {
		$chave = User::chaveCookie ( $id_usuario );
		User::saveCookie ( $chave );

		$navegador = User::getBrowser ();
		$NomePC = gethostname ();
		$ip = $_SERVER ['REMOTE_ADDR'];
		$user_ip = getenv ( 'REMOTE_ADDR' );

		// Cadastra no banco

		$sql = new Sql ();
		$sql->query ( "CALL sp_cadastra_login_automatico(:COMPUTADOR, :NAVEGADOR, :CHAVE, :USUARIO, :IP);", array (
				":COMPUTADOR" => $NomePC,
				":NAVEGADOR" => $navegador ["name"],
				":CHAVE" => $chave,
				":USUARIO" => $id_usuario,
				":IP" => $ip
		) );
	}
	public static function desconectar() {
		$NomePC = gethostname ();
		$ip = $_SERVER ['REMOTE_ADDR'];

		// Remove do banco no banco

		$sql = new Sql ();
		$sql->query ( "delete from tb_login_automatico where nome_computador = :COMPUTADOR AND ip = :IP;", array (
				":COMPUTADOR" => $NomePC,
				":IP" => $ip
		) );
	}
	public static function chaveCookie($id_usuario = int) {
		// Gerar hash (com o id do usuario + NomePC + id do usuario)
		$PCName = gethostname ();
		$ip = $_SERVER ['REMOTE_ADDR'];
		$chave = $id_usuario . $PCName . $id_usuario . $ip;

		// Criptografar a chave

		return hash ( 'ripemd160', $chave );
	}
	public static function saveCookie($valor = string) {
		$cookie_name = "autoLoginZWare";
		$cookie_value = $valor;
		setcookie ( $cookie_name, $cookie_value, time () + (86400 * 365), "/" ); // 86400 = 1 day
	}
	public function loadCookie() {
		$cookie_name = "autoLoginZWare";

		if (isset ( $_COOKIE [$cookie_name] )) {

			// Verificar id_usuario no banco;
			$sql = new Sql ();
			$idsUsuario = $sql->select ( "select usuarios_id_usuario from tb_login_automatico where chave = :CHAVE", array (

					":CHAVE" => $_COOKIE [$cookie_name]
			) );

			if (COUNT ( $idsUsuario ) > 0) {
				$results = $sql->select ( "select * from tb_usuarios u join tb_pessoas p on p.id_pessoa = u.pessoas_id_pessoa where u.id_usuario = :USUARIO", array (

						":USUARIO" => $idsUsuario [0] ["usuarios_id_usuario"]
				) );

				if (count ( $results ) > 0) {

					$NovaArray = $results [0];

					// Trata nome e sobrenome
					$NovaArray ["nome_pessoa"] = utf8_encode ( mb_convert_case ( $results [0] ["nome_pessoa"], MB_CASE_TITLE, "ISO-8859-1" ) );
					$NovaArray ["sobrenome_pessoa"] = utf8_encode ( mb_convert_case ( $results [0] ["sobrenome_pessoa"], MB_CASE_TITLE, "ISO-8859-1" ) );

					$this->setData ( $NovaArray );

					$_SESSION [User::SESSION] = $NovaArray;

					return $NovaArray;
				}
			}
		}
	}
	public static function verifyLogin($inadmin = true) {
		if (! isset ( $_SESSION [User::SESSION] ) || ! $_SESSION [User::SESSION] || 

		// Verifica se o usuario pode logar, caso sim, efetua o Login.

		! ( int ) $_SESSION [user::SESSION] ["id_usuario"] > 0) {

			header ( "Location: /admin/login" );
			exit ();
		}
	}
	public static function permissaoUsuarioMaster() {
		if (isset ( $_SESSION [User::SESSION] ) && $_SESSION [User::SESSION] && ( int ) $_SESSION [user::SESSION] ["id_usuario"] > 0) {

			$sql = new Sql ();

			$results = $sql->select ( "select * from tb_permissoes where usuarios_id_usuario = :USUARIO and tipo_permissao = :PERMISSAO", array (

					":USUARIO" => ( int ) $_SESSION [user::SESSION] ["id_usuario"],
					":PERMISSAO" => "MAS"
			) );

			if (count ( $results ) === 0) {
				return false;
				exit ();
			} else {
				return true;
				exit ();
			}
		} else {
			return false;
			exit ();
		}
	}
	public static function acessoAdmin() {
		if (isset ( $_SESSION [User::SESSION] ) && $_SESSION [User::SESSION] && ( int ) $_SESSION [user::SESSION] ["id_usuario"] > 0) {

			$sql = new Sql ();

			$results = $sql->select ( "select * from tb_permissoes where usuarios_id_usuario = :USUARIO and tipo_permissao = :PERMISSAO", array (

					":USUARIO" => ( int ) $_SESSION [user::SESSION] ["id_usuario"],
					":PERMISSAO" => "MAS"
			) );

			if (count ( $results ) === 0) {
				header ( "Location: /" );
				exit ();
			} else {
				return true;
				exit ();
			}
		} else {
			header ( "Location: /" );
			exit ();
		}
	}
	public static function logado() {
		if (isset ( $_SESSION [User::SESSION] ) && $_SESSION [User::SESSION] && ( int ) $_SESSION [user::SESSION] ["id_usuario"] > 0) {
			return true;
			exit ();
		} else {
			return false;
			exit ();
		}
	}
	public function dadosUsuario() {
		$sql = new Sql ();

		if (isset ( $_SESSION [User::SESSION] ) && $_SESSION [User::SESSION] && ( int ) $_SESSION [user::SESSION] ["id_usuario"] > 0) {
			$results = $sql->select ( "call sp_retorna_dados_usuario(:id_usuario)", array (

					":id_usuario" => ( int ) $_SESSION [user::SESSION] ["id_usuario"]
			) );

			// Cria uma nova variável com o primeiro registro da consulta acima
			$NovaArray = $results [0];

			// Trata nome e sobrenome
			$NovaArray ["nome_pessoa"] = utf8_encode ( mb_convert_case ( $results [0] ["nome_pessoa"], MB_CASE_TITLE, "ISO-8859-1" ) );
			$NovaArray ["sobrenome_pessoa"] = utf8_encode ( mb_convert_case ( $results [0] ["sobrenome_pessoa"], MB_CASE_TITLE, "ISO-8859-1" ) );

			return $NovaArray;
		} else {
			$results = [ 
					"nome_pessoa" => ""
			];
			return $results;
		}
	}
	public static function verifyLogado() {
		if (isset ( $_SESSION [User::SESSION] ) && $_SESSION [User::SESSION] && ( int ) $_SESSION [user::SESSION] ["id_usuario"] > 0) {

			if (( bool ) $_SESSION [user::SESSION] ["inadmin"] === true) {

				header ( "Location: /admin" );

				exit ();
			} else {

				header ( "Location: /" );

				exit ();
			}
		}
	}
	public static function retornaNome() {
		$nome = ( string ) $_SESSION [user::SESSION] ["nome_pessoa"];
	}
	public static function logout() {
		$_SESSION [User::SESSION] = NULL;
		User::desconectar ();
	}
	public static function formataNome($nome = string) {
		$texto = ucwords ( $nome );
		return User::adjustments ( $nome );
	}
	public static function adjustments($text = string) {
		$accents = array (
				"á",
				"à",
				"â",
				"ã",
				"ä",
				"é",
				"è",
				"ê",
				"ë",
				"í",
				"ì",
				"î",
				"ï",
				"ó",
				"ò",
				"ô",
				"õ",
				"ö",
				"ú",
				"ù",
				"û",
				"ü",
				"ç",
				"Á",
				"À",
				"Â",
				"Ã",
				"Ä",
				"É",
				"È",
				"Ê",
				"Ë",
				"Í",
				"Ì",
				"Î",
				"Ï",
				"Ó",
				"Ò",
				"Ô",
				"Õ",
				"Ö",
				"Ú",
				"Ù",
				"Û",
				"Ü",
				"Ç"
		);
		$utf8 = array (
				"&aacute;",
				"&agrave;",
				"&acirc;",
				"&atilde;",
				"&auml;",
				"&eacute;",
				"&egrave;",
				"&ecirc;",
				"&euml;",
				"&iacute;",
				"&igrave;",
				"&icirc;",
				"&iuml;",
				"&oacute;",
				"&ograve;",
				"&ocirc;",
				"&otilde",
				"&ouml;",
				"&uacute;",
				"&ugrave;",
				"&ucirc;",
				"&uuml;",
				"&ccedil;",
				"&Aacute;",
				"&Agrave;",
				"&Acirc;",
				"&Atilde;",
				"&Auml;",
				"&Eacute;",
				"&Egrave;",
				"&Ecirc;",
				"&Euml;",
				"&Iacute;",
				"&Igrave;",
				"&Icirc;",
				"&Iuml;",
				"&Oacute;",
				"&Ograve;",
				"&Ocirc;",
				"&Otilde",
				"&Ouml;",
				"&Uacute;",
				"&Ugrave;",
				"&Ucirc;",
				"&Uuml;",
				"&Ccedil;"
		);
		return str_replace ( $accents, $utf8, $text );
	}
	public function cadastraUsuario() {
		// Verifica se o email de usuario ja existe, caso sim, retorna a pagina com um alerta
		$sql = new sql ();

		// select pessoas_id_pessoa from tb_usuarios u join tb_pessoas p on u.pessoas_id_pessoa = p.id_pessoa where p.email_pessoa = 'email';

		$codigoParaConfirmacao = hash ( 'ripemd160', $this->getemail () . time () );
		$senhaSegura = User::passwordEncript ( $this->getsenha () );

		$results = $sql->select ( "select pessoas_id_pessoa from tb_usuarios u join tb_pessoas p on u.pessoas_id_pessoa = p.id_pessoa where p.email_pessoa = :EMAIL;", array (

				":EMAIL" => $this->getemail ()
		) );

		if (count ( $results ) > 0) {

			// Existe outro usuário cadastrado com este email
			$this->{"set" . "erro"} ( "emailExiste" );
		} else {
			// Não existe outro usuario com este email

			// Cria usuario sem link de pessoa

			$sql->select ( "CALL sp_cadastra_usuario_comum(:HASH, :NOME, :SOBRENOME, :EMAIL, :CELULAR, :FONE, :SEXO, :NASCIMENTO, :SENHA);", array (
					":HASH" => $codigoParaConfirmacao,
					":NOME" => User::formataTexto ( strtoupper ( $this->getnome () ), true, true, true, true ),
					":SOBRENOME" => User::formataTexto ( strtoupper ( $this->getsobrenome () ), true, true, true, true ),
					":EMAIL" => User::formataTexto ( $this->getemail (), false, true, true, false ),
					":CELULAR" => $this->getcelular (),
					":FONE" => $this->getfone (),
					":SEXO" => $this->getsexo (),
					":NASCIMENTO" => $this->getnascimento (),
					":SENHA" => $senhaSegura
			) );

			// Envia um email de confirmação

			$link = "https://zware.com.br/validacaoEmail?code=$codigoParaConfirmacao";

			$mailer = new Mailer ( $this->getemail (), $this->getnome (), "Verifique seu e-mail no site ZWare", "Modelo", array (
					"name" => User::formataNome ( $this->getnome () ),
					"link" => $link,
					"tipo" => "verificacao"
			) );

			$mailer->send ();

			// Preenche array com os dados informados
			$dados = array (
					"nome_pessoa" => User::formataTexto ( $this->getnome (), true, true, true, false ),
					"sobrenome_pessoa" => User::formataTexto ( $this->getsobrenome (), true, true, true, false ),
					"email_pessoa" => $this->getemail (),
					"celular" => $this->getcelular (),
					"fone" => $this->getfone (),
					"erro" => "emailValido"
			);

			return $dados;
		}
	}
	public static function listAll() {
		$sql = new Sql ();

		$results = $sql->select ( "SELECT * FROM tb_users a INNER JOIN tb_persons b ON a.idperson = b.idperson ORDER BY b.desperson" );

		return $results;
	}
	public static function passwordEncript($password) {
		$options = [ 

				'cost' => 12
		];

		return password_hash ( $password, PASSWORD_BCRYPT, $options );
	}
	public function save() {
		$sql = new Sql ();

		$results = $sql->select ( "CALL sp_users_save(:DESPERSON, :DESLOGIN, :DESPASSWORD, :DESEMAIL, :NRPHONE, :INADMIN)", array (

				":DESPERSON" => $this->getdesperson (),

				":DESLOGIN" => $this->getdeslogin (),

				":DESPASSWORD" => User::passwordEncript ( $this->getdespassword () ),

				":DESEMAIL" => $this->getdesemail (),

				":NRPHONE" => $this->getnrphone (),

				":INADMIN" => $this->getinadmin ()
		) );

		$this->setData ( $results [0] );
	}
	public static function existeEmailUsuario($email = string) {
		$sql = new Sql ();

		$retorno = $sql->select ( "select u.id_usuario, p.nome_pessoa, p.email_pessoa from tb_usuarios u join tb_pessoas	p on p.id_pessoa = u.pessoas_id_pessoa
        where p.email_pessoa = :EMAIL", array (
				":EMAIL" => $email
		) );

		if (count ( $retorno ) > 0) {

			// Gera Hash
			$hashEmail = hash ( 'ripemd160', $email . time () );

			$link = "http://zware.com.br/recuperarSenha?code=$hashEmail";

			$sql->query ( "INSERT INTO tb_recuperacao_senha (hash_recuperacao_senha, usuarios_id_usuario) values(:HASHEMAIL, :USUARIO);", array (
					":HASHEMAIL" => $hashEmail,
					":USUARIO" => $retorno [0] ["id_usuario"]
			) );

			$mailer = new Mailer ( $email, $retorno [0] ["nome_pessoa"], "Recuperar sua senha no site ZWare", "Modelo", array (
					"name" => User::formataNome ( utf8_encode ( ucwords ( $retorno [0] ["nome_pessoa"] ) ) ),
					"link" => $link,
					"tipo" => "alteracaoSenha"
			) );

			$mailer->send ();

<<<<<<< HEAD
			return true;
		} else {
			return false;
		}
	}
	public static function validaCodigoAlteracaoSenha($codigo = string) {
		$sql = new Sql ();
		$retorno = $sql->select ( "select * from tb_recuperacao_senha where hash_recuperacao_senha = :HASHEMAIL and data_criacao > subdate(current_timestamp(), interval 5 minute)", array (
				":HASHEMAIL" => $codigo
		) );

		if (count ( $retorno ) > 0) {
			return true;
		} else {
			return false;
		}
	}
	public static function idCodigoAlteracaoSenha($codigo = string) {
		$sql = new Sql ();
		$retorno = $sql->select ( "select usuarios_id_usuario from tb_recuperacao_senha where hash_recuperacao_senha = :HASHEMAIL", array (
				":HASHEMAIL" => $codigo
		) );

		if (count ( $retorno ) > 0) {
			return ( int ) $retorno [0] ["usuarios_id_usuario"];
		} else {
			return ( int ) 0;
		}
	}
	public function alteraSenha($id = int, $senha = string) {
		$sql = new Sql ();

		$SenhaNova = User::passwordEncript ( $senha );
=======
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

    public static function retornaNome()
    {
        $nome = (string) $_SESSION[user::SESSION]["nome_pessoa"];         
    }

    public static function logout()
    {
        $_SESSION[User::SESSION] = NULL;
    }
        
    public static function formataNome($nome = string)
    {
        $texto = ucwords ($nome);
        return User::adjustments($nome);
    }
    
    public static function adjustments( $text = string)
    {
        $accents = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");        
        $utf8 = array("&aacute;", "&agrave;", "&acirc;", "&atilde;", "&auml;", "&eacute;", "&egrave;", "&ecirc;", "&euml;", "&iacute;", "&igrave;", "&icirc;", "&iuml;","&oacute;", "&ograve;", "&ocirc;", "&otilde", "&ouml;","&uacute;", "&ugrave;", "&ucirc;", "&uuml;", "&ccedil;", 
                      "&Aacute;", "&Agrave;", "&Acirc;", "&Atilde;", "&Auml;", "&Eacute;", "&Egrave;", "&Ecirc;", "&Euml;", "&Iacute;", "&Igrave;", "&Icirc;", "&Iuml;","&Oacute;", "&Ograve;", "&Ocirc;", "&Otilde", "&Ouml;","&Uacute;", "&Ugrave;", "&Ucirc;", "&Uuml;", "&Ccedil;");
        return str_replace($accents, $utf8, $text);        
    }    
    
    public function cadastraUsuario()
    {
        // Verifica se o email de usuario ja existe, caso sim, retorna a pagina com um alerta
        $sql = new sql();
>>>>>>> ada7e190bba4be36931f8d82080802f68cf30bec

		$sql->query ( "update tb_usuarios set senha_usuario = :SENHA WHERE id_usuario = :USUARIO", array (
				":SENHA" => $SenhaNova,
				":USUARIO" => $id
		) );

		$sql->query ( "delete from tb_recuperacao_senha where usuarios_id_usuario = :USUARIO", array (
				":USUARIO" => $id
		) );
	}

	// Retorna os dados do usuario através do id de usuário
	public function get($id_usuario) {
		$sql = new Sql ();

		$results = $sql->select ( "select p.id_pessoa, p.nome_pessoa, p.sobrenome_pessoa, p.email_pessoa, p.data_cadastro, p.ativo, p.email_verificado, u.id_usuario, u.login_usuario from tb_pessoas p 
        join tb_usuarios u on u.pessoas_id_pessoa = p.id_pessoa where id_usuario = :id_usuario", array (

				":id_usuario" => $id_usuario
		) );

		$this->setData ( $results [0] );
	}
	private function retornaDadosUsuario($id_usuario) {
		$sql = new Sql ();

<<<<<<< HEAD
		$results = $sql->select ( "select p.id_pessoa, p.nome_pessoa, p.sobrenome_pessoa, p.email_pessoa, p.data_cadastro, p.ativo, p.email_verificado, u.id_usuario, u.login_usuario from tb_pessoas p 
        join tb_usuarios u on u.pessoas_id_pessoa = p.id_pessoa where id_usuario = :id_usuario", array (

				":id_usuario" => $id_usuario
		) );
=======
            // Cria usuario sem link de pessoa            
            
            $sql->select("CALL sp_cadastra_usuario_comum(:HASH, :NOME, :SOBRENOME, :EMAIL, :CELULAR, :FONE, :SEXO, :NASCIMENTO, :SENHA);", array(
                ":HASH" => $codigoParaConfirmacao,
                ":NOME" => User::formataTexto($this->getnome(), true, true, true),
                ":SOBRENOME" => User::formataTexto($this->getsobrenome(), true, true, true),
                ":EMAIL" => User::formataTexto($this->getemail(), false, true, true),
                ":CELULAR" => $this->getcelular(),
                ":FONE" => $this->getfone(),
                ":SEXO" => $this->getsexo(),
                ":NASCIMENTO" => $this->getnascimento(),
                ":SENHA" => $senhaSegura
            ));
>>>>>>> ada7e190bba4be36931f8d82080802f68cf30bec

		$this->setData ( $results [0] );
	}
	public function update() {
		$sql = new Sql ();

		$results = $sql->select ( "CALL sp_usersupdate_save(:id_usuario, :DESPERSON, :DESLOGIN, :DESPASSWORD, :DESEMAIL, :NRPHONE, :INADMIN)", array (

<<<<<<< HEAD
				":id_usuario" => $this->getid_usuario (),

				":DESPERSON" => $this->getdesperson (),

				":DESLOGIN" => $this->getdeslogin (),

				":DESPASSWORD" => User::passwordEncript ( $this->getdespassword () ),
=======
            $mailer = new Mailer($this->getemail(), $this->getnome(), "Verifique seu e-mail no site ZWare", "Modelo", array(
                "name" => User::formataNome($this->getnome()),
                "link" => $link,
                "tipo"=>"verificacao"
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
>>>>>>> ada7e190bba4be36931f8d82080802f68cf30bec

				":DESEMAIL" => $this->getdesemail (),

				":NRPHONE" => $this->getnrphone (),

				":INADMIN" => $this->getinadmin ()
		) );

		$this->setData ( $results [0] );
	}
	public function delete() {
		$sql = new Sql ();

		$sql->query ( "CALL sp_users_delete(:id_usuario)", array (

				":id_usuario" => $this->getid_usuario ()
		) );
	}
	public static function getForgot($email) {
		$sql = new Sql ();

		$results = $sql->select ( "SELECT * FROM tb_persons a INNER JOIN tb_users b ON a.idperson = b.idperson WHERE a.desemail = :EMAIL", array (

				":EMAIL" => $email
		) );

		if (count ( $results ) === 0) {

			throw new \Exception ( "Não foi possível recuperar a senha." );
		} else {

			$data = $results [0];

			$results2 = $sql->select ( "CALL sp_userspasswordsrecoveries_create(:id_usuario, :DESIP)", array (

					":id_usuario" => $data ["id_usuario"],

					":DESIP" => $_SERVER ["REMOTE_ADDR"]
			) );

			if (count ( $results2 ) === 0) {

				throw new \Exception ( "Não foi possível recuperar a senha." );
			} else {

				$dataRecovery = $results2 [0];

				$code = base64_encode ( openssl_encrypt ( $dataRecovery ["idrecovery"], User::METHOD, User::CRIPTKEY, false, User::IV ) );

				$link = "https://zware.com.br/admin/forgot/reset?code=$code";

				$mailer = new Mailer ( $data ["desemail"], $data ["desperson"], "Redefinir senha do site ZWare", "forgot", array (

						"name" => $data ["desperson"],

						"link" => $link
				) );

				$mailer->send ();

				return $data;
			}
		}
	}
	public static function validForgotDecrypt($code) {
		$idrecovery = openssl_decrypt ( base64_decode ( $code ), User::METHOD, User::CRIPTKEY, false, User::IV );

		$sql = new Sql ();

		$results = $sql->select ( "SELECT * FROM tb_userspasswordsrecoveries a

                    INNER JOIN tb_users b ON b.id_usuario = a.id_usuario

                    INNER JOIN tb_persons c ON b.idperson = c.idperson



                    WHERE a.idrecovery = :IDRECOVERY

                    AND a.dtrecovery is NULL

                    AND date_add(a.dtregister, interval 1 hour) >= NOW()", array (

				":IDRECOVERY" => $idrecovery
		) );

		if (count ( $results ) === 0) {

			throw new \Exception ( "Não foi possível recuperar a senha." );
		} else {

			return $results [0];
		}
	}
	public static function setForgotUsed($idrecovery) {
		$sql = new Sql ();

		$sql->query ( "UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :IDRECOVERY", array (

				":IDRECOVERY" => $idrecovery
		) );
	}
	public function setPassword($password) {
		$sql = new Sql ();

		$sql->query ( "UPDATE tb_users SET despassword = :PASSWORD WHERE id_usuario = :id_usuario", array (

				":PASSWORD" => User::passwordEncript ( $password ),

				":id_usuario" => $this->getid_usuario ()
		) );
	}
	public function verificarValidacaoEmail($codigo = string) {
		$sql = new Sql ();

		$sqlResult = $sql->select ( "SELECT nome_pessoa, sobrenome_pessoa, email_pessoa, celular, fone, sexo_pessoa, data_nascimento_pessoa, 
        senha FROM tb_confirmacao_email where data_envio > subdate(current_date(), interval 24 hour) and hash_usuario = :hashU;", array (
				":hashU" => $codigo
		) );

		if (count ( $sqlResult ) > 0) {

			$results = $sqlResult [0];

			$this->setData ( $results );

			$sqlResult = $sql->select ( "CALL sp_efetiva_cadastro(:NOME, :SOBRENOME, :EMAIL, :SEXO, :NASCIMENTO, :CELULAR, :FONE, :SENHA);", array (
					":NOME" => User::formataTexto ( $this->getnome_pessoa (), true, true, true, false ),
					":SOBRENOME" => User::formataTexto ( $this->getsobrenome_pessoa (), true, true, true, false ),
					":EMAIL" => $this->getemail_pessoa (),
					":SEXO" => $this->getsexo_pessoa (),
					":NASCIMENTO" => $this->getdata_nascimento_pessoa (),
					":CELULAR" => $this->getcelular (),
					":FONE" => $this->getfone (),
					":SENHA" => $this->getsenha ()
			) );

			if (count ( $sqlResult ) > 0) {
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