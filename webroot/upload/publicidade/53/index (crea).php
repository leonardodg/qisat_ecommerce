<?php 
$diretorio = '';
for ($i = 0; $i < $_GET['niveis']; $i++) {
    $diretorio .= '../';
}
require_once($diretorio.'../../../config.php');
global $CFG;
require_once($CFG->dirroot.'/ecommerce/dao/ProdutoDao.php');
require_once($CFG->dirroot.'/ecommerce/dao/PromocaoDao.php');
function retornarValor($id, $tipo, $entidade){
	global $CFG;
	$produtoDao = new ProdutoDao();
	$produto = $produtoDao->buscaPeloId($id);
	if($tipo == 'valordesconto' || $tipo == 'porcentagem'){
		$idEntidade = get_record_sql('SELECT id FROM '.$CFG->prefix.'alternative_host WHERE shortname like "'.$entidade.'"');
		$promocaoDao = new PromocaoDao();
		$promocoes = $promocaoDao->selecionaPromocaoViaIdProduto($id, $idEntidade->id);
		$valordesconto = 0;
		$porcentagem = 0;
		foreach ($promocoes as $promocao) {
			if($tipo == 'valordesconto'){
				if($promocao->getDescontoValor()==0){
					$valordesconto = max($valordesconto, $produto->getPreco()*$promocao->getDescontoPorcentagem()/100);
				}
				$valordesconto = max($valordesconto, $promocao->getDescontoValor());
			} else {
				if($promocao->getDescontoPorcentagem()==0){
					$porcentagem = max($porcentagem, $promocao->getDescontoValor()/$produto->getPreco()*100);
				}
				$porcentagem = max($porcentagem, $promocao->getDescontoPorcentagem());
			}
		}
		if($tipo == 'valordesconto'){
			return number_format(round($produto->getPreco()-$valordesconto), 2, ',', '.');
		} else {
			return round($porcentagem);
		}
	} else {
		return number_format(round($produto->getPreco()), 2, ',', '.');
	}
}
/* <?php echo retornarValor(121, "valortabela", "CREA-RO");?> */
/********************************************************************************************
 * Parametros da função:
 * 1º - Id do produto - sem aspas
 * 2º - Tipo do valor a ser retornado(valortabela, valordesconto ou porcentagem) - com aspas
 * 3º - Entidade a ser consultada(QiSat, CREA-RO, CREA-TO, CREA-BA, CREA-DF) - com aspas
********************************************************************************************/
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
<div align="center" style=" font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333; margin:auto; padding-bottom:3px; padding-top:3px;">Caso não esteja visualizando o e-mail abaixo, <a href="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/index.html" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</div>
<table align="center" id="Table_01" width="780" border="0" cellpadding="0" cellspacing="0" style="">
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_01.png" width="780" height="104" alt=""></td>
	</tr>
	<tr>
		<td><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_02.png" alt="" width="780" height="137"></a></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_03.png" width="780" height="232" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_04.png" width="780" height="101" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_05.png" width="780" height="58" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_06.png" width="780" height="70" alt=""></td>
	</tr>
	<tr>
		<td><?php echo retornarValor(154, "valordesconto", "CDFEG");?><img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_07.png" alt="" width="780" height="186" usemap="#capitulo1" border="0"></td>
	</tr>
	<tr>
		<td>
		  <img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_08.png" alt="" width="780" height="163" usemap="#capitulo2" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_09.png" alt="" width="780" height="159" usemap="#capitulo3" border="0"></td>
	</tr>
	<tr>
		<td>
		  <img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_10.png" alt="" width="780" height="153" usemap="#capitulo4" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_11.png" alt="" width="780" height="155" usemap="#capitulo5" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_12.png" alt="" width="780" height="150" usemap="#capitulo6" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_13.png" alt="" width="780" height="156" usemap="#capitulo7" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_14.png" alt="" width="780" height="87" usemap="#SaibaMais" border="0"></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_15.png" width="780" height="200" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="http://public.qisat.com.br/campanhas/e-convites/cursos/cdfeg/images/img_16.png" alt="" width="780" height="208" usemap="#rodape" border="0"></td>
	</tr>
</table>
<map name="capitulo1">
  <area shape="rect" coords="698,54,720,76" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="698,80,720,102" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo2">
  <area shape="rect" coords="698,64,720,86" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="698,90,720,112" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo3">
  <area shape="rect" coords="698,67,720,89" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="698,93,720,115" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo4">
  <area shape="rect" coords="698,50,720,73" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="698,76,720,98" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo5">
  <area shape="rect" coords="698,46,720,68" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="698,72,720,95" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo6">
  <area shape="rect" coords="698,28,720,51" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="698,54,720,76" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
</map>
<map name="capitulo7">
  <area shape="rect" coords="698,26,720,48" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
  <area shape="rect" coords="698,52,720,76" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">
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