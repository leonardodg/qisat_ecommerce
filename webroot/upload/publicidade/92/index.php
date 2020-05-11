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
<title>[QiSat] Catálogo de Cursos para Engenharia</title>
<style type="text/css">
a:link {
	color: #333333;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #333333;
}
a:hover {
	text-decoration: underline;
	color: #111111;
}
a:active {	
	text-decoration: none;
	color: #333333;
}
body {
	background-color: #FFFFFF;
}
body,td,th {
	font-family: Tahoma, Arial, Helvetica;
	color: #333333;
}
</style>
</head>
<body>
<div align="center" style="font-size:10px; color:#333333; margin:auto; padding-bottom:10px; padding-top:5px;">Caso não esteja visualizando o e-mail abaixo, <a href="http://www.qisat.com.br/ecommerce/catalogo/arquivos/6/index.php?niveis=1" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</div>
<table width="710" border="0" align="center" cellpadding="0" cellspacing="0" >
 	<tr>
    	<td>
        	<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
                <tr>
                    <td width="700" ><a href="http://www.qisat.com.br" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2017/catalogo_geral/images/topo.png" border="0" usemap="#topo" alt="Catálogo de Cursos QiSat"></a></td>
              </tr>
           </table></td>
	<tr>
    	<td>
        	<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
        		<tr>
    				<td height="30" align="center" style="color:#333333; font-size:14px; font-family:Tahoma, Arial;">Atualização técnica em:</td>
  				</tr>
 				 <tr>
    				<td align="center">
    					<table width="100%" border="0" cellspacing="0" cellpadding="0">
  							<tr>
                                <td width="140"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=10" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/ens_presencial.gif" alt="Ensino Presencial" border="0"></a></td>
                                <td width="140"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=3" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/proj_estrutural.gif" alt="Projeto Estrutural em Concreto Armado" border="0"></a></td>
                                <td width="173"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=4" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/proj_eletrico.gif" alt="Projeto de Instala&ccedil;&otilde;es El&eacute;tricas Prediais" border="0"></a></td>
                                <td width="170"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=6" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/proj_hidraulico.gif" alt="Projeto de Instala&ccedil;&otilde;es Hidr&aacute;ulicas e Sanit&aacute;rias Prediais" border="0"></a></td>
                                <td width="180"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=5" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/proj_cad.gif" alt="Cad para Engenharia" border="0"></a></td>
							</tr>
						</table></td>
				</tr>
    			<tr>
  					<td>
    					<table width="680" border="0" cellpadding="0" cellspacing="0" align="center">
            				<tr>
  					<td colspan="5">
    					<table width="680" border="0" cellpadding="0" cellspacing="0" align="center">
   					  <tr style="font-size:12px; color:#222222;">
            					<td  height="39"  align="center" colspan="4" style="padding-left:0px; color:#5BBF22;" ><b>Cursos Presenciais</b></td>
                          </tr>
            				<tr style="font-size:11px; color:#222222;">
           	 					<td width="96" bgcolor="#E4EBDB"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/qiTec_novo_01.png" width="96" height="48"></td>
    			 				<td width="477" align="center" bgcolor="#E4EBDB" ><a href="http://www.qisat.com.br/ecommerce/produtos/calendarioCursosPresenciais.php" target="_blank"><b>Confira a Programação dos Cursos Presenciais de <em>Software</em> AltoQi</b></a></td>
               				  <td width="106" align="center" valign="middle" bgcolor="#E4EBDB"><a href="http://www.qisat.com.br/ecommerce/produtos/calendarioCursosPresenciais.php" target="_blank"><b>CLIQUE AQUI</b></a></td>
       					  </tr>
               				</table></td>
  				</tr>
            				<tr style="font-size:12px; color:#015596;">
            					<td  height="35"  align="center" colspan= "4" style="padding-left:0px; color:#015596;"><b>Cursos Via Internet</b></td>
                            </tr>
        				</table></td>
  				</tr>
  				<tr>
    				<td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/estrutural.gif"></td>
  				</tr>
  				<tr>
    				<td>
                    	<table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#222222; ">
                          <tr style="font-size:12px; color:#222222;">
                            <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO DE <em>SOFTWARE</em></b></td>
                            <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
                            <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR PROMOCIONAL</td>
                            <td width="77" bgcolor="#e1e2e3" align="center"></td>
                          </tr>
							<tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=225" target="_blank"><strong>Curso Software Eberick Pré-moldado a distância (Lan&ccedil;amento)</strong></a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(225, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(225, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=225" target="_blank">VER AULA</a></td>
                          </tr>                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=179" target="_blank">Curso Software Eberick V10 a distância</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(179, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(179, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=179" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                            <td width="77" align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
                          </tr>
						</table></td>
  				</tr>
  				<tr>
                    <td>
                    	<table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
                          <tr style="font-size:12px; color:#333333;">
                            <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO TEÓRICO</b></td>
                            <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
                            <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR PROMOCIONAL</td>
                            <td width="77" bgcolor="#e1e2e3" align="center"></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=239" target="_blank"><strong>Curso Concreto Armado - Requisitos para Desenv. de Projetos de Edificações (Lançamento)</strong></a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(239, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(239, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=239" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=181" target="_blank">Curso Fundações - Engenharia Geotécnica via internet</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(181, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(181, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=181" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=140" target="_blank">Curso NBR 6118:2014 - Concreto Armado: Parte 1 </a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(140, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(140, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=140" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=141" target="_blank">Curso NBR 6118:2014 - Concreto Armado: Parte 2 </a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(141, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(141, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=141" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=142" target="_blank">Curso NBR 6118:2014 - Concreto Armado: Parte 3</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(142, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(142, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=142" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=107" target="_blank">Curso Alvenaria Estrutural para Arquitetos</a></td>
                          	<td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(107, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(107, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=107" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=109" target="_blank">Curso Alvenaria Estrutural para Construtoras</a></td>
                          	<td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(109, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(109, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=109" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=61" target="_blank">Curso Alvenaria Estrutural para Projetistas</a></td>
                          	<td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(61, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(61, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=61" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=34" target="_blank">Curso Concreto Pré-Moldado - Fundamentos do Sistema Construtivo</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(34, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(34, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=34" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=30" target="_blank">Curso Soluções de Contenção - Taludes, 
Muros de Arrimo e Escoramentos</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(30, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(30, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=30" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=31" target="_blank">Curso Norma Regulamentadora 18 Ilustrada</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(31, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(31, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#222222; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=31" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=27" target="_blank">Curso Conceitos de Estabilidade Global para Projeto de Edifícios</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(27, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(27, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#222222; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=27" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=10" target="_blank">Palestra Durabilidade das Estruturas de Concreto</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(10, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(10, "valordesconto", "QiSat");?></b></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=10" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                            <td width="77" align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
                          </tr>
		    </table>
</td>
  </tr>
  <tr>
    				<td>
                    	<table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#222222; ">
                          <tr style="font-size:12px; color:#222222;">
                            <td height="27" bgcolor="#e1e2e3" style="padding-left:15px;"><b>SÉRIE DE CAPÍTULOS VIA INTERNET - TEÓRICO</b></td>
                            <td width="205" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR POR CAPÍTULO</td>
                            <td width="77" bgcolor="#e1e2e3" align="center"></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank"><b>Concreto Armado - Requisitos para Desenvolvimento de Projetos de Edificações</b></a></td>
                            <td align="center" bgcolor="#ecedee">CONSULTAR</td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">CAPÍTULOS</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank"><b>Fundações - Engenharia Geotécnica</b></a></td>
                            <td align="center" bgcolor="#ecedee">CONSULTAR</td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">CAPÍTULOS</a></td>
                          </tr>
                                                  <tr>
                            <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                          </tr>
						</table></td>
			  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/eletrico.gif"></td>
  </tr>
  <tr>
    <td><table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="77" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1720&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=172" target="_blank">Curso Software QiElétrico a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(172, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(172, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1720&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=172" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td width="77" align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO TEÓRICO</b></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="77" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=143" target="_blank">Curso Norma Regulamentadora 10 </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(143, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(143, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=143" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=29" target="_blank">Curso Sistemas de Cabeamento Estruturado</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(29, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(29, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=29" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=24" target="_blank">Curso Instalações Elétricas Residenciais </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(24, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(24, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=24" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=37" target="_blank">Curso Instalações Elétricas Prediais, Telefonia e Infraestrutura de TV</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(37, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(37, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=37" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td width="77" align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/hidraulica.gif"></td>
  </tr>
  <tr>
    <td><table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="77" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=131" target="_blank">Curso Software QiHidrossanitário a distância </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(131, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(131, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=131" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=136" target="_blank">Curso Software QiIncêndio a distância </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(136, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(136, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=136" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td width="77" align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO TEÓRICO</b></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="77" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=226" target="_blank"><strong>Curso Combate a Incêndio - Hidrantes e Mangotinhos (Lançamento)</strong></a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(226, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(226, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=226" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=4" target="_blank">Curso Instalações Prediais de Águas Pluviais</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(4, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(4, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=4" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=5" target="_blank">Curso Instalações Prediais Esgoto Sanitário</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(5, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(5, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=5" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=7" target="_blank">Curso Instalações Prediais Água Fria - Fundamentos</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(7, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(7, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=7" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=8" target="_blank">Curso Instalações Prediais Água Fria - Dimensionamento</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(8, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(8, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=8" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=11" target="_blank">Curso Instalações Prediais de Água Quente - Geração</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(11, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(11, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=11" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=25" target="_blank">Curso Instalações  Prediais de Água Quente - Distribuição</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(25, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(25, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=25" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td>
    	<table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#222222; ">
        	<tr style="font-size:12px; color:#222222;">
            	<td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>SÉRIE DE CAPÍTULOS VIA INTERNET - TEÓRICO</b></td>
                <td width="205" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR POR CAPÍTULO</td>
                <td width="77" bgcolor="#e1e2e3" align="center"></td>
            </tr>
            <tr>
                <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=213" target="_blank"><b>Combate a Incêndio - Hidrantes e Mangotinhos</b></a></td>
                <td align="center" bgcolor="#ecedee">CONSULTAR</td>
                <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=213" target="_blank">CAPÍTULOS</a></td>
            </tr>
            <tr>
                <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
                <td width="88" align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
              </tr>
		</table></td>
	</tr>
	<tr>
		<td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/pacotes2.gif"></td>
	</tr>
	<tr>
    	<td><table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  	<tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>PACOTES VIA INTERNET </b></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="77" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=210" target="_blank"><b>Pacote de cursos Projeto Estrutural em Concreto - Teoria e Prática</b></a></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(210, "valortabela", "QiSat");?></td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;"><b>R$ <?php echo retornarValor(210, "valordesconto", "QiSat");?></b></td>
    <td width="77" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=210" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=211" target="_blank"><b>Pacote de Cursos Projeto Estrutural em Concreto - Teoria</b></a></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(211, "valortabela", "QiSat");?></td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;"><b>R$ <?php echo retornarValor(211, "valordesconto", "QiSat");?></b></td>
    <td width="77" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=211" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px;color: #333333;font-size:11px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=219" target="_blank"><b>Pacote de cursos Projeto Elétrico Predial - Teoria e Prática</b></a></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(219, "valortabela", "QiSat");?></td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;"><b>R$ <?php echo retornarValor(219, "valordesconto", "QiSat");?></b></td>
    <td width="77" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=219" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px;color: #333333;font-size:11px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=220" target="_blank"><b>Pacote de cursos Projeto Elétrico Predial - Teoria</b></a></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(220, "valortabela", "QiSat");?></td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;"><b>R$ <?php echo retornarValor(220, "valordesconto", "QiSat");?></b></td>
    <td width="77" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=220" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="30" bgcolor="#e1e2e3" style="padding-left:15px; color: #333333;font-size:11px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=207" target="_blank"><b>Pacote de Cursos Projeto Cabeamento Estruturado Predial - <br>Teoria e Prática</b></a></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(207, "valortabela", "QiSat");?></td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;"><b>R$ <?php echo retornarValor(207, "valordesconto", "QiSat");?></b></td>
    <td width="77" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=207" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=204" target="_blank"><b>Pacote de Cursos Projeto Hidráulico Predial - Teoria e Prática</b></a></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(204, "valortabela", "QiSat");?></td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;"><b>R$ <?php echo retornarValor(204, "valordesconto", "QiSat");?></b></td>
    <td width="77" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=204" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=203" target="_blank"><b>Pacote de Cursos Projeto Hidráulico Predial - Teoria</b></a></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(203, "valortabela", "QiSat");?></td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;"><b>R$ <?php echo retornarValor(203, "valordesconto", "QiSat");?></b></td>
    <td width="77" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=203" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=208" target="_blank"><b>Pacote de Cursos AltoQi - Premium</b></a></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(208, "valortabela", "QiSat");?></td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;"><b>R$ <?php echo retornarValor(208, "valordesconto", "QiSat");?></b></td>
    <td width="77" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=208" target="_blank">DETALHES</a></span></td>
  </tr>  
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=173" target="_blank"><b>Pacote de Cursos QiBuilder - Full</b></a></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(173, "valortabela", "QiSat");?></td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;"><b>R$ <?php echo retornarValor(173, "valordesconto", "QiSat");?></b></td>
    <td width="77" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=173" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td width="77" align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/cad.gif"></td>
  </tr>
  <tr>
    <td><table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="77" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=168" target="_blank">Curso Software QiEditor de Armaduras a distância</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(168, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(168, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=168" target="_blank">VER AULA</a></td>
  </tr>
  
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=134" target="_blank">Curso Software QiBuilder - CAD a distância</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(134, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(134, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=134" target="_blank">VER AULA</a></td>
  </tr>
  
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=135" target="_blank">Curso Software QiBuilder - Gerenciador de Arquivos a distância</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(135, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(135, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=135" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td width="77" align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO TEÓRICO</b></td>
    <td width="88" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="116" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="77" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=22" target="_blank">Curso Acústica Aplicada a Home Theater</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(22, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(22, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=22" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=23" target="_blank">Curso Gestão de Projetos em Escritórios de Arquitetura e Construção Civil</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(23, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(23, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=23" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=3" target="_blank">Curso Marketing para Engenharia, Arquitetura e Agronomia</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(3, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(3, "valordesconto", "QiSat");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=3" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td width="77" align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
  	<td>
    	<table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#222222; ">
			<tr style="font-size:12px; color:#222222;">
            	<td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>SÉRIE DE CAPÍTULOS VIA INTERNET - TEÓRICO</b></td>
                <td width="205" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR POR CAPÍTULO</td>
                <td width="77" bgcolor="#e1e2e3" align="center"></td>
            </tr>
			<tr>
            	<td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=244" target="_blank"><strong>Como Negociar e Vender Serviços de Engenharia, Arquitetura e Agronomia (Lan&ccedil;amento)</strong></a></td>
                <td align="center" bgcolor="#ecedee">&nbsp;CONSULTAR</td>
                <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=244" target="_blank">CAPÍTULOS</a></td>
			</tr>
            <tr>
            	<td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank"><b>Licenciamento Ambiental - Bases para o Licenciamento</b></a></td>
                <td align="center" bgcolor="#ecedee">&nbsp;CONSULTAR</td>
                <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=236" target="_blank">CAPÍTULOS</a></td>
 			</tr>
			<tr>
           		<td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=193" target="_blank"><b>Desempenho de Edificações Habitacionais - Requisitos Gerais</b></a></td>
                <td align="center" bgcolor="#ecedee">&nbsp;CONSULTAR</td>
                <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=193" target="_blank">CAPÍTULOS</a></td>
			</tr>
			<tr>
				<td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=221" target="_blank"><b>Irrigação e Drenagem - Outorga e Planejamento da Irrigação</b></a></td>
                <td align="center" bgcolor="#ecedee">&nbsp;CONSULTAR</td>
                <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=193" target="_blank">CAPÍTULOS</a></td>
            </tr>
            <tr>
  				<td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
                <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
            </tr>
			</table></td>
			  </tr>
              <tr>
    				<td>
                    	<table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#222222; ">
                          
						</table></td>
  				</tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/webconferencia.gif"></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr>
    <td colspan="6" height="16" bgcolor="#ecedee" style="padding-left:15px" align="center"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=8" target="_blank">Conheça as Webconferências disponíveis no Canal QiSat.</a></td>
  </tr>
<tr>
  	<td colspan="6" align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/linha_rodape.gif"></td>
  </tr>
<tr>
    <td width="180" height="80" style="padding-left:15px; font-weight:bold; color:#666666; text-transform:uppercase;">Para maiores informações</td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/linha.jpg" width="1" height="52" /></td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/contato.png" width="14" height="52" /></td>
    <td align="left" style="line-height:140%; padding-left:15px; color:#666666">(48) 3332-5000<br />
    <span style="color:#666666">inscricoes@qisat.com.br</span><br />
      <a href="http://www.qisat.com.br/" target="_blank" style="color:#666666">www.qisat.com.br</a></td>
    <td align="left"><a href="http://www.qisat.com.br" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2017/catalogo_geral/images/logoqisat.png" border="0"></a></td>
    <td align="left"><a href="http://www.altoqi.com.br/" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/logoaltoqi.png" border="0"></a></td>
  </tr>
  <tr>
  	<td colspan="6" align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/linha_rodape.gif"></td>
  </tr>
  <tr>
    <td colspan="6" align="center" width="700"><div style="font-family:Tahoma, Verdana, Arial; font-size:10px; color:#333333; padding:10px; margin:auto; text-align:center">© 2003 - 2017 - Todos os Direitos Reservados à MN Tecnologia e Treinamento Ltda. | Para mais informações entre em <a href="mailto:central@qisat.com.br"><span style="color:#02416d;">contato.</span></a></div></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="682" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
</table></td>
  </tr>
</table>
</table>
<map name="topo">
<area shape="rect" coords="678,7,704,33" href="http://www.qisat.com.br/contato/contato.php" target="_blank" alt="MSN QiSat">
<area shape="rect" coords="652,7,676,33" href="http://www.youtube.com/qisat/" target="_blank" alt="Canal QiSat no YouTube">
<area shape="rect" coords="625,7,650,33" href="http://twitter.com/qisat/" target="_blank" alt="Twitter QiSat">
<area shape="rect" coords="598,7,624,33" href="http://br.linkedin.com/in/qisat" target="_blank" alt="QiSat no LinkedIn">
<area shape="rect" coords="572,7,597,33" href="http://www.facebook.com/qisat" target="_blank" alt="QiSat no Facebook">
<area shape="rect" coords="38,24,160,67" href="http://www.qisat.com.br" target="_blank" alt="[QiSat] O Canal de e-learning da Engenharia">
</map>
</body>
</html>