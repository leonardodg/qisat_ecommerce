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
<title>Série Concreto Armado</title>
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
<div align="center" style=" font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333; margin:auto; padding-bottom:3px; padding-top:3px;">Caso não esteja visualizando o e-mail abaixo, <a href="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/index.php?preview=1&produtoid=202&folder=63" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</div>
<table align="center" id="Table_01" width="780" border="0" cellpadding="0" cellspacing="0" style="">
	<tr>
		<td><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_01.gif" BORDER="0"></a></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_03.gif"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_04.gif"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_05.gif"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_06.gif"></td>
	</tr>
    	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_08.gif"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;">
		  <div style="position: absolute; left: 601px; top: 11px; height: 50px; width: 104px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px;"><?php
    		$i=0;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?>				</div></div>
		  <img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_09.gif"  usemap="#capitulo1" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 600px; top: 6px; height: 47px; width: 104px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px;"><?php
			$i=1;		
    		if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
		  <img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_11.gif" usemap="#capitulo2" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 599px; top: 10px; height: 47px; width: 104px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px;"><?php
			$i=2;		
    		if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_12.gif" usemap="#capitulo3" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 599px; top: 15px; height: 47px; width: 105px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px;"><?php
    		$i=3;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
		  <img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_13.gif" usemap="#capitulo4" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 600px; top: 13px; height: 48px; width: 105px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px;"><?php
    		$i=4;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_14.gif" usemap="#capitulo5" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 601px; top: 17px; height: 47px; width: 103px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px;"><?php
    		$i=5;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_15.gif" usemap="#capitulo6" border="0"></td>
	</tr>
	<tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 600px; top: 15px; height: 48px; width: 105px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px;"><?php
    		$i=6;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_16.gif" usemap="#capitulo7" border="0"></td>
	</tr>
    <tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 600px; top: 15px; height: 48px; width: 105px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px;"><?php
    		$i=6;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
		  <img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_17.gif" usemap="#capitulo7" border="0"></td>
	</tr>
    <tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 600px; top: 15px; height: 48px; width: 105px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px;"><?php
    		$i=6;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
		  <img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_19.gif" usemap="#capitulo7" border="0"></td>
	</tr>
    <tr>
		<td><div class="background" style="position: relative;"><div style="position: absolute; left: 601px; top: 15px; height: 48px; width: 104px; color: #2D291E; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 16px;"><?php
    		$i=6;
			if($produtosSerie_precosPromocionais[$i] != $produtosSerie_precos[$i]){
    			echo 'de <span style="font-size:14px; text-decoration: line-through;">' . $produtosSerie_moedas[$i] . ' ' . $produtosSerie_precos[$i] . '</span><br>';
    			echo 'por ' . $produtosSerie_moedas[$i] . ' <span style="font-size:20px;"><strong>' . $produtosSerie_precosPromocionais[$i] . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $produtosSerie_datasFim[$i] .'</span>';
    		}else{
    			echo '<strong>'. $produtosSerie_moedas[$i] . '</strong> <span style="font-size:20px;">' . $produtosSerie_precos[$i] . '</span>';	
    		}    	
    	?></div></div>
		  <img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_20.gif" usemap="#capitulo7" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_21.gif" alt="" width="780" usemap="#SaibaMais" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_22.gif"></td>
	</tr>
	<tr>
		<td>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/63/images/convite_curso_24.jpg" usemap="#rodape" border="0"></td>
	</tr>
</table>
<map name="capitulo1">
  <area shape="rect" coords="706,11,728,33" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
  <area shape="rect" coords="706,37,727,58" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
</map>
<map name="capitulo2">
  <area shape="rect" coords="705,3,727,25" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
  <area shape="rect" coords="704,30,727,53" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
</map>
<map name="capitulo3">
   <area shape="rect" coords="704,6,726,28" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
  <area shape="rect" coords="705,33,726,54" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
</map>
<map name="capitulo4">
  <area shape="rect" coords="706,11,728,33" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
  <area shape="rect" coords="704,37,727,59" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
</map>
<map name="capitulo5">
  <area shape="rect" coords="706,11,728,33" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
  <area shape="rect" coords="706,37,728,61" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
</map>
<map name="capitulo6">
  <area shape="rect" coords="706,11,728,33" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
  <area shape="rect" coords="706,37,729,62" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
</map>
<map name="capitulo7">
   <area shape="rect" coords="705,9,727,34" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
  <area shape="rect" coords="706,35,728,58" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
</map>
<map name="SaibaMais">
  <area shape="rect" coords="233,3,547,52" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">
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
</body>
</html>