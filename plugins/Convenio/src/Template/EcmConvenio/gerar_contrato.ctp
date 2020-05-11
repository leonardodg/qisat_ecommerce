<?php
    $this->layout = false;
    require_once (ROOT . DS . 'vendor' . DS . 'fpdf181' . DS . 'fpdf.php');

    $isEntidade = (strtolower ( $convenio->ecm_convenio_tipo_instituicao->descricao) == 'entidade de classe');
    $tipoInstituicao = utf8_decode ( $convenio->ecm_convenio_tipo_instituicao->descricao);

    $marginEsquerda = 50;
    $marginTopo = 100;
    $marginCentro = 180;

    $pdf = new FPDF ( "P", "pt", "A4" );
    $pdf->AddPage ();
    $pdf->Image ( \Cake\Routing\Router::url('/img/contrato/', true) . 'topo-contrato.jpg', 25, 10, 550, 67 );
    $pdf->Image ( \Cake\Routing\Router::url('/img/contrato/', true) . 'rodape-contrato.jpg', 25, 800, 550, 29 );

    $dia = date ( 'd', time () );

    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    $mes = strftime('%B', strtotime('today'));

    $ano = date ( 'Y', time () );

    $pdf->SetTitle ('Termo de Ades�o Projeto QiSat Rede Educacional On Line');

    $pdf->SetFont ( 'Arial', '', 10 );

    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 5, 5, 'Florian�polis, ' . $dia . ' de ' . $mes . ' de ' . $ano );

    $marginTopo += 20;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 5, 5, '� ' . utf8_decode ( $convenio->nome_instituicao) );

    /*$marginTopo += 20;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 5, 5, utf8_decode ( $convenio->mdl_cidade->nome . ' - ' . $convenio->mdl_cidade->mdl_estado->uf) );*/

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 30;
    $pdf->SetXY ( $marginCentro, $marginTopo );
    $pdf->Cell ( 5, 5, 'Termo de Ades�o Projeto QiSat Rede Educacional On Line' );

    $pdf->SetFont ( 'Arial', '', 10 );
    $marginTopo += 30;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 5, 5, 'Prezado(a) ' . utf8_decode ( $convenio->nome_responsavel) . ',' );

    $marginTopo += 30;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->SetLeftMargin ( 65 );
    $pdf->Cell ( 5, 5, 'O Projeto QiSat Rede Educacional On Line visa o interc�mbio de conhecimento entre a ' . $tipoInstituicao );
    $pdf->SetXY ( $marginEsquerda, $marginTopo + 8 );
    $pdf->MultiCell ( 498, 12, 'e o QiSat - Canal de E-learning da Engenharia, portal www.qisat.com.br, direcionado �s �reas da Engenharia Civil, El�trica, Sanit�ria e Arquitetura levando descontos especiais para os associados da entidade conveniada nas inscri��es de cursos de aperfei�oamento t�cnico do canal QiSat.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 80;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 20, 5, '1)' );
    $pdf->Cell ( 5, 5, 'Benef�cios propostos no per�odo de vig�ncia do Termo de Ades�o' );

    if ($isEntidade) {

        $pdf->SetFont ( 'Arial', 'B', 10 );
        $marginTopo += 30;
        $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
        $pdf->Cell ( 20, 5, 'A.' );
        $pdf->Cell ( 100, 5, 'Desconto Associado ' );

        $pdf->SetFont ( 'Arial', '', 10 );
        $pdf->Cell ( 20, 5, ' - Desconto de 20% no valor normal da inscri��o de curso QiSat,  ' );
        $marginTopo += 10;
        $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
        $pdf->MultiCell ( 430, 15, 'com parcelamento m�nimo de R$ 100,00 via cart�o de cr�dito, ao associado que comprovar no ato da compra, v�nculo profissional na ' . $tipoInstituicao . ' conveniada. Desconto n�o acumulativo, v�lido somente para compra atrav�s da Central de Inscri��es pelo telefone (48) 3332-5000, email: qisat@qisat.com.br.' );

        $pdf->SetFont ( 'Arial', 'B', 10 );
        $marginTopo += 70;
        $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
        $pdf->Cell ( 20, 5, 'B.' );
        $pdf->Cell ( 70, 5, 'Apoio Did�tico  ' );
        $pdf->SetFont ( 'Arial', '', 10 );
        $pdf->MultiCell ( 430, 5, ' - Uma inscri��o QiSat, sem �nus, em curso a dist�ncia dos softwares' );

        $marginTopo += 10;
        $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
        $pdf->MultiCell ( 430, 15, 'AltoQi para a entidade que adquirir software Eberick, Hydros, Lumine, QiCAD, Hydros Inc�ndio e/ou Lumine Cabeamento na vig�ncia do Termo de Ades�o ao Projeto QiSat Rede Educacional On Line.' );
    } else {

        $pdf->SetFont ( 'Arial', 'B', 10 );
        $marginTopo += 30;
        $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
        $pdf->Cell ( 20, 5, 'A.' );
        $pdf->Cell ( 100, 5, 'Desconto Professor' );

        $pdf->SetFont ( 'Arial', '', 10 );
        $pdf->Cell ( 20, 5, ' - Desconto de 50% no valor normal da inscri��o de curso QiSat, com ' );
        $marginTopo += 10;
        $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
        $pdf->MultiCell ( 430, 15, 'parcelamento m�nimo de R$ 100,00 via cart�o de cr�dito, ao professor que comprovar no ato da compra, v�nculo profissional na Institui��o de Ensino conveniada. Desconto n�o acumulativo, v�lido somente para compra atrav�s da Central de Inscri��es pelo telefone (48) 3332-5000, email: qisat@qisat.com.br.' );

        $pdf->SetFont ( 'Arial', 'B', 10 );
        $marginTopo += 70;
        $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
        $pdf->Cell ( 20, 5, 'B.' );
        $pdf->Cell ( 100, 5, 'Desconto Estudante' );
        $pdf->SetFont ( 'Arial', '', 10 );
        $pdf->MultiCell ( 430, 5, ' - Desconto de 30% no valor normal da inscri��o de curso QiSat, com' );

        $marginTopo += 10;
        $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
        $pdf->MultiCell ( 430, 15, 'parcelamento m�nimo de R$ 100,00 via cart�o de cr�dito, ao estudante que comprovar no ato da compra, matr�cula em cursos da Institui��o de Ensino conveniada. Desconto n�o acumulativo, v�lido somente para compra atrav�s da Central de Inscri��es pelo telefone (48) 3332-5000, email: qisat@qisat.com.br.' );

        $pdf->SetFont ( 'Arial', 'B', 10 );
        $marginTopo += 70;
        $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
        $pdf->Cell ( 20, 5, 'C.' );
        $pdf->Cell ( 80, 5, 'Apoio Did�tico' );
        $pdf->SetFont ( 'Arial', '', 10 );
        $pdf->MultiCell ( 430, 5, ' - Uma inscri��o QiSat, sem �nus, em curso a dist�ncia dos softwares AltoQi' );

        $marginTopo += 10;
        $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
        $pdf->MultiCell ( 430, 15, 'para a institui��o que adquirir software Eberick, Hydros, Lumine, QiCAD, Hydros Inc�ndio e/ou Lumine Cabeamento na vig�ncia do Termo de Adescart�o ao Projeto QiSat Rede Educacional On Line.' );
    }

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 60;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 20, 5, '2)' );
    $pdf->Cell ( 5, 5, 'Atribui��es' );

    $marginTopo += 20;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );

    $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
    $pdf->Cell ( 20, 5, 'Para que a alian�a se concretize a empresa estabelece as seguintes a��es:' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 30;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->Cell ( 20, 5, 'A.' );

    $pdf->Cell ( 70, 5, 'Da ' . $tipoInstituicao );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 25;
    $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
    $pdf->Cell ( 20, 5, 'I.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo - 5) );
    $pdf->SetFont ( 'Arial', '', 10 );

    $pdf->MultiCell ( 380, 15, 'Efetuar no site QiSat o cadastro da ' . $tipoInstituicao . '.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 15;
    $pdf->SetXY ( ($marginEsquerda + 37), $marginTopo );
    $pdf->Cell ( 20, 5, 'II.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo - 5) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Enviar para o email qisat@qisat.com.br o Termo de Ades�o devidamente assinado apresentando formalmente o nome e cargo da pessoa respons�vel pelo conv�nio e pela implementa��o das a��es junto � ' . $tipoInstituicao . ' e o formul�rio ANEXO I.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 45;
    $pdf->SetXY ( ($marginEsquerda + 34), $marginTopo );
    $pdf->Cell ( 20, 5, 'III.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo - 5) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Para justificar e viabilizar o conv�nio o ANEXO I dever� conter a listas dos associados adimplentes da ' . $tipoInstituicao . '.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 30;
    $pdf->SetXY ( ($marginEsquerda + 33), $marginTopo );
    $pdf->Cell ( 20, 5, 'IV.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo - 5) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Zelar pela aquisi��o dos direitos da ' . $tipoInstituicao . ' relativo aos benef�cios aprovados, mediante comunica��o eficiente e formal junto � Empresa, atrav�s da pessoa respons�vel pelo Projeto.' );

    if (! $isEntidade) {
        $pdf->AddPage ();
        $marginTopo = 70;
    }

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 45;
    $pdf->SetXY ( ($marginEsquerda + 36), $marginTopo );
    $pdf->Cell ( 20, 5, 'V.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo - 5) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Utilizar os Cursos QiSat como material did�tico de apoio junto a ' . $tipoInstituicao . ' conveniada.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 30;
    $pdf->SetXY ( ($marginEsquerda + 33), $marginTopo );
    $pdf->Cell ( 20, 5, 'VI.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo - 5) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Realizar a difus�o do conv�nio nos canais de comunica��o da ' . $tipoInstituicao . ', destacando os descontos previstos no Termo de Ades�o ao projeto.' );

    /* Segunda Pagina */
    if ($isEntidade) {
        $pdf->AddPage ();
        $marginTopo = 70;
    }
    $pdf->Image ( \Cake\Routing\Router::url('/img/contrato/', true) . 'topo-contrato.jpg', 25, 10, 550, 67 );
    $pdf->Image ( \Cake\Routing\Router::url('/img/contrato/', true) . 'rodape-contrato.jpg', 25, 800, 550, 29 );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 40;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->Cell ( 20, 5, 'B.' );
    $pdf->Cell ( 70, 5, 'Do QiSat' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 25;
    $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
    $pdf->Cell ( 20, 5, 'I.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo - 5) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Conceder os benef�cios previstos no Termo de Ades�o via Central de Inscri��es pelo telefone (48) 3332-5000, email: qisat@qisat.com.br, ap�s o recebimento do ANEXO I e do TERMO DE ADES�O assinado pelo respons�vel perante a ' . $tipoInstituicao . '.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 45;
    $pdf->SetXY ( ($marginEsquerda + 37), ($marginTopo + 15) );
    $pdf->Cell ( 20, 5, 'II.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo + 10) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Manter o Projeto QiSat Rede Educacional On Line dispon�vel no www.qisat.com.br identificando as ' . $tipoInstituicao . ' conveniadas.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 60;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 20, 5, '3)' );
    $pdf->Cell ( 5, 5, 'Prazo do Conv�nio' );

    $pdf->SetXY ( ($marginEsquerda + 40), ($marginTopo + 10) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 430, 15, 'Per�odo de vinte e quatro meses (dois anos) a partir da data da assinatura do Termo de Ades�o.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 60;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 20, 5, '4)' );
    $pdf->Cell ( 5, 5, 'Disposi��es Gerais' );

    $pdf->SetXY ( ($marginEsquerda + 40), ($marginTopo + 10) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 430, 15, 'Qualquer informa��o e orienta��o pertinente ao processo apresentado poder�o ser obtidas sempre atrav�s do e-mail qisat@qisat.com.br e pelo telefone (48) 3332-5000.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 60;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 20, 5, '5)' );
    $pdf->Cell ( 5, 5, 'Ades�o' );

    $marginTopo += 15;
    $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->Cell ( 20, 5, 'Nome do Respons�vel: ' . utf8_decode ( $convenio->nome_responsavel) );

    $marginTopo += 13;
    $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
    $pdf->Cell ( 5, 5, 'Cargo Ocupado: ' . utf8_decode ( $convenio->cargo) );

    $marginTopo += 13;
    $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
    $pdf->Cell ( 5, 5, 'E-mail: ' . $convenio->email);

    $marginTopo += 13;
    $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
    $pdf->Cell ( 5, 5, 'Telefone: ' . $convenio->telefone);

    $marginTopo += 20;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 450, 15, 'O QiSat agradece a especial aten��o e externa desde j� agradecimentos e cumprimentos pelo Conv�nio estabelecido.' );

    $marginTopo += 60;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', 'B', 10 );
    $pdf->Cell ( 5, 5, 'Atenciosamente,' );

    $marginTopo += 30;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->Cell ( 5, 5, 'Stella Maris Maciel Sebasti�o' );

    $marginTopo += 12;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->Cell ( 5, 5, 'Diretoria' );

    $marginTopo += 12;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->Cell ( 5, 5, 'E-mail: stella@qisat.com.br' );

    $marginTopo += 12;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->Cell ( 5, 5, 'Telefone: (48) 3332-5000 ramal: 5050 ' );

    $marginTopo += 12;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->Cell ( 5, 5, 'Website: www.qisat.com.br' );

    $marginTopo += 40;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', 'B', 10 );
    $pdf->Cell ( 5, 5, 'De acordo com o Termo de Ades�o do Projeto QiSat Rede Educacional em  ____/____/_______.' );

    $marginTopo += 40;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', 'B', 10 );
    $pdf->Cell ( 5, 5, 'Respons�vel pelo Conv�nio:' );

    $pdf->SetLeftMargin ( $marginEsquerda + 250 );

    if ($isEntidade) {
        $pdf->Cell ( 5, 5, 'Presidente da Entidade:' );
    } else {
        $pdf->Cell ( 5, 5, 'Coordenador da Institui��o:' );
    }

    $marginTopo += 30;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->Cell ( 5, 5, '_____________________________________' );

    $pdf->SetLeftMargin ( $marginEsquerda + 250 );
    $pdf->Cell ( 5, 5, '_____________________________________' );

    $marginTopo += 12;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->Cell ( 5, 5, utf8_decode ( $convenio->nome_responsavel) );

    $pdf->SetLeftMargin ( $marginEsquerda + 250 );
    $pdf->Cell ( 5, 5, utf8_decode ( $convenio->nome_coordenador) );

    /*
     * $marginTopo += 40; $pdf->SetXY ( ($marginEsquerda+20), $marginTopo ); $pdf->SetFont ( 'Arial', 'B', 10 ); $pdf->Cell ( 5, 5,'Presidente da Entidade:'); $marginTopo += 30; $pdf->SetXY ( ($marginEsquerda+20), $marginTopo ); $pdf->SetFont ( 'Arial', '', 10 ); $pdf->Cell ( 5, 5,'_____________________________________'); $marginTopo += 10; $pdf->SetXY ( ($marginEsquerda+20), $marginTopo ); $pdf->SetFont ( 'Arial', '', 10 ); $pdf->Cell ( 5, 5,utf8_decode($convenio->getNomeCoordenador()));
     */

    $pdf->Output ();
?>