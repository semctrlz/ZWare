<?php

function nameCase($nome = string)
{
    if (isset($nome) and $nome != "") {
        $nameArray = explode(" ", $nome);
        $novonome = "";
        foreach ($nameArray as $val) {
            if ($val != "") {
                if (strlen(trim($val)) < 4) {
                    $novonome .= " " . trim(utf8_encode ( mb_strtolower ( $val, "ISO-8859-1" ) ));
                } else {
                    $novonome .= " " . trim(utf8_encode ( mb_convert_case ( $val, MB_CASE_TITLE, "ISO-8859-1" ) ));
                }
            }
        }

        return $novonome;
    }
    return "";
}

