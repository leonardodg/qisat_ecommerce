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

    $pdf->SetTitle ('Termo de Adesуo Projeto QiSat Rede Educacional On Line');

    $pdf->SetFont ( 'Arial', '', 10 );

    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 5, 5, 'Florianѓpolis, ' . $dia . ' de ' . $mes . ' de ' . $ano );

    $marginTopo += 20;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 5, 5, 'С ' . utf8_decode ( $convenio->nome_instituicao) );

    /*$marginTopo += 20;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 5, 5, utf8_decode ( $convenio->mdl_cidade->nome . ' - ' . $convenio->mdl_cidade->mdl_estado->uf) );*/

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 30;
    $pdf->SetXY ( $marginCentro, $marginTopo );
    $pdf->Cell ( 5, 5, 'Termo de Adesуo Projeto QiSat Rede Educacional On Line' );

    $pdf->SetFont ( 'Arial', '', 10 );
    $marginTopo += 30;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 5, 5, 'Prezado(a) ' . utf8_decode ( $convenio->nome_responsavel) . ',' );

    $marginTopo += 30;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->SetLeftMargin ( 65 );
    $pdf->Cell ( 5, 5, 'O Projeto QiSat Rede Educacional On Line visa o intercтmbio de conhecimento entre a ' . $tipoInstituicao );
    $pdf->SetXY ( $marginEsquerda, $marginTopo + 8 );
    $pdf->MultiCell ( 498, 12, 'e o QiSat - Canal de E-learning da Engenharia, portal www.qisat.com.br, direcionado рs сreas da Engenharia Civil, Elщtrica, Sanitсria e Arquitetura levando descontos especiais para os associados da entidade conveniada nas inscriчѕes de cursos de aperfeiчoamento tщcnico do canal QiSat.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 80;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 20, 5, '1)' );
    $pdf->Cell ( 5, 5, 'Benefэcios propostos no perэodo de vigъncia do Termo de Adesуo' );

    if ($isEntidade) {

        $pdf->SetFont ( 'Arial', 'B', 10 );
        $marginTopo += 30;
        $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
        $pdf->Cell ( 20, 5, 'A.' );
        $pdf->Cell ( 100, 5, 'Desconto Associado ' );

        $pdf->SetFont ( 'Arial', '', 10 );
        $pdf->Cell ( 20, 5, ' - Desconto de 20% no valor normal da inscriчуo de curso QiSat,  ' );
        $marginTopo += 10;
        $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
        $pdf->MultiCell ( 430, 15, 'com parcelamento mэnimo de R$ 100,00 via cartуo de crщdito, ao associado que comprovar no ato da compra, vэnculo profissional na ' . $tipoInstituicao . ' conveniada. Desconto nуo acumulativo, vсlido somente para compra atravщs da Central de Inscriчѕes pelo telefone (48) 3332-5000, email: qisat@qisat.com.br.' );

        $pdf->SetFont ( 'Arial', 'B', 10 );
        $marginTopo += 70;
        $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
        $pdf->Cell ( 20, 5, 'B.' );
        $pdf->Cell ( 70, 5, 'Apoio Didсtico  ' );
        $pdf->SetFont ( 'Arial', '', 10 );
        $pdf->MultiCell ( 430, 5, ' - Uma inscriчуo QiSat, sem єnus, em curso a distтncia dos softwares' );

        $marginTopo += 10;
        $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
        $pdf->MultiCell ( 430, 15, 'AltoQi para a entidade que adquirir software Eberick, Hydros, Lumine, QiCAD, Hydros Incъndio e/ou Lumine Cabeamento na vigъncia do Termo de Adesуo ao Projeto QiSat Rede Educacional On Line.' );
    } else {

        $pdf->SetFont ( 'Arial', 'B', 10 );
        $marginTopo += 30;
        $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
        $pdf->Cell ( 20, 5, 'A.' );
        $pdf->Cell ( 100, 5, 'Desconto Professor' );

        $pdf->SetFont ( 'Arial', '', 10 );
        $pdf->Cell ( 20, 5, ' - Desconto de 50% no valor normal da inscriчуo de curso QiSat, com ' );
        $marginTopo += 10;
        $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
        $pdf->MultiCell ( 430, 15, 'parcelamento mэnimo de R$ 100,00 via cartуo de crщdito, ao professor que comprovar no ato da compra, vэnculo profissional na Instituiчуo de Ensino conveniada. Desconto nуo acumulativo, vсlido somente para compra atravщs da Central de Inscriчѕes pelo telefone (48) 3332-5000, email: qisat@qisat.com.br.' );

        $pdf->SetFont ( 'Arial', 'B', 10 );
        $marginTopo += 70;
        $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
        $pdf->Cell ( 20, 5, 'B.' );
        $pdf->Cell ( 100, 5, 'Desconto Estudante' );
        $pdf->SetFont ( 'Arial', '', 10 );
        $pdf->MultiCell ( 430, 5, ' - Desconto de 30% no valor normal da inscriчуo de curso QiSat, com' );

        $marginTopo += 10;
        $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
        $pdf->MultiCell ( 430, 15, 'parcelamento mэnimo de R$ 100,00 via cartуo de crщdito, ao estudante que comprovar no ato da compra, matrэcula em cursos da Instituiчуo de Ensino conveniada. Desconto nуo acumulativo, vсlido somente para compra atravщs da Central de Inscriчѕes pelo telefone (48) 3332-5000, email: qisat@qisat.com.br.' );

        $pdf->SetFont ( 'Arial', 'B', 10 );
        $marginTopo += 70;
        $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
        $pdf->Cell ( 20, 5, 'C.' );
        $pdf->Cell ( 80, 5, 'Apoio Didсtico' );
        $pdf->SetFont ( 'Arial', '', 10 );
        $pdf->MultiCell ( 430, 5, ' - Uma inscriчуo QiSat, sem єnus, em curso a distтncia dos softwares AltoQi' );

        $marginTopo += 10;
        $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
        $pdf->MultiCell ( 430, 15, 'para a instituiчуo que adquirir software Eberick, Hydros, Lumine, QiCAD, Hydros Incъndio e/ou Lumine Cabeamento na vigъncia do Termo de Adescartуo ao Projeto QiSat Rede Educacional On Line.' );
    }

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 60;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 20, 5, '2)' );
    $pdf->Cell ( 5, 5, 'Atribuiчѕes' );

    $marginTopo += 20;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );

    $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
    $pdf->Cell ( 20, 5, 'Para que a alianчa se concretize a empresa estabelece as seguintes aчѕes:' );

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
    $pdf->MultiCell ( 410, 15, 'Enviar para o email qisat@qisat.com.br o Termo de Adesуo devidamente assinado apresentando formalmente o nome e cargo da pessoa responsсvel pelo convъnio e pela implementaчуo das aчѕes junto р ' . $tipoInstituicao . ' e o formulсrio ANEXO I.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 45;
    $pdf->SetXY ( ($marginEsquerda + 34), $marginTopo );
    $pdf->Cell ( 20, 5, 'III.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo - 5) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Para justificar e viabilizar o convъnio o ANEXO I deverс conter a listas dos associados adimplentes da ' . $tipoInstituicao . '.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 30;
    $pdf->SetXY ( ($marginEsquerda + 33), $marginTopo );
    $pdf->Cell ( 20, 5, 'IV.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo - 5) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Zelar pela aquisiчуo dos direitos da ' . $tipoInstituicao . ' relativo aos benefэcios aprovados, mediante comunicaчуo eficiente e formal junto р Empresa, atravщs da pessoa responsсvel pelo Projeto.' );

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
    $pdf->MultiCell ( 410, 15, 'Utilizar os Cursos QiSat como material didсtico de apoio junto a ' . $tipoInstituicao . ' conveniada.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 30;
    $pdf->SetXY ( ($marginEsquerda + 33), $marginTopo );
    $pdf->Cell ( 20, 5, 'VI.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo - 5) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Realizar a difusуo do convъnio nos canais de comunicaчуo da ' . $tipoInstituicao . ', destacando os descontos previstos no Termo de Adesуo ao projeto.' );

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
    $pdf->MultiCell ( 410, 15, 'Conceder os benefэcios previstos no Termo de Adesуo via Central de Inscriчѕes pelo telefone (48) 3332-5000, email: qisat@qisat.com.br, apѓs o recebimento do ANEXO I e do TERMO DE ADESУO assinado pelo responsсvel perante a ' . $tipoInstituicao . '.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 45;
    $pdf->SetXY ( ($marginEsquerda + 37), ($marginTopo + 15) );
    $pdf->Cell ( 20, 5, 'II.' );

    $pdf->SetXY ( ($marginEsquerda + 60), ($marginTopo + 10) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 410, 15, 'Manter o Projeto QiSat Rede Educacional On Line disponэvel no www.qisat.com.br identificando as ' . $tipoInstituicao . ' conveniadas.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 60;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 20, 5, '3)' );
    $pdf->Cell ( 5, 5, 'Prazo do Convъnio' );

    $pdf->SetXY ( ($marginEsquerda + 40), ($marginTopo + 10) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 430, 15, 'Perуodo de vinte e quatro meses (dois anos) a partir da data da assinatura do Termo de Adesуo.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 60;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 20, 5, '4)' );
    $pdf->Cell ( 5, 5, 'Disposiчѕes Gerais' );

    $pdf->SetXY ( ($marginEsquerda + 40), ($marginTopo + 10) );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->MultiCell ( 430, 15, 'Qualquer informaчуo e orientaчуo pertinente ao processo apresentado poderуo ser obtidas sempre atravщs do e-mail qisat@qisat.com.br e pelo telefone (48) 3332-5000.' );

    $pdf->SetFont ( 'Arial', 'B', 10 );
    $marginTopo += 60;
    $pdf->SetXY ( $marginEsquerda, $marginTopo );
    $pdf->Cell ( 20, 5, '5)' );
    $pdf->Cell ( 5, 5, 'Adesуo' );

    $marginTopo += 15;
    $pdf->SetXY ( ($marginEsquerda + 40), $marginTopo );
    $pdf->SetFont ( 'Arial', '', 10 );
    $pdf->Cell ( 20, 5, 'Nome do Responsсvel: ' . utf8_decode ( $convenio->nome_responsavel) );

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
    $pdf->MultiCell ( 450, 15, 'O QiSat agradece a especial atenчуo e externa desde jс agradecimentos e cumprimentos pelo Convъnio estabelecido.' );

    $marginTopo += 60;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', 'B', 10 );
    $pdf->Cell ( 5, 5, 'Atenciosamente,' );

    $marginTopo += 30;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->Cell ( 5, 5, 'Stella Maris Maciel Sebastiуo' );

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
    $pdf->Cell ( 5, 5, 'De acordo com o Termo de Adesуo do Projeto QiSat Rede Educacional em  ____/____/_______.' );

    $marginTopo += 40;
    $pdf->SetXY ( ($marginEsquerda + 20), $marginTopo );
    $pdf->SetFont ( 'Arial', 'B', 10 );
    $pdf->Cell ( 5, 5, 'Responsсvel pelo Convъnio:' );

    $pdf->SetLeftMargin ( $marginEsquerda + 250 );

    if ($isEntidade) {
        $pdf->Cell ( 5, 5, 'Presidente da Entidade:' );
    } else {
        $pdf->Cell ( 5, 5, 'Coordenador da Instituiчуo:' );
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