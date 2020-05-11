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
<?php 
	include ('../../dadosConvite.php');/*Arquivo com os dados do curso. Não remover*/	
	
	##################### Vari�veis com os dados s####################
	## $nomeCurso = Nome do curso									##
	## $preco = Valor integral do curso							 	##
	## $moeda = Moeda de refer�ncia								 	##
	## $dataInicioPromocao = Data de in�cio da promo��o 			##
 	## $dataFimPromocao = Data do fim da promo��o					##
 	## $descontoValor = Valor do desconto em $						##
 	## $descontoPorcentagem = Valor do desconto em %                ##
 	## $desconto = Valor do desconto em $							##
 	## $precoPromocional = Pre�o promocional						##
 	## $nuMaxParcelas = Numero m�ximo de parcelas					##
	##################################################################
	 
	//echo $nomeCurso . '<br>';
	//echo $preco . '<br>';
	//echo $moeda . '<br>';
	//echo $dataInicioPromocao . '<br>';
 	//echo $dataFimPromocao . '<br>';
 	//echo $descontoValor . '<br>';
 	//echo $descontoPorcentagem . '<br>';
 	//echo $desconto . '<br>';
 	//echo $precoPromocional . '<br>';
 	//echo $nuMaxParcelas . '<br>';
		
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
	font-weight: bold; font-size: 11px; color: #85623d; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
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
	font-weight: bold; font-size: 22px; color: #85623d; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
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
                ?></td>
		</tr>
		<tr>
			<td width="200" rowspan="3" valign="top" bgcolor="#FFFFFF"><img
				src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/6/images/lateral.gif" alt="QiTec Cursos e Palestras Presenciais"
				width="200" height="767" border="0" usemap="#Map2"></td>
			<td width="449" height="165" valign="top" bgcolor="#FFFFFF"><img
				src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/6/images/topo.gif" alt="QiTec Cursos e Palestras Presenciais"
				width="450" height="186" border="0" usemap="#Map4">
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
			<td class="Texto" bgcolor="#FFFFFF"; style="text-align:justify">
					O curso tem como objetivo incentivar a análise de várias soluções estruturais, visando adotar a mais adequada e econômica. Além disso, propicia o desenvolvimento e apresentação de projetos mais competitivos no mercado, adequando-os à esta nova realidade.</TD>
		</tr>
		<tr>
          <td valign="top" bgcolor="#FFFFFF" class="titulos" style="text-align:justify; padding: 8px 18px 8px 25px;"><img height=19 width="125" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/6/images/tit_topico.gif" />
			<br /><br />
			<span style="font-weight: bold; font-size: 11px; color: #85623d; line-height: 16px; font-family: Arial, Helvetica, sans-serif; ">Idéias Iniciais</span><br>
            <span class="topicos">&#9679;</span>Projeto estrutural: definições, objetivos, filosofia, histórico, 
            contexto atual, perfil dos profissionais, etc...<br />
            <span class="topicos">&#9679;</span>Tipos de estruturas e seus requisitos para elaboração do 
            projeto.<br />
            <span class="topicos">&#9679;</span>Material técnico e exigências para elaboração de cada tipo de 
            estrutura.<br />
            <span class="topicos">&#9679;</span>Conteúdo do projeto estrutural.<br />
            <span class="topicos">&#9679;</span>Noções de marketing na contratação do projeto estrutural.<br />
            <span class="topicos">&#9679;</span>Aspectos de contratação e etapas de desenvolvimento do 
            projeto.<br />
            <span class="topicos">&#9679;</span>Integração com os demais projetos complementares.<br /><BR>
			<span style="font-weight: bold; font-size: 11px; color: #85623d; line-height: 16px; font-family: Arial, Helvetica, sans-serif;">Estudos de concepção e lançamento de estruturas (estudos de caso)</span><BR>
            <span class="topicos">&#9679;</span>Como iniciar um estudo de lançamento de estrutura.<br />
            <span class="topicos">&#9679;</span> Alternativas de estruturas utilizando os sistemas construtivos adequados para o projeto em questão.<br />
            <span class="topicos">&#9679;</span>Propostas de alternativas estruturais que considerem a variação no número de pilares, na altura das vigas, espessura e tipos de lajes e tipos de fundações.<br />
            <span class="topicos">&#9679;</span>Análise comparativa de consumo de materiais.<br />
            <span class="topicos">&#9679;</span>Elementos componentes do projeto básico anteprojeto).<br /><br>
            <span style="font-weight: bold; font-size: 11px; color: #85623d; line-height: 16px; font-family: Arial, Helvetica, sans-serif;">Desenvolvimento do projeto executivo</span><br>
            <span class="topicos">&#9679;</span>Informações indispensáveis e detalhes complementares nas plantas de forma.<br />
            <span class="topicos">&#9679;</span>Detalhamentos das formas e armaduras à otimizações adicionais a serem incorporadas ao detalhamento automático.<br />
            <span class="topicos">&#9679;</span>Especificações técnicas dos materiais e processos executivos.</span><br>
            <span class="topicos">&#9679;</span>Critérios de projetos.<br />
            <span class="topicos">&#9679;</span>Manual de inspeção e manutenção preventiva.<br /><br>
			<img height="17" width="67" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/6/images/tit_form.gif" /><br />
            <br />
			<span class="topicos">&#9679;</span>Aula expositiva.<br />
			<span class="topicos">&#9679;</span>Carga Horária: 18h / aula.<br />
			<span class="topicos">&#9679;</span>Direcionamento de 15 a 30 profissionais<br />
			<BR>
			<BR>
			<img height="17" width="85" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/6/images/tit_certificado.gif" />
			<BR><BR>
            Com 75% de participação você recebe o certificado 
            do curso até 30 dias após a data da edição. <BR>
            <BR>
			<img height="16" width="97" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/6/images/tit_invest.gif" />
			<BR><BR>
            <div align="right">
								<?php
    		if($precoPromocional != $preco){
    			echo 'de <span style="font-size:16px; text-decoration: line-through;">' . $moeda . ' ' . $preco . '</span><br>';
    			echo 'por <span style="font-size:18px;color:#85623d">' . $moeda . ' <span style="font-size:26px;color:#85623d"><strong>' . $precoPromocional . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">até '. $dataFimPromocao .'</span>';
    		}else{
    			echo $moeda . ' <span style="font-size:26px;color:#85623d"><strong>' . $preco . '</strong></span>';	
    		}     	
    	?>
								<BR>
								<img width="132" height="32" border="0"
									src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/6/images/bandeiras.gif"
									alt="Bandeiras Visa e Mastercard e Boleto Eletrônico" /></div></span></TD>
      </TR>
          <TR>
          <td colspan="3" align="center" bgcolor="#FFFFFF" style="padding-left:200px"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=46" target="_blank"><img width="204" height="35" border="0" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/6/images/saibamais.gif" ></a><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=46" target="_blank"><img width="204" height="35" border="0"  src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/6/images/inscrevase.gif" ></a></td>
      </TR>
		<tr bgcolor="#FFFFFF">
			<td colSpan=2 height="101" width="650">
				<img height="101" width="650" border="0" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/6/images/rod.gif" usemap=#Map></td>
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
	<area shape=RECT coords="282,51,368,70"
		href="mailto:qisat@qisat.com.br">
	<area shape=RECT target=_blank coords="559,19,633,81"
		href="http://www.altoqi.com.br">
</map>
 <map name="Map4">
					<area shape="rect" coords="275,4,310,38"
						href="http://www.facebook.com/qisat" target="_blank"
						alt="QiSat no Facebook">
					<area shape="rect" coords="311,5,344,39"
						href="http://br.linkedin.com/in/qisat" target="_blank"
						alt="QiSat no LinkedIn">
					<area shape="rect" coords="343,4,376,38"
						href="http://twitter.com/qisat/" target="_blank"
						alt="Twitter QiSat">
					<area shape="rect" coords="378,4,411,38"
						href="http://www.youtube.com/qisat/" target="_blank"
						alt="Canal QiSat no YouTube">
					<area shape="rect" coords="412,4,445,38"
						href="http://www.qisat.com.br/contato/contato.php" target="_blank"
						alt="MSN QiSat">
				</map>