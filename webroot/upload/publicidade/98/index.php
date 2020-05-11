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
<div align="center" style="font-size:10px; color:#333333; margin:auto; padding-bottom:10px; padding-top:5px;">Caso não esteja visualizando o e-mail abaixo, <a href="http://www.qisat.com.br/ecommerce/catalogo/arquivos/12/index.php?niveis=1" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</div>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" >
 	<tr>
    	<td>
        	<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
                  <tr>
                    <td width="700" ><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2016/09_1715_2/images/topo.png" width="700" height="180" border="0"></a></td>
              </tr>
           </table></td>
	<tr>
    	<td>
        
        	<table width="680" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
        		<tr>
    				<td width="455" align="center" style="padding:20px 10px 10px 20px; line-height:140%; text-align:left;"><div style="font-family: Tahoma, Arial, Helvetica, sans-serif; font-size:12px; color:#666666; text-align:left;">
   			<b>Confira nossos últimos lançamentos:</b>
            <ul>
            <li><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=221" target="_blank">Série Irrigação e Drenagem – Outorga e Planejamento da Irrigação</a></li>
            <li><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=213" target="_blank">Série Combate a Incêndio – Hidrantes e Mangotinhos</a></li>
            <li><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">Série Concreto Armado - Requisitos para Desenvolvimento de Projetos de Edificações</a></li>
            <li><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=193" target="_blank">Série Desempenho de Edificações Habitacionais - Requisitos Gerais</a></li></ul>
            Responda este e-mail e garanta esta condição.<br/>
    	</div>
