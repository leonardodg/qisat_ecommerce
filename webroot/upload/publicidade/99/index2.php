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
 * Parametros da fun��o:
 * 1� - Id do produto - sem aspas
 * 2� - Tipo do valor a ser retornado(valortabela, valordesconto ou porcentagem) - com aspas
 * 3� - Entidade a ser consultada(QiSat, CREA-RO, CREA-TO, CREA-BA, CREA-DF) - com aspas
********************************************************************************************/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>[QiSat] Cat�logo de Cursos na �rea Hidr�ulica</title>
<style type="text/css">
<!--
a:link {
	color: #373737;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #555555;
}
a:hover {
	text-decoration: underline;
	color: #111111;
}
a:active {
	text-decoration: none;
	color: #555555;
}
body {
	background-color: #FFFFFF;
}
body,td,th {
	font-family: Tahoma, Arial, Helvetica, sans-serif;
	color: #555555;
}
-->
</style></head>
<body>
<div align="center" style="font-size:10px; color:#333333; margin:auto; padding-bottom:10px;">Caso n�o esteja visualizando o e-mail abaixo, <a href="http://www.qisat.com.br/ecommerce/catalogo/arquivos/13/index2.php?niveis=1" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</div>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
         <tr>
        	<td><img src="http://public.qisat.com.br/campanhas/e-convites/2017/catalogo_geral/images/topo.png"  width="718" height="113" usemap="#Map" border="0"></td>
  </tr>
         <tr>
       	   <td align="center">
		<TABLE border=0 cellSpacing=0 cellPadding=0 width=700 bgColor="#ffffff" align="center">
         <tr>
       	   <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1718/images/top_03.png" border="0" ></td>
         </tr>
         <tr>
       	   <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1718/images/top_04.png" border="0" ></td>
         </tr>
  <tr>
    <td height="28" align="center" style="color:#0093e2; font-size:18px; font-family:Tahoma, Arial; font-weight:bold;">�REA HIDR�ULICA</td>
  </tr>
  <tr>
    <td  height="12" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1718/images/linha.gif"></td>
  </tr>  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1718/images/hidraulico.gif" width="680" height="27"></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#373737; ">
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR</td>
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="font-size:11px;">DEMONSTRATIVO</span></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://qisat.com.br/ecommerce/produtos/info.php?id=131" target="_blank">Curso Software QiHidrossanit�rio a dist�ncia </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(131, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://qisat.com.br/ecommerce/produtos/info.php?id=131" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=136" target="_blank">Curso Software QiInc�ndio a dist�ncia </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(136, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=136" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=12" target="_blank">Curso Software Hydros V4 a dist&acirc;ncia</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(12, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=12" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=17" target="_blank">Curso Software Inc�ndio - Hydros V4 a dist�ncia</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(17, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=17" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO T�CNICO</b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR</td>
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="font-size:11px;">DEMONSTRATIVO</span></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=226" target="_blank">Curso Combate a Inc�ndio - Hidrantes e Mangotinhos</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(226, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=226" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=4" target="_blank">Curso Instala��es Prediais de �guas Pluviais</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(4, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=4" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=5" target="_blank">Curso Instala��es Prediais Esgoto Sanit�rio</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(5, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=5" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
<tr>
  <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=7" target="_blank">Curso Instala��es Prediais �gua Fria - Fundamentos</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(7, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=7" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=8" target="_blank">Curso Instala��es Prediais �gua Fria - Dimensionamento</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(8, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8" style="color:#23598c;">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=8" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=11" target="_blank">Curso Instala��es Prediais de �gua Quente - Gera��o</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(11, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=11" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=25" target="_blank">Curso Instala��es Prediais de �gua Quente - Distribui��o</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(25, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=25" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
<tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    </tr></table>
    <table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>S�RIES DE CAP�TULOS - TE�RICO</b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR</td>
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="font-size:11px;">DEMONSTRATIVO</span></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=213" target="_blank">S�rie Combate a Inc�ndio - Hidrantes e Mangotinhos</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(213, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=213" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    </tr></table>
<table width="678" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
<tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>PACOTES DE CURSOS</b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR</td>
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="font-size:11px;">DEMONSTRATIVO</span></td>
  </tr>
    <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=125" target="_blank"><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos Instala��es Hidrossanit�rias Prediais via internet</strong></span></a></td>
    <td width="90" bgcolor="#ecedee" align="center" style="font-size:11px;">R$ <?php echo retornarValor(125, "valortabela", "QiSat");?></td>
    
    <td width="100" bgcolor="#ecedee" align="center"><span style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=125" target="_blank" style="color:#23598c;">VER PACOTE</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=130" target="_blank"><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos Instala��es Hidrossanit�rias Prediais - Te�rico via internet</strong></span></a></td>
    <td width="90" bgcolor="#ecedee" align="center" style="font-size:11px;">R$ <?php echo retornarValor(130, "valortabela", "QiSat");?></td>
    
    <td width="100" bgcolor="#ecedee" align="center"><span style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=130" target="_blank" style="color:#23598c;">VER PACOTE</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=176" target="_blank"><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos Eberick e Instala��es � Premium via internet

</strong></span></a></td>
    <td width="90" bgcolor="#ecedee" align="center" style="font-size:11px;">R$ <?php echo retornarValor(176, "valortabela", "QiSat");?></td>
    
    <td width="100" bgcolor="#ecedee" align="center"><span style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=176" target="_blank" style="color:#23598c;">VER PACOTE</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=175" target="_blank"><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos Eberick e Instala��es � Full via internet</strong></span></a></td>
    <td width="90" bgcolor="#ecedee" align="center" style="font-size:11px;">R$ <?php echo retornarValor(175, "valortabela", "QiSat");?></td>
    
    <td width="100" bgcolor="#ecedee" align="center"><span style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=175" target="_blank" style="color:#23598c;">VER PACOTE</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=173" target="_blank"><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos QiBuilder - Full via internet</strong></span></a></td>
    <td width="90" bgcolor="#ecedee" align="center" style="font-size:11px;">R$ <?php echo retornarValor(173, "valortabela", "QiSat");?></td>
    
    <td width="100" bgcolor="#ecedee" align="center"><span style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1718/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=173" target="_blank" style="color:#23598c;">VER PACOTE</a></span></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
   <tr style="font-size:12px; color:#555555;">
    <td height="27" colspan="4" align="center" bgcolor="#e1e2e3"><b>PROGRAMA��O COMPLETA</b></td>
  </tr>
  <tr>
    <td height="16"  colspan="4" align="center" bgcolor="#ecedee"><a href="http://www.qisat.com.br" target="_blank">Clique aqui para conhecer os cursos de outras �reas.</a></td>
  </tr>
<tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr></table></td>
  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1718/images/webconferencia.gif" width="680" height="27"></td>
  </tr>
    <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:11px; color:#555555;">
    <td height="20" colspan="6" align="center" bgcolor="#e1e2e3" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=8" target="_blank">Conhe�a as Webconfer�ncias dispon�veis no Canal QiSat.</a></td>
    </tr>
    <tr>
  	<td colspan="6">
    	<table width="680" border="0" cellpadding="0" cellspacing="0" align="center">
        	<tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
            <tr style="font-size:11px; color:#222222;">
           	  <td height="48" width="96"  ><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1718/images/back_01.gif"></td>
    			<td width="518" height="48" align="center" bgcolor="#E2EBD8" ><span class="style3"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=10" target="_blank">Confira a Programa��o dos Cursos Presenciais de <em>Software</em> AltoQi</a></span></td>
                <td width="96" height="48" align="center" bgcolor="#E2EBD8"><span class="style8"></span></td>
            </tr>
        </table>  </tr>
  <tr>
    <td width="200" height="80" style="padding-left:15px; font-weight:bold; color:#666666; text-transform:uppercase;">Para maiores informa��es</td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1716/images/linha.jpg" width="1" height="52" /></td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1716/images/contato.png" width="14" height="52" /></td>
    <td align="left" style="line-height:140%; padding-left:15px; color:#666666">(48) 3332-5000<br />
    <span style="color:#666666">central@qisat.com.br</span><br />
      <a href="http://www.qisat.com.br/" target="_blank" style="color:#666666">www.qisat.com.br</a></td>
    <td align="center"><a href="http://www.qisat.com.br" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2017/catalogo_geral/images/logoqisat.png" border="0"></a></td>
    <td align="center"><a href="http://www.altoqi.com.br/" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1716/images/logoaltoqi.png" width="119" height="38" border="0"></a></td>
  </tr>
  <tr>
  	<td colspan="6"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1718/images/linha_rodape.gif" border="0" ></td>
  </tr>
   <tr>
 	<td colspan="6"><div style="font-family:Tahoma, Verdana, Arial; font-size:10px; color:#666666; padding:10px; margin:auto; text-align:center">� 2003 - 2017 - Todos os Direitos Reservados � MN Tecnologia e Treinamento Ltda. | Para mais informa��es entre em <a href="mailto:central@qisat.com.br"><span style="color:#02416d;">contato.</span></a></div></td>
 </tr>
  </table></td>
  </tr>
</table></td>
         </tr>
</table>
<map name="Map">
<area shape="rect" coords="34,23,165,73" href="http://www.qisat.com.br" target="_blank" alt="[Qisat] Cursos para engenharia e arquitetura">
<area shape="rect" coords="572,8,597,34" href="http://www.facebook.com/qisat" target="_blank" alt="QiSat no Facebook">
<area shape="rect" coords="598,7,624,33" href="http://br.linkedin.com/in/qisat" target="_blank" alt="QiSat no LinkedIn">
<area shape="rect" coords="626,7,651,33" href="http://twitter.com/qisat/" target="_blank" alt="Twitter QiSat">
<area shape="rect" coords="653,8,677,34" href="http://www.youtube.com/qisat/" target="_blank" alt="Canal QiSat no YouTube">
<area shape="rect" coords="679,8,705,34" href="http://www.qisat.com.br/contato/contato.php" target="_blank" alt="MSN QiSat">
</map>
</body>
</html>