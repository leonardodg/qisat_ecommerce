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
 * 3º - Entidade a ser consultada(QiSat, CREA-RO, CREA-TO, CREA-BA, CREA-DF, CREA-MS) - com aspas
********************************************************************************************/
?>
<title>Programa de Capacitação do CREA-RO</title>
<style type="text/css">
a:link {
	color: #373737;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #373737;
}
a:hover {
	text-decoration: underline!important; 
	color: #111111;
}
a:active {
	text-decoration: none;
	color: #373737;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #ebebeb;
	color:#595959;
	font-family: Tahoma, Arial, Helvetica, sans-serif;
	font-size:12px;
	margin:auto;
	background-color:#EBEBEB;
}
.titulo {
	color:#015da2;
	font-family: Tahoma, Arial, Helvetica, sans-serif;
	font-size:14px;
	text-align:center; 
	font-weight:bolder;
}
.style10 {color: #015596; font-weight: bold; }
#assinatura a:link { color:#015da2 !important;   }
</style>
<body>
  <div align="center" style="font-size:11px; color:#333333; margin:auto; padding:2px;">Caso não esteja visualizando o e-mail abaixo, <a href="http://www.qisat.com.br/ecommerce/catalogo/arquivos/19/index.php?niveis=1" target="_blank"  style="color:#015DA2; font-size:11px">CLIQUE AQUI</a>.</div>

			<table width="740" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#FFFFFF; border:solid 1px #dedede; color:#666666; font-size:12px; font-family:Tahoma, Verdana, Arial;">
          <tr><td><img src="http://public.qisat.com.br/campanhas/e-convites/2016/CREAMS/images/cabecalho_irrigacao2.png" width="741" height="206" border="0"></td></tr>
          <tr>
                <td style=" padding:20px 40px 10px 20px; line-height:150%; text-align:left;">
          <span style=" font-size:14px"><b>Prezado Profissional,</b></span>
                  <p><span style=" font-size:14px"><strong>A Agronomia solicitou e o QiSat atendeu!</strong> Lançamento disponível na Plataforma do Convenio CREA-MS.</span>
              <ul>
              <li><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1659&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=221" target="_blank"><strong>Nova Série Online</strong>: Irrigação e Drenagem – Outorga e Planejamento da Irrigação</a></li>
                 </ul>
                  <p>Participe!</p>
Mais informações, acesse <a href="http://crea-ms.qisat.com.br/ecommerce/webstore.php" target="_blank"><u><b>crea-ms.qisat.com.br</b></u></a> ou entre em contato com a Central de Inscrições pelo e-mail <b>central@qisat.com.br</b> ou pelo telefone <strong>(48) 3332-5000</strong>.<br></td>
              </tr>
              <tr>
  	<td align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/linha_rodape.gif" width="710"></td>
  </tr>
  <tr>
				  <td><table width="710" border="0" cellpadding="0" cellspacing="0" align="center">
                    <tr style="font-size:12px; color:#015596;">
                     <td  height="35"  colspan= "3" style="text-align:center" ><span class="style10">CURSOS VIA INTERNET</span></td>
                    </tr>
                  </table></td>
			  </tr>
  <tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
    <tr style="font-size:14px; color:#222222;">
    <td height="27" colspan="4" align="center" bgcolor="#714623"><strong>ÁREA ESTRUTURAL</strong></td>
    </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="90" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR TABELA</td>
    <td width="155" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="90" align="center" bgcolor="#e1e2e3">Aula Exemplo</td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=179" target="_blank"><b>Curso Software Eberick V10 a distância (LANÇAMENTO)</b></a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(179, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(179, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=179" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=121" target="_blank">Curso Software Eberick V9 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(121, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(121, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=121" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=112" target="_blank">Curso Software Eberick V8 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(112, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(112, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px;"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=112" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:12px; color:#555555;">
    <td height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>CURSO TEÓRICO</b></td>
    <td width="90" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR TABELA</td>
    <td width="155" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="90" align="center" bgcolor="#e1e2e3">Aula Exemplo</td>
    <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left; padding-right:20px; "><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=181" target="_blank"  style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px;"><strong>Curso Fundações - Engenharia Geotécnica via internet</strong></a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(181, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(181, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=181" target="_blank">VER AULA</a></td>
  </tr>
    <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left; padding-right:20px; "><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=140" target="_blank"  style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px;">Curso NBR 6118:2014 - Concreto Armado: Parte 1</a></a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(140, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(140, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=140" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left; padding-right:20px; "><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=141" target="_blank"  style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px;">Curso NBR 6118:2014 - Concreto Armado: Parte 2</a></a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(141, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(141, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=141" target="_blank">VER AULA</a></td>
  </tr>
    <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left; padding-right:20px; "><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=142" target="_blank"  style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px;">Curso NBR 6118:2014 - Concreto Armado: Parte 3</a></a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(142, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(142, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=142" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left; padding-right:20px; "><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=107" target="_blank"  style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px;">Curso Alvenaria Estrutural para Arquitetos</a></a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(107, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(107, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=107" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left; padding-right:20px; "><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=109" target="_blank"  style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px;">Curso Alvenaria Estrutural para Construtoras</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(109, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(109, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=109" target="_blank">VER AULA</a></td>
  </tr>
 <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left; "><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=61" target="_blank"  style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; ">Curso Alvenaria Estrutural para Projetistas</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(61, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(61, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=61" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left; "><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=34" target="_blank">Curso Concreto Pré-Moldado - Fundamentos do Sistema Construtivo</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(34, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(34, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=34" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=30" target="_blank">Curso Soluções de Contenção - Taludes, Muros de Arrimo e Escoramentos</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(30, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(30, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=30" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=31" target="_blank">Curso Norma Regulamentadora 18 Ilustrada</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(31, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(31, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=31" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=27" target="_blank">Curso Conceitos de Estabilidade Global para Projeto de Edifícios</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(27, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(27, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=27" target="_blank">VER AULA</a></td>
  </tr>

  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px;text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=10" target="_blank">Palestra: Durabilidade das Estruturas de Concreto</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(10, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(10, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/08_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=10" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
</table>
</td>
<tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:12px; color:#555555;">
    <td width="475" height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>SÉRIE DE CAPÍTULOS - TEÓRICO</b></td>
    <td colspan="2" align="center" bgcolor="#e1e2e3" style="font-size:11px;"> VALOR POR CAPÍTULO</td>
    <td width="79" bgcolor="#e1e2e3" align="center"> </td>
  </tr>
  <tr>
    <td height="17" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank"><strong>Série Concreto Armado - Requisitos para Desenvolvimento de Projetos de Edificações (LANÇAMENTO)</strong></a></td>
    <td colspan="2" align="center" bgcolor="#ecedee"> CONSULTAR</td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=202" target="_blank">CAPÍTULOS</a></td>
  </tr>
  <tr>
    <td height="17" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank"><strong>Série Fundações - Engenharia Geotécnica via internet</strong></a></td>
    <td colspan="2" align="center" bgcolor="#ecedee"> CONSULTAR</td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=153" target="_blank">CAPÍTULOS</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td width="88" align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td width="63" align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
</table>
</td>
  </tr>
  </tr>
  <tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:14px; color:#444444;">
    <td height="27" colspan="4" align="center" bgcolor="#ffcc28"><strong>ÁREA ELÉTRICA</strong></td>
    </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR TABELA</td>
    <td width="155" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center">Aula Exemplo</td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=172" target="_blank"><strong>Curso Software QiElétrico a distância</strong></a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(172, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(172, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=172" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=14" target="_blank">Curso Software Lumine V4 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(14, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(14, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=14" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=28" target="_blank">Curso Software Cabeamento Estruturado - Lumine V4 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(28, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(28, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=28" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:12px; color:#555555;">
    <td height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>CURSO TEÓRICO</b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR TABELA</td>
    <td width="155" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center">Aula Exemplo</td>
  </tr>
  <tr>
    <td height="17" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=143" target="_blank">Curso Norma Regulamentadora 10</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(143, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(143, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=143" target="_blank">VER AULA</a></td>
  </tr>  
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=29" target="_blank">Curso Sistemas de Cabeamento Estruturado</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(29, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(29, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=29" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=24" target="_blank">Curso Instalações Elétricas Residenciais </a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(24, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(24, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=24" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=37" target="_blank">Curso Instalações Elétricas Prediais, Telefonia e Infraestrutura de TV</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(37, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(37, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=37" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:14px; color:#444444;">
    <td height="27" colspan="4" align="center" bgcolor="#00aff0"><strong>ÁREA HIDRÁULICA</strong></td>
    </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR TABELA</td>
    <td width="155" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center">Aula Exemplo</td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=131" target="_blank">Curso Software QiHidrossanitário a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(131, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(131, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=131" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1715&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=136" target="_blank">Curso Software QiIncêndio a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(136, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(136, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=136" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=12" target="_blank">Curso Software Hydros V4 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(12, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(12, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=12" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=17" target="_blank">Curso Software Sistema Incêndio utilizando Hydros V4 a distância</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(17, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(17, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=17" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:12px; color:#555555;">
    <td height="36" bgcolor="#e1e2e3" style="padding-left:15px;text-align:left"><b>CURSO TEÓRICO</b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR TABELA</td>
    <td width="155" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center">Aula Exemplo</td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=4" target="_blank">Curso Instalações Prediais de Águas Pluviais</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(4, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(4, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=4" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=5" target="_blank">Curso Instalações Prediais Esgoto Sanitário</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(5, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(5, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=5" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=7" target="_blank">Curso Instalações Prediais Água Fria - Fundamentos</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(7, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(7, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=7" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=8" target="_blank">Curso Instalações Prediais Água Fria - Dimensionamento</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(8, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(8, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=8" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=11" target="_blank">Curso Instalações Prediais de Água Quente - Geração</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(11, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(11, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=11" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=25" target="_blank">Curso Instalações  Prediais de Água Quente - Distribuição</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(25, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(25, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=25" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td colspan="2" align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    </tr>
</table>
</td>
  </tr>
  <tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:12px; color:#555555;">
    <td width="475" height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>SÉRIE DE CAPÍTULOS - TEÓRICO</b></td>
    <td colspan="2" align="center" bgcolor="#e1e2e3" style="font-size:11px;"> VALOR POR CAPÍTULO</td>
    <td width="79" bgcolor="#e1e2e3" align="center"> </td>
  </tr>
  <tr>
    <td height="17" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=213" target="_blank"><strong>Série Combate a Incêndio – Hidrantes e Mangotinhos (LANÇAMENTO)</strong></a></td>
    <td colspan="2" align="center" bgcolor="#ecedee"> CONSULTAR</td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=213" target="_blank">CAPÍTULOS</a></td>
  </tr>  
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td width="88" align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td width="63" align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:14px; color:#444444;">
    <td height="27" colspan="4" align="center" bgcolor="#666666"><strong>PACOTE DE CURSOS</strong></td>
    </tr>
    <tr style="font-size:12px; color:#555555;">
    <td height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>PACOTES</b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR TABELA</td>
    <td width="155" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center"></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px; text-align:left; font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=210" target="_blank"><strong>Pacote de Cursos Projeto Estrutural em Concreto - Teoria e Prática via internet</strong></a></td>
    <td width="90" align="center" bgcolor="#ecedee" style="font-size:11px;">R$ <?php echo retornarValor(210, "valortabela", "CREA-MS");?></td>
    <td width="155" align="center" bgcolor="#ecedee" style="font-size:11px;"><b>R$ <?php echo retornarValor(210, "valordesconto", "CREA-MS");?></b></td>
    <td width="90" align="center" bgcolor="#ecedee"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=210" target="_blank"><span style="color:#555555; font-size:9px ">DETALHES</span></a></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px; text-align:left; font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=211" target="_blank"><strong>Pacote de Cursos Projeto Estrutural em Concreto - Teoria via internet</strong></a></td>
    <td width="90" align="center" bgcolor="#ecedee" style="font-size:11px;">R$ <?php echo retornarValor(211, "valortabela", "CREA-MS");?></td>
    <td width="155" align="center" bgcolor="#ecedee" style="font-size:11px;"><b>R$ <?php echo retornarValor(211, "valordesconto", "CREA-MS");?></b></td>
    <td width="90" align="center" bgcolor="#ecedee"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=211" target="_blank"><span style="color:#555555; font-size:9px ">DETALHES</span></a></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=219" target="_blank"><span style="font-size:11PX;"><STRONG>Pacote de Cursos Projeto Elétrico Predial - Teoria e Prática via internet</STRONG> </span></a>
    <td width="90" bgcolor="#ecedee" align="center" style="font-size:11px;">R$ <?php echo retornarValor(219, "valortabela", "CREA-MS");?></td>
    <td width="155" align="center" bgcolor="#ecedee" style="font-size:11px;"><b>R$ <?php echo retornarValor(219, "valordesconto", "CREA-MS");?></b></td>
    <td width="90" bgcolor="#ecedee" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=219" target="_blank"><span style="color:#555555; font-size:9px ">DETALHES</span></a></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=220" target="_blank"><span style="font-size:11PX;"><STRONG>Pacote de Cursos Projeto Elétrico Predial - Teoria via internet</STRONG> </span></a>
    <td width="90" bgcolor="#ecedee" align="center" style="font-size:11px;">R$ <?php echo retornarValor(220, "valortabela", "CREA-MS");?></td>
    <td width="155" align="center" bgcolor="#ecedee" style="font-size:11px;"><b>R$ <?php echo retornarValor(220, "valordesconto", "CREA-MS");?></b></td>
    <td width="90" bgcolor="#ecedee" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=220" target="_blank"><span style="color:#555555; font-size:9px ">DETALHES</span></a></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="40" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=207" target="_blank"><span style="font-size:11PX;"><strong>Pacote de Cursos Projeto Cabeamento Estruturado Predial - <br>
       Teoria e Prática via internet</strong></span></a></td>
    <td bgcolor="#ecedee" align="center" style="font-size:11px;">R$ <?php echo retornarValor(207, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee" style="font-size:11px;"><b>R$ <?php echo retornarValor(207, "valordesconto", "CREA-MS");?></b></td>
    <td bgcolor="#ecedee" align="center"><span style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=207" target="_blank">DETALHES</a></a></span></td>
  </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="40" bgcolor="#ecedee" style="padding-left:15px;text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=204" target="_blank"><span style="font-size:11PX;"><strong>Pacote de Cursos Projeto Hidráulico Predial - Teoria e Prática <br>via internet</strong></span></a></td>
    <td width="90" align="center" bgcolor="#ecedee" style="font-size:11px;">R$ <?php echo retornarValor(204, "valortabela", "CREA-MS");?></td>
    <td width="155" align="center" bgcolor="#ecedee" style="font-size:11px;"><b>R$ <?php echo retornarValor(204, "valordesconto", "CREA-MS");?></b></td>
    <td width="90" align="center" bgcolor="#ecedee"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=204" target="_blank"><span style="color:#555555; font-size:9px ">DETALHES</span></a></td>
  </tr>
    <tr style="font-size:12px; color:#555555;">
    <td height="40" bgcolor="#ecedee" style="padding-left:15px;text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=203" target="_blank"><span style="font-size:11PX;"><strong>Pacote de Cursos Projeto Hidráulico Predial - Teoria via internet </strong></span></a></td>
    <td width="90" align="center" bgcolor="#ecedee" style="font-size:11px;">R$ <?php echo retornarValor(203, "valortabela", "CREA-MS");?></td>
    <td width="155" align="center" bgcolor="#ecedee" style="font-size:11px;"><b>R$ <b><?php echo retornarValor(203, "valordesconto", "CREA-MS");?></b></b></td>
    <td width="90" align="center" bgcolor="#ecedee"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=203" target="_blank"><span style="color:#555555; font-size:9px ">DETALHES</span></a></td>
  </tr>
    <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px; text-align:left; font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=208" target="_blank"><strong>Pacote de Cursos AltoQi - Premium via internet</strong></a></td>
    <td width="90" align="center" bgcolor="#ecedee" style="font-size:11px;">R$ <?php echo retornarValor(208, "valortabela", "CREA-MS");?></td>
    <td width="155" align="center" bgcolor="#ecedee" style="font-size:11px;"><b>R$ <?php echo retornarValor(208, "valordesconto", "CREA-MS");?></b></td>
    <td width="90" align="center" bgcolor="#ecedee"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=208" target="_blank"><span style="color:#555555; font-size:9px ">DETALHES</span></a></td>
  </tr>  
  <tr style="font-size:12px; color:#555555;">
    <td height="27" bgcolor="#ecedee" style="padding-left:15px; text-align:left; font-size:11px;"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=173" target="_blank"><strong>Pacote de Cursos QiBuilder - Full via internet</strong></a></td>
    <td width="90" align="center" bgcolor="#ecedee" style="font-size:11px;">R$ <?php echo retornarValor(173, "valortabela", "CREA-MS");?></td>
    <td width="155" align="center" bgcolor="#ecedee" style="font-size:11px;"><b>R$ <?php echo retornarValor(173, "valordesconto", "CREA-MS");?></b></td>
    <td width="90" align="center" bgcolor="#ecedee"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" alt="" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=173" target="_blank"><span style="color:#555555; font-size:9px ">DETALHES</span></a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:14px; color:#333333;">
    <td height="27" colspan="4" align="center" bgcolor="#bc0b28"><strong>ÁREA CAD E OUTROS</strong></td>
    </tr>
  <tr style="font-size:12px; color:#555555;">
    <td height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>CURSO DE <em>SOFTWARE</em></b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR TABELA</td>
    <td width="155" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center">Aula Exemplo</td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=168" target="_blank"><strong>Curso Software QiEditor de Armaduras a distância</strong></a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(168, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(168, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=168" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=134" target="_blank">Curso Software QiBuilder - CAD</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(134, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(134, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=134" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=135" target="_blank">Curso Software QiBuilder - Gerenciador de Arquivos</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(135, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(135, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=135" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td width="72" align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    </tr>
</table></td>
  </tr>
  <tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:12px; color:#555555;">
    <td height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>CURSO TEÓRICO</b></td>
    <td width="90" bgcolor="#e1e2e3" align="center" style="font-size:11px;">VALOR TABELA</td>
    <td width="155" align="center" bgcolor="#e1e2e3" style="font-size:11px;">VALOR PROMOCIONAL</td>
    <td width="90" bgcolor="#e1e2e3" align="center">Aula Exemplo</td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=21" target="_blank">Curso Acústica Arquitetônica - Fundamentos e Conceitos</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(21, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(21, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=21" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=22" target="_blank">Curso Acústica Aplicada a Home Theater</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(22, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(22, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=22" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=23" target="_blank">Curso Gestão de Projetos em Escritórios de Arquitetura e Construção Civil</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(23, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(23, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=23" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="16" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=3" target="_blank">Curso Marketing para Engenharia, Arquitetura e Agronomia</a></td>
    <td align="center" bgcolor="#ecedee">R$ <?php echo retornarValor(3, "valortabela", "CREA-MS");?></td>
    <td align="center" bgcolor="#ecedee"><b>R$ <?php echo retornarValor(3, "valordesconto", "CREA-MS");?></b></td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=3" target="_blank">VER AULA</a></td>
  </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    </tr>
</table>
<tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:12px; color:#555555;">
    <td width="475" height="36" bgcolor="#e1e2e3" style="padding-left:15px; text-align:left"><b>SÉRIE DE CAPÍTULOS - TEÓRICO</b></td>
    <td colspan="2" align="center" bgcolor="#e1e2e3" style="font-size:11px;"> VALOR POR CAPÍTULO</td>
    <td width="79" bgcolor="#e1e2e3" align="center"> </td>
  </tr>
  <tr>
    <td height="17" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=221" target="_blank"><strong>Série Irrigação e Drenagem – Outorga e Planejamento da Irrigação (LANÇAMENTO)</strong></a></td>
    <td colspan="2" align="center" bgcolor="#ecedee"> CONSULTAR</td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=221" target="_blank">CAPÍTULOS</a></td>
  </tr>
  <tr>
    <td height="17" bgcolor="#ecedee" style="padding-left:15px; text-align:left"><a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=193" target="_blank"><strong>Série Desempenho de Edificações Habitacionais - Requisitos Gerais (LANÇAMENTO)</strong></a></td>
    <td colspan="2" align="center" bgcolor="#ecedee"> CONSULTAR</td>
    <td align="center" bgcolor="#ecedee" style="color:#555555; font-size:9px "><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/seta.png" width="8" height="8">&nbsp;<a href="http://public.qisat.com.br/or.asp?cl=[Chave]&bd=[BD]&or=1658&lk=http://crea-ms.qisat.com.br/ecommerce/produtos/info.php?id=193" target="_blank">CAPÍTULOS</a></td>
  </tr>  
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td width="88" align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td width="63" align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
</table>
</td>
  </tr>
<tr>
    <td><table width="710" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px; color:#555555; ">
  <tr style="font-size:14px; color:#444444;">
    <td height="27" colspan="4" align="center" bgcolor="#006699"><strong>WEBCONFERÊNCIA ONLINE</strong></td>
    </tr>
  <tr bgcolor="#ecedee">
    <td height="16" colspan="4" style="padding-left:15px; text-align:center"><a href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=8" target="_blank">Conheça as Webconferências disponíveis no Canal QiSat.</a></td>
    </tr>
  <tr>
    <td height="6" bgcolor="#FFFFFF" style="padding-left:15px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="padding-left:13px"></td>
    <td align="center" bgcolor="#FFFFFF" style="color:#555555; font-size:9px"></td>
  </tr>
</table></td>
  </tr>
  </tr>
          </table>
          <table width="740" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    <td width="200" height="80" style="padding-left:15px; font-weight:bold; color:#666666; text-transform:uppercase; font-size:11px">Para maiores informações</td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1660/images/linha.jpg" width="1" height="52" /></td>
    <td width="20" align="center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1660/images/contato.png" width="14" height="52" /></td>
    <td align="left" style="line-height:140%; padding-left:15px; font-size:11px; color:#666666">(48) 3332-5000<br />
    <span style="color:#666666">central@qisat.com.br</span><br />
      <a href="http://www.qisat.com.br/" target="_blank" style="color:#666666">www.qisat.com.br</a></td>
    <td align="center"><a href="http://www.qisat.com.br" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1660/images/logoqisat.png" width="91" height="66" border="0"></a></td>
    <td align="center"><a href="http://www.altoqi.com.br/" target="_blank"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/11_1660/images/logoaltoqi.png" width="119" height="38" border="0"></a></td>
  </tr>
                  <tr>
  	<td colspan="6" align= "center"><img src="http://public.qisat.com.br/campanhas/e-convites/2015/09_1658/images/linha_rodape.gif"></td>
  </tr>
</table>
		<div style="font-family:Tahoma, Verdana, Arial; font-size:10px; color:#666666; padding:10px; margin:auto; text-align:center">© 2003 - 2016 - Todos os Direitos Reservados à MN Tecnologia e Treinamento Ltda. | Para mais informações entre em <a href="mailto:central@qisat.com.br"><span style="color:#02416d;">contato.</span></a></div>
</body>
</html>