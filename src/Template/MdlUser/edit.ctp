<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>
<?= $this->Html->script('/webroot/js/accounting.js') ?>
<?= $this->Html->script('/webroot/js/jquery.mask.min.js') ?>
<?= $this->JqueryMask->getScript();?>

<?php 

    $script = $this->JqueryMask->maskTelefone('#phone1');
    $script .= $this->JqueryMask->mask('#mdl-user-endereco-cep',['00000-000']);
    $script = $this->Jquery->domReady($script);

    echo $this->Html->scriptBlock($script);

    $pais =  [ 'AD' => 'Andorra', 'AE' => 'Emirados Árabes Unidos', 'AF' => 'Afeganistão', 'AG' => 'Antígua e Barbuda', 'AI' => 'Anguilla', 'AL' => 'Albânia', 'AM' => 'Armênia', 'AN' => 'Antilhas holandesas', 'AO' => 'Angola', 'AQ' => 'Antártida', 'AR' => 'Argentina', 'AS' => 'Samoa Americana', 'AT' => 'Áustria', 'AU' => 'Austrália', 'AW' => 'Aruba', 'AX' => 'ilhas Åland', 'AZ' => 'Azerbaijão', 'BA' => 'Bósnia Herzegovina', 'BB' => 'Barbados', 'BD' => 'Bangladesh', 'BE' => 'Bélgica', 'BF' => 'Burkina Faso', 'BG' => 'Bulgária', 'BH' => 'Barein', 'BI' => 'Burundi', 'BJ' => 'Benin', 'BL' => 'São Bartolomeu', 'BM' => 'Bermuda', 'BN' => 'Brunei', 'BO' => 'Bolívia', 'BR' => 'Brasil', 'BS' => 'Bahamas', 'BT' => 'Butão', 'BV' => 'Ilhas Bouvet', 'BW' => 'Botsuana', 'BY' => 'Belarus', 'BZ' => 'Belize', 'CA' => 'Canadá', 'CC' => 'Ilhas (Keeling) cocos', 'CD' => 'Congo, República Democrática do', 'CF' => 'República Centro-Africana', 'CG' => 'Congo', 'CH' => 'Suíça', 'CI' => 'Costa do Marfim', 'CK' => 'Ilhas Cook', 'CL' => 'Chile', 'CM' => 'Camarões', 'CN' => 'China', 'CO' => 'Colômbia', 'CR' => 'Costa Rica', 'CU' => 'Cuba', 'CV' => 'Cabo Verde', 'CX' => 'Ilhas Christmas', 'CY' => 'Chipre', 'CZ' => 'República Tcheca', 'DE' => 'Alemanha, República Federal da', 'DJ' => 'Djibouti', 'DK' => 'Dinamarca', 'DM' => 'Dominica', 'DO' => 'República Dominicana', 'DZ' => 'Argélia', 'EC' => 'Equador', 'EE' => 'Estônia', 'EG' => 'Egito', 'EH' => 'Saara Ocidental', 'ER' => 'Eritréia', 'ES' => 'Espanha', 'ET' => 'Etiópia', 'FI' => 'Finlandia', 'FJ' => 'Fiji', 'FK' => 'Ilhas Falkland (Malvinas)', 'FM' => 'Micronésia, Estados Federados da', 'FO' => 'Ilhas Faeroes', 'FR' => 'França', 'GA' => 'Gabão', 'GB' => 'Reino Unido da Grã-Bretanha e da Irlanda do Norte', 'GD' => 'Grenada', 'GE' => 'Geórgia', 'GF' => 'Guiana francesa', 'GG' => 'Guernsey', 'GH' => 'Gana', 'GI' => 'Gibraltar', 'GL' => 'Groenlândia', 'GM' => 'Gâmbia', 'GN' => 'Guiné', 'GP' => 'Guadalupe', 'GQ' => 'Guiné equatorial', 'GR' => 'Grécia', 'GS' => 'Ilhas Geórgia do Sul e Sandwich do Sul', 'GT' => 'Guatemala', 'GU' => 'Guam', 'GW' => 'Guiné Bissau', 'GY' => 'Guiana', 'HK' => 'Hong Kong', 'HM' => 'Ilhas Heard e Mc Donald', 'HN' => 'Honduras', 'HR' => 'Croácia', 'HT' => 'Haiti', 'HU' => 'Hungria', 'ID' => 'Indonésia', 'IE' => 'Irlanda (Eire)', 'IL' => 'Israel', 'IM' => 'Ilha do Homem', 'IN' => 'Índia', 'IO' => 'Território Britânico do Oceano Índico', 'IQ' => 'Iraque', 'IR' => 'Irã', 'IS' => 'Islândia', 'IT' => 'Itália', 'JE' => 'Jersey', 'JM' => 'Jamaica', 'JO' => 'Jordânia', 'JP' => 'Japão', 'KE' => 'Quênia', 'KG' => 'Quirguistão', 'KH' => 'Camboja', 'KI' => 'Kiribati', 'KM' => 'Comoros', 'KN' => 'São Cristovão  e Nevis', 'KP' => 'Coréia do Norte (República Democrática Popular da Coréia)', 'KR' => 'Coreia do Sul (República da Coréia)', 'KW' => 'Kuwait', 'KY' => 'Ilhas Cayman', 'KZ' => 'Casaquistão', 'LA' => 'Laos', 'LB' => 'Líbano', 'LC' => 'Santa Lúcia', 'LI' => 'Liechtenstein', 'LK' => 'Sri Lanka', 'LR' => 'Libéria', 'LS' => 'Lesoto', 'LT' => 'Lituânia', 'LU' => 'Luxemburgo', 'LV' => 'Latvia', 'LY' => 'Líbia', 'MA' => 'Marrocos', 'MC' => 'Mônaco', 'MD' => 'Moldova', 'ME' => 'Montenegro', 'MF' => 'São Martinho', 'MG' => 'Madagascar', 'MH' => 'Ilhas Marshall', 'MK' => 'Macedônia', 'ML' => 'Mali', 'MM' => 'Myanmar (Burma)', 'MN' => 'Mongólia', 'MO' => 'Macau', 'MP' => 'Ilhas Marianas do Norte', 'MQ' => 'Martinica', 'MR' => 'Mauritânia', 'MS' => 'Montserrat', 'MT' => 'Malta', 'MU' => 'Maurício', 'MV' => 'Maldivas', 'MW' => 'Malavi', 'MX' => 'Mexico', 'MY' => 'Malásia', 'MZ' => 'Moçambique', 'NA' => 'Namíbia', 'NC' => 'Nova Caledônia', 'NE' => 'Niger', 'NF' => 'Ilhas Norfolk', 'NG' => 'Nigéria', 'NI' => 'Nicarágua', 'NL' => 'Holanda', 'NO' => 'Noruega', 'NP' => 'Nepal', 'NR' => 'Nauru', 'NU' => 'Niue', 'NZ' => 'Nova Zelândia', 'OM' => 'Omã', 'PA' => 'Panamá', 'PE' => 'Peru', 'PF' => 'Polinésia Francesa', 'PG' => 'Papua Nova Guiné', 'PH' => 'Filipinas', 'PK' => 'Paquistão', 'PL' => 'Polônia', 'PM' => 'Saint-Pierre e Miquelon', 'PN' => 'Ilha Pitcairn', 'PR' => 'Porto Rico', 'PS' => 'Palestina', 'PT' => 'Portugal', 'PW' => 'República di Belau (Palau)', 'PY' => 'Paraguai', 'QA' => 'Quatar', 'RE' => 'Reunião', 'RO' => 'Romênia', 'RS' => 'Sérvia', 'RU' => 'Rússia (Federação Russa)', 'RW' => 'Ruanda', 'SA' => 'Arábia Saudita', 'SB' => 'Ilhas Salomão', 'SC' => 'Seychelles', 'SD' => 'Sudão', 'SE' => 'Suécia', 'SG' => 'Cingapura', 'SH' => 'Santa Helena', 'SI' => 'Eslovênia', 'SJ' => 'Ilhas Svalbard e Jan Mayen', 'SK' => 'Eslováquia', 'SL' => 'Serra Leoa', 'SM' => 'San Marino', 'SN' => 'Senegal', 'SO' => 'Somália', 'SR' => 'Suriname', 'ST' => 'São Tomé e Príncipe', 'SV' => 'El Salvador', 'SY' => 'Siria', 'SZ' => 'Suazilandia', 'TC' => 'Ilhas Turcks e Caicos', 'TD' => 'Chade', 'TF' => 'Território Ultramarino das Terras Austrais e Antárticas Francesas', 'TG' => 'Togo', 'TH' => 'Tailandia', 'TJ' => 'Tadjiquistão', 'TK' => 'Tokelau', 'TL' => 'Timor-Leste', 'TM' => 'Turcomenistão', 'TN' => 'Tunísia', 'TO' => 'Tonga', 'TR' => 'Turquia', 'TT' => 'Trinidad e Tobago', 'TV' => 'Tuvalu', 'TW' => 'Taiwan', 'TZ' => 'Tanzânia', 'UA' => 'Ucrânia', 'UG' => 'Uganda', 'UM' => 'Ilhas Menores Distantes dos Estados Unidos', 'US' => 'Estados Unidos da América', 'UY' => 'Uruguai', 'UZ' => 'Uzbequistão', 'VA' => 'Vaticano', 'VC' => 'São Vicente e Granadinas', 'VE' => 'Venezuela', 'VG' => 'Ilhas Virgens Britânicas', 'VI' => 'Ilhas Virgens Americanas', 'VN' => 'Vietnã', 'VU' => 'Vanuatu', 'WF' => 'Ilhas Wallis e Futuna', 'WS' => 'Samoa ocidental', 'YE' => 'Iêmen', 'YT' => 'Mayotte', 'ZA' => 'África do Sul', 'ZM' => 'Zâmbia', 'ZW' => 'Zimbábue', 'CS' => 'Sérvia e Montenegro', 'FX' => 'França (metropolitana)', 'KO' => 'Kosovo', 'TP' => 'Timor Leste', 'WA' => 'País de Gales', 'ZR' => 'Zaire'];
    $inscricao =  [ 'Contribuinte' => 'Contribuinte','Contribuinte Isento' => 'Contribuinte Isento','Nao Contribuinte' => 'Nao Contribuinte'];
