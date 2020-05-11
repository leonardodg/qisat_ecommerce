<?php 
	include ('../../dadosConvite.php');/*Arquivo com os dados do curso. NÃ£o remover*/	
	
	##################### Variáveis com os dados s####################
	## $nomeCurso = Nome do curso									##
	## $preco = Valor integral do curso							 	##
	## $moeda = Moeda de referência								 	##
	## $dataInicioPromocao = Data de início da promoção 			##
 	## $dataFimPromocao = Data do fim da promoção					##
 	## $descontoValor = Valor do desconto em $						##
 	## $descontoPorcentagem = Valor do desconto em %                ##
 	## $desconto = Valor do desconto em $							##
 	## $precoPromocional = Preço promocional						##
 	## $nuMaxParcelas = Numero máximo de parcelas					##
	##################################################################
	
	######## Opções disponíveis qunado o produto for uma série #######
	## $produtosSerie_ids[] = ids dos produtos da série			 	##
	## $produtosSerie_nomes[] = nomes dos produtos da série		 	##
	## $produtosSerie_siglas[] = sigla dos produtos da série		##
	## $produtosSerie_precos[] = preço de tabela do produto da série##
	## $produtosSerie_moedas[] = moeda do produto da serie			##
	## $produtosSerie_datasInicio[] = data de inicio da promoção	##
	## $produtosSerie_datasFim[] = data de fim da promoção			##
	## $produtosSerie_descontos[] = descontos da série				##
	## $produtosSerie_precosPromocionais[] = preços da série		##
	## $produtosSerie_numeroParcelas[] = max parcelas da série 	 	##
	##################################################################
	 
	//echo $nomeCurso . '<br>';
	//echo $preco . '<br>';
	//echo $moeda . '<br>';
	//echo $dataInicioPromocao . '<br>';
 	//echo $dataFimPromocao . '<br>';
 	//echo $descontoValor . '<br>';
 	//echo $descontoPorcentagem . '<br>';
 	//echo $desconto . '<br>';
 	//echo $precoPromocional . '<br>';
 	//echo $nuMaxParcelas . '<br>';
	
	//echo '<pre>';
	//print_r($produtosSerie_ids);
	//print_r($produtosSerie_nomes);
	//print_r($produtosSerie_siglas);
	//print_r($produtosSerie_precos);
	//print_r($produtosSerie_moedas);
	//print_r($produtosSerie_datasInicio);
	//print_r($produtosSerie_datasFim);
	//print_r($produtosSerie_descontos);
	//print_r($produtosSerie_precosPromocionais);
	//print_r($produtosSerie_numeroParcelas);
	//echo '</pre>';
	
?>
<html>
<head>
<title>Série Licenciamento Ambiental - Bases para o Licenciamento</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">

<style type="text/css">
p{
	margin:0;
}
body {
	margin: auto;
	
}
</style>
</head>
<body>
<div align="center" style=" font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333; margin:auto; padding-bottom:3px; padding-top:3px;">Caso não esteja visualizando o e-mail abaixo, <a href="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/index.php?preview=1&produtoid=236&folder=75" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</div>
<table width="780px" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px #CDD8E0"><tr><td>
<table width="780" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_02.png"></td>
	</tr>
    <tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_03.png"></td>
	</tr>
    <tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_04.png"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_05.png"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_06.png"></td>
	</tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
  <tr>
  <tr>
    <td width="588" rowspan="2"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_07.png" border="0"></td>
    <td width="131" height="49" valign="bottom" style="color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px; padding-left:11px; padding-bottom:3px;"><?php
    		$i=0;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></td>
    <td width="61" rowspan="2"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/botoes.png" border="0"></a></td>
    </tr>
    
  <tr>
    <td height="40" valign="top" style="padding-left:8px;"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/cargahoraria.gif"  usemap="#capitulo1" border="0"></td>
    </tr>
    	<tr>
    <td width="588" rowspan="2"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_08.png" border="0"></td>
    <td width="131" height="49" valign="bottom" style="color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px; padding-left:11px; padding-bottom:3px;"><?php
    		$i=0;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></td>
    <td width="61" rowspan="2"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/botoes.png" border="0"></a></td>
    </tr>
    
  <tr>
    <td height="40" valign="top" style="padding-left:8px;"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/cargahoraria1.gif"   border="0"></td>
    </tr>

    
    <td width="588" rowspan="2"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_09.png" border="0"></td>
    <td width="131" height="49" valign="bottom" style="color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px; padding-left:11px; padding-bottom:3px;"><?php
    		$i=0;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></td>
    <td width="61" rowspan="2"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/botoes.png" border="0"></a></td>
    </tr>
    
  <tr>
    <td height="40" valign="top" style="padding-left:8px;"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/cargahoraria.gif"   border="0"></td>
    </tr>

