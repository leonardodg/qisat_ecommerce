<?php 
	require_once('../../../../../config.php');	
	global $CFG;
	require_once($CFG->dirroot.'/ecommerce/dao/ProdutoDao.php');
	
	$preview = $_GET['preview'];
	$produtoid = $_GET['produtoid'];
	$folder = $_GET['folder'];
	
	$produtoDao = new ProdutoDao();
	$produto = $produtoDao->buscaPeloId($produtoid);
			
	if($preview){
		$uf = 'UF';
	 	$cidade = 'Cidade';
	 	$data = ' Datas e horarios do curso';
	 	$endereco = 'Endereço contendo rua, bairro e referência';
	 	$ministrante = 'Nome e sobrenome do ministrante';
	 	$nomeCurso = $produto->getNome();
	 	
		$linkCurso = $CFG->wwwroot.'/ecommerce/produtos/info.php?id='.$produto->getId();
		$linkConvite = $CFG->wwwroot.'/ecommerce/cursopresencial/convites/arquivos/'.$folder.'/';
		
	 	$preco = number_format($produto->getPreco(), 2, ',', '.');
		
		if($produto->getMoeda() == "real"){
			$moeda = get_string('real', 'block_ecommerce');
		}else{
			$moeda = get_string('dolar', 'block_ecommerce');
		}
	 	
	 	$caragaHoraria = 'Carga horária total';
	}else{
		include ('../../dadosConvite.php');/*Arquivo com os dados do curso. Não remover*/	
	}
	
	/*
	 ##################### Variáveis com os dados #####################
	 ## $id  = Estado 												 ##
	 ## $uf  = Estado 												 ##
	 ## $cidade = Cidade										     ##
	 ## $data =  Datas e horarios do curso                           ##
	 ## $endereco = Endereço contendo rua, bairro e referência		 ##
	 ## $ministrante = Nome e sobrenome do ministrante				 ##
	 ## $nomeCurso = Nome do curso									 ##
	 ## $linkCurso = Link de referência no site QiSat				 ##
	 ## $preco = Valor integral do curso							 ##
	 ## $moeda = Moeda de referência								 ##
	 ## $caragaHoraria = Carga horária total                         ##
	 ## $linkConvite = link para o diretório do convite              ##
	 ##################################################################
	 */

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $nomeCurso;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="shortcut icon" href="<?php echo $CFG->themewww .'/'. current_theme() ?>/favicon.ico" />