?>

<div class="col-md-12">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Editar Usuário/Funcionário') ?></legend>

        <?= $this->Form->hidden('mdl_user_dado.tipousuario') ?>

        <div class="row">
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('firstname', ['label' => __('Nome')]) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('lastname', ['label' => __('Sobrenome')]) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('mdl_user_dado.numero', ['label' => __('CPF ou CNPJ')]) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('email', ['label' => __('E-mail')]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('idnumber', ['label' => __('Chave'), 'disabled' => true]) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('username', ['label' => __('Login'), 'disabled' => true]) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('password', ['label' => __('Senha'), 'disabled' => true ]) ?>
                <?php 
                    if( $showpassword )
                        echo '<span class="glyphicon glyphicon-eye-open form-control-feedback showPassword" aria-hidden="true"></span>';
                ?>
                
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('phone1', ['label' => __('Telefone')]) ?>
            </div>
        </div>

        <h4>Endereço</h4>

        <div class="row">
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('mdl_user_endereco.cep', ['label' => __('CEP')]) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('country', ['options'=>$pais, 'label'=> 'Pais', 'empty' => __('(Selecione o Pais)') ]) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="selectStateView" style="<?= ($user->country != 'BR' && $user->country != '') && !$user->mdl_user_endereco->state ? 'display:none' : 'display:block' ?>">
                    <?= $this->Form->input('mdl_user_endereco.state', ['options'=>$listaEstado, 'label'=> 'Estado', 'empty' => __('(Selecione o Estado)'), 'disabled' => ($user->country != 'BR' && $user->country != '') && !$user->mdl_user_endereco->state ? true : false, 'class' => 'selectState']) ?>
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <?php
                    
                    if($user->country != 'BR' && $user->country != '')
                        echo '<label class="labelCity" data-ext="true" > Cidade - Exterior </label>';
                    else
                        echo '<label class="labelCity" data-ext="false" > Cidade </label>';
                    
                    echo $this->Form->input('city', ['options'=> $user->mdl_user_endereco['state'] ? $listaCidade[$user->mdl_user_endereco['state']] : [], 'label'=> false, 'empty' => __('(Selecione a Cidade )'), 'class' => 'selectCity', 'disabled' => $user->mdl_user_endereco['state'] && array_key_exists($user->city, $listaCidade[$user->mdl_user_endereco['state']]) ? false : true , 'style' => (($user->mdl_user_endereco['state'] && $user->city && array_key_exists($user->city, $listaCidade[$user->mdl_user_endereco['state']])) or ($user->city == '' && $user->mdl_user_endereco['state'] == '' && $user->country == '')) ? 'display:block' : 'display:none' ]);

                    echo $this->Form->input('city', [ 'label'=> false, 'class' => 'textCity', 'type' => 'text',
                    'disabled' => (($user->country != 'BR' && $user->country != '') or ($user->city && $user->mdl_user_endereco['state'] && !array_key_exists($user->city, $listaCidade[$user->mdl_user_endereco['state']]))) ? false : true, 'style' => (($user->country != 'BR' && $user->country != '')or ($user->city && $user->mdl_user_endereco['state'] && !array_key_exists($user->city, $listaCidade[$user->mdl_user_endereco['state']]))) ? 'display:block' : 'display:none']);

                 ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('mdl_user_endereco.district', ['label' => __('Bairro'),'rows' =>1]) ?>
            </div>
            <div class="col-xs-12 col-md-5">
                <?= $this->Form->input('address', ['label' => __('Logradouro')]) ?>
            </div>
            <div class="col-xs-12 col-md-1">
                <?= $this->Form->input('mdl_user_endereco.number', ['label' => __('Número')]) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('mdl_user_endereco.complement', ['label' => __('Complemento'), 'rows' =>1]) ?>
            </div>
        </div>

        <h4>Politica de Privacidade</h4>

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p><strong>Autorizo receber e-mails, quando:</strong></p>
                <div class="checkbox">
                  <label>
                    <?= $this->Form->input('mdl_user_dado.email_oferta', ['label' => false, 'type' => 'checkbox']) ?> 
                    Houver ofertas e lançamentos do QiSat.
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <?= $this->Form->input('mdl_user_dado.email_andamento', ['label' => false, 'type' => 'checkbox']) ?> 
                    Forem relacionados ao andamento de meus cursos.
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <?= $this->Form->input('mdl_user_dado.email_mensagem_privada', ['label' => false, 'type' => 'checkbox']) ?> 
                    Receber uma mensagem privada em minha conta.
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <?= $this->Form->input('mdl_user_dado.email_ausente', ['label' => false, 'type' => 'checkbox']) ?> 
                    Eu ficar muito tempo sem acessar meu curso.
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <?= $this->Form->input('mdl_user_dado.email_suporte', ['label' => false, 'type' => 'checkbox']) ?> 
                    Quando o instrutor me responder no suporte.
                  </label>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
               <p><strong>Autorizo receber ligações, quando:</strong></p>
                <div class="checkbox">
                  <label>
                    <?= $this->Form->input('mdl_user_dado.ligacao_lancamentos', ['label' => false, 'type' => 'checkbox']) ?> 
                     Houver lançamentos relacionados a cursos que já fiz.
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <?= $this->Form->input('mdl_user_dado.ligacao_pagamento', ['label' => false, 'type' => 'checkbox']) ?> 
                    Tiver algum pagamento em aberto.
                  </label>
                </div>
                <p><strong>Autorizo receber SMS, quando:</strong></p>
                <div class="checkbox">
                  <label>
                    <?= $this->Form->input('mdl_user_dado.sms_informacoes', ['label' => false, 'type' => 'checkbox']) ?> 
                    Houver informações importantes relacionadas ao meu curso.
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <?= $this->Form->input('mdl_user_dado.sms_lancamentos', ['label' => false, 'type' => 'checkbox']) ?> 
                    Houver lançamentos relacionados a cursos que já fiz.
                  </label>
                </div>
            </div>
        </div>

        <h4>Funcionário - Empresa</h4>
        <div class="row">
            <div class="col-xs-12 col-md-3">
                <div class="checkbox">
                  <label>
                    <?= $this->Form->input('mdl_user_dado.funcionarioqisat', ['label' => false, 'type' => 'checkbox']) ?> 
                    Funcionário QiSat/AltoQi.
                  </label>
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                 <?= $this->Form->input('mdl_user_dado.ecm_alternative_host_id', ['options'=>$ecmAlternativeHost, 'label'=> false, 'empty' => __('(Selecione a Empresa)'), 'style' => ($user->mdl_user_dado['funcionarioqisat'] or $user->mdl_user_dado['ecm_alternative_host_id']) ? 'display:block' : 'display:none']) ?>
            </div>
            <div class="col-xs-12 col-md-6">
            </div>
        </div>

        <div class="dadosEmpresa">
            <h4>Dados Para Empresa - Conta Azul</h4>

            <div class="row">

                <div class="col-xs-12 col-md-3">
                    <?= $this->Form->input('mdl_user_dado.tipo_inscricao_estadual', ['options'=>$inscricao, 'label'=> __('Tipo de Inscrição Estadual'), 'empty' => __('(Selecione o tipo)') ]) ?>
                </div>

                <div class="col-xs-12 col-md-3">
                    <?= $this->Form->input('mdl_user_dado.numero_inscricao_estadual', ['label' => __('Inscrição Estadual')]) ?>
                </div>

                <div class="col-xs-12 col-md-3">
                    <?= $this->Form->input('mdl_user_dado.numero_inscricao_municipal', ['label' => __('Inscrição Municipal')]) ?>
                </div>
            </div>
        </div>

        <div class="row text-right">
            <div class="col-xs-12 col-md-12">
                    <?= $this->Html->link('Cancelar', \Cake\Routing\Router::url([ 'plugin' => false,'controller' => 'MdlUser', 'action' => 'listar-usuario']),  [ 'class' => 'button' ]) ?>
                    <?= $this->Form->button(__('Submit')) ?>
            </div>
        </div>

    </fieldset>
    <?= $this->Form->end() ?>
</div>


<script type="text/javascript">

        var listaCity = <?= $listaCidadeJson ?>;

        if($("input[name='mdl_user_dado[tipousuario]'").val() != 'juridico')
            $(".dadosEmpresa").hide();
        else
            $(".dadosEmpresa:visible").show();

    
        function validaCPF(cpf){
                exp = /\.|\-/g;
                cpf = cpf.toString().replace( exp, "" );
                var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10)),
                    soma1=0, soma2=0, vlr =11, digitoGerado;

                for(i=0;i<9;i++){
                        soma1+=eval(cpf.charAt(i)*(vlr-1));
                        soma2+=eval(cpf.charAt(i)*vlr);
                        vlr--;
                }       
                soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
                soma2 = (((soma2+(2*soma1))*10)%11) == 10 ? 0 : (((soma2+(2*soma1))*10)%11);
                digitoGerado = (soma1*10)+soma2;

                return (digitoGerado!=digitoDigitado) ? false : true; 
        };

        function validarCNPJ(cnpj){
                var valida = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2);
                var dig1= new Number;
                var dig2= new Number;

                exp = /\.|\-|\//g
                cnpj = cnpj.toString().replace( exp, "" ); 
                var digito = new Number(eval(cnpj.charAt(12)+cnpj.charAt(13)));

                for(i = 0; i<valida.length; i++){
                        dig1 += (i>0? (cnpj.charAt(i-1)*valida[i]):0);  
                        dig2 += cnpj.charAt(i)*valida[i];       
                }
                dig1 = (((dig1%11)<2)? 0:(11-(dig1%11)));
                dig2 = (((dig2%11)<2)? 0:(11-(dig2%11)));

                return (((dig1*10)+dig2) != digito) ? false : true;
        }

        $("#mdl-user-dado-numero").keyup(function(e){
            var code = e.keyCode || e.which;
            var tamanho = $(this).val().replace(/[^\d]+/g,'').length;
            var elem = this;

            if (code != 9 && code != 13  && code != 32 && code != 86 && code != 17 && code != undefined ) {
                try {
                    $("#mdl-user-dado-numero").unmask();
                } catch (e) {}

                if(tamanho <= 11)
                    $("#mdl-user-dado-numero").mask("999.999.999-999");
                else if(tamanho > 11)
                    $("#mdl-user-dado-numero").mask("99.999.999/9999-99");

                setTimeout(function(){
                    elem.selectionStart = elem.selectionEnd = 10000;
                }, 0);
                var currentValue = $(this).val();
                $(this).val('');
                $(this).val(currentValue);
            }
        });

        $("#mdl-user-endereco-cep").focusout(function(){
            var val = $(this).val();

            $.ajax({
                type: "GET",
                url: 'https://api.postmon.com.br/cep/'+val,
                dataType: "jsonp",
                success:function(data) {
                    console.log(data);
                    if(data){

                        var op = '<option value="">Selecione a Cidade ('+data.estado+')</option>';
                        var citys = listaCity.find(function(el){ return (data.estado in el) });
                        $('.selectCity').find('option').empty();
                        citys[data.estado].map(function(el){
                                            op+='<option value="'+el.nome+'">'+el.nome+'</option>';
                                        });

                        $('.selectCity').html(op);

                        $('#country').val('BR');
                        $('#address').val(data.logradouro);
                        $('#mdl-user-endereco-district').val(data.bairro);
                        $('#mdl-user-endereco-number').val('');
                        if(data.complemento) 
                            $('#mdl-user-endereco-complement').val(data.complemento);
                        else
                            $('#mdl-user-endereco-complement').val('');
                        $('#mdl-user-endereco-state').val(data.estado);
                        $('.selectCity').val(data.cidade);
                        
                    }
                }
            });
        });

        $("#mdl-user-dado-numero").focusout(function(){
             var tamanho = $(this).val().replace(/[^\d]+/g,'').length;

             if(tamanho>11){
                if(validarCNPJ($(this).val()) == false)
                    bootbox.alert('CNPJ Invalido!');
                else{
                    $("input[name='mdl_user_dado[tipousuario]'").val('juridico');
                    $(".dadosEmpresa:hidden").show();
                }
            }else{ 
                if(validaCPF($(this).val()) == false)
                    bootbox.alert('CPF Invalido!');
                else{
                    $("input[name='mdl_user_dado[tipousuario]'").val('fisico');
                    $(".dadosEmpresa:visible").hide();
                }
            }
        });

        $('label[for="password"]').click(function(){
            if($('#password').prop('type')=='password'){
                $('#password').prop('type', 'text');
            }else{
                $('#password').prop('type', 'password');
            }
        });

        $('#mdl-user-dado-funcionarioqisat').change(function(){
            var checked = $(this).prop('checked');
            if(checked)
                $('#mdl-user-dado-ecm-alternative-host-id:hidden').show();
            else{
                $('#mdl-user-dado-ecm-alternative-host-id:visible').hide();
                $('#mdl-user-dado-ecm-alternative-host-id').val('');
            }
        });

        $('#country').change(function(){
            var val = $(this).val();
            $('.textCity').val('');
            $('.selectState').val('');
            $('.selectCity').val('');
           // $('.selectState:enabled').prop('disabled', true);
            $('.selectCity:enabled').prop('disabled', true);
            $('.selectCity').find('option').empty();

            if(val == 'BR'){
                $('.selectStateView:hidden').show();
                $('.selectState:disabled').prop('disabled', false);
                $('.textCity:enabled').prop('disabled', true);
                $('.textCity:visible').hide();
                $('.selectCity:hidden').show();
                $('.labelCity').text('Cidade');
            }else if(val != ''){
                $('.textCity:disabled').prop('disabled', false);
                $('.labelCity').text('Cidade - Exterior');
                $('.selectStateView:visible').hide();
                $('.selectCity:visible').hide();
                $('.textCity:hidden').show();
            }else{
                $('.labelCity').text('Cidade');
                $('.selectStateView:hidden').show();
                $('.selectCity:hidden').show();
                $('.textCity:visible').hide();
            }
        });

         $('#mdl-user-endereco-state').change(function(){
            var val = $(this).val();
            var op = '<option value="">Selecione a Cidade ('+val+')</option>';
            var citys = listaCity.find(function(el){ return (val in el) });

            $('.selectCity:disabled').prop('disabled', false);
            $('.textCity').val('');
            $('.textCity:enabled').prop('disabled', true);
            $('.textCity:visible').hide();
            $('.selectCity:hidden').show();
            $('.selectCity').find('option').empty();

            if(citys){
                citys[val].map(function(el){
                    op+='<option value="'+el.nome+'">'+el.nome+'</option>';
                });
                $('.selectCity').html(op);
            }else{
                $('.selectCity').prop('disabled', true);
            }
        });

</script>