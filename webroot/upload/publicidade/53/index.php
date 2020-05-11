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
<title>Série Fundações - Engenharia Geotécnica</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">

<style type="text/css">
body {
	margin: auto;
	
}
</style>
</head>
<body>
<div align="center" style=" font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333; margin:auto; padding-bottom:3px; padding-top:3px;">Caso não esteja visualizando o e-mail abaixo, <a href="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/index.php?preview=1&produtoid=153&folder=53" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</div>
<table align="center" id="Table_01" width="780" border="0" cellpadding="0" cellspacing="0" style="">
	<tr>
		<td><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_02.png" alt="" width="780" height="137" border="0"></a></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_03.png" width="780" height="232" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_04.png" width="780" height="101" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_05.png" width="780" height="58" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_06.png" width="780" height="70" alt=""></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;">
		  <div style="position: absolute; left: 594px; top: 53px; height: 72px; width: 126px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height:16px;"><?php
    		$i=0;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?>				</div></div>
		  <img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_07.png" alt="" width="780" height="186" usemap="#capitulo1" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 594px; top: 61px; height: 72px; width: 126px; color: #2D291E; font-family: Arial, Helvetica, sans-serif;font-size: 12px; line-height:16px;"><?php
			$i=1;		
    		if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
		  <img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_08.png" alt="" width="780" height="163" usemap="#capitulo2" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 594px; top: 66px; height: 72px; width: 126px; color: #2D291E; font-family: Arial, Helvetica, sans-serif;font-size: 12px; line-height:16px;"><?php
			$i=2;		
    		if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_09.png" alt="" width="780" height="159" usemap="#capitulo3" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 594px; top: 49px; height: 72px; width: 126px; color: #2D291E; font-family: Arial, Helvetica, sans-serif;font-size: 12px; line-height:16px;"><?php
    		$i=3;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
		  <img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_10.png" alt="" width="780" height="153" usemap="#capitulo4" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 594px; top: 55px; height: 72px; width: 126px; color: #2D291E; font-family: Arial, Helvetica, sans-serif;font-size: 12px; line-height:16px;"><?php
    		$i=4;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_11.png" alt="" width="780" height="155" usemap="#capitulo5" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 594px; top: 45px; height: 72px; width: 126px; color: #2D291E; font-family: Arial, Helvetica, sans-serif;font-size: 12px; line-height:16px;"><?php
    		$i=5;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_12.png" alt="" width="780" height="150" usemap="#capitulo6" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 594px; top: 39px; height: 72px; width: 126px; color: #2D291E; font-family: Arial, Helvetica, sans-serif;font-size: 12px; line-height:16px;"><?php
    		$i=6;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_13.png" alt="" width="780" height="156" usemap="#capitulo7" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_14.png" alt="" width="780" height="87" usemap="#SaibaMais" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_15.png" width="780" height="200" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/53/images/img_16.png" alt="" width="780" height="208" usemap="#rodape" border="0"></td>
	</tr>
</table>
<map name="capitulo1">
  <area shape="rect" coords="718,64,740,86" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="718,100,740,122" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo2">
  <area shape="rect" coords="718,108,740,130" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="718,72,740,94" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo3">
  <area shape="rect" coords="718,113,740,135" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="718,77,740,99" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo4">
  <area shape="rect" coords="718,96,740,118" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="718,60,740,83" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo5">
  <area shape="rect" coords="718,101,740,124" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="718,66,740,88" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo6">
  <area shape="rect" coords="718,53,740,76" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="718,93,740,115" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo7">
  <area shape="rect" coords="718,86,740,110" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="718,41,740,63" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="SaibaMais">
  <area shape="rect" coords="233,18,547,67" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="rodape">
  <area shape="rect" coords="52,160,76,183" href="http://www.facebook.com/qisat" target="_blank" alt="QiSat no facebook">
  <area shape="rect" coords="81,160,105,184" href="http://br.linkedin.com/in/qisat" target="_blank" alt="QiSat no LinkedIn">
  <area shape="rect" coords="109,160,133,184" href="http://twitter.com/qisat/" target="_blank" alt="Twitter QiSat">
  <area shape="rect" coords="137,161,161,184" href="http://www.youtube.com/qisat/" target="_blank" alt="Canal QiSat no YouTube">
  <area shape="rect" coords="166,160,189,183" href="http://www.qisat.com.br/contato/contato.php" target="_blank" alt="MSN QiSat">
  <area shape="rect" coords="459,71,706,113" href="http://www.altoqi.com.br">
  <area shape="rect" coords="47,45,293,79" href="http://www.qisat.com.br/">
</map>
</body>
</html>