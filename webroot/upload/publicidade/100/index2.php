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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>[QiSat] Catálogo de Cursos na Área Estrutural</title>
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
	font-size: 9px;
}
body {
	background-color: #FFFFFF;
}
body,td,th {
	font-family: Tahoma, Arial, Helvetica, sans-serif;
	color: #555555;
}
.style1 {
	color: #555555;
	font-weight: bold;
}
-->
</style></head>
<body>
<div align="center" style="font-size:10px; color:#333333; margin:auto; padding-bottom:10px;">Caso não esteja visualizando o e-mail abaixo, <a href="http://www.qisat.com.br/ecommerce/catalogo/arquivos/14/index2.php?niveis=1" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</div>
<div align="center" style="margin:auto">
  <table width="718" border="0" cellpadding="0" cellspacing="0">
      <tr>
        	<td height="76"><img src="http://public.qisat.com.br/campanhas/e-convites/2017/catalogo_geral/images/topo.png"  width="718" height="113" usemap="#Map" border="0"></td>
      </tr>
<tr>
       	   <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1716/images/top_03.png" alt="Catálogo de Cursos QiSat" border="0" width="681" height="27" ></td>
      </tr>
         <tr>
       	   <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/top_04.png" alt="Catálogo de Cursos QiSat" border="0" width="681" height="110" ></td>
         </tr>
         <tr>
         <td>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="28" align="center" style="color:#66430d; font-size:18px; font-family:Tahoma, Arial; font-weight:bold;">ÁREA ESTRUTURAL</td>
  </tr>
  <tr>
    <td width="700" height="12" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/linha.gif"></td>
  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1716/images/estrutural.gif" alt="Cursos via Internet" width="680" height="27"></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#373737; ">
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><strong>CURSO DE <em>SOFTWARE</em></strong></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR</td>
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="font-size:11px;">DEMONSTRATIVO</span></td>
  </tr>
<tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=225" target="_blank">Curso Software Eberick Pré-moldado a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(225, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=225" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
    <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=179" target="_blank">Curso Software Eberick V10 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(179, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=179" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=121" target="_blank">Curso Software Eberick V9 a distância </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(121, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=121" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=112" target="_blank">Curso Software Eberick V8 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(112, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=112" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
<tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr></table></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><strong>CURSO TEÓRICO</strong></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR</td>
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="font-size:11px;">DEMONSTRATIVO</span></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px">
    <a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=239" target="_blank">Curso Concreto Armado – Requisitos para Desenvolvimento de Projetos de Edificações</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(239, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=139" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px">
    <a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=181" target="_blank">Curso Fundações - Engenharia Geotécnica via internet</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(181, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=181" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px">
    <a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=140" target="_blank">Curso NBR 6118:2014 - Concreto Armado: Parte 1 </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(140, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=140" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px">
    <a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=141" target="_blank">Curso NBR 6118:2014 - Concreto Armado: Parte 2 </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(141, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=141" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px">
    <a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=142" target="_blank">Curso NBR 6118:2014 - Concreto Armado: Parte 3 </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(142, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=142" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px">
    <a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=107" target="_blank" class="style2">Curso Alvenaria Estrutural para Arquitetos</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(107, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=107" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=109" target="_blank" class="style2">Curso Alvenaria Estrutural para Construtoras </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(109, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=109" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=61" target="_blank">Curso Alvenaria Estrutural para Projetistas</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(61, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=61" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr><tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=34" target="_blank">Curso Concreto Pré-moldado - Fundamentos do Sistema Construtivo</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(34, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=34" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=27" target="_blank">Curso Conceitos de Estabilidade Global para Projeto de Edifícios</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(27, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=27" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
<tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=30" target="_blank"> Curso Soluções de Contenção: Taludes, Muros de Arrimo e Escoramentos</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(30, "valortabela", "QiSat");?></td>
     <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=30" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
  <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=31" target="_blank">Curso Norma Regulamentadora 18 Ilustrada</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(31, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=31" target="_blank" style="color:#23598c;">VER AULA</a></td>
                </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=10" target="_blank">Palestra: Durabilidade das Estruturas de Concreto</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(10, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=10" target="_blank" style="color:#23598c;">VER AULA</a></td>
  </tr>
<tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr></table>
  <table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#373737; ">
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><strong>SÉRIE DE CAPÍTULOS - TEÓRICO</strong></td>
    <td colspan="1" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR POR CAPÍTULO</td>
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="font-size:11px;">DEMONSTRATIVO</span></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank"><b>Série Concreto Armado - Requisitos para Desenvolvimento de Projetos de Edificações via internet</b></a></td>
    <td colspan="1" align="center" bgcolor="#ecedee"></b>CONSULTAR</td>
     <td width="100" align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank" style="color:#23598c;">CAPÍTULOS</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank"><b>Série Fundações - Engenharia Geotécnica via internet</b></a></td>
    <td colspan="1" align="center" bgcolor="#ecedee"></b>CONSULTAR</td>
     <td width="100" align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank" style="color:#23598c;">CAPÍTULOS</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
  
    <td width="90" align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
    <td width="100" align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr></table>
<table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
<tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><strong>PACOTES DE CURSOS</strong></td>
    <td colspan="1" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR</td>
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="font-size:11px;">DEMONSTRATIVO</span></td>
  </tr>  
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px; color:#373737"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=210" target="_blank" ><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos Projeto Estrutural em Concreto - Teoria e Prática via internet</strong></span></a></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(210, "valortabela", "QiSat");?></td>
    
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8" >&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=210" target="_blank" style="color:#23598c;">VER PACOTE</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px; color:#373737"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=211" target="_blank" ><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos Projeto Estrutural em Concreto - Teoria via internet</strong></span></a></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(211, "valortabela", "QiSat");?></td>
    
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8" >&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=211" target="_blank" style="color:#23598c;">VER PACOTE</a></span></td>
  </tr>
  <TR style="COLOR: #555555; FONT-SIZE: 12px">
          <TD style="PADDING-LEFT: 15px" bgColor=#e1e2e3 height=27><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=208"  target=_blank><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos AltoQi - Premium via internet
</strong></SPAN></A></TD>
          <TD width="90" align="center" bgColor=#e1e2e3 style="FONT-SIZE: 11px">R$ <?php echo retornarValor(208, "valortabela", "QiSat");?></TD>
          
          <TD bgColor=#e1e2e3 align="center"><SPAN style="COLOR: #555555; FONT-SIZE: 9px"><IMG alt="" src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=208" target=_blank>VER PACOTE</A></SPAN></TD></TR>
<tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px; color:#373737"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=173" target="_blank" ><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos QiBuilder - Full via internet</strong></span></a></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(173, "valortabela", "QiSat");?></td>
    
    <td width="100" bgcolor="#e1e2e3" align="center"><span style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/seta.png" width="8" height="8" >&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=173" target="_blank" style="color:#23598c;">VER PACOTE</a></span></td>
  </tr>          
<tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
  
    <td width="90" align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="27" colspan="4" align="center" bgcolor="#e1e2e3"><strong>PROGRAMAÇÃO COMPLETA</strong></td>
  </tr>
  <tr>
    <td height="16"  colspan="4" align="center" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br" target="_blank">Clique aqui para conhecer os cursos de outras áreas.</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr></table></td>
  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1716/images/webconferencia.gif" alt="Webconferências Online" width="680" height="27"></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:11px; color:#555555;">
    <td height="20" colspan="6" align="center" bgcolor="#e1e2e3" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=8" target="_blank">Conheça as Webconferências disponíveis no Canal QiSat.</a></td>
    </tr>
    <tr>
  	<td colspan="6">
    	<table width="680" border="0" cellpadding="0" cellspacing="0" align="center">
        	<tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
            <tr style="font-size:11px; color:#222222;">
           	  <td height="48" width="96"  ><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1716/images/back_01.gif"></td>
    			<td width="518" height="48" align="center" bgcolor="#E2EBD8" ><span class="style3"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=10" target="_blank">Confira a Programação dos Cursos Presenciais de <em>Software</em> AltoQi</a></span></td>
                <td width="96" height="48" align="center" bgcolor="#E2EBD8"><span class="style8"></span></td>
            </tr>
        </table></tr>
        <tr>
                  <td width="200" height="80" style="padding-left:15px; font-weight:bold; color:#666666; text-transform:uppercase;">Para maiores informações</td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1716/images/linha.jpg" width="1" height="52" /></td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1716/images/contato.png" width="14" height="52" /></td>
    <td align="left" style="line-height:140%; padding-left:15px; color:#666666">(48) 3332-5000<br />
    <span style="color:#666666">central@qisat.com.br</span><br />
      <a href="http://www.qisat.com.br/" target="_blank" style="color:#666666">www.qisat.com.br</a></td>
    <td align="center"><a href="http://www.qisat.com.br" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2017/catalogo_geral/images/logoqisat.png" border="0"></a></td>
    <td align="center"><a href="http://www.altoqi.com.br/" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1716/images/logoaltoqi.png" width="119" height="38" border="0"></a></td>
        </tr>
  <tr>
  	<td colspan="6"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1716/images/linha_rodape.gif" border="0" ></td>
  </tr>
   <tr>
 	<td colspan="6"><div style="font-family:Tahoma, Verdana, Arial; font-size:10px; color:#666666; padding:10px; margin:auto; text-align:center">© 2003 - 2016 - Todos os Direitos Reservados à MN Tecnologia e Treinamento Ltda. | Para mais informações entre em <a href="mailto:central@qisat.com.br"><span style="color:#02416d;">contato.</span></a></div></td>
 </tr>
  <tr>
    <td height="6" colspan="6" align="center" bgcolor="#FFFFFF" style="padding-left:15px; color:#23598c">&nbsp;</td>
</table></td>
  </tr>
</table></td>
</tr>
</table>
</div>
<map name="topo">
<area shape="rect" coords="517,9,542,35" href="http://www.facebook.com/qisat" target="_blank" alt="QiSat no Facebook">
<area shape="rect" coords="602,8,626,34" href="http://www.youtube.com/qisat/" target="_blank" alt="Canal QiSat no YouTube">
<area shape="rect" coords="628,8,654,34" href="http://www.qisat.com.br/contato/contato.php" target="_blank" alt="MSN QiSat">
<area shape="rect" coords="544,9,570,35" href="http://br.linkedin.com/in/qisat" target="_blank" alt="QiSat no LinkedIn">
<area shape="rect" coords="572,9,597,35" href="http://twitter.com/qisat/" target="_blank" alt="Twitter QiSat"></map>
<map name="rodape">
<area shape="rect" coords="281,19,417,40" href="/" target="_blank" alt="QiSat">
<area shape="rect" coords="546,14,617,68" href="http://www.altoqi.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1716&lk=/" target="_blank" alt="AltoQi"></map>
<map name="Map">
<area shape="rect" coords="28,23,162,72" href="http://www.qisat.com.br" target="_blank" alt="[Qisat] - O Canala de e-learning da Engenharia">

        	    <area shape="rect" coords="572,8,597,34" href="http://www.facebook.com/qisat" target="_blank" alt="QiSat no Facebook">
        	    <area shape="rect" coords="598,7,624,33" href="http://br.linkedin.com/in/qisat" target="_blank" alt="QiSat no LinkedIn">
        	    <area shape="rect" coords="626,7,651,33" href="http://twitter.com/qisat/" target="_blank" alt="Twitter QiSat">
        	    <area shape="rect" coords="653,8,677,34" href="http://www.youtube.com/qisat/" target="_blank" alt="Canal QiSat no YouTube">
        	    <area shape="rect" coords="679,8,705,34" href="http://www.qisat.com.br/contato/contato.php" target="_blank" alt="MSN QiSat">
      	    </map>
</map>
</body>
</html>