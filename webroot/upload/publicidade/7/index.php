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
	font-weight: bold; font-size: 11px; color: #ff8700; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
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
	font-weight: bold; font-size: 22px; color: #ff8700; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
.valorantigo {
	font-size:16px; text-decoration: line-through;
}
.datafimpromocao {
	font-size:12px;color:#999999;
}
.valores {
	line-height: 1.7;
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
				src="<?php echo $linkConvite;?>images/lateral.gif" alt="QiTec Cursos e Palestras Presenciais"
				width="200" height="767" border="0" usemap="#Map2">
			</td>
			<td width="449" height="165" valign="top" bgcolor="#FFFFFF"><img
				src="<?php echo $linkConvite;?>images/topo.gif" alt="QiTec Cursos e Palestras Presenciais"
				width="450" height="175" border="0" usemap="#Map4"> <map name="Map4">
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
					<area shape="rect" coords="412,4,445,38"
						href="http://www.qisat.com.br/contato/contato.php" target="_blank"
						alt="MSN QiSat">
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
			<td class="Texto" bgcolor="#FFFFFF">
					O curso Técnico Eberick - Conceitos, Análises  e Aplicações tem como objetivo o estudo dos conceitos fundamentais da análise estrutural para projetos de edifícios e a correlação dos principais recursos do AltoQi Eberick com as prescrições da NBR 6118:2014.
			</TD>
		</tr>
		<tr>
			<td bgcolor="#FFFFFF">
				<table cellSpacing="0" cellPadding="0" align="center" border="0">
        <tr>
          <td class="titulos" width="200" valign="top"><img height=19 width="125" src="<?php echo $linkConvite;?>images/tit_topico.gif" />
			<br /><br />
			<span class="topicos">Ligações entre os elementos da estrutura</span><br />
            <span class="topicos">&#9679;</span>Vínculos e graus de liberdade das estruturas reticuladas.<br />
            <span class="topicos">&#9679;</span>Ligações rígidas, semi-rígidas e flexíveis entre os elementos<br />
            <span class="topicos">&#9679;</span>Tipos de ligações entre elementos do Eberick<br />
            <span class="topicos">&#9679;</span>Influência dos vínculos e ligações no comportamento da estrutura<br /><br>
			<span class="topicos">Considerações sobre a análise de lajes de concreto armado</span><BR>
            <span class="topicos">&#9679;</span>Teorias fundamentais ao estudo das placas<br />
            <span class="topicos">&#9679;</span>Modelos de análise de lajes <br />
            <span class="topicos">&#9679;</span>Fatores intervenientes na distribuição dos esforços na laje<br />
            <span class="topicos">&#9679;</span>Alternativas de refinamento de resultados<br />
            <span class="topicos"><BR>Durabilidade das estruturas de concreto</span><br />
            <span class="topicos">&#9679;</span>Principais conceitos relacionados a Durabilidade e Vida Útil<br />
			<span class="topicos">&#9679;</span>Propriedades dos materiais<br />
			<span class="topicos">&#9679;</span>Configurações dos materiais no Eberick<br />
			<span class="topicos"><BR>Consideração da ação do vento em edifícios</span><br />
			<span class="topicos">&#9679;</span>Cálculo da força estática equivalente do vento;<br />
			<span class="topicos">&#9679;</span>Parâmetros de configuração e avaliação dos resultados obtidos no Eberick<br />
			<br><span class="topicos">Análise das estruturas reticuladas de edifícios</span><br>
			<span class="topicos">&#9679;</span>Resolução da estrutura através de análise por elementos isolados, pórtico espacial, pavimentos isolados e da estrutura integrada. Vantagens e limitações de cada método. <br />
			<span class="topicos">&#9679;</span>Comparação de resultados entre os modelos de pórtico espacial e pavimentos isolados<br />
			<span class="topicos">&#9679;</span>Estudo de caso: modelagem de um edifício e otimização dos resultados           </TD>
          <TD width="11">&nbsp;</TD>
          <TD width="200" height="100" valign="top" class="titulos">
		  <span class="topicos">Avaliação dos efeitos de 2ª ordem da estrutura</span><br>
            <span class="topicos">&#9679;</span>Conceitos fundamentais sobre a não-linearidade física e geométrica<br />
            <span class="topicos">&#9679;</span>Avaliação da estabilidade global pelos parâmetros Alfa, Gama-z e Processo P-Delta<br />
            <span class="topicos">&#9679;</span>Avaliação das Imperfeições Geométricas Globais<br />
            <BR><span class="topicos">Segurança das estruturas e combinações de ações</span><br>
            <span class="topicos">&#9679;</span>Introdução à segurança nas estruturas<br />
            <span class="topicos">&#9679;</span>Coeficientes de ponderação e Combinação de ações na estrutura<br />
            <span class="topicos">&#9679;</span>Critérios de consideração das ações no Eberick.<br />
            <span class="topicos">&#9679;</span>Aspectos Normativos e conseqüências para o projeto<br /><br>
            <span class="topicos">Verificação das estruturas ao ELS - deformações excessivas</span><br>
            <span class="topicos">&#9679;</span>Flechas elásticas, imediatas e diferidas. Aplicações no Eberick.<br /><br><br>
            <img height="17" width="67" src="<?php echo $linkConvite;?>images/tit_form.gif" />
			<br /><br />
			<span class="topicos">&#9679;</span>Aula expositiva.<br />
			<span class="topicos">&#9679;</span>Carga Horária: 18h / aula.<br />
			<span class="topicos">&#9679;</span>Direcionamento de 15 a 30 profissionais<br />
			<BR>
			<BR>
			<img height="17" width="85" src="<?php echo $linkConvite;?>images/tit_certificado.gif" />
			<BR><BR>
            Com 75% de participação você recebe o certificado 
            do curso até 30 dias após a data da edição. <BR>
            <BR>
			<img height="16" width="97" src="<?php echo $linkConvite;?>images/tit_invest.gif" />
			<BR><BR>
            <div align="right">
				<?php
					if($precoPromocional != $preco){
						echo '<span class="valores">';
						echo 'de <span class="valorantigo">' . $moeda . ' ' . $preco . '</span><br>';
						echo 'por <span class="valor1">' . $moeda . ' </span><span class="valor2"><strong>' . $precoPromocional . '</strong></span><br>';
						echo '<span class="datafimpromocao">at&eacute; '. $dataFimPromocao .'</span>';
						echo '<span>';
					}else{
						echo '<span class="valor1">' . $moeda . '</span>';
						echo '<span class="valor2">' . $preco . '</span>';
					}     	
				?>  
								<BR>
								<img width="95" height="32" border="0"
									src="<?php echo $linkConvite;?>images/bandeiras.gif"
									alt="Bandeiras Visa e Mastercard e Boleto Eletrônico" />
			</div>
          <br><br></TD></TR>
          <TR>
          <td colspan="3" align="center"><a href="http://www.altoqi.com.br/software/projeto-estrutural/eberick-v9" target="_blank"><img width="204" height="35" border="0" alt="Conheça o Software" src="<?php echo $linkConvite;?>images/conheca.gif" ></a><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=45<?php echo $info_curso_presencial;?>" target="_blank"><img width="204" height="35" border="0"  src="<?php echo $linkConvite;?>images/inscrevase.gif" ></a></td>
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
				Av. Osmar Cunha, 183, Edifício Ceisa Center, sala 301, Bloco C, Centro | Florianópolis - SC - Brasil
			</TD>
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
	<area shape=RECT coords="282,51,368,70"
		href="mailto:qisat@qisat.com.br">
	<area shape=RECT target=_blank coords="559,19,633,81"
		href="http://www.altoqi.com.br">
</map>
