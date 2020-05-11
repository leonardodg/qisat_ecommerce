<?php

namespace App\Lib\ConvertType;

/**
 * IntegerToString classe Helper com funções de conversão
 * incomuns do tipo Inteiro para o tipo String
 */
class IntegerToString
{
    /**
     * Função responsavel por retornar o valor do inteiro em extenso
     *
     * @param Integer $valor
     * @param Boolean $maiusculas
     * @return string Valor do inteiro informado em extenso
     */
    static function extenso($valor = 0, $maiusculas = false) {
        $singular = array("", "", "mil", "milh&atilde;o", "bilh&atilde;o", "trilh&atilde;o", "quatrilh&atilde;o");
        $plural = array("", "", "mil", "milh&otilde;es", "bilh&otilde;es", "trilh&otilde;es","quatrilh&otilde;es");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "tr&ecirc;s", "quatro", "cinco", "seis", "sete", "oito", "nove");

        $z = 0;
        $rt = "";

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for($i=0;$i<count($inteiro);$i++)
            for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
                $inteiro[$i] = "0".$inteiro[$i];

        $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
        for ($i=0;$i<count($inteiro);$i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
            $t = count($inteiro)-1-$i;
            $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")$z++; elseif ($z > 0) $z--;
            if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
            if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        if(!$maiusculas){
            return($rt ? trim($rt) : "zero");
        } else {

            if ($rt) $rt=ereg_replace(" E "," e ",ucwords($rt));
            return (($rt) ? trim($rt) : "Zero");
        }
    }
}