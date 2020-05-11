<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa                |
// |                                                                      |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto CEF SINCO: Carlos Magno / Reinaldo Silva      |
// +----------------------------------------------------------------------+

$codigobanco = "104";
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";
$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

//valor tem 10 digitos, sem virgula
$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
//agencia é 4 digitos
$agencia = formata_numero($dadosboleto["agencia"],4,0);
//conta é 5 digitos
$conta = formata_numero($dadosboleto["conta"],5,0);
//dv da conta
$conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
//carteira é 2 caracteres
$carteira = $dadosboleto["carteira"];

//nosso número (sem dv) é 17 digitos
$nnum = $dadosboleto["inicio_nosso_numero"] . formata_numero($dadosboleto["nosso_numero"],17,0);
//dv do nosso número
$dv_nosso_numero = digitoVerificador_nossonumero($nnum);
$nossonumero_dv ="$nnum$dv_nosso_numero";

//conta cedente (sem dv) é 6 digitos
$conta_cedente = formata_numero($dadosboleto["conta_cedente"],6,0);
//dv da conta cedente
$conta_cedente_dv = formata_numero($dadosboleto["conta_cedente_dv"],1,0);

$ag_contacedente  = $agencia . $conta_cedente;
$fixo             = $dadosboleto["campo_fixo_obrigatorio"];
$campo_livre      = "$fixo$conta_cedente$nnum";

// 43 numeros para o calculo do digito verificador do codigo de barras
$dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$campo_livre", 9, 0);
// Numero para o codigo de barras com 44 digitos
$linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$campo_livre";

$nossonumero = substr($nossonumero_dv,0,18).'-'.substr($nossonumero_dv,18,1);
$agencia_codigo = $agencia." / ". $conta_cedente ."-". $conta_cedente_dv;


$dadosboleto["codigo_barras"] = $linha;
$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
$dadosboleto["agencia_codigo"] = $agencia_codigo;
$dadosboleto["nosso_numero"] = $nossonumero;
$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

function digitoVerificador_nossonumero($numero) {
    $resto2 = modulo_11($numero, 9, 1);
    $digito = 11 - $resto2;
    if ($digito == 10 || $digito == 11) {
        $dv = 0;
    } else {
        $dv = $digito;
    }
    return $dv;
}

function digitoVerificador_barra($numero) {
    $resto2 = modulo_11($numero, 9, 1);
    if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10) {
        $dv = 1;
    } else {
        $dv = 11 - $resto2;
    }
    return $dv;
}

// FUNÇÕES
// Algumas foram retiradas do Projeto PhpBoleto e modificadas para atender as particularidades de cada banco

function formata_numero($numero,$loop,$insert,$tipo = "geral") {
    if ($tipo == "geral") {
        $numero = str_replace(",","",$numero);
        while(strlen($numero)<$loop){
            $numero = $insert . $numero;
        }
    }
    if ($tipo == "valor") {
        /*
        retira as virgulas
        formata o numero
        preenche com zeros
        */
        $numero = str_replace(",","",$numero);
        while(strlen($numero)<$loop){
            $numero = $insert . $numero;
        }
    }
    if ($tipo == "convenio") {
        while(strlen($numero)<$loop){
            $numero = $numero . $insert;
        }
    }
    return $numero;
}

