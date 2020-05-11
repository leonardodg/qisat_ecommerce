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
 
	include ('../../dadosConvite.php');/*Arquivo com os dados do curso. Não remover*/	
	
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
.topicos {font-weight: bold; font-size: 12px; color: #0e4b62; line-height: 16px; font-family: Arial, Helvetica, sans-serif;}
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
	font-weight: bold; font-size: 22px; color: #0e4b62; line-height: 16px; font-family: Arial, Helvetica, sans-serif;
}
</STYLE>

<META content="MSHTML 6.00.2900.8124" name=GENERATOR>
</HEAD>

<body>
	<table cellSpacing=0 cellPadding=0 width=649 align=center border=0>
		<tr>
			<td colspan="2" class="CabRod" align="center">Caso não consiga visualizar as imagens abaixo, <a href="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/index.php?preview=1&produtoid=177&folder=59" target="_blank" style="color:#015DA2;">CLIQUE AQUI</a>.</td>
		</tr>
		<tr>
			<td width="200" rowspan="3" valign="top" bgcolor="#FFFFFF"><img
				src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/images/lateral.gif" width="200" height="805" border="0" usemap="#Map2"></td>
			<td width="449" height="220" valign="top" bgcolor="#FFFFFF"><img
				src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/images/topo.gif" width="450" height="190" border="0" usemap="#Map4"><table width="407" align="center">
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
				  <p><b>Público Alvo:</b> Destinado aos profissionais que elaboram ou pretendem elaborar projetos de edificações para o estado de SC e buscam aprofundar seus conhecimentos sobre os critérios necessários para desenvolvimento de projetos hidráulicos preventivos de incêndio e ter contato com técnicas que visam repassar, através de experiência prática, os procedimentos aplicados nas etapas de dimensionamento desses projetos, para assim agregar maior dinamismo e eficiência na rotina de elaboração dos mesmos visando a produção de projetos preventivos de incêndio mais competitivos do ponto de vista técnico e comercial.</p>
				  <p><b>Objetivos do curso:</b> Este curso tem como objetivo apresentar os conceitos técnicos e os critérios estipulados pela Instrução Normativa 007 do Corpo de Bombeiro de Santa Catarina (IN 007/DAT/CBMSC) e demais normas vigentes para elaboração de projetos hidráulicos de prevenção de incêndio, abordando conceitos gerais sobre os processos e aprofundando nos quesitos relacionados ao dimensionamento, buscando apresentar técnicas que visam agregar maior dinamismo e eficiência técnica ao processo.</p></TD>
		</tr>
        
		<tr>
			<td bgcolor="#FFFFFF">
				<table cellSpacing=0 cellPadding=0 align="center" border=0 style="text-align:justify">
        <tr>
          <td class="titulos" width="401" valign="top"><img height=19 width="125" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/images/tit_topico.gif"/><br/><br/>
			  <span class="topicos">Carga de fogo</span><br/>
		 <ul>
              <li> Classificação da carga de fogo pela IN 007;</li>
			  <li> Modelo de planilha para dimensionamento automático de carga de fogo;</li>
			  <li> Enquadrando a edificação em uma classe de fogo.</li>
         </ul>
			  <span class="topicos">Sistema Hidráulico</span>
		  <ul>
              <li> Abrigos de mangueiras;</li>
			  <li> Mangueiras (tipos, uso, classe);</li>
			  <li> Esguichos (tipos, mais recomendado);</li>
		      <li> Colunas de incêndio;</li>
	          <li> Requisitos de instalação</li>
          </ul>
			  <span class="topicos">Parâmetros do Sistema</span>
		  <ul>	  
              <li> Composição dos sistemas sob comando</li>
			  <li> Reservatório elevado com sistema de bombeamento</li>
		      <li> Reservatório inferior com sistema de bombeamento</li>
		      <li> Castelo d’água</li>
           </ul>   
            
<span class="topicos">Dimensionamento</span>
			<ul>
			  <li> Edificação multifamiliar com reservatório superior (sem bomba de reforço) de acordo com a IN 007 Corpo Militar do Estado de Santa Catarina;</li>
		      <li> Edificação industrial com reservatório inferior (com bomba de reforço) de acordo com a IN 007 Corpo Militar do Estado de Santa Catarina.</li>
			</ul>
            <img height="17" width="67" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/images/tit_form.gif" /><br/><br/>
            <ul>
			<li> Aula expositiva.</li>
			<li> Carga Horária: 8h / aula.</li>
			<li> Direcionamento de 15 a 18 profissionais.</li>
            </ul>
			<img height="17" width="85" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/images/tit_certificado.gif" /><br><br>
			No final do curso, os alunos que tiverem alcançado 75% de participação e aproveitamento do conteúdo receberão um certificado, que será enviado por correio, em um prazo máximo de 30 dias.<br><br><br>
			<img src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/images/tit_invest.gif" width="97" height="16" border="0"/><br><br>
            <div align="right">
				<?php
    		if($precoPromocional != $preco){
    			echo 'de <span style="font-size:16px; text-decoration: line-through;">' . $moeda . ' ' . $preco . '</span><br>';
    			echo 'por ' . $moeda . ' <span style="font-size:26px;"><strong>' . $precoPromocional . '</strong></span><br>';
    			echo '<span style="font-size:12px;color:#999999;">at&eacute; '. $dataFimPromocao .'</span>';
    		}else{
    			echo $moeda . ' <span style="font-size:26px;"><strong>' . $preco . '</strong></span>';	
    		}     	
    	?>  				
				<BR>
			<img width="101" height="32" border="0" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/images/bandeiras.gif" alt="Bandeiras Visa e Mastercard e Boleto Eletrônico" /></div><br></td>
          </tr>
                            <TR>
          <td align="center"><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=177" target="_blank"><img width="204" height="35" border="0" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/images/saiba.gif" ></a><a href="http://www.qisat.com.br/ecommerce/produtos/info.php?id=177" target="_blank"><img width="204" height="35" border="0"  src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/images/inscrevase.gif" ></a></td>
        </TR>
				</TABLE></TD>
		</TR>
		<tr bgcolor="#FFFFFF">
			<td colSpan=2 height="101" width="650">
			  <img height="101" width="650" border="0" src="http://www.qisat.com.br/ecommerce/produtos/convites/arquivos/59/images/rod.gif" usemap=#Map></td>
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
	<area shape=RECT target=_blank coords="541,36,631,81"
		href="http://www.altoqi.com.br">
</map>
<map name="Map4">
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