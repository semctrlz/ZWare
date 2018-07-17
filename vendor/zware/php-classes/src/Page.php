<?php

namespace ZWare;
use Rain\Tpl;

    class Page{
        
        private $tpl;
        private $defaults = ["data"=>[]];
        private $options = [];
        
        public function __construct($opts = array()){
            $this->options = array_merge($this->defaults, $opts);
            
            $config = array(
                "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/",
                "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
                "debug"         => false // Altere para false para aumentar o desempenho
            );
            
            Tpl::configure($config);
            
            $this->tpl = new Tpl;
            
            $this->setData($this->options["data"]);
            
            $this->tpl->draw("header");            
    }
    
    public function setTpl($name, $data = array(), $returnhtml = false){
        
        $this->setData($data);
        return $this->tpl->draw($name, $returnhtml);
    }
    
    private function setData($data = array()){
        foreach ($data as $key => $value){
                $this->tpl->assign($key,$value);
            }
    }
    

    public function __destruct() {
        $this->tpl->draw("footer");
    }


}

?>