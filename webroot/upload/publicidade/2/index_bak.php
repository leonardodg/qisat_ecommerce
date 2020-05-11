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
		$linkConvite = $CFG->wwwroot.'/ecommerce/produtos/convites/arquivos/'.$folder.'/';
		
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

<link rel="shortcut icon" href="<?php echo $CFG->themewww .'file:///S|/'. current_theme() ?>/favicon.ico" />

<STYLE type=text/css>
.Texto {
	padding: 8px 18px 8px 25px; font-size: 11px; color: #666666; line-height: 16px; FONT-FAMILY: Arial, Helvetica, sans-serif
}
.InfoCurso {
	font-weight: bold; font-size: 14px; color: #818283; line-height: 20px; font-style: normal; font-family: Arial, Helvetica, sans-serif;vertical-align: top
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
	background-color: #000000;
}
.CabRod {
	font-size: 10px; color: #ffffff; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
.topicos {
	font-weight: bold; font-size: 11px; color: #2b1906; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
.valor1 {
	font-weight: bold; font-size: 20px; color: #666666; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
.valor2 {
	font-weight: bold; font-size: 22px; color: #2b1906; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
.titulos {
	padding: 4px; font-size: 11px; color: #666666; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
.TxtCurso {
	font-size: 14px; color: #333333; line-height: 20px; font-style: normal; font-family: Arial, Helvetica, sans-serif; font-weight: bold;
}
.dataehora {
	padding-bottom:3px; padding-top:2px; line-height: 16px;
}
</STYLE>

<META content="MSHTML 6.00.2900.8124" name=GENERATOR>
</HEAD>

<body>
	<table cellSpacing=0 cellPadding=0 width=650 align=center border=0>
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
			<td width="449" height="220" valign="top" bgcolor="#FFFFFF"><img
				src="<?php echo $linkConvite;?>images/topo.gif" alt="QiTec Cursos e Palestras Presenciais"
				width="450" height="169" border="0" usemap="#Map4"> <map name="Map4">
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
						<td class="InfoCurso"><em>Software</em>:</td>
						<td class="TxtCurso">Eberick V8</td>
					</tr>
					<tr>
						<td class="InfoCurso">Ministrante:</td>
						<td class="TxtCurso">Eng. <?php echo $ministrante;?></td>
					</tr>
				</table>
		
		
		<tr>
			<td class="Texto" bgcolor="#FFFFFF">
					O curso tem como objetivo proporcionar conhecimento sobre os principais recursos do AltoQi Eberick, software para elaboração de projetos estruturais em concreto armado, aos profissionais e estudantes de engenharia, arquitetura e técnicos de áreas relacionadas que atuam ou pretendem atuar na elaboração de projetos estruturais.
			</TD>
		</tr>
		<tr>
			<td bgcolor="#FFFFFF">
				<table cellSpacing=0 cellPadding=0 align="center" border=0>
        <tr>
          <td class="titulos" width="200" valign="top"><img height=19 width="125" src="<?php echo $linkConvite;?>images/tit_topico.gif" /><br /><br />
			<span class="topicos">AltoQi Eberick</span>
			<li>Importação de plantas via formato DXF</li>
            <li>Ferramentas básicas de CAD</li>
			<li>Lançamento gráfico da estrutura</li>
			<li>Configurações</li>
			<li>Processamento e análise dos esforços e deslocamentos na estrutura</li> 
			<li>Dimensionamento e detalhamento das peças</li> 
			<li>Geração de pranchas de formas e detalhamentos</li>
			<li>O editor de armaduras do Eberick</li><br><br>
			<span class="topicos">Módulo Formas</span>
			<li>Detalhamento das Formas</li>
			<li>Cortes na Estrutura</li>
			<li>Biblioteca de Símbolos</li>
			<li> Plantas de Locação</li><br><br>
			<span class="topicos">Módulo Escadas</span>
			<li>Lançamento gráfico das escadas</li>
			<li>Análise dos esforços e dimensionamento dos lances e patamares</li>
	  	  	<li>Otimização da armadura e detalhamento final</li><br><br>
            <span class="topicos">Módulo Fundações</span>
			<li>Lançamento gráfico das fundações</li>
			<li>Análise dos esforços e dimensionamento das sapatas</li>
	  	  	<li>Análise dos esforços e dimensionamento dos blocos sobre estaca e tubulões</li>       
	  	    <li>Otimização da armadura e detalhamento final</li><br></td>
          <td width=11></td>
          <td width="200" valign="top" class="titulos"><br>
            <br>
			<span class="topicos">Módulo Reservatórios</span><br>
			<li>Lançamento gráfico do reservatório</li> 
			<li>Análise dos esforços e dimensionamento das lajes e paredes</li>
			<li>Otimização da armadura e detalhamento final</li><br><br>
			<span class="topicos">Tópicos Especiais</span>
			<li>Lançamento de vigas curvas</li>
			<li>Vigas de equilíbrio</li>
			<li>Verificando e corrigindo problemas de alinhamento
			<li>Fundações associadas</li><br><br>
		    <img height="17" width="67" src="<?php echo $linkConvite;?>images/tit_form.gif" /><br /><br />
			<li>Aula prática em computador</li>
			<li>Carga Horária de 24 horas (variável em função do andamento da turma)</li>
			<li>Direcionamento: 15/18 profissionais</li>
			Número mínimo para 15 participantes. Caso o curso não obtenha a participação mínima de 15 inscritos, a empresa resguarda o direito de prorrogação do evento no período máximo de 90 dias.<br><br>
			<img height="17" width="85" src="<?php echo $linkConvite;?>images/tit_certificado.gif" /><br><br>
            No final do curso, os alunos que tiverem alcançado 75% de participação e aproveitamento do conteúdo receberão um certificado, que será enviado por correio, em um prazo máximo de 30 dias.<br><br>
			<img height="16" width="97" src="<?php echo $linkConvite;?>images/tit_invest.gif" /><br><br>
            <div align="right">
				<span class="valor1"><?php echo $moeda;?></span> 
				<span class="valor2"><?php echo $preco;?></span>
				<BR>
				<img width="95" height="32" border="0" src="<?php echo $linkConvite;?>images/bandeiras.gif" alt="Bandeiras Visa e Mastercard e Boleto Eletrônico" />
			</div></td>
        </tr>
        <tr>
          <td colspan="3" align="center"><img width="407" height="37" border="0" src="<?php echo $linkConvite;?>images/bot.gif" usemap=#Map3></td>
        </tr>
				</TABLE></TD>
		</TR>
		<tr bgcolor="#FFFFFF">
			<td colSpan=2 height="101" width="650">
				<img height="101" width="650" border="0" src="<?php echo $linkConvite;?>images/rod.gif" usemap=#Map>
			</td>
		</TR>
		<TR>
			<td colspan="2" align="center" class="CabRod">
				Av. Osmar Cunha, 185, Edifício Ceisa Center, sala 301, Bloco C, Centro | Florianópolis - SC - Brasil
			</TD>
		</TR>
	</table>
</body>
</html>

<MAP id=Map3 name=Map3>
	<AREA shape=RECT target=_blank coords=226,5,388,28
		href="<?php echo $linkCurso;?>">
	<AREA shape=RECT target=_blank coords=11,10,189,29
		href="http://www.altoqi.com.br/software/projeto-estrutural/eberick-v8">
</MAP>
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