function fbarcode($valor){

    $fino = 1 ;
    $largo = 3 ;
    $altura = 50 ;

    $barcodes[0] = "00110" ;
    $barcodes[1] = "10001" ;
    $barcodes[2] = "01001" ;
    $barcodes[3] = "11000" ;
    $barcodes[4] = "00101" ;
    $barcodes[5] = "10100" ;
    $barcodes[6] = "01100" ;
    $barcodes[7] = "00011" ;
    $barcodes[8] = "10010" ;
    $barcodes[9] = "01010" ;
    for($f1=9;$f1>=0;$f1--){
        for($f2=9;$f2>=0;$f2--){
            $f = ($f1 * 10) + $f2 ;
            $texto = "" ;
            for($i=1;$i<6;$i++){
                $texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
            }
            $barcodes[$f] = $texto;
        }
    }

//Desenho da barra

//Guarda inicial
    ?><img src=/webroot/img/boletophp/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
        src=/webroot/img/boletophp/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
        src=/webroot/img/boletophp/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
        src=/webroot/img/boletophp/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
    <?php
    $texto = $valor ;
    if((strlen($texto) % 2) <> 0){
        $texto = "0" . $texto;
    }

// Draw dos dados
    while (strlen($texto) > 0) {
        $i = round(esquerda($texto,2));
        $texto = direita($texto,strlen($texto)-2);
        $f = $barcodes[$i];
        for($i=1;$i<11;$i+=2){
            if (substr($f,($i-1),1) == "0") {
                $f1 = $fino ;
            }else{
                $f1 = $largo ;
            }
            ?>
            src=/webroot/img/boletophp/p.png width=<?php echo $f1?> height=<?php echo $altura?> border=0><img
                <?php
                if (substr($f,$i,1) == "0") {
                    $f2 = $fino ;
                }else{
                    $f2 = $largo ;
                }
                ?>
                src=/webroot/img/boletophp/b.png width=<?php echo $f2?> height=<?php echo $altura?> border=0><img
            <?php
        }
    }

// Draw guarda final
    ?>
    src=/webroot/img/boletophp/p.png width=<?php echo $largo?> height=<?php echo $altura?> border=0><img
        src=/webroot/img/boletophp/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
        src=/webroot/img/boletophp/p.png width=<?php echo 1?> height=<?php echo $altura?> border=0>
    <?php
} //Fim da função

function esquerda($entra,$comp){
    return substr($entra,0,$comp);
}

function direita($entra,$comp){
    return substr($entra,strlen($entra)-$comp,$comp);
}

function fator_vencimento($data) {
    $data = explode("/",$data);
    $ano = $data[2];
    $mes = $data[1];
    $dia = $data[0];
    return(abs((_dateToDays("1997","10","07")) - (_dateToDays($ano, $mes, $dia))));
}

function _dateToDays($year,$month,$day) {
    $century = substr($year, 0, 2);
    $year = substr($year, 2, 2);
    if ($month > 2) {
        $month -= 3;
    } else {
        $month += 9;
        if ($year) {
            $year--;
        } else {
            $year = 99;
            $century --;
        }
    }
    return ( floor((  146097 * $century)    /  4 ) +
        floor(( 1461 * $year)        /  4 ) +
        floor(( 153 * $month +  2) /  5 ) +
        $day +  1721119);
}

function modulo_10($num) {
    $numtotal10 = 0;
    $fator = 2;

    // Separacao dos numeros
    for ($i = strlen($num); $i > 0; $i--) {
        // pega cada numero isoladamente
        $numeros[$i] = substr($num,$i-1,1);
        // Efetua multiplicacao do numero pelo (falor 10)
        // 2002-07-07 01:33:34 Macete para adequar ao Mod10 do Itaú
        $temp = $numeros[$i] * $fator;
        $temp0=0;
        foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
        $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
        // monta sequencia para soma dos digitos no (modulo 10)
        $numtotal10 += $parcial10[$i];
        if ($fator == 2) {
            $fator = 1;
        } else {
            $fator = 2; // intercala fator de multiplicacao (modulo 10)
        }
    }

    // várias linhas removidas, vide função original
    // Calculo do modulo 10
    $resto = $numtotal10 % 10;
    $digito = 10 - $resto;
    if ($resto == 0) {
        $digito = 0;
    }

    return $digito;

}

function modulo_11($num, $base=9, $r=0)  {
    /**
     *   Autor:
     *           Pablo Costa <pablo@users.sourceforge.net>
     *
     *   Função:
     *    Calculo do Modulo 11 para geracao do digito verificador
     *    de boletos bancarios conforme documentos obtidos
     *    da Febraban - www.febraban.org.br
     *
     *   Entrada:
     *     $num: string numérica para a qual se deseja calcularo digito verificador;
     *     $base: valor maximo de multiplicacao [2-$base]
     *     $r: quando especificado um devolve somente o resto
     *
     *   Saída:
     *     Retorna o Digito verificador.
     *
     *   Observações:
     *     - Script desenvolvido sem nenhum reaproveitamento de código pré existente.
     *     - Assume-se que a verificação do formato das variáveis de entrada é feita antes da execução deste script.
     */

    $soma = 0;
    $fator = 2;

    /* Separacao dos numeros */
    for ($i = strlen($num); $i > 0; $i--) {
        // pega cada numero isoladamente
        $numeros[$i] = substr($num,$i-1,1);
        // Efetua multiplicacao do numero pelo falor
        $parcial[$i] = $numeros[$i] * $fator;
        // Soma dos digitos
        $soma += $parcial[$i];
        if ($fator == $base) {
            // restaura fator de multiplicacao para 2
            $fator = 1;
        }
        $fator++;
    }

    /* Calculo do modulo 11 */
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;
        if ($digito == 10) {
            $digito = 0;
        }
        return $digito;
    } elseif ($r == 1){
        $resto = $soma % 11;
        return $resto;
    }
}