<div style="font-family: Tahoma, Arial, Helvetica, sans-serif; font-size:12px; color:#666666;padding:0px 0px 10px 0px; line-height:140%; text-align:left;">  
Cordialmente,<br />
<b>Equipe QiSat</b><br />
Central de Inscrições: (48) 3332-5000 / Fax: (48) 3332-5010<br>
E-mail: <a href="mailto:qisat@qisat.com.br" style="color:#015DA2;">qisat@qisat.com.br</a><br>
<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br" target="_blank" style="color:#015DA2;"><em>http://www.qisat.com.br</em></a></div></td>
    				  </tr> 				 
   			  <tr>
  					<td>
    					<table width="680" border="0" cellpadding="0" cellspacing="0" align="center">   					  <tr style="font-size:12px; color:#015596;">
            					<td  height="35"  align="center" colspan= "4" style="padding-left:0px; color:#015596;"><b><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/estrutural.gif" width="680" height="27"><br/><b>Cursos Via Internet</b></td>
                            </tr>
        				</table></td>
  				</tr>
  				<tr>
    				<td align="center">&nbsp;</td>
  				</tr>
  				<tr>
    				<td>
                    	<table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#222222; ">
                          <tr style="font-size:12px; color:#222222;">
                            <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO DE <em>SOFTWARE</em></b></td>
                            <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
                            <td width="90" bgcolor="#e1e2e3" align="center"></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=179" target="_blank"><b>Curso Software Eberick V10 a distância (LANÇAMENTO)</b></a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(179, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=179" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=121" target="_blank">Curso Software Eberick V9 a distância </a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(121, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=121" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=112" target="_blank">Curso Software Eberick V8 a distância</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(112, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=112" target="_blank">VER AULA</a></td>
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
                    	<table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
                          <tr style="font-size:12px; color:#333333;">
                            <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO TEÓRICO</b></td>
                            <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
                            <td width="90" bgcolor="#e1e2e3" align="center"></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=181" target="_blank"><strong>Curso Fundações - Engenharia Geotécnica via internet</strong></a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(181, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=181" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=140" target="_blank">Curso NBR 6118:2014 - Concreto Armado: Parte 1 </a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(140, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=140" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=141" target="_blank">Curso NBR 6118:2014 - Concreto Armado: Parte 2 </a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(141, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=141" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=142" target="_blank">Curso NBR 6118:2014 - Concreto Armado: Parte 3 </a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(142, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=142" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=107" target="_blank">Curso Alvenaria Estrutural para Arquitetos</a></td>
                          	<td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(107, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=107" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=109" target="_blank">Curso Alvenaria Estrutural para Construtoras</a></td>
                          	<td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(109, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=109" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=61" target="_blank">Curso Alvenaria Estrutural para Projetistas</a></td>
                          	<td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(61, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=61" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=34" target="_blank">Curso Concreto Pré-Moldado - Fundamentos do Sistema Construtivo</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(34, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=34" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=30" target="_blank">Curso Soluções de Contenção - Taludes, 
Muros de Arrimo e Escoramentos</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(30, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=30" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=31" target="_blank">Curso Norma Regulamentadora 18 Ilustrada</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(31, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#222222; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=31" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=27" target="_blank">Curso Conceitos de Estabilidade Global para Projeto de Edifícios</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(27, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#222222; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=27" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=10" target="_blank">Palestra Durabilidade das Estruturas de Concreto</a></td>
                            <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(10, "valortabela", "QiSat");?></td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=10" target="_blank">VER AULA</a></td>
                          </tr>
                          <tr>
                            <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
                          </tr>
		    </table>
            <table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#222222; ">
                          <tr style="font-size:12px; color:#222222;">
                            <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>SÉRIE DE CAPÍTULOS - TEÓRICO</b></td>
                            <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR POR CAPÍTULO</td>
                            <td width="90" bgcolor="#e1e2e3" align="center"></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank"><B>Série Concreto Armado - Requisitos para Desenvolvimento de Projetos de Edificações (LANÇAMENTO)</B> </a></td>
                            <td align="center" bgcolor="#ecedee">CONSULTAR </td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">CAPÍTULOS</a></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank"><B>Série Fundações - Engenharia Geotécnica via internet</B></a></td>
                            <td align="center" bgcolor="#ecedee">&nbsp;CONSULTAR</td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">CAPÍTULO</a></td>
                          </tr>
                          <tr>
                          
                          </tr>
                          <tr>
                            <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
                          </tr>
						</table>
</td>
  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/eletrico.gif" width="680" height="27"></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
        <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=172" target="_blank"><strong>Curso Software QiElétrico a distância </strong></a></td>
        <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(172, "valortabela", "QiSat");?></td>
        <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=172" target="_blank">VER AULA</a></td>
      </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=14" target="_blank">Curso Software Lumine V4 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(14, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=14" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=28" target="_blank">Curso Software Cabeamento Estruturado - Lumine V4 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(28, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=28" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO TEÓRICO</b></td>
    <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=143" target="_blank">Curso Norma Regulamentadora 10</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(143, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=143" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=29" target="_blank">Curso Sistemas de Cabeamento Estruturado</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(29, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=29" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=24" target="_blank">Curso Instalações Elétricas Residenciais </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(24, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=24" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=37" target="_blank">Curso Instalações Elétricas Prediais, Telefonia e Infraestrutura de TV</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(37, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=37" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/hidraulica.gif" width="680" height="27"></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=131" target="_blank">Curso Software QiHidrossanitário a distância </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(131, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=131" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=136" target="_blank">Curso Software QiIncêndio a distância </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(136, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=136" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=12" target="_blank">Curso Software Hydros V4 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(12, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=12" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=17" target="_blank">Curso Software Incêndio - Hydros V4 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(17, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=17" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO TEÓRICO</b></td>
    <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=4" target="_blank">Curso Instalações Prediais de Águas Pluviais</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(4, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=4" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=5" target="_blank">Curso Instalações Prediais Esgoto Sanitário</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(5, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=5" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=7" target="_blank">Curso Instalações Prediais Água Fria - Fundamentos</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(7, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=7" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=8" target="_blank">Curso Instalações Prediais Água Fria - Dimensionamento</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(8, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=8" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=11" target="_blank">Curso Instalações Prediais de Água Quente - Geração</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(11, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=11" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=25" target="_blank">Curso Instalações  Prediais de Água Quente - Distribuição</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(25, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=25" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table>
<table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#222222; ">
                          <tr style="font-size:12px; color:#222222;">
                            <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>SÉRIE DE CAPÍTULOS - TEÓRICO</b></td>
                            <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR POR CAPÍTULO</td>
                            <td width="90" bgcolor="#e1e2e3" align="center"></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=213" target="_blank"><B>Série Combate a Incêndio – Hidrantes e Mangotinhos (LANÇAMENTO)</B> </a></td>
                            <td align="center" bgcolor="#ecedee">CONSULTAR </td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=213" target="_blank">CAPÍTULOS</a></td>
                          </tr>
                         <tr>
                          
                          </tr>
                          <tr>
                            <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
                          </tr>
						</table>
</td>
  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/pacotes2.gif" width="680" height="27"></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>PACOTES</b></td>
    <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=210" target="_blank"><b>Pacote de Cursos Projeto Estrutural em Concreto - Teoria e Prática via internet</b></a></td>
    <td width="87" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(210, "valortabela", "QiSat");?></td>
    <td width="76" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=210" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=211" target="_blank"><b>Pacote de Cursos Projeto Estrutural em Concreto - Teoria via internet</b></a></td>
    <td width="87" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(211, "valortabela", "QiSat");?></td>
    <td width="76" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=211" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px;color: #333333;font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=219" target="_blank"><b>Pacote de Cursos Projeto Elétrico Predial - Teoria e Prática via internet</b></a></td>
    <td width="86" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(219, "valortabela", "QiSat");?></td>
    <td width="76" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=219" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px;color: #333333;font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=220" target="_blank"><b>Pacote de Cursos Projeto Elétrico Predial - Teoria via internet</b></a></td>
    <td width="86" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(220, "valortabela", "QiSat");?></td>
    <td width="76" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=220" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color: #333333;font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=207" target="_blank"><b>Pacote de Cursos Projeto Cabeamento Estruturado Predial - Teoria e Prática via internet</b></a></td>
    <td width="86" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(207, "valortabela", "QiSat");?></td>
    <td width="76" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=207" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=204" target="_blank"><b>Pacote de Cursos Projeto Hidráulico Predial - Teoria e Prática via internet</b></a></td>
    <td width="86" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(204, "valortabela", "QiSat");?></td>
    <td width="76" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=204" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=203" target="_blank"><b>Pacote de Cursos Projeto Hidráulico Predial - Teoria via internet</b></a></td>
    <td width="86" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(203, "valortabela", "QiSat");?></td>
    <td width="76" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=203" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=208" target="_blank"><b>Pacote de Cursos AltoQi - Premium via internet</b></a></td>
    <td width="87" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(208, "valortabela", "QiSat");?></td>
    <td width="76" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=208" target="_blank">DETALHES</a></span></td>
  </tr>  
  <tr style="font-size:12px; color:#333333;">
    <td height="16" bgcolor="#e1e2e3" style="padding-left:15px; color:#333333; font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=173" target="_blank"><b>Pacote de Cursos QiBuilder - Full via internet</b></a></td>
    <td width="87" bgcolor="#e1e2e3" align="center" style="font-size:11px;">R$ <?php echo retornarValor(173, "valortabela", "QiSat");?></td>
    <td width="76" bgcolor="#e1e2e3" align="center"><span style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=173" target="_blank">DETALHES</a></span></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/cad.gif"></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=168" target="_blank">Curso Software QiEditor de Armaduras a distância</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(168, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=168" target="_blank">VER AULA</a></td>
  </tr>
    <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=134" target="_blank">Curso Software QiBuilder - CAD a distância </a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(134, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=134" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=135" target="_blank">Curso Software QiBuilder - Gerenciador de Arquivos a distância </a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(135, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=135" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr style="font-size:12px; color:#333333;">
    <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>CURSO TEÓRICO</b></td>
    <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR NORMAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=21" target="_blank">Curso Acústica Arquitetônica - Fundamentos e Conceitos</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(21, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=21" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=22" target="_blank">Curso Acústica Aplicada a Home Theater</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(22, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=22" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=23" target="_blank">Curso Gestão de Projetos em Escritórios de Arquitetura e Construção Civil</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(23, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=23" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=3" target="_blank">Curso Marketing para Engenharia, Arquitetura e Agronomia</a></td>
    <td align="center" bgcolor="#ecedee" style="padding-left:13px">R$ <?php echo retornarValor(3, "valortabela", "QiSat");?></td>
    <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=3" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
  </tr>
</table>
<table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#222222; ">
                          <tr style="font-size:12px; color:#222222;">
                            <td height="27" bgcolor="#e1e2e3" style="padding-left:15px"><b>SÉRIE DE CAPÍTULOS - TEÓRICO</em></b></td>
                            <td width="100" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR POR CAPÍTULO</td>
                            <td width="90" bgcolor="#e1e2e3" align="center"></td>
                          </tr>
                          <tr>
                            <td height="16" bgcolor="#ecedee" style="padding-left:15px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=193" target="_blank"><B>Série Desempenho de Edificações Habitacionais - Requisitos Gerais (LANÇAMENTO)</B> </a></td>
                            <td align="center" bgcolor="#ecedee">CONSULTAR</td>
                            <td align="center" bgcolor="#ecedee" style="color:#333333; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1715/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/info.php?id=193" target="_blank">CAPÍTULOS</a></td>
                          </tr>                          
                          <tr>
                            <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
                            <td align="center" bgcolor="#FFFFFF" style="color:#333333; font-size:9px"></td>
                          </tr>
					  </table></td>
  </tr>
  <tr>
    <td align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/webconferencia.gif" width="680" height="27"></td>

  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
  <tr>
    <td colspan="5" height="16" bgcolor="#ffffff" style="padding-left:15px" align="center"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/ecommerce/produtos/category.php?id=8" target="_blank">Conheça as Webconferências disponíveis no Canal QiSat.</a></td>
  </tr>
<tr>
  	<td colspan="5" align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/linha_rodape.gif"></td>
  </tr>
<tr>
    <td width="399" height="80" style="padding-left:100px; font-weight:bold; color:#666666; text-transform:uppercase;"><img src="http://public.qisat.com.br/campanhas/e-convites/2016/08_1715_2/images/icones.png" usemap="#Map" border="0"></td>
    <td width="17" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/linha.jpg" width="1" height="52" /></td>
    <td width="34" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/contato.png" width="14" height="52" /></td>
    <td width="112" align="center"><a href="http://www.qisat.com.br" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/logoqisat.png" width="91" height="66" border="0"></a></td>
    <td width="119" align="center"><a href="http://www.altoqi.com.br/" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1715/images/logoaltoqi.png" width="119" height="38" border="0"></a></td>
  </tr>
  <tr>
  	<td colspan="5" align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1715/images/linha_rodape.gif"></td>
  </tr>
  <tr>
    <td colspan="5" align="center"><div style="font-family:Tahoma, Verdana, Arial; font-size:10px; color:#333333; padding:10px; margin:auto; text-align:center">© 2003 - 2016 - Todos os Direitos Reservados à MN Tecnologia e Treinamento Ltda. | Para mais informações entre em <a href="mailto:central@qisat.com.br"><span style="color:#02416d;">contato.</span></a></div></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="680" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#333333; ">
</table></td>
  </tr>
</table>
</table>
<map name="topo">
<area shape="rect" coords="823,11,848,37" href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.facebook.com/qisat" target="_blank" alt="QiSat no Facebook">
<area shape="rect" coords="850,11,876,37" href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://br.linkedin.com/in/qisat" target="_blank" alt="QiSat no LinkedIn">
<area shape="rect" coords="881,10,906,36" href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://twitter.com/qisat/" target="_blank" alt="Twitter QiSat">
<area shape="rect" coords="910,10,934,36" href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.youtube.com/qisat/" target="_blank" alt="Canal QiSat no YouTube">
<area shape="rect" coords="937,10,963,36" href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br/contato/contato.php" target="_blank" alt="MSN QiSat"></map>
<map name="topo2">
<area shape="rect" coords="148,1,421,55" href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://www.qisat.com.br" alt="[QiSat] O Canal de e-learning da Engenharia"></map>
</body>
</html>