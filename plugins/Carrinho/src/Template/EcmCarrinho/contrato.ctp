<div class="ecmCarrinho col-md-12">
    <h3><?= __('Contrato On-Line de Prestação de Serviço') ?></h3>
    <p>Que, entre si fazem, de um lado MN TECNOLOGIA E TREINAMENTO
        LTDA com sede &agrave; Av. Osmar Cunha, 183, Edif&iacute;cio Ceisa
        Center, Sala 301, Bloco C, Centro na cidade de
        Florian&oacute;polis, Estado de Santa Catarina, inscrita no
        CNPJ sob o n&ordm; 03.984.954/0001-74, doravante chamada
        pelo nome fantasia QISAT, e, de outro,
        <?php echo $usuario->firstname . " " . $usuario->lastname; ?>
        , portador(a) dos documentos informados e devidamente
        cadastrados em nosso banco de dados e identificados pelo seu
        nome de identifica&ccedil;&atilde;o, doravante chamado de
        ALUNO, conforme as cl&aacute;usulas e
        condi&ccedil;&otilde;es a seguir ajustadas.</p>
    <p>
        <strong> CL&Aacute;USULA PRIMEIRA - DO OBJETO </strong>
    </p>

    <p> 1.1 - O objeto deste contrato &eacute; presta&ccedil;&atilde;o
        ao ALUNO pelo QISAT dos cursos listados a seguir:</p>

    <?php
        use App\Lib\ConvertType\IntegerToString;
        $Cont = 1;
        $cabecalho = "";
        $tabela = "";
        $rodape = "";
        foreach ($ecmCarrinho->ecm_carrinho_item as $item){
            if($item->status == "Adicionado" && !isset($item->ecm_curso_presencial_turma_id)){
                foreach ($item->ecm_produto->mdl_course as $course) {
                    $Cont = 1;
                    if (isset($item->ecm_produto->ecm_produto_prazo_extra)) {
                        $enrolperiod = $item->ecm_produto->ecm_produto_prazo_extra->enrolperiod;
                    } else if (isset($item->ecm_produto->ecm_produto_pacote)) {
                        $enrolperiod = $item->ecm_produto->ecm_produto_pacote->enrolperiod;
                    } else {
                        $enrolperiod = $course->mdl_enrol[0]->enrolperiod / 86400;
                    }
                    $cabecalho = "<p>1.1." . $Cont++ . " - Realiza&ccedil;&atilde;o do(s) Curso(s) presencial(is):</p>
                    <table><thead><tr><th>Curso</th>
                    <th>Prazo de Acesso</th>
                    <th>Tempo por aula</th></tr></thead><tbody>";
                    $quant = 1;
                    do {
                        $tabela .= "<tr><th>" . $course->fullname . "</th>
                        <th>" . $enrolperiod . " dias (" . IntegerToString::extenso($enrolperiod) . ")</th><th>";
                        if ($course->timeaccesssection == "0") {
                            $tabela .= "Prazo Ilimitado";
                        } else {
                            $tabela .= $course->timeaccesssection . " (" .
                                IntegerToString::extenso($course->timeaccesssection) . ") horas";
                        }
                        $tabela .= "</th></tr>";
                    } while($item->quantidade > $quant++);
                    $rodape = "</tbody></table><br/><p>1.1." . $Cont++ . "
                    - Emiss&atilde;o eletr&ocirc;nica de certificado de
                    participa&ccedil;&atilde;o, caso o ALUNO atinja 95% (noventa
                    e cinco por cento) de participa&ccedil;&atilde;o no(s)
                    curso(s), a valida&ccedil;&atilde;o dos acessos &eacute;
                    verificada atrav&eacute;s do controle de acesso do QISAT.
                    Par&aacute;grafo &uacute;nico: O acesso ao treinamento -
                    objeto deste contrato - pode dar-se a qualquer dia e a
                    qualquer hor&aacute;rio (24 horas por dia, inclusive finais
                    de semanas e feriados), respeitando os limites
                    m&aacute;ximos de acessos por aula (em horas) e no
                    treinamento (contados automaticamente a partir da data da
                    aquisi&ccedil;&atilde;o).</p><p>1.1." . $Cont++ . "
                    - A emiss&atilde;o do certificado encerra o prazo contratado
                    de acesso ao curso.</p>";
                }
            }
        }
        echo $cabecalho.$tabela.$rodape;
        $cabecalho = "";
        $tabela = "";
        $rodape = "";
        foreach ($ecmCarrinho->ecm_carrinho_item as $item){
            if($item->status == "Adicionado" && isset($item->ecm_curso_presencial_turma_id)){
                $datas = "";
                foreach ($item->ecm_curso_presencial_turma->ecm_curso_presencial_data as $ecm_curso_presencial_data){
                    $datas .= $ecm_curso_presencial_data->datainicio->format('d/m/Y') . ' || ';
                }
                $cidade = $item->ecm_curso_presencial_turma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->nome . "/" .
                    $item->ecm_curso_presencial_turma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->mdl_estado->uf;
                $cabecalho = "<p>1.1.".$Cont." - Realiza&ccedil;&atilde;o do(s) Curso(s) presencial(is):</p>
                    <table><thead><tr><th>Curso</th>
                    <th>Data</th>
                    <th>Cidade/UF</th></tr></thead><tbody>";
                $quant = 1;
                do {
                    $tabela .= "<tr><th>" . $item->ecm_produto->nome . "</th>
                        <th>" . substr($datas, 0, -4) . "</th>
                        <th>" . $cidade . "</th></tr>";
                } while($item->quantidade > $quant++);
                $rodape = "</tbody></table><br/>";
            }
        }
        echo $cabecalho.$tabela.$rodape;
    ?>

    <p>
        <strong>CL&Aacute;USULA SEGUNDA - DO PAGAMENTO DO CURSO </strong>
    </p>
    <p> 2.1 - O ALUNO pagar&aacute; ao QISAT, a t&iacute;tulo
        de remunera&ccedil;&atilde;o pelos servi&ccedil;os
        educacionais prestados, o valor do(s) curso(s)
        estabelecidos no formul&aacute;rio eletr&ocirc;nico de
        inscri&ccedil;&atilde;o, segundo os termos e
        condi&ccedil;&otilde;es nele assinalados.</p>
    <p>2.1.1 - Curso(s) via internet:</p>
    <p> Par&aacute;grafo primeiro: O acesso ao(s) curso(s)
        fica(m) condicionado(s) &agrave; libera&ccedil;&atilde;o
        comercial e financeira do ALUNO por parte do QISAT.<br>
        Par&aacute;grafo segundo: O acesso estar&aacute;
        dispon&iacute;vel em at&eacute; 2 (dois) dias &uacute;teis
        ap&oacute;s a aprova&ccedil;&atilde;o financeira do
        cadastro ou a confirma&ccedil;&atilde;o de dep&oacute;sito,
        no caso de pagamento &agrave; vista.<br> Par&aacute;grafo
        terceiro: No caso de parcelamento do valor, havendo atraso
        de sete dias no pagamento da qualquer parcela, o acesso do
        ALUNO ser&aacute; bloqueado at&eacute; que a
        pend&ecirc;ncia seja quitada.</p>
    <p>
        <strong>CL&Aacute;USULA TERCEIRA - DEVERES E DIREITOS DO QISAT </strong>
    </p>
    <p> 3.1 &ndash; S&atilde;o deveres do QISAT.:</p>
    <p>3.1.1 - Curso(s) via internet:</p>
    <p>I. O QISAT permitir&aacute; acesso 24 (vinte e quatro)
        horas por dia, 7(sete) dias por semana, com o n&uacute;mero
        limitado de horas conforme prazos estabelecidos no website
        www.qisat.com.br, salvo caso fortuito ou motivo de
        for&ccedil;a maior.<br> II. O QISAT se exime de qualquer
        responsabilidade quanto &agrave; indisponibilidade gerada
        por problemas do respons&aacute;vel pela conex&atilde;o do
        site &agrave; rede de internet, n&atilde;o cabendo
        ressarcimento ao ALUNO por qualquer problema de
        conex&atilde;o com a internet, oriundo do acesso do ALUNO
        ou do QISAT.<br> III. O QISAT se reserva ao direito de
        efetuar eventuais manuten&ccedil;&otilde;es em seus
        sistemas, visando melhoria na qualidade do servi&ccedil;o
        prestado.<br> IV. O QISAT dever&aacute; disponibilizar o
        conte&uacute;do do(s) treinamento(s) contratado(s) para uso
        do ALUNO, atrav&eacute;s de um nome de usu&aacute;rio e de
        uma senha espec&iacute;fica de acesso aos m&oacute;dulos
        (aulas) estabelecidos pelo QISAT, que estar&atilde;o
        hospedados no site do QISAT, ou em outros sites por ela
        autorizados;<br> V. O QISAT dever&aacute; disponibilizar um
        professor monitor para esclarecimento de: D&uacute;vidas
        referentes aos recursos do programa abordados no projeto
        exemplo; Procedimentos de download,
        instala&ccedil;&atilde;o e configura&ccedil;&atilde;o das
        vers&otilde;es livres dos softwares da AltoQi
        necess&aacute;rias ao acompanhamento do curso; As
        d&uacute;vidas dever&atilde;o ser enviadas atrav&eacute;s
        do sistema Tira-d&uacute;vidas, disponibilizado na
        plataforma de ensino do QiSat, e ter&atilde;o prazo de
        resolu&ccedil;&atilde;o de at&eacute; 2 (dois) dias
        &uacute;teis;<br> VI. Cabe ao QISAT coordenar
        administrativa e academicamente o(s) treinamento(s),
        zelando pela sua qualidade e pelo cumprimento do regimento
        da institui&ccedil;&atilde;o mantenedora de treinamentos e
        metodologias de ensino a dist&acirc;ncia;<br> VII. O QISAT
        dever&aacute; informar ao ALUNO as atividades programadas
        para o(s) treinamentos(s);<br> VIII. O QISAT dever&aacute;
        emitir certificado de conclus&atilde;o ao ALUNO, caso este
        o tenha solicitado, ap&oacute;s a realiza&ccedil;&atilde;o
        das avalia&ccedil;&otilde;es, e atingindo determinado
        aproveitamento, ou segundo os crit&eacute;rios de
        avalia&ccedil;&atilde;o estabelecidos pelo QISAT.</p>
    <p>
        <strong>CL&Aacute;USULA QUARTA - DEVERES E DIREITOS DO ALUNO </strong>
    </p>
    <p>4.1 - Curso(s) via internet:</p>
    <p> 4.1.1 - O ALUNO ser&aacute;
        respons&aacute;vel pela correta utiliza&ccedil;&atilde;o de
        seu nome de usu&aacute;rio e senha, que s&atilde;o de uso
        pessoal e intransfer&iacute;vel.<br> 4.1.2
        - O ALUNO dever&aacute; providenciar, por conta
        pr&oacute;pria, os equipamentos e softwares, seguindo os
        requisitos m&iacute;nimos mencionados no website do QISAT,
        com acesso &agrave; Internet e ter um endere&ccedil;o
        eletr&ocirc;nico permanente para contato;<br> 4.1.3
        - Responder, no prazo estabelecido pela
        Coordena&ccedil;&atilde;o do QISAT, a todas as mensagens
        recebidas;<br> 4.1.4 - Participar das
        avalia&ccedil;&otilde;es propostas, quando houver o
        interesse da certifica&ccedil;&atilde;o, segundo normas e
        calend&aacute;rio estabelecidos pela
        Coordena&ccedil;&atilde;o do(s) treinamentos(s) e pelo
        QISAT.<br> 4.1.5 - Constituem abuso de
        uso da internet e para a inscri&ccedil;&atilde;o deste
        treinamento:<br> I. Violar a privacidade de outros ALUNOS;<br>
        II. Utilizar indevidamente c&oacute;digos de acesso e/ou
        senha de outros ALUNOS;<br> III. Ceder o seu nome de
        usu&aacute;rio e senha de uso pessoal e
        intransfer&iacute;vel a terceiros;<br> IV. Reproduzir, sob
        qualquer forma, o material, cujo uso deve ser feito
        exclusivamente em &acirc;mbito privado pelo ALUNO, sob pena
        de responder, civil e criminalmente, perante o QISAT e
        terceiros, nos termos da Lei n&deg; 9.609, de 19 de
        fevereiro de 1998, por viola&ccedil;&atilde;o da
        propriedade intelectual;<br> V. Propagar v&iacute;rus de
        computador, programas invasivos (worms), ou outras formas
        de programas computacionais, auto-replicantes ou
        n&atilde;o, que prejudiquem a opera&ccedil;&atilde;o das
        redes e de computadores individuais;<br> VI. Tentar burlar
        o sistema de seguran&ccedil;a de computadores para os quais
        n&atilde;o possua autoriza&ccedil;&atilde;o para acesso;<br>
        VII. Corromper ou destruir dados, arquivos ou programas;<br>
        VIII. Divulgar por meio de correio eletr&ocirc;nico sua
        promo&ccedil;&atilde;o pessoal com fins profissionais,
        comerciais ou eleitorais;<br> IX. Veicular mensagens que
        possam vir a ser consideradas ofensivas e subversivas ou
        que firam princ&iacute;pios &eacute;ticos.<br> X.
        Transmitir ou exibir o(s) conte&uacute;do(s) do(s)
        treinamento(s) em locais de acesso coletivo, salas de
        proje&ccedil;&atilde;o ou audit&oacute;rios, para fins
        pessoais, comerciais ou acad&ecirc;micos, sem a
        autoriza&ccedil;&atilde;o expressa do QISAT;<br>
        Par&aacute;grafo &uacute;nico: O QISAT reserva-se no
        direito de bloquear o acesso ao sistema, sem pr&eacute;vio
        aviso, de qualquer ALUNO, que esteja enquadrado nessas
        condi&ccedil;&otilde;es de abuso, previstas na
        cl&aacute;usula quarta deste instrumento, ou na
        legisla&ccedil;&atilde;o de uso da internet.</p>
    <p>
        <strong>CL&Aacute;USULA QUINTA - DA VIG&Ecirc;NCIA E EXTIN&Ccedil;&Atilde;O </strong>
    </p>
    <p>5.1 - Curso(s) via internet:</p>
    <p>5.1.1 - A vig&ecirc;ncia do
        presente contrato inicia-se a partir da
        aceita&ccedil;&atilde;o, conforme orienta&ccedil;&atilde;o
        do QISAT, e seu t&eacute;rmino se dar&aacute; na
        conclus&atilde;o do(s) treinamentos(s), no caso de
        pagamento &agrave; vista ou t&eacute;rmino de pagamento
        anterior &agrave; realiza&ccedil;&atilde;o do(s)
        treinamentos(s). Para pagamentos parcelados, o contrato
        ter&aacute; vig&ecirc;ncia at&eacute; que seja quitada
        &uacute;ltima parcela pelo ALUNO.<br> 5.1.2
        - O QISAT poder&aacute; rescindir, imediatamente, o
        presente contrato, independentemente de aviso ou
        notifica&ccedil;&atilde;o, em caso de descumprimento de
        qualquer das cl&aacute;usulas constantes do presente
        contrato, pelo ALUNO.<br> 5.1.3 - As
        cl&aacute;usulas relacionadas a abuso de uso e de direitos
        autorais permanecer&atilde;o vigentes ap&oacute;s o
        enceramento do contrato.<br> 5.1.4 - O
        presente contrato poder&aacute; tamb&eacute;m ser
        rescindido, por qualquer das partes, em caso de
        descumprimento, pela outra, das suas
        obriga&ccedil;&otilde;es.<br> Par&aacute;grafo
        &uacute;nico: N&atilde;o cabe ressarcimento ao ALUNO em
        caso de desist&ecirc;ncia do(s) treinamento(s).</p>
    <p>
        <strong>CL&Aacute;USULA SEXTA - DA CL&Aacute;USULA PENAL </strong>
    </p>
    <p> 6.1 - Em havendo quaisquer impedimentos ao n&atilde;o
        cumprimento do presente contrato por motivo de
        responsabilidade do ALUNO, o QISAT reserva-se ao direito de
        suspender os servi&ccedil;os previstos neste contrato e
        obriga-se a avisar o ALUNO para que o mesmo proceda
        &agrave; regulariza&ccedil;&atilde;o da pend&ecirc;ncia. </p>
    <p>
        <strong>CL&Aacute;USULA S&Eacute;TIMA - CONDI&Ccedil;&Otilde;ES GERAIS </strong>
    </p>
    <span>7.1 - Curso(s) via internet:</span>
    <br>
    <p> 7.1.1 - As cl&aacute;usulas
        apresentadas no presente contrato ser&atilde;o aceitas a
        partir do momento que o ALUNO der sua concord&acirc;ncia,
        assinalando a op&ccedil;&atilde;o "Aceito os termos do
        CONTRATO ON-LINE DE PRESTA&Ccedil;&Atilde;O DE
        SERVI&Ccedil;OS".<br> 7.1.2 - As formas
        de pagamento s&atilde;o apresentadas no site e as
        informa&ccedil;&otilde;es dadas pelo ALUNO no
        formul&aacute;rio de cobran&ccedil;a s&atilde;o de total
        responsabilidade do ALUNO.<br> 7.1.3 - O
        ALUNO autorizar&aacute; o QISAT a efetuar a cobran&ccedil;a
        na forma e meio escolhidos por ele, dentre os oferecidos
        pelo QISAT, ficando o QISAT e a empresa autorizada de
        d&eacute;bito (banco ou administradora de cart&otilde;es de
        cr&eacute;dito), isentas de qualquer responsabilidade ou
        obrigatoriedade. </p>
    <p>
        <strong>CL&Aacute;USULA OITAVA - DO FORO</strong>
    </p>
    <p>8.1 - Fica eleito o Foro da cidade de
        Florian&oacute;polis - SC, para dirimir quaisquer
        d&uacute;vidas oriundas do presente contrato, com
        ren&uacute;ncia expressa de qualquer outro, por mais
        privilegiado que seja. </p>
    <p>Ultima altera&ccedil;&atilde;o realizada em 22/08/2014.</p>
</div>