<tr>
    <td width="588" rowspan="2"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_10.png" border="0"></td>
    <td width="131" height="49" valign="bottom" style="color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px; padding-left:11px; padding-bottom:3px;"><?php
    		$i=1;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></td>
    <td width="61" rowspan="2"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/botoes.png" border="0"></a></td>
    </tr>
    
  <tr>
    <td height="40" valign="top" style="padding-left:8px;"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/cargahoraria1.gif" border="0"></td>
    </tr>
        <tr>
    <td width="588" rowspan="2"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_11.png" border="0"></td>
    <td width="131" height="49" valign="bottom" style="color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px; padding-left:11px; padding-bottom:3px;"><?php
    		$i=2;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></td>
    <td width="61" rowspan="2"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/botoes.png" border="0"></a></td>
    </tr>
    
  <tr>
    <td height="40" valign="top" style="padding-left:8px;"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/cargahoraria1.gif" border="0"></td>
    </tr>
    <tr>
    <td width="588" rowspan="2"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_12.png" border="0"></td>
    <td width="131" height="49" valign="bottom" style="color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px; padding-left:11px; padding-bottom:3px;"><?php
    		$i=2;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></td>
    <td width="61" rowspan="2"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/botoes.png" border="0"></a></td>
    </tr>
    
  <tr>
    <td height="40" valign="top" style="padding-left:8px;"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/cargahoraria1.gif" border="0"></td>
    </tr>
    <tr>
    <td width="588" rowspan="2"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_13.png" border="0"></td>
    <td width="131" height="49" valign="bottom" style="color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px; padding-left:11px; padding-bottom:3px;"><?php
    		$i=3;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></td>
    <td width="61" rowspan="2"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/botoes.png" border="0"></a></td>
    </tr>
    
  <tr>
    <td height="40" valign="top" style="padding-left:8px;"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/cargahoraria.gif" border="0"></td>
    </tr>
    <tr>
    <td width="588" rowspan="2"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_14.png" border="0"></td>
    <td width="131" height="49" valign="bottom" style="color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px; padding-left:11px; padding-bottom:3px;"><?php
    		$i=4;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></td>
    <td width="61" rowspan="2"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/botoes.png" border="0"></a></td>
    </tr>
    
  <tr>
    <td height="40" valign="top" style="padding-left:8px;"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/cargahoraria.gif" border="0"></td>
    </tr>   
 


      </table>

  </td></tr>
	
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_15.png" alt="" width="780" usemap="#SaibaMais" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_16.png"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/75/images/img_17.png" usemap="#rodape" border="0"></td>
	</tr>
</table>
</td></tr></table>
</body>
</html>
<map name="SaibaMais">
  <area shape="rect" coords="234,6,548,55" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank">
</map>
<map name="rodape">
  <area shape="rect" coords="52,160,76,183" href="http://www.facebook.com/qisat" target="_blank" alt="QiSat no facebook">
  <area shape="rect" coords="81,160,105,184" href="http://br.linkedin.com/in/qisat" target="_blank" alt="QiSat no LinkedIn">
  <area shape="rect" coords="109,160,133,184" href="http://twitter.com/qisat/" target="_blank" alt="Twitter QiSat">
  <area shape="rect" coords="137,161,161,184" href="http://www.youtube.com/qisat/" target="_blank" alt="Canal QiSat no YouTube">
  <area shape="rect" coords="166,160,189,183" href="http://www.qisat.com.br/contato/contato.php" target="_blank" alt="MSN QiSat">
  <area shape="rect" coords="457,73,704,115" href="http://www.altoqi.com.br">
  <area shape="rect" coords="47,45,293,79" href="http://www.qisat.com.br/">
</map>