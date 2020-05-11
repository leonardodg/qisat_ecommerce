<?php
$diretorio = '';
for ($i = 0; $i < $_GET['niveis']; $i++) {
    $diretorio .= '../';
}
require_once($diretorio.'../../../config.php');
global $CFG;
require_once($CFG->dirroot.'/ecommerce/catalogo/lib.php');
function retornaValorConvenio($id, $tipo){
	global $CFG;
	$produtoDao = new ProdutoDao();
	$produto = $produtoDao->buscaPeloId($id);
	switch($tipo){
		case 'professor':
			return number_format(round($produto->getPreco()-($produto->getPreco()*$CFG->convenio_desconto_professor/100)), 2, ',', '.');
			break;
		case 'estudante':
			return number_format(round($produto->getPreco()-($produto->getPreco()*$CFG->convenio_desconto_aluno/100)), 2, ',', '.');
			break;
		case 'associado':
			return number_format(round($produto->getPreco()-($produto->getPreco()*$CFG->convenio_desconto_associado/100)), 2, ',', '.');
			break;
		default:
			return number_format(round($produto->getPreco()), 2, ',', '.');
			break;
	};
}
if(isset($_GET['convenio'])){
	$nome_da_instituicao = retornarValor($_GET['convenio'], "instituicaoconvenio");
} else {
	$nome_da_instituicao = 'Nome da institui��o';
}
/* <?php echo retornaValorConvenio(121, "valortabela");?> */
/************************************************************************************************
 * Parametros da fun��o:
 * 1� - Id do produto - sem aspas
 * 2� - Tipo do valor a ser retornado(valortabela, professor, estudante ou associado) - com aspas
************************************************************************************************/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>[QiSat] Conv�nio de Cursos para Engenharia</title>
<style type="text/css">
a:link {
	color: #505E6B;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #505E6B;
}
a:hover {
	text-decoration: underline;
	color: #015594;
}
a:active {
	text-decoration: none;
	color: #505E6B;
}
body {
	background-color: #eeeeee;;
	font-family:Tahoma,Arial;
	color:#333333;
}
.style5 {
	color: #5BBF22;
	font-weight: bold;
	}
