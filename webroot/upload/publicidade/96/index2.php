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
<title>[QiSat] Catálogo de Cursos na Área Elétrica</title>
<style type="text/css">
<!--
a:link {
	color: #373737;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #373737;
}
a:hover {
	text-decoration: underline;
	color: #111111;
}
a:active {
	text-decoration: none;
	color: #373737;
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
<BODY>
<DIV style="PADDING-BOTTOM: 10px; MARGIN: auto; COLOR: #333333; FONT-SIZE: 10px" 
align="center">Caso não esteja visualizando o e-mail abaixo, <A style="COLOR: #015da2" href="http://www.qisat.com.br/ecommerce/catalogo/arquivos/10/index2.php?niveis=1" target=_blank>CLIQUE AQUI</A>.</DIV>
<div align="center" style="margin:auto">
	<table width="984" border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1720/images/topo.png"  usemap="#topo" border="0"></td>
      </tr>
<tr>
	<td>
<TABLE border=0 cellSpacing=0 cellPadding=0 width=700 bgColor="#ffffff" align="center">
      <tr>
       	   <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1720/images/top_03.png" alt="Catálogo de Cursos QiSat" border="0" ></td>
         </tr>
         <tr>
       	   <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1720/images/top_04.png" alt="Catálogo de Cursos QiSat" border="0" ></td>
         </tr>
  	<TR>
  <TD height=28 align="center" style="FONT-FAMILY: Tahoma, Arial; COLOR: #d0af14; FONT-SIZE: 18px; FONT-WEIGHT: bold; padding-top:10px; padding-bottom:0px;" >ÁREA ELÉTRICA</TD></TR>
  <TR>
    <TD height=12 width=700 align="center"><IMG src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1720/images/linha.gif"></TD></TR>
  <TR>
    <TD align="center"><IMG alt="Cursos via Internet" src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1720/images/eletrico.gif" width=680 height=27></TD></TR>
  <TR>
    <TD>
      <TABLE border=0 cellSpacing=1 cellPadding=0 width=680 bgColor="#ffffff" align="center" style="FONT-FAMILY: Tahoma, Arial, Helvetica, sans-serif; FONT-SIZE: 11px">
        <TR style="COLOR: #555555; FONT-SIZE: 12px">
          <TD bgColor= "#e1e2e3" height=27 style="PADDING-LEFT: 15px"><STRONG>CURSO DE <EM>SOFTWARE</EM></STRONG></TD>
          <TD  width=90 align="center" style="FONT-SIZE: 11px" bgColor= "#e1e2e3">VALOR</TD>
          <TD bgColor="#e1e2e3" width=100 align="center"><SPAN style="FONT-SIZE: 11px">DEMONSTRATIVO</SPAN></TD></TR>
        <TR>
                <TD style="PADDING-LEFT: 15px" bgColor="#ecedee" height=16><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=172" target=_blank>Curso Software QiElétrico a distância</A></TD>
          <TD bgColor="#ecedee" align="center">R$ <?php echo retornarValor(172, "valortabela", "QiSat");?></TD>
          <TD bgColor="#ecedee" align="center" style="COLOR: #555555; FONT-SIZE: 9px"><IMG src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=172" target=_blank>VER AULA</A></TD></TR>
          <TD style="PADDING-LEFT: 15px" bgColor="#ecedee" height=16><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=14" target=_blank>Curso Software Lumine V4 a distância</A></TD>
          <TD bgColor="#ecedee" align="center">R$ <?php echo retornarValor(14, "valortabela", "QiSat");?></TD>
          <TD bgColor="#ecedee" align="center" style="COLOR: #555555; FONT-SIZE: 9px"><IMG src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=14" target=_blank>VER AULA</A></TD></TR>
        <TR>
          <TD style="PADDING-LEFT: 15px" bgColor="#ecedee" height=16><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=28" target=_blank>Curso Software Cabeamento Estruturado - Lumine V4 a distância</A></TD>
          <TD bgColor="#ecedee" align="center">R$ <?php echo retornarValor(28, "valortabela", "QiSat");?></TD>
          <TD style="COLOR: #555555; FONT-SIZE: 9px" bgColor="#ecedee" 
          align="center"><IMG src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=28" target=_blank>VER AULA</A></TD></TR>
        <TR>
          <TD bgColor="#ffffff" height=6 style="PADDING-LEFT: 15px" ></TD>
          <TD bgColor="#ffffff" align="center" style="PADDING-LEFT: 13px"></TD>
          <TD bgColor="#ffffff" align="center" style="COLOR: #555555; FONT-SIZE: 9px"></TD></TR></TABLE></TD></TR>
  <TR>
    <TD>
      <TABLE style="FONT-FAMILY: Tahoma, Arial, Helvetica, sans-serif; FONT-SIZE: 11px" border=0 cellSpacing=1 cellPadding=0 width=680 bgColor="#ffffff" align="center">
        <TR style="FONT-SIZE: 12px">
          <TD style="PADDING-LEFT: 15px" bgColor=#e1e2e3 height=27><STRONG>CURSO TEÓRICO</STRONG></TD>
          <TD style="FONT-SIZE: 11px" bgColor=#e1e2e3 width=90 align="center">VALOR</TD>
          <TD bgColor=#e1e2e3 width=100 align="center"><SPAN style="FONT-SIZE: 11px">DEMONSTRATIVO</SPAN></TD></TR>
          <TR>
          <TD style="PADDING-LEFT: 15px;" bgColor="#ecedee" height=16><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=143" target=_blank>Curso Norma Regulamentadora 10</A></TD>
          <TD bgColor="#ecedee" align="center">R$ <?php echo retornarValor(143, "valortabela", "QiSat");?></TD>
          <TD style="COLOR: #555555; FONT-SIZE: 9px" bgColor="#ecedee" align="center"><IMG src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=143" target=_blank>VER AULA</A></TD></TR>                  
        <TR>
          <TD style="PADDING-LEFT: 15px;" bgColor="#ecedee" height=16><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=29" target=_blank>Curso Sistemas de Cabeamento Estruturado</A></TD>
          <TD bgColor="#ecedee" align="center">R$ <?php echo retornarValor(29, "valortabela", "QiSat");?></TD>
          <TD style="COLOR: #555555; FONT-SIZE: 9px" bgColor="#ecedee" align="center"><IMG src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=29" target=_blank>VER AULA</A></TD></TR>
        <TR>
          <TD style="PADDING-LEFT: 15px" bgColor="#ecedee" height=16><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=24" target=_blank>Curso Instalações Elétricas Residenciais </A></TD>
          <TD bgColor="#ecedee" align="center">R$ <?php echo retornarValor(24, "valortabela", "QiSat");?></TD>
          <TD style="COLOR: #555555; FONT-SIZE: 9px" bgColor="#ecedee" align="center"><IMG src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=24" target=_blank>VER AULA</A></TD></TR>
        <TR>
          <TD style="PADDING-LEFT: 15px" bgColor="#ecedee" height=16><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=37" target=_blank>Curso Instalações Elétricas Prediais, Telefonia e TV</A></TD>
          <TD bgColor="#ecedee" align="center">R$ <?php echo retornarValor(37, "valortabela", "QiSat");?></TD>
          <TD style="COLOR: #555555; FONT-SIZE: 9px" bgColor="#ecedee" align="center"><IMG src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=37" target=_blank>VER AULA</A></TD></TR>
        <TR>
          <TD style="PADDING-LEFT: 15px" bgColor="#ffffff" height=6></TD>
          <TD style="PADDING-LEFT: 13px" bgColor="#ffffff" align="center"></TD>
          <TD style="FONT-SIZE: 9px" bgColor="#ffffff" align="center"></TD></TR></TABLE>
      <TABLE style="FONT-FAMILY: Tahoma, Arial, Helvetica, sans-serif; FONT-SIZE: 11px" border=0 cellSpacing=1 cellPadding=0 width=680 bgColor="#ffffff" align="center">
        <TR style="COLOR: #555555; FONT-SIZE: 12px">
           <TD style="PADDING-LEFT: 15px; background-color:#e1e2e3; color:#555555;" height=27><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=219" target=_blank><SPAN style=" FONT-SIZE: 11px"><STRONG>Pacote de Cursos Projeto Elétrico Predial - Teoria e Prática via internet</STRONG></SPAN></A></TD>
          <TD style="FONT-SIZE: 11px" bgColor=#e1e2e3 width=90 align="center">R$ <?php echo retornarValor(219, "valortabela", "QiSat");?></TD>
          
          <TD bgColor=#e1e2e3 width=100 align="center"><SPAN style="COLOR: #555555; FONT-SIZE: 9px"><IMG alt="" src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=219" target=_blank>VER PACOTE</A></SPAN></TD></TR>
          <TR style="COLOR: #555555; FONT-SIZE: 12px">
           <TD style="PADDING-LEFT: 15px; background-color:#e1e2e3; color:#555555;" height=27><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=220" target=_blank><SPAN style=" FONT-SIZE: 11px"><STRONG>Pacote de Cursos Projeto Elétrico Predial - Teoria via internet</STRONG></SPAN></A></TD>
          <TD style="FONT-SIZE: 11px" bgColor=#e1e2e3 width=90 align="center">R$ <?php echo retornarValor(220, "valortabela", "QiSat");?></TD>
          
          <TD bgColor=#e1e2e3 width=100 align="center"><SPAN style="COLOR: #555555; FONT-SIZE: 9px"><IMG alt="" src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=220" target=_blank>VER PACOTE</A></SPAN></TD></TR>
        <TR style="COLOR: #555555; FONT-SIZE: 12px">
          <TD style="PADDING-LEFT: 15px" bgColor=#e1e2e3 height=27><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=207"  target=_blank><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos Projeto Cabeamento Estruturado Predial - <br>Teoria e Prática via internet</strong></SPAN></A></TD>
          <TD style="FONT-SIZE: 11px" bgColor=#e1e2e3 align="center">R$ <?php echo retornarValor(207, "valortabela", "QiSat");?></TD>
          
          <TD bgColor=#e1e2e3 align="center"><SPAN style="COLOR: #555555; FONT-SIZE: 9px"><IMG alt="" src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=207" target=_blank>VER PACOTE</A></SPAN></TD></TR>
          <TR style="COLOR: #555555; FONT-SIZE: 12px">
          <TD style="PADDING-LEFT: 15px" bgColor=#e1e2e3 height=27><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=208"  target=_blank><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos AltoQi - Premium via internet</strong></SPAN></A></TD>
          <TD style="FONT-SIZE: 11px" bgColor=#e1e2e3 align="center">R$ <?php echo retornarValor(208, "valortabela", "QiSat");?></TD>
          
          <TD bgColor=#e1e2e3 align="center"><SPAN style="COLOR: #555555; FONT-SIZE: 9px"><IMG alt="" src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=208" target=_blank>VER PACOTE</A></SPAN></TD></TR>          
          <TR style="COLOR: #555555; FONT-SIZE: 12px">
          <TD style="PADDING-LEFT: 15px" bgColor=#e1e2e3 height=27><A href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=173"  target=_blank><SPAN style="FONT-SIZE: 11px"><strong>Pacote de Cursos QiBuilder - Full via internet</strong></SPAN></A></TD>
          <TD style="FONT-SIZE: 11px" bgColor=#e1e2e3 align="center">R$ <?php echo retornarValor(173, "valortabela", "QiSat");?></TD>
          
          <TD bgColor=#e1e2e3 align="center"><SPAN style="COLOR: #555555; FONT-SIZE: 9px"><IMG alt="" src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1720/images/seta.png" width=8 height=8>&nbsp;<A style="COLOR: #23598c" href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=173" target=_blank>VER PACOTE</A></SPAN></TD></TR>
            <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
            <tr style="font-size:12px; color:#555555;">
    <td height="27" colspan="4" align="center" bgcolor="#e1e2e3"><strong>PROGRAMAÇÃO COMPLETA</strong></td>
  </tr>
  <tr>
    <td height="16"  colspan="4" align="center" bgcolor="#ecedee"><a href="http://www.qisat.com.br/ecommerce/webstore.php" target="_blank">Clique aqui para conhecer os cursos de outras áreas.</a></td>
  </tr>
        <TR>
          <TD style="PADDING-LEFT: 15px" bgColor="#ffffff" height=6></TD>
          <TD style="PADDING-LEFT: 13px" bgColor="#ffffff" align="center"></TD>
          
          
          </TR></TABLE></TD></TR>
<tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1720/images/webconferencia.gif" alt="Webconferências Online" width="680" height="27"></td>
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
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
            <tr style="font-size:11px; color:#222222;">
           	  <td width="96" height="48" bgcolor="#E2EBD8"  >&nbsp;</td>
    			<td width="518" height="48" align="center" bgcolor="#E2EBD8" ><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=10" target="_blank">Confira a Programação dos Cursos Presenciais de <em>Software</em> AltoQi</a></td>
                <td width="96" height="48" align="center" bgcolor="#E2EBD8"></td>
            </tr>
        </table>  </tr>
        <tr>
          <td width="200" height="80" style="padding-left:15px; font-weight:bold; color:#666666; text-transform:uppercase;">Para maiores informações</td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1716/images/linha.jpg" width="1" height="52" /></td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1716/images/contato.png" width="14" height="52" /></td>
    <td align="left" style="line-height:140%; padding-left:15px; color:#666666">(48) 3332-5000<br />
    <span style="color:#666666">central@qisat.com.br</span><br />
      <a href="http://www.qisat.com.br/" target="_blank" style="color:#666666">www.qisat.com.br</a></td>
    <td align="center"><a href="http://www.qisat.com.br" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2017/03_15/images/logoqisat.png" width="114" height="38" border="0"></a></td>
    <td align="center"><a href="http://www.altoqi.com.br/" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1716/images/logoaltoqi.png" width="119" height="38" border="0"></a></td>
        </tr>
  <tr>
  	<td colspan="6"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1720/images/linha_rodape.gif" alt="Catálogo de Cursos QiSat" border="0" ></td>
  </tr>
   <tr>
 	<td colspan="6"><div style="font-family:Tahoma, Verdana, Arial; font-size:10px; color:#666666; padding:10px; margin:auto; text-align:center">© 2003 - 2017 - Todos os Direitos Reservados à MN Tecnologia e Treinamento Ltda. | Para mais informações entre em <a href="mailto:central@qisat.com.br"><span style="color:#02416d;">contato.</span></a></div></td>
 </tr>
  <tr>
    <td height="6" colspan="6" align="center" bgcolor="#FFFFFF" style="padding-left:15px; color:#23598c">&nbsp;</td>
</table></td>
    </tr></TABLE></td>
      </tr>
      </TABLE>
</div>
<map name="topo">
  <area shape="rect" coords="823,9,849,35" href="http://www.facebook.com/qisat" target="_blank" alt="QiSat no facebook">
  <area shape="rect" coords="851,9,877,35" href="http://br.linkedin.com/in/qisat" target="_blank" alt="QiSat no LinkedIn">
  <area shape="rect" coords="880,9,904,34" href="http://twitter.com/qisat/" target="_blank" alt="Twitter QiSat">
  <area shape="rect" coords="908,9,934,33" href="http://www.youtube.com/qisat/" target="_blank" alt="Canal QiSat no YouTube">
  <area shape="rect" coords="938,9,962,34" href="http://www.qisat.com.br/contato/contato.php" target="_blank" alt="MSN QiSat">
  <area shape="rect" coords="146,15,423,91" href="http://www.qisat.com.br" target="_blank" alt="[Qisat] - O Canal de e-learning da Engenharia"></map>
</BODY>
</html>