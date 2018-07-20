<?php 



namespace ZWare;



use Rain\Tpl;



class Page {



	private $tpl;

	private $options = [];

	private $defaults = [

		"header"=>true,

		"footer"=>true,

		"tipoHeader" => "header",

		"data"=>[]

	];



	public function __construct($opts = array(), $values = array(array()), $local = "", $tpl_dir = "/views/")

	{                
		$this->options = array_merge($this->defaults, $opts);

		$config = array(		    

		    "tpl_dir"       => $_SERVER['DOCUMENT_ROOT'].$tpl_dir,

		    "cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/views-cache/",

		    "debug"         => false

		);

		Tpl::configure( $config );			

		$this->tpl = new Tpl();			

			foreach ($values as $key => $value) 
			{				
				$this->tpl->assign($key, $value);
			}		

		$this->tpl->assign("local", $local);

		if ($this->options['data']) $this->setData($this->options['data']);

		if ($this->options['header'] === true) $this->tpl->draw($this->options['tipoHeader'], false);



	}



	public function __destruct()

	{
		if ($this->options['footer'] === true) $this->tpl->draw("footer", false);
	}



	private function setData($data = array())

	{



		foreach($data as $key => $val)

		{



			$this->tpl->assign($key, $val);



		}



	}



	public function setTpl($tplname, $data = array(), $returnHTML = false)

	{



		$this->setData($data);



		return $this->tpl->draw($tplname, $returnHTML);



	}



}



 ?>