.style11 {color: #505E6B}
</style>
</head>

<body>
<div align="center" style="font-size:10px; color:#333333; margin-bottom:10px;">Caso n�o esteja visualizando o e-mail abaixo, <a href="http://www.qisat.com.br/ecommerce/catalogo/arquivos/1/index.php?niveis=1" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</div>
<div align="center" style="margin:auto; vertical-align:bottom;padding:auto";>
<table width="984" border="0" align="center" cellpadding="0" cellspacing="0"  >
	<tr>
    	<td><img src="http://public.qisat.com.br/campanhas/e-convites/2016/PREDUC/preduc.png" alt="Cat�logo de Cursos QiSat" border="0" usemap="#topo"></td>
    </tr>
</table>
<table width="740" style="border:solid 1px #dedede; color:#666666;">
	<tr >
    	<td>
<table width="740" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
 <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
 
  <tr align="center" style=" color: #333333;">
    <td valign="top"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:20px; font-weight:bold; color:#333333; margin-bottom:0px; margin-right:0px; padding-top:10px;"><?php echo $nome_da_instituicao?></div></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td height="30" align="center" style="color:#333333; font-size:14px; font-family:Tahoma, Arial;">Atualiza��o t�cnica em:</td>
  </tr>
  <tr>
  	<td>
    	<table width="700" cellpadding="0" cellspacing="0" border="0" align= "center">
        	<tr>
                <td width="140"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=10" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/ens_presencial.gif" alt="Projeto Estrutural em Concreto Armado" border="0"></a></td>
              <td width="140"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&lk=http://www.qisat.com.br/ecommerce/produtos/category.php?id=3" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/proj_estrutural.gif" alt="Projeto Estrutural em Concreto Armado" border="0"></a></td>
                <td width="173"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&lk=http://www.qisat.com.br/ecommerce/produtos/category.php?id=4" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/proj_eletrico.gif" alt="Projeto de Instala��es El�tricas Prediais" border="0"></a></td>
                <td width="170"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&lk=http://www.qisat.com.br/ecommerce/produtos/category.php?id=6" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/proj_hidraulico.gif" alt="Projeto de Instala��es Hidr�ulicas e Sanit�rias Prediais" border="0"></a></td>
                <td width="180"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&lk=http://www.qisat.com.br/ecommerce/produtos/category.php?id=5" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/proj_cad.gif" alt="Cad para Engenharia" border="0"></a></td>
           </tr>
           <tr style="font-size:12px; color:#222222;">
            	<td  height="35"  align="left" colspan="2" style="padding-left:0px; text-align:left" ><span class="style5">Cursos presenciais</span></td>
            </tr>
        </table></td>
  </tr>
  <tr>
  	<td>
    	<table align = "center" width="700" height="48" cellpadding="0" cellspacing="0" border="0">
        	<tr style="font-size:11px; color:#222222;">
            	
            	<td width="140"  background="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/corp_01.gif" bgcolor="#E3ECD9"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/qiTec_novo_01.png"></td>
              <td width="380" align="center" background="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/corp_02.gif" bgcolor="#E3EBD9"><span class="style3"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=convenios2015_qisat&lk=http://www.qisat.com.br/ecommerce/produtos/category.php?id=10" target="_blank"><b>Confira a Programa��o dos Cursos Presenciais de <em>Software</em> AltoQi</b>
                <br>
                </a> <span class="style11">O desconto para cursos presenciais neste conv�nio � de 15%</span></span></td>
              <td width="180" align="center" background="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/corp_03.gif" bgcolor="#E2EBD9"><span class="style8"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=convenios2015_qisat&lk=http://www.qisat.com.br/ecommerce/produtos/category.php?id=10" target="_blank" >CLIQUE AQUI</a></span></td>
          </tr>
        </table></td>
  </tr>
  <tr>
    <td height="60" align="center" style="font-family:Tahoma, Arial; font-size:12px; color:#333333">Confira abaixo o valor dos cursos com descontos especiais para <strong>professor, estudante e associado</strong> atrav�s do<br> 
      conv�nio realizado.</td>
  </tr>
    <tr style="font-size:12px; color:#015596;">
            	<td  height="35"  align="left" colspan= "2" style="padding-left:20px; text-align:left" ><span class="style10"><strong>Cursos via internet</strong></span></td>
  </tr>
  <tr>
    <td align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/area_estrutural.gif" alt="�rea Estrutural" width="700" height="27"></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">CURSO DE <em>SOFTWARE</em></td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=179' target='_blank'>Curso Software Eberick V10 a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(179, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(179, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(179, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(179, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=179' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=121' target='_blank'>Curso Software Eberick V9 a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(121, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(121, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(121, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(121, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=121' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=112' target='_blank'>Curso Software Eberick V8 a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(112, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(112, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(112, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(112, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=112' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">CURSO TE�RICO</td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=181' target='_blank'>Curso Funda��es - Engenharia Geot�cnica via internet</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(181, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(181, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(181, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(181, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=181' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=140' target='_blank'>Curso NBR 6118:2014 - Concreto Armado: Parte 1</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(140, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(140, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(140, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(140, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=140' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=141' target='_blank'>Curso NBR 6118:2014 - Concreto Armado: Parte 2</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(141, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(141, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(141, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(141, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=141' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=142' target='_blank'>Curso NBR 6118:2014 - Concreto Armado: Parte 3</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(142, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(142, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(142, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(142, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=142' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=107' target='_blank'>Curso  Alvenaria Estrutural Para Arquitetos</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(107, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(107, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(107, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(107, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=107' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=109' target='_blank'>Curso  Alvenaria Estrutural Para Construtoras</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(109, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(109, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(109, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(109, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=109' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=61' target='_blank'>Curso  Alvenaria Estrutural Para Projetistas</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(61, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(61, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(61, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(61, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=61' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=34' target='_blank'>Curso Concreto Pr�-Moldado - Fundamentos do Sistema Construtivo</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(34, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(34, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(34, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(34, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=34' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=30' target='_blank'>Curso Solu��es de Conten��o: Taludes, Muros de Arrimo e Escoramentos</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(30, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(30, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(30, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(30, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=30' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=31' target='_blank'>Curso Norma Regulamentadora 18 Ilustrada</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(31, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(31, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(31, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(31, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=31' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=27' target='_blank'>Curso Conceitos de Estabilidade Global para Projeto de Edif�cios</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(27, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(27, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(27, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(27, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=27' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='23' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=10' target='_blank'>Palestra Durabilidade das Estruturas de Concreto</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(10, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(10, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(10, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(10, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=10' target='_blank'>VER AULA</a></td>	  </tr>

  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">S�RIE DE CAP�TULOS - TE�RICO</td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=153' target='_blank'>S�rie Funda��es - Engenharia Geot�cnica via internet</a></td>    <td bgcolor='#ecedee'  style="text-align:center">CONSULTAR</td>    <td bgcolor='#ecedee' style="text-align:center">CONSULTAR</td>    <td bgcolor='#ecedee' style='text-align:center'>CONSULTAR</td>    <td bgcolor='#ecedee' style='text-align:center'>CONSULTAR</td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=153' target='_blank'>CAP�TULOS</a></td>	  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/area_eletrica.gif" alt="�rea El�trica" width="700" height="27"></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">CURSO DE <em>SOFTWARE</em></td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=14' target='_blank'>Curso Software Lumine V4 a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(14, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(14, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(14, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(14, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=14' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=28' target='_blank'>Curso Software Cabeamento Estruturado - Lumine V4 a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(28, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(28, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(28, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(28, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=28' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">CURSO TE�RICO</td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=143' target='_blank'>Curso Norma Regulamentadora 10</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(143, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(143, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(143, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(143, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=143' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=29' target='_blank'>Curso Sistemas de Cabeamento Estruturado</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(29, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(29, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(29, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(29, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=29' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=24' target='_blank'>Curso Instala��es El�tricas Residenciais</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(24, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(24, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(24, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(24, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=24' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=37' target='_blank'>Curso Instala��es El�tricas Prediais, Telefonia e TV</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(37, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(37, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(37, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(37, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=37' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/area_hidraulica.gif" alt="�rea Hidr�ulica" width="700" height="27"></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">CURSO DE <em>SOFTWARE</em></td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=131' target='_blank'>Curso Software QiHidrossanit�rio a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(131, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(131, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(131, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(131, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=131' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=136' target='_blank'>Curso Software QiInc�ndio a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(136, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(136, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(136, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(136, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=136' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=12' target='_blank'>Curso Software Hydros V4 a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(12, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(12, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(12, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(12, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=12' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=17' target='_blank'>Curso Software Inc�ndio - Hydros V4 a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(17, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(17, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(17, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(17, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=17' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">CURSO TE�RICO</td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=4' target='_blank'>Curso Instala��es Prediais de �guas Pluviais</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(4, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(4, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(4, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(4, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=4' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=5' target='_blank'>Curso Instala��es Prediais de Esgoto Sanit�rio</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(5, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(5, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(5, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(5, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=5' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=7' target='_blank'>Curso Instala��es Prediais de �gua Fria - Fundamentos</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(7, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(7, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(7, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(7, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=7' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=8' target='_blank'>Curso: Instala��es Prediais de �gua Fria-Dimensionamento</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(8, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(8, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(8, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(8, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=8' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=11' target='_blank'>Curso: Instala��es Prediais de �gua Quente-Gera��o</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(11, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(11, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(11, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(11, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=11' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=25' target='_blank'>Curso: Instala��es Prediais: �gua Quente-Distribui��o</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(25, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(25, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(25, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(25, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=25' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">S�RIE DE CAP�TULOS - TE�RICO</td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=213' target='_blank'>S�rie Combate a Inc�ndio � Hidrantes e Mangotinhos</a></td>    <td bgcolor='#ecedee'  style="text-align:center">CONSULTAR</td>    <td bgcolor='#ecedee' style="text-align:center">CONSULTAR</td>    <td bgcolor='#ecedee' style='text-align:center'>CONSULTAR</td>    <td bgcolor='#ecedee' style='text-align:center'>CONSULTAR</td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=213' target='_blank'>CAP�TULOS</a></td>	  
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/area_cad.gif" alt="�rea Cad e Outros" width="700" height="27"></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">CURSO DE <em>SOFTWARE</em></td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=168' target='_blank'>Curso Software QiEditor de Armaduras a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(168, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(168, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(168, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(168, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=168' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=134' target='_blank'>Curso Software QiBuilder - CAD a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(134, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(134, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(134, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(134, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=134' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=135' target='_blank'>Curso Software QiBuilder - Gerenciador de Arquivos a dist�ncia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(135, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(135, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(135, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(135, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=135' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">CURSO T�CNICO</td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=21' target='_blank'>Curso: Ac�stica Arquitet�nica - Fundamentos e Conceitos</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(21, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(21, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(21, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(21, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=21' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=22' target='_blank'>Curso: Ac�stica Aplicada a Home Theater</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(22, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(22, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(22, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(22, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=22' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=23' target='_blank'>Curso: Gest�o de Projetos em Escrit�rios de Arquitetura e Constru��o Civil</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(23, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(23, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(23, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(23, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=23' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=3' target='_blank'>Curso: Marketing p/ Engenharia Arquitetura e Agronomia</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(3, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(3, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(3, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(3, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=3' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/pacotes.gif" alt="Pacote de Cursos" width="700" height="27"></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#505E6B; ">
  <tr style="font-size:12px; color:#015594;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px">PACOTES</td>
    <td width="78" bgcolor="#e1e2e3" align="center">VALOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">PROFESSOR</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ESTUDANTE</td>
    <td width="78" bgcolor="#e1e2e3" align="center">ASSOCIADO</td>
    <td width="74" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=210' target='_blank'>Pacote de Cursos Projeto Estrutural em Concreto - Teoria e Pr�tica via internet</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(210, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(210, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(210, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(210, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=210' target='_blank'>VER AULA</a></td>	  </tr><tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=211' target='_blank'>Pacote de Cursos Projeto Estrutural em Concreto - Teoria via internet</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(211, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(211, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(211, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(211, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=211' target='_blank'>VER AULA</a></td>     </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=219' target='_blank'>Pacote de Cursos Projeto El�trico Predial - Teoria e Pr�tica via internet</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(219, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(219, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(219, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(219, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=219' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=220' target='_blank'>Pacote de Cursos Projeto El�trico Predial - Teoria via internet</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(220, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(220, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(220, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(220, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=220' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=207' target='_blank'>Pacote de Cursos Projeto Cabeamento Estruturado Predial - <br>Teoria e Pr�tica via internet</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(127, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(207, "professor");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(207, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(207, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=207' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=204' target='_blank'>Pacote de Cursos Projeto Hidr�ulico Predial - Teoria e Pr�tica <br>via internet</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(204, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(204, "professor");?></td>    
  <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(204, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(204, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=204' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=203' target='_blank'>Pacote de Cursos Projeto Hidr�ulico Predial - Teoria via internet </a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(203, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(203, "professor");?></td>    
  <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(203, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(203, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=203' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=208' target='_blank'>Pacote de Cursos AltoQi - Premium via internet</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(208, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(208, "professor");?></td>    
  <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(208, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(208, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=208' target='_blank'>VER AULA</a></td>	  </tr>  
  <tr>    <td height='16' bgcolor='#ecedee' style='padding-left:15px'><a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=173' target='_blank'>Pacote de Cursos QiBuilder - Full via internet</a></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(173, "valortabela");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(173, "professor");?></td>    
  <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(173, "estudante");?></td>    <td bgcolor='#ecedee' style='padding-left:13px'>R$ <?php echo retornaValorConvenio(173, "associado");?></td>    <td align='center' bgcolor='#ecedee' style='color:#015594; font-size:9px;'><img src='http://public.qisat.com.br/campanhas/e-convites/2013/convenios/images/seta.png' width='8' height='8'>&nbsp;<a href='http://www.qisat.com.br/ecommerce/produtos/info.php?id=173' target='_blank'>VER AULA</a></td>	  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#015594; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
<tr>
  	<td colspan="6" align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/linha_rodape.gif"></td>
  </tr>
<tr>
    <td width="200" height="80" style="padding-left:15px; font-weight:bold; color:#666666; text-transform:uppercase;">Para maiores informa��es</td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/linha.jpg" width="1" height="52" /></td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/contato.png" width="14" height="52" /></td>
    <td align="left" style="line-height:140%; padding-left:15px; color:#666666">(48) 3332-5000<br />
    <span style="color:#666666">central@qisat.com.br</span><br />
      <a href="http://www.qisat.com.br/" target="_blank" style="color:#666666">www.qisat.com.br</a></td>
    <td align="center"><a href="http://www.qisat.com.br" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/logoqisat.png" width="91" height="66" border="0"></a></td>
    <td align="center"><a href="http://www.altoqi.com.br/" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/convenios/images/logoaltoqi.png" width="119" height="38" border="0"></a></td>
  </tr>  
</table></td>
  </tr>
</table>
</td>
</tr>
</table>

</div>
<div style="font-family:Tahoma, Verdana, Arial; font-size:10px; color:#666666; padding:10px; margin:auto; text-align:center">� 2003 - 2016 - Todos os Direitos Reservados � MN Tecnologia e Treinamento Ltda. | Para mais informa��es entre em <a href="mailto:central@qisat.com.br"><span style="color:#02416d;">contato.</span></a></div>
</div>

<map name="topo">
  <area shape="rect" coords="844,7,974,91" href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=convenios2013_qisat&lk=http://www.qisat.com.br" target="_blank" alt="Cat�logo de Cursos Qisat">
</map>

</body>

</html>