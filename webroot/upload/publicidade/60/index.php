<?php 
	require_once('../../../../../config.php');	
	include ('../../dadosConvite.php');/*Arquivo com os dados do curso. Não remover*/				
	global $CFG;
	
	$info_curso_presencial = $_GET['id'];
	$preview = $_GET['preview'];
	$produtoid = $_GET['produtoid'];
	$folder = $_GET['folder'];
	
	if(isset($info_curso_presencial)){
		$info_curso_presencial = '&info_curso_presencial=' . $info_curso_presencial;
	}else{
		$info_curso_presencial = '';
	}
		
	if($preview){
		$uf = 'UF';
	 	$cidade = 'Cidade';
	 	$data = ' Datas e horarios do curso';
	 	$endereco = 'Endereço contendo rua, bairro e referência';
	 	$ministrante = 'Nome e sobrenome do ministrante';
	 	$caragaHoraria = 'Carga horária total';
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
	 ## $dataInicioPromocao = Data de início da promoção 			 ##
 	 ## $dataFimPromocao = Data do fim da promoção					 ##
 	 ## $descontoValor = Valor do desconto em $						 ##
 	 ## $descontoPorcentagem = Valor do desconto em %                ##
 	 ## $desconto = Valor do desconto em $							 ##
 	 ## $precoPromocional = Preço promocional						 ##
 	 ## $nuMaxParcelas = Numero máximo de parcelas					 ##
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
.Texto {
	padding: 8px 18px 8px 25px; font-size: 11px; color: #666666; line-height: 16px; FONT-FAMILY: Arial, Helvetica, sans-serif
}
.InfoCurso {
	font-weight: bold; font-size: 14px; color: #818283; line-height: 26px; font-style: normal; font-family: Arial, Helvetica, sans-serif;vertical-align: top
}
a:link {
	font-weight: bold; color: #999999; text-decoration: none
}
a:visited {
	color: #999999; text-decoration: none;
}
a:hover {
	color: #999999; text-decoration: none;
}
a:active {
	color: #999999; text-decoration: none;
}
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
.topicos {
	font-weight: bold; font-size: 11px; color: #0D5AA3; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
.titulos {
	padding: 4px; font-size: 11px; color: #666666; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
.TxtCurso {
	font-size: 14px; color: #333333; line-height: 26px; font-style: normal; font-family: Arial, Helvetica, sans-serif; font-weight: bold;
}
.dataehora {
	padding-bottom:5px; padding-top:5px; line-height: 16px;
}
.valor1 {
	font-weight: bold; font-size: 20px; color: #666666; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
.valor2 {
	font-weight: bold; font-size: 22px; color: #ff5a00; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
</STYLE>

<META content="MSHTML 6.00.2900.8124" name=GENERATOR>
</HEAD>

<body>
	<table cellSpacing=0 cellPadding=0 width=649 align=center border=0>
		<tr>
			<td colspan="2" class="CabRod" align="center">Caso não consiga visualizar as imagens abaixo, <a href="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/index.php?preview=1&produtoid=178&folder=60" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</td>
		</tr>
		<tr>
			<td width="200" rowspan="3" valign="top" bgcolor="#FFFFFF"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/images/lateral.png" alt="QiTec Cursos e Palestras Presenciais"
				width="200" height="839" border="0" usemap="#Map2"></td>
			<td width="449" height="220" valign="top" bgcolor="#FFFFFF"><img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/images/topo.gif" alt="QiTec Cursos e Palestras Presenciais"
				width="450" border="0" usemap="#Map4"> 
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
						<td class="InfoCurso"><em>Software</em>:</td>
						<td class="TxtCurso">QiElétrico</td>
					</tr>
					<tr>
						<td class="InfoCurso">Ministrante:</td>
						<td class="TxtCurso">Eng. <?php echo $ministrante;?></td>
					</tr>
				</table>
		
		
		<tr>
			<td class="Texto" bgcolor="#FFFFFF" style="text-align:justify">
		  O curso tem como objetivo apresentar as características da nova plataforma QiBuilder, bem como sua
família de produtos, e os recursos do QiIncêndio, software para desenvolvimento de projetos preventivos de incêndio. Explicar as ferramentas para lançamento e desenvolvimento do projeto e os diversos recursos para otimizar o tempo de realização do mesmo.</TD>
		</tr>
		<tr>
			<td bgcolor="#FFFFFF">
				<table cellSpacing=0 cellPadding=0  align="center" border=0>
        <tr>
          <td class="titulos" width="160" valign="top"><img height=19 width="125" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/images/tit_topico.gif" />
			<br /><br />
            <span class="topicos">&#9679; </span>Ferramentas Básicas de CAD<br />
			<span class="topicos">&#9679; </span>Configurando e criando um projeto novo<br />
			<span class="topicos">&#9679; </span>Lançamento dos componentes do projeto preventivo<br />
			<span class="topicos">&#9679; </span>Lançamento e dimensionamento da rede de hidrantes por gravidade<br />
			<span class="topicos">&#9679; </span>Dimensionamento da bomba hidráulica para complemento das pressões na rede de hidrantes<br />
			<span class="topicos">&#9679; </span>Lançamento e dimensionamento da rede de sprinkler aplicando bomba hidráulica<br />
			<span class="topicos">&#9679; </span>Geração das planilhas e diagramas de pressões<br />
			<span class="topicos">&#9679; </span>Geração de desenhos complementares tais como, cortes, esquemas
isométricos entre outros<br />
            <span class="topicos">&#9679; </span>Geração de pranchas<br /></TD>
          <TD width=11>&nbsp;</TD>
          <TD class="titulos" width="200" valign="top">
            <img height="17" width="67" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/images/tit_form.gif" />
			<br /><br />
			<span class="topicos">&#9679; </span>Aula prática em computador.<br />
			<span class="topicos">&#9679; </span>Carga Horária: 16h / aula.<br />
			<span class="topicos">&#9679; </span>Direcionamento de 15 a 18 profissionais.</li>
            <BR><BR>
			<img height="17" width="85" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/images/tit_certificado.gif" />
			<BR><BR>
            No final do curso, os alunos que tiverem alcançado 75% de participação e aproveitamento do conteúdo receberão um certificado, que será enviado por correio, em um prazo máximo de 30 dias.<BR>
            <BR>
			<img height="16" width="97" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/images/tit_invest.gif" />
			<BR><BR>
            <div align="right">
				<?php
    		if($precoPromocional != $preco){
    			echo 'de <span style="font-size:16px; text-decoration: line-through;">' . $moeda . ' ' . $preco . '</span><br>';
    			echo 'por ' . $moeda . ' <span style="font-size:26px; color:#0D5AA3"><strong>' . $precoPromocional . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $dataFimPromocao .'</span>';
    		}else{
    			echo $moeda . ' <span style="font-size:26px; color:#0D5AA3"><strong>' . $preco . '</strong></span>';	
    		}     	
    	?>  				
				<BR>
			<img border="0" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/images/bandeiras.gif" alt="Bandeiras Visa e Mastercard e Boleto Eletrônico" /></div></TD></TR>
        <TR>
          <td colspan="3" align="center"><a href="http://www.altoqi.com.br/software/qibuilder/projetos-de-prevencao-e-combate-a-incendio/qiincendio" target="_blank"><img width="204" height="35" border="0" alt="Conheça o Software" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/images/inscrevase.gif" useMap=#Map3></a><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=178" target="_blank"><img width="204" height="35" border="0" alt="Conheça o Software" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/images/conheca.gif" useMap=#Map3></a></td>
        </TR>
				</TABLE></TD>
		</TR>
		<tr bgcolor="#FFFFFF">
			<td colSpan=2 height="101" width="650">
				<img height="101" width="650" border="0" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/60/images/rod.gif" usemap=#Map></td>
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
	<area shape="RECT" target="_blank" coords="13,23,87,85"
		href="http://www.mntecnologia.com.br/">
	<area shape=RECT target=_blank coords="259,31,397,50"
		href="http://www.qisat.com.br">
	<area shape=RECT coords="284,51,370,70"
		href="mailto:qisat@qisat.com.br">
	<area shape=RECT target=_blank coords="559,19,633,81"
		href="http://www.altoqi.com.br">
</map>
<map name="Map4">
					<area shape="rect" coords="274,4,309,38"
						href="http://www.facebook.com/qisat" target="_blank"
						alt="QiSat no Facebook">
					<area shape="rect" coords="310,4,343,38"
						href="http://br.linkedin.com/in/qisat" target="_blank"
						alt="QiSat no LinkedIn">
					<area shape="rect" coords="344,4,377,38"
						href="http://twitter.com/qisat/" target="_blank"
						alt="Twitter QiSat">
					<area shape="rect" coords="378,4,411,38"
						href="http://www.youtube.com/qisat/" target="_blank"
						alt="Canal QiSat no YouTube">
					<area shape="rect" coords="413,4,446,38"
						href="http://www.qisat.com.br/contato/contato.php" target="_blank"
						alt="MSN QiSat">
				</map>