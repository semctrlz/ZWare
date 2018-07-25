<?php
namespace ZWare;

class Model
{

    private $values = [];

    public function __call($name, $arguments)
    {
        $method = substr($name, 0, 3);

        $fieldname = substr($name, 3, strlen($name));

        switch ($method) {

            case "get":
                return $this->values[$fieldname];
                break;

            case "set":
                $this->values[$fieldname] = $arguments[0];
                break;
        }
    }

    public function setData($data = array())
    {
        foreach ($data as $key => $value) {
            $this->{"set" . $key}($value);
        }
    }

    public function getData()
    {
        return $this->values;
    }

    public static function formataTexto($texto = string, $maiusculas = bool, $trim = bool, $removeInvalidChars = bool)
    {
        $textoRetorno = $texto;

        $invalidChars = array("");

        if ($maiusculas == true) {
            $textoRetorno = strtoupper($textoRetorno);
        }

        if ($trim == true) {
            $textoRetorno = trim($textoRetorno);
        }

        if ($removeInvalidChars == true) {
            $textoRetorno = str_replace($invalidChars, "", $textoRetorno);
        }

        return utf8_decode($textoRetorno);
    }
        
}

?>