<STYLE type=text/css>
.Texto {padding: 8px 18px 8px 25px; font-size: 11px; color: #666666; line-height: 16px; FONT-FAMILY: Arial, Helvetica, sans-serif}
.InfoCurso {
	font-weight: bold; font-size: 14px; color: #818283; line-height: 26px; font-style: normal; font-family: Arial, Helvetica, sans-serif;vertical-align: top
}
a:link {font-weight: bold; color: #999999; text-decoration: none}
a:visited {color: #999999; text-decoration: none;}
a:hover {color: #999999; text-decoration: none;}
a:active {color: #999999; text-decoration: none;}
body {
	margin-top: 0px;
	background-color: #f9f9f9;
}
.CabRod {
	font-size: 10px;
	color: #666666;
	line-height: 16px;
	font-family: Arial, Helvetica, sans-serif;
}
.topicos {font-weight: bold; font-size: 11px; color: #0e4b62; line-height: 16px; font-family: Arial, Helvetica, sans-serif;}
.titulos {padding: 4px; font-size: 11px; color: #666666; line-height: 16px; font-family: Arial, Helvetica, sans-serif;}
.TxtCurso {
	font-size: 14px; color: #333333; line-height: 26px; font-style: normal; font-family: Arial, Helvetica, sans-serif; font-weight: bold;
}
.dataehora {
	padding-bottom:5px; padding-top:5px; line-height: 16px;
}
.style1 {font-size: 5px}
.valor1 {
	font-weight: bold; font-size: 20px; color: #666666; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
.valor2 {
	font-weight: bold; font-size: 22px; color: #0089E1; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
</STYLE>

<META content="MSHTML 6.00.2900.8124" name=GENERATOR>
</HEAD>

<body>
	<table cellSpacing=0 cellPadding=0 width=649 align=center border=0>
		<tr>
			<td colspan="2" class="CabRod" align="center">Caso não consiga visualizar as imagens abaixo,
                <?php 
					if ($preview) {
						echo '<a href="' . $linkConvite . 'index.php?preview=1&produtoid=' . $produtoid . '&folder=' . $folder .'">CLIQUE AQUI.</a>';
					}else{
						echo '<a href="' . $linkConvite . 'index.php?id=' . $id . '&folder=' . $folder .'">CLIQUE AQUI.</a>';
					}
                ?>    
			</td>
		</tr>
		<tr>
			<td width="200" rowspan="3" valign="top" bgcolor="#FFFFFF"><img
				src="<?php echo $linkConvite;?>images/lateral.gif" width="200" height="805" border="0" usemap="#Map2">
			</td>
			<td width="449" height="220" valign="top" bgcolor="#FFFFFF"><img
				src="<?php echo $linkConvite;?>images/topo.gif" width="450" height="190" border="0" usemap="#Map4"> <map name="Map4">
					<area shape="rect" coords="412,4,445,42"
						href="http://www.qisat.com.br/contato/contato.php" target="_blank"
						alt="MSN QiSat">
					<area shape="rect" coords="378,4,411,42"
						href="http://www.youtube.com/qisat/" target="_blank"
						alt="Canal QiSat no YouTube">
					<area shape="rect" coords="344,4,377,42"
						href="http://twitter.com/qisat/" target="_blank"
						alt="Twitter QiSat">
					<area shape="rect" coords="310,4,343,42"
						href="http://br.linkedin.com/in/qisat" target="_blank"
						alt="QiSat no LinkedIn">
					<area shape="rect" coords="274,4,309,42"
						href="http://www.facebook.com/qisat" target="_blank"
						alt="QiSat no Facebook">
				</map>


				<table width="407" align="center">
					<tr>
						<td class="InfoCurso" width="90">Cidade/UF:</td>
						<td class="TxtCurso"><?php echo $cidade.'/'.$uf?></td>
					</tr>
					<tr>
						<td class="InfoCurso">Data e Hora:</td>
						<td class="TxtCurso"><?php echo $data?></td>
					</tr>					
					<tr>
						<td class="InfoCurso">Endereço:</td>
						<td class="TxtCurso"><?php echo $endereco;?></td>
					
					</tr>
					<tr>
						<td class="InfoCurso">Ministrante:</td>
						<td class="TxtCurso">Eng. <?php echo $ministrante;?></td>
					</tr>
				</table>
		
		
		<tr>
			<td class="Texto" bgcolor="#FFFFFF" style="text-align:justify">
					Destinado aos profissionais que buscam aprofundar seus conhecimentos sobre os critérios necessários para desenvolvimento de projetos hidráulicos preventivos de incêndio e ter contato com técnicas que visam repassar, através de experiência prática, os procedimentos aplicados nas etapas de dimensionamento desses projetos, para assim agregar maior dinamismo e eficiência na rotina de elaboração dos mesmos visando a produção de projetos preventivos de incêndios mais competitivos do ponto de vista técnico e comercial.
			</TD>
		</tr>
		<tr>
			<td bgcolor="#FFFFFF">
				<table cellSpacing=0 cellPadding=0 align="center" border=0>
        <tr>
          <td class="titulos" width="160" valign="top"><img height=19 width="125" src="<?php echo $linkConvite;?>images/tit_topico.gif"/><br/><br/>
			  <span class="topicos">Carga de fogo</span><br/>
			  <span class="topicos">&#9679; </span>Classificação da carga de fogo pela IN 007;<br/>
			  <span class="topicos">&#9679; </span>Modelo de planilha para dimensionamento automático de carga de fogo;<br/>
			  <span class="topicos">&#9679; </span>Enquadrando a edificação em uma classe de fogo.<br/><br/>
			  <span class="topicos">Sistema Hidráulico</span><br/>
			  <span class="topicos">&#9679; </span>Abrigos de mangueiras;<br/>
			  <span class="topicos">&#9679; </span>Mangueiras (tipos, uso, classe);<br/>
			  <span class="topicos">&#9679; </span>Esguichos (tipos, mais recomendado);<br/>
		      <span class="topicos">&#9679; </span>Colunas de incêndio.<br/>
	          <span class="topicos">&#9679; </span>Requisitos de instalação<br/><br/>
			  <span class="topicos">Parâmetros do Sistema</span><br/>
			  <span class="topicos">&#9679; </span>Composição dos sistemas sob comando<br/>
			  <span class="topicos">&#9679; </span>Reservatório elevado com sistema de bombeamento<br/>
		      <span class="topicos">&#9679; </span>Reservatório inferior com sistema de bombeamento<br/>
		      <span class="topicos">&#9679; </span>Castelo d’água<br/><br/>
            
          </td>
          <td width=11>&nbsp;</td>
          <td class="titulos" width="210" valign="top">
          	  <span class="topicos">Dimensionamento</span><br/>
			  <span class="topicos">&#9679; </span>Edificação multifamiliar com reservatório superior (sem bomba de reforço) de acordo com a IN 007 Corpo Militar do Estado de Santa Catarina;<br/>
		      <span class="topicos">&#9679; </span>Edificação industrial com reservatório inferior (com bomba de reforço) de acordo com a IN 007 Corpo Militar do Estado de Santa Catarina.<br/><br/>

            <img height="17" width="67" src="<?php echo $linkConvite;?>images/tit_form.gif" /><br/><br/>
			<span class="topicos">&#9679; </span>Aula expositiva.<br/>
			<span class="topicos">&#9679; </span>Carga Horária: 8h / aula.<br>
			<span class="topicos">&#9679; </span>Direcionamento de 15 a 18 profissionais.<br>
			<br>
			<br>
			<img height="17" width="85" src="<?php echo $linkConvite;?>images/tit_certificado.gif" /><br><br>
			No final do curso, os alunos que tiverem alcançado 75% de participação e aproveitamento do conteúdo receberão um certificado, que será enviado por correio, em um prazo máximo de 30 dias.<br><br><br>
			<img src="<?php echo $linkConvite;?>images/tit_invest.gif" width="97" height="16" border="0"/><br><br>
            <div align="right">
								<span class="valor1"><?php echo $moeda;?></span> 
								<span class="valor2"><?php echo $preco;?></span>
								<BR>
								<img width="95" height="32" border="0"
									src="<?php echo $linkConvite;?>images/bandeiras.gif"
									alt="Bandeiras Visa e Mastercard e Boleto Eletrônico" />
							</div><br><br></td></tr>
                            <TR>
          <td colspan="3" align="center"><a href="http://www.altoqi.com.br/software/projetos-hidraulicos/hydros-v4" target="_blank"><img width="204" height="35" border="0" src="<?php echo $linkConvite;?>images/saiba.gif" ></a><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=47" target="_blank"><img width="204" height="35" border="0"  src="<?php echo $linkConvite;?>images/inscrevase.gif" ></a></td>
        </TR>
				</TABLE></TD>
		</TR>
		<tr bgcolor="#FFFFFF">
			<td colSpan=2 height="101" width="650">
				<img height="101" width="650" border="0" src="<?php echo $linkConvite;?>images/rod.gif" usemap=#Map>
			</td>
		</TR>
		<TR>
			<td colspan="2" align="center" class="CabRod">
				Av. Osmar Cunha, 183, Edifício Ceisa Center, sala 301, Bloco C, Centro | Florianópolis - SC - Brasil</TD>
		</TR>
	</table>
</body>
</html>

<map id=Map2 name=Map2>
	<area shape=RECT target=_blank coords=10,11,190,114
		href="http://www.qisat.com.br/ecommerce/produtos/category.php?id=10">
</map>

<map id=Map name=Map>
	<area shape="RECT" target="_blank" coords="14,24,88,86"
		href="http://www.mntecnologia.com.br/">
	<area shape=RECT target=_blank coords="259,31,397,50"
		href="http://www.qisat.com.br">
	<area shape=RECT coords="282,51,368,70"
		href="mailto:qisat@qisat.com.br">
	<area shape=RECT target=_blank coords="559,19,633,81"
		href="http://www.altoqi.com.br">
</map>