function monta_linha_digitavel($codigo) {

    // Posição 	Conteúdo
    // 1 a 3    Número do banco
    // 4        Código da Moeda - 9 para Real
    // 5        Digito verificador do Código de Barras
    // 6 a 9   Fator de Vencimento
    // 10 a 19 Valor (8 inteiros e 2 decimais)
    // 20 a 44 Campo Livre definido por cada banco (25 caracteres)

    // 1. Campo - composto pelo código do banco, código da moéda, as cinco primeiras posições
    // do campo livre e DV (modulo10) deste campo
    $p1 = substr($codigo, 0, 4);
    $p2 = substr($codigo, 19, 5);
    $p3 = modulo_10("$p1$p2");
    $p4 = "$p1$p2$p3";
    $p5 = substr($p4, 0, 5);
    $p6 = substr($p4, 5);
    $campo1 = "$p5.$p6";

    // 2. Campo - composto pelas posiçoes 6 a 15 do campo livre
    // e livre e DV (modulo10) deste campo
    $p1 = substr($codigo, 24, 10);
    $p2 = modulo_10($p1);
    $p3 = "$p1$p2";
    $p4 = substr($p3, 0, 5);
    $p5 = substr($p3, 5);
    $campo2 = "$p4.$p5";

    // 3. Campo composto pelas posicoes 16 a 25 do campo livre
    // e livre e DV (modulo10) deste campo
    $p1 = substr($codigo, 34, 10);
    $p2 = modulo_10($p1);
    $p3 = "$p1$p2";
    $p4 = substr($p3, 0, 5);
    $p5 = substr($p3, 5);
    $campo3 = "$p4.$p5";

    // 4. Campo - digito verificador do codigo de barras
    $campo4 = substr($codigo, 4, 1);

    // 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
    // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
    // tratar de valor zerado, a representacao deve ser 000 (tres zeros).
    $p1 = substr($codigo, 5, 4);
    $p2 = substr($codigo, 9, 10);
    $campo5 = "$p1$p2";

    return "$campo1 $campo2 $campo3 $campo4 $campo5";
}

function geraCodigoBanco($numero) {
    $parte1 = substr($numero, 0, 3);
    $parte2 = modulo_11($parte1);
    return $parte1 . "-" . $parte2;
}

?>

<?php
    // +----------------------------------------------------------------------+
    // | BoletoPhp - Vers�o Beta                                              |
    // +----------------------------------------------------------------------+
    // | Este arquivo est� dispon�vel sob a Licen�a GPL dispon�vel pela Web   |
    // | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
    // | Voc� deve ter recebido uma c�pia da GNU Public License junto com     |
    // | esse pacote; se n�o, escreva para:                                   |
    // |                                                                      |
    // | Free Software Foundation, Inc.                                       |
    // | 59 Temple Place - Suite 330                                          |
    // | Boston, MA 02111-1307, USA.                                          |
    // +----------------------------------------------------------------------+

    // +----------------------------------------------------------------------+
    // | Originado do Projeto BBBoletoFree que tiveram colabora��es de Daniel |
    // | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
    // | PHPBoleto de Jo�o Prado Maia e Pablo Martins F. Costa                |
    // |                                                                      |
    // | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
    // | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
    // +----------------------------------------------------------------------+

    // +----------------------------------------------------------------------+
    // | Equipe Coordena��o Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
    // | Desenvolvimento Boleto CEF SINCO: Carlos Magno / Reinaldo Silva      |
    // +----------------------------------------------------------------------+
    ?>
    <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
    <HTML>
    <HEAD>
        <TITLE><?php echo $dadosboleto["identificacao"]; ?></TITLE>
        <META http-equiv=Content-Type content=text/html charset=ISO-8859-1>
        <meta name="Generator" content="Projeto BoletoPHP - www.boletophp.com.br - Licença GPL" />
        <style type=text/css>
            <!--.cp {  font: bold 10px Arial; color: black}
            <!--.ti {  font: 9px Arial, Helvetica, sans-serif}
            <!--.ld { font: bold 15px Arial; color: #000000}
            <!--.ct { FONT: 9px "Arial Narrow"; COLOR: #000033}
            <!--.cn { FONT: 9px Arial; COLOR: black }
            <!--.bc { font: bold 20px Arial; color: #000000 }
            <!--.ld2 { font: bold 12px Arial; color: #000000 }
            --></style>
    </head>
    <BODY text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>
    <table width=666 cellspacing=0 cellpadding=0 border=0><tr><td valign=top class=cp>
                <DIV ALIGN="CENTER">Imprimir Boleto Bancário</DIV>
                <DIV ALIGN="CENTER"><input type="button" onclick="window.print();" value="Imprimir Boleto Bancário" name="imprimir"></DIV>
            </TD></TR><TR><TD valign=top class=cp></td></tr></table><br><table cellspacing=0 cellpadding=0 width=666 border=0><TBODY><TR><TD class=ct width=666><img height=1 src=/webroot/img/boletophp/6.png width=665 border=0></TD></TR><TR><TD class=ct width=666><div align=right><b class=cp>Recibo
                        do Sacado</b></div></TD></tr></tbody></table><table width=666 cellspacing=5 cellpadding=0 border=0><tr><td width=41></TD></tr></table>

    <BR><table cellspacing=0 cellpadding=0 width=666 border=0><tr><td class=cp width=150>
  <span class="campo"><IMG
          src="/webroot/img/boletophp/logocaixa.jpg" width="150" height="40"
          border=0></span></td>
            <td width=3 valign=bottom><img height=22 src=/webroot/img/boletophp/3.png width=2 border=0></td><td class=cpt width=58 valign=bottom><div align=center><font class=bc><?php echo $dadosboleto["codigo_banco_com_dv"]?></font></div></td><td width=3 valign=bottom><img height=22 src=/webroot/img/boletophp/3.png width=2 border=0></td><td class=ld align=right width=453 valign=bottom><span class=ld>
<span class="campotitulo">
<?php echo $dadosboleto["linha_digitavel"]?>
</span></span></td>
        </tr><tbody><tr><td colspan=5><img height=2 src=/webroot/img/boletophp/2.png width=666 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=298 height=13>Cedente</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=126 height=13>Agência/Código
                do Cedente</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=34 height=13>Espécie</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=53 height=13>Quantidade</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=120 height=13>Nosso
                número</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=298 height=12>
                <span class="campo"><?php echo $dadosboleto["cedente"]; ?></span></td>
            <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=126 height=12>
  <span class="campo">
  <?php echo $dadosboleto["agencia_codigo"]?>
  </span></td>
            <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top  width=34 height=12><span class="campo">
  <?php echo $dadosboleto["especie"]?>
</span>
            </td>
            <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top  width=53 height=12><span class="campo">
  <?php echo $dadosboleto["quantidade"]?>
</span>
            </td>
            <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=120 height=12>
  <span class="campo">
  <?php echo $dadosboleto["nosso_numero"]?>
  </span></td>
        </tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=298 height=1><img height=1 src=/webroot/img/boletophp/2.png width=298 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=126 height=1><img height=1 src=/webroot/img/boletophp/2.png width=126 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=34 height=1><img height=1 src=/webroot/img/boletophp/2.png width=34 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=53 height=1><img height=1 src=/webroot/img/boletophp/2.png width=53 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=120 height=1><img height=1 src=/webroot/img/boletophp/2.png width=120 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top colspan=3 height=13>Número
                do documento</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=132 height=13>CPF/CNPJ</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=134 height=13>Vencimento</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>Valor
                documento</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top colspan=3 height=12>
  <span class="campo">
  <?php echo $dadosboleto["numero_documento"]?>
  </span></td>
            <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=132 height=12>
  <span class="campo">
  <?php echo $dadosboleto["cpf_cnpj"]?>
  </span></td>
            <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=134 height=12>
  <span class="campo">
  <?php echo $dadosboleto["data_vencimento"]?>
  </span></td>
            <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12>
  <span class="campo">
  <?php echo $dadosboleto["valor_boleto"]?>
  </span></td>
        </tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=/webroot/img/boletophp/2.png width=113 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=72 height=1><img height=1 src=/webroot/img/boletophp/2.png width=72 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=132 height=1><img height=1 src=/webroot/img/boletophp/2.png width=132 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=134 height=1><img height=1 src=/webroot/img/boletophp/2.png width=134 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=113 height=13>(-)
                Desconto / Abatimentos</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=112 height=13>(-)
                Outras deduções</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=113 height=13>(+)
                Mora / Multa</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=113 height=13>(+)
                Outros acréscimos</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(=)
                Valor cobrado</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=113 height=12></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=112 height=12></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=113 height=12></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=113 height=12></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=/webroot/img/boletophp/2.png width=113 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=112 height=1><img height=1 src=/webroot/img/boletophp/2.png width=112 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=/webroot/img/boletophp/2.png width=113 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=/webroot/img/boletophp/2.png width=113 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=659 height=13>Sacado</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=659 height=12>
  <span class="campo">
  <?php echo $dadosboleto["sacado"]?>
  </span></td>
        </tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=659 height=1><img height=1 src=/webroot/img/boletophp/2.png width=659 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct  width=7 height=12></td><td class=ct  width=564 ></td><td class=ct  width=7 height=12></td><td class=ct  width=88 >Autenticação
                mecânica</td></tr><tr><td  width=7 ></td><td class=cp width=564 >
<span class="campo">

  </span>
            </td><td  width=7 ></td><td  width=88 ></td></tr></tbody></table><table cellspacing=0 cellpadding=0 width=666 border=0><tbody><tr><td width=7></td><td  width=500 class=cp>
                <br><br><br>
            </td><td width=159></td></tr></tbody></table><table cellspacing=0 cellpadding=0 width=666 border=0><tr><td class=ct width=666></td></tr><tbody><tr><td class=ct width=666>
                <div align=right>Corte na linha pontilhada</div></td></tr><tr><td class=ct width=666><img height=1 src=/webroot/img/boletophp/6.png width=665 border=0></td></tr></tbody></table><br><table cellspacing=0 cellpadding=0 width=666 border=0><tr><td class=cp width=150>
  <span class="campo"><IMG
          src="/webroot/img/boletophp/logocaixa.jpg" width="150" height="40"
          border=0></span></td>
            <td width=3 valign=bottom><img height=22 src=/webroot/img/boletophp/3.png width=2 border=0></td><td class=cpt width=58 valign=bottom><div align=center><font class=bc><?php echo $dadosboleto["codigo_banco_com_dv"]?></font></div></td><td width=3 valign=bottom><img height=22 src=/webroot/img/boletophp/3.png width=2 border=0></td><td class=ld align=right width=453 valign=bottom><span class=ld>
<span class="campotitulo">
<?php echo $dadosboleto["linha_digitavel"]?>
</span></span></td>
        </tr><tbody><tr><td colspan=5><img height=2 src=/webroot/img/boletophp/2.png width=666 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=472 height=13>Local
                de pagamento</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>Vencimento</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=472 height=12>Pagável
                em qualquer Banco até o vencimento</td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12>
  <span class="campo">
  <?php echo $dadosboleto["data_vencimento"]?>
  </span></td>
        </tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=472 height=1><img height=1 src=/webroot/img/boletophp/2.png width=472 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=472 height=13>Cedente</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>Agência/Código
                cedente</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=472 height=12>
  <span class="campo">
  <?php echo $dadosboleto["cedente"]?>
  </span></td>
            <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12>
  <span class="campo">
  <?php echo $dadosboleto["agencia_codigo"]?>
  </span></td>
        </tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=472 height=1><img height=1 src=/webroot/img/boletophp/2.png width=472 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13>
                <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=113 height=13>Data
                do documento</td><td class=ct valign=top width=7 height=13> <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=153 height=13>N<u>o</u>
                documento</td><td class=ct valign=top width=7 height=13> <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=62 height=13>Espécie
                doc.</td><td class=ct valign=top width=7 height=13> <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=34 height=13>Aceite</td><td class=ct valign=top width=7 height=13>
                <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=82 height=13>Data
                processamento</td><td class=ct valign=top width=7 height=13> <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>Nosso
                número</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top  width=113 height=12><div align=left>
  <span class="campo">
  <?php echo $dadosboleto["data_documento"]?>
  </span></div></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=153 height=12>
    <span class="campo">
    <?php echo $dadosboleto["numero_documento"]?>
    </span></td>
            <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top  width=62 height=12><div align=left><span class="campo">
    <?php echo $dadosboleto["especie_doc"]?>
  </span>
                </div></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top  width=34 height=12><div align=left><span class="campo">
 <?php echo $dadosboleto["aceite"]?>
 </span>
                </div></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top  width=82 height=12><div align=left>
   <span class="campo">
   <?php echo $dadosboleto["data_processamento"]?>
   </span></div></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12>
     <span class="campo">
     <?php echo $dadosboleto["nosso_numero"]?>
     </span></td>
        </tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=113 height=1><img height=1 src=/webroot/img/boletophp/2.png width=113 border=0></td><td valign=top width=7 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=153 height=1><img height=1 src=/webroot/img/boletophp/2.png width=153 border=0></td><td valign=top width=7 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=62 height=1><img height=1 src=/webroot/img/boletophp/2.png width=62 border=0></td><td valign=top width=7 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=34 height=1><img height=1 src=/webroot/img/boletophp/2.png width=34 border=0></td><td valign=top width=7 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=82 height=1><img height=1 src=/webroot/img/boletophp/2.png width=82 border=0></td><td valign=top width=7 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr>
            <td class=ct valign=top width=7 height=13> <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top COLSPAN="3" height=13>Uso
                do banco</td><td class=ct valign=top height=13 width=7> <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=83 height=13>Carteira</td><td class=ct valign=top height=13 width=7>
                <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=53 height=13>Espécie</td><td class=ct valign=top height=13 width=7>
                <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=123 height=13>Quantidade</td><td class=ct valign=top height=13 width=7>
                <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=72 height=13>
                Valor Documento</td><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(=)
                Valor documento</td></tr><tr> <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td valign=top class=cp height=12 COLSPAN="3"><div align=left>
                </div></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top  width=83>
                <div align=left> <span class="campo">
  <?php echo $dadosboleto["carteira"]?>
</span></div></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top  width=53><div align=left><span class="campo">
<?php echo $dadosboleto["especie"]?>
</span>
                </div></td><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top  width=123><span class="campo">
 <?php echo $dadosboleto["quantidade"]?>
 </span>
            </td>
            <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top  width=72>
   <span class="campo">
   <?php echo $dadosboleto["valor_unitario"]?>
   </span></td>
            <td class=cp valign=top width=7 height=12> <img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12>
   <span class="campo">
   <?php echo $dadosboleto["valor_boleto"]?>
   </span></td>
        </tr><tr><td valign=top width=7 height=1> <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=75 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=31 height=1><img height=1 src=/webroot/img/boletophp/2.png width=31 border=0></td><td valign=top width=7 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=83 height=1><img height=1 src=/webroot/img/boletophp/2.png width=83 border=0></td><td valign=top width=7 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=53 height=1><img height=1 src=/webroot/img/boletophp/2.png width=53 border=0></td><td valign=top width=7 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=123 height=1><img height=1 src=/webroot/img/boletophp/2.png width=123 border=0></td><td valign=top width=7 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=72 height=1><img height=1 src=/webroot/img/boletophp/2.png width=72 border=0></td><td valign=top width=7 height=1>
                <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody>
    </table><table cellspacing=0 cellpadding=0 width=666 border=0><tbody><tr><td align=right width=10><table cellspacing=0 cellpadding=0 border=0 align=left><tbody>
                    <tr> <td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td></tr><tr>
                        <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td></tr><tr>
                        <td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=1 border=0></td></tr></tbody></table></td><td valign=top width=468 rowspan=5><font class=ct>Instruções
                    (Texto de responsabilidade do cedente)</font><br><br><span class=cp> <FONT class=campo>
                        <?php echo $dadosboleto["instrucoes1"]; ?><br>
                        <?php echo $dadosboleto["instrucoes2"]; ?><br>
                        <?php echo $dadosboleto["instrucoes3"]; ?></FONT><br><br>
</span></td>
            <td align=right width=188><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(-)
                            Desconto / Abatimentos</td></tr><tr> <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr>
                        <td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10>
                <table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td></tr><tr><td valign=top width=7 height=1>
                            <img height=1 src=/webroot/img/boletophp/2.png width=1 border=0></td></tr></tbody></table></td><td align=right width=188><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(-)
                            Outras deduções</td></tr><tr><td class=cp valign=top width=7 height=12> <img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10>
                <table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr><td class=ct valign=top width=7 height=13>
                            <img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td></tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=1 border=0></td></tr></tbody></table></td><td align=right width=188>
                <table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(+)
                            Mora / Multa</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr>
                        <td valign=top width=7 height=1> <img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1>
                            <img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10><table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr>
                        <td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td></tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=1 border=0></td></tr></tbody></table></td><td align=right width=188>
                <table cellspacing=0 cellpadding=0 border=0><tbody><tr> <td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(+)
                            Outros acréscimos</td></tr><tr> <td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody></table></td></tr><tr><td align=right width=10><table cellspacing=0 cellpadding=0 border=0 align=left><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td></tr></tbody></table></td><td align=right width=188><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>(=)
                            Valor cobrado</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top align=right width=180 height=12></td></tr></tbody>
                </table></td></tr></tbody></table><table cellspacing=0 cellpadding=0 width=666 border=0><tbody><tr><td valign=top width=666 height=1><img height=1 src=/webroot/img/boletophp/2.png width=666 border=0></td></tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=659 height=13>Sacado</td></tr><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=659 height=12><span class="campo">
<?php echo $dadosboleto["sacado"]?>
</span>
            </td>
        </tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=cp valign=top width=7 height=12><img height=12 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=659 height=12><span class="campo">
<?php echo $dadosboleto["endereco1"]?>
</span>
            </td>
        </tr></tbody></table><table cellspacing=0 cellpadding=0 border=0><tbody><tr><td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=cp valign=top width=472 height=13>
  <span class="campo">
  <?php echo $dadosboleto["endereco2"]?>
  </span></td>
            <td class=ct valign=top width=7 height=13><img height=13 src=/webroot/img/boletophp/1.png width=1 border=0></td><td class=ct valign=top width=180 height=13>Cód.
                baixa</td></tr><tr><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=472 height=1><img height=1 src=/webroot/img/boletophp/2.png width=472 border=0></td><td valign=top width=7 height=1><img height=1 src=/webroot/img/boletophp/2.png width=7 border=0></td><td valign=top width=180 height=1><img height=1 src=/webroot/img/boletophp/2.png width=180 border=0></td></tr></tbody></table><TABLE cellSpacing=0 cellPadding=0 border=0 width=666><TBODY><TR><TD class=ct  width=7 height=12></TD><TD class=ct  width=409 >Sacador/Avalista</TD><TD class=ct  width=250 ><div align=right>Autenticação
                    mecânica - <b class=cp>Ficha de Compensação</b></div></TD></TR><TR><TD class=ct  colspan=3 ></TD></tr></tbody></table><TABLE cellSpacing=0 cellPadding=0 width=666 border=0><TBODY><TR><TD vAlign=bottom align=left height=50><?php fbarcode($dadosboleto["codigo_barras"]); ?>
            </TD>
        </tr></tbody></table><TABLE cellSpacing=0 cellPadding=0 width=666 border=0><TR><TD class=ct width=666></TD></TR><TBODY><TR><TD class=ct width=666><div align=right>Corte
                    na linha pontilhada</div></TD></TR><TR><TD class=ct width=666><img height=1 src=/webroot/img/boletophp/6.png width=665 border=0></TD></tr></tbody></table>
    </BODY>
</HTML>
