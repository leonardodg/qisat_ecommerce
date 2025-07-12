<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= __('E-commerce QiSat') ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('base.css?vs=1509444060') ?>
    <?= $this->Html->css('cake.css?vs=1509444060') ?>
    <?= $this->Html->css('qisat.css?vs=1509444060') ?>

    <?= $this->Html->script('jquery.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?= $this->Html->script('bootbox.min.js') ?>
    <?= $this->Html->script('es6-promise.js') ?>
    <?= $this->Html->script('cross-storage-master/client.js') ?>
    <?= $this->Html->script('jquery_nicescroll/jquery.nicescroll.min.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <script>
        var executeDefaultAjax = true;
        $(document).ready(function(){
            var dialog;
            $( document ).ajaxStart(function() {
                if(executeDefaultAjax) {
                    dialog = bootbox.dialog({
                        title: "Carregando...",
                        message: '<div style="text-align: center"><img src="<?=$this->Url->build('/');?>webroot/img/preload/preload.gif"></div>',
                        closeButton: false
                    });
                }
            });

            $( document ).ajaxStop(function() {
                dialog.modal('hide');
            });
            $("#menu-accordion-left").niceScroll();
        });

    </script>


</head>
<body>

    <nav class="top-bar expanded" data-topbar role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="header-main__brand">
                <h1><a href="/" class="header-main__logo"></a></h1>
          </div>
        </div>
        
        <div class="collapse navbar-collapse top-bar-section" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="https://<?= $linkHostSite?>/cursos">Cursos</a>
                </li>
                <li>
                    <a href="https://<?= $linkHostSite?>/aluno">Área do Aluno</a>
                </li>
                <li>
                    <a href="https://<?= $linkHostSite?>/institucional">Institucional</a>
                </li>
                
                <?php
                if(!is_null($this->request->session()->read('Auth.User'))) {
                    $nome =  $this->request->session()->read('Auth.User.firstname');
                    $nome .= ' ' . $this->request->session()->read('Auth.User.lastname');
                    echo '<li class="current-item"><a>'.$nome.'</a></li>';
                }
                ?>
          </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
    <?= $this->Flash->render() ?>
    <div class="container clearfix col-md-12">
        <nav class="col-md-2" id="actions-sidebar">
            <?= $this->cell('Menu')->render('display'); ?>
        </nav>
        <style>
            .div-favoritar{
                color:#000;
            }
            .div-favoritar .favoritar{
                color:#bcbcbc;
            }
            .div-favoritar:hover > .favoritar{
                color:#d1ae00;
            }
        </style>

        <div class="col-sm-12 col-md-10" id="container">
            <div class="col-md-10">
                <div class="div-favoritar" style="float: left;margin-top:37px;cursor:pointer;">
                    <i class="glyphicon glyphicon-star favoritar"></i> Favoritar
                </div>
            </div>
            <?= $this->fetch('content') ?>
        </div>
    </div>
                <!-- ########################## FOOTER ########################## -->
                    <footer class="footer-main">

                        <div class="footer-primary">
                            <div class="row">

                                        <ul class="footer-primary__columns small-block-grid-2 medium-block-grid-3 large-block-grid-6">

                                            <li class="footer-primary__columns--item">
                                                    <ul class="footer-primary__list">
                                                        <li class="footer-primary__title">Sobre o QiSat</li>
                                                        <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://<?= $linkHostSite?>/institucional/sobre-a-empresa" title="Institucional"   > Institucional </a></li>
                                                        <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://<?= $linkHostSite?>/institucional/linha-do-tempo" title="Linha do Tempo"   > Linha do Tempo </a></li>
                                                        <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://<?= $linkHostSite?>/institucional/convenios-e-parceiros" title="Convênios e Parceiros"  >Convênios e Parceiros</a></li>
                                                        <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://<?= $linkHostSite?>/institucional/instrutores-e-professores" title="Instrutores e Professores"  >Instrutores e Professores</a></li>
                                                    </ul>
                                            </li>

                                            <li class="footer-primary__columns--item">
                                                <ul class="footer-primary__list">
                                                    <li class="footer-primary__title"> Páginas </li>
                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://<?= $linkHostSite?>/cursos/online" title="Cursos Online"  >Cursos Online</a></li>

                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://<?= $linkHostSite?>/cursos/presenciais" title="Cursos Presenciais"  >Cursos Presenciais</a></li>

                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://<?= $linkHostSite?>/aluno" title="Área do Aluno"  href-portal >Área do Aluno</a></li>

                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://<?= $linkHostSite?>/cadastro" title="Cadastro"  >Cadastro</a></li>
                                                </ul>
                                            </li>


<!--                                            <li class="footer-primary__columns--item">
                                                <ul class="footer-primary__list">
                                                    <li class="footer-primary__title"> Blogs </li>

                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="http://maisengenharia.altoqi.com.br/" title="Blog Mais Engenharia" target="_blank" >Blog Mais Engenharia </a></li>

                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="http://eberick.com.br/" title="Blog Eberick" target="_blank"> Blog Eberick </a></li>

                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="http://www.qibuilder.com.br/" title="Blog QiBuilder" target="_blank" > Blog QiBuilder </a></li>

                                                    </ul>
                                            </li> -->


                                            <li class="footer-primary__columns--item">
                                                <ul class="footer-primary__list">
                                                    <li class="footer-primary__title"> Redes Sociais </li>
                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://www.facebook.com/qisat" title="Facebook" target="_blank" >Facebook</a></li>
                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://www.linkedin.com/in/qisat" title="Linkedin" target="_blank" >Linkedin</a></li>
                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://www.instagram.com/qisat/" title="Instagram" target="_blank" >Instagram</a></li>
                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://www.youtube.com/user/qisat" title="YouTube" target="_blank" >YouTube</a></li>
                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://twitter.com/QiSat" title="Twitter" target="_blank" >Twitter</a></li>

                                                </ul>
                                            </li>

                                            <li class="footer-primary__columns--item">
                                                <ul class="footer-primary__list">
                                                     <li class="footer-primary__title"> Contatos</li>
                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" title="Ligamos para você" href="https://<?= $linkHostSite?>/institucional/contatos" >Ligamos para você</a></li>
                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" href="https://<?= $linkHostSite?>/institucional/contatos" title="Mensagem" >Mensagem</a></li>
                                                    <li class="footer-primary__list__item"><a class="footer-primary__list__item--link" title="Mensagem" href="https://<?= $linkHostSite?>/institucional/contatos" >Chat</a></li>
                                                </ul>
                                            </li>

                                            <li class="footer-primary__columns--item">
                                                <img class="footer-primary__list__item--brand-qisat" src="/img/brand_small-light.svg" alt="Brand QiSat Light">
                                                <br><br><span class="footer-primary__title"> (48) 3332-5000 </span>
                                                <br><span class="footer-primary__title "> <a href="https://api.whatsapp.com/send?1=pt_BR&phone=5548998151222"> (48) 99815-1222 </a></span>
                                            </li> <!-- //  -->
                                    </ul>

                            </div> <!-- ///row -->
                        </div> <!-- // footer-primary -->

                        <div class="footer-secondary">
                                <div class="row">
                                        <ul class="footer-secundary__columns small-block-grid-1   large-block-grid-3">
                                            <li>
                                                    <ul class="footer-secondary__list">
                                                        <li class="footer-secondary__list__item">
                                                            <a class="footer-secondary__list__item--link" href="https://<?= $linkHostSite?>/termos-de-uso"  >Termos de uso</a>
                                                        </li>
                                                        <li class="footer-secondary__list__item">
                                                            <a class="footer-secondary__list__item--link" href="https://<?= $linkHostSite?>/politica-de-privacidade" >Política de Privacidade</a>
                                                        </li>
                                                    </ul>
                                            </li>

                                            <li>
                                                    <ul class="footer-secondary__list footer-secondary__list--email ">
                                                        <li class="footer-secondary__list__item">QiSat | Cursos aplicados à engenharia e arquitetura
                                                        <a href="mailto:inscricoes@qisat.com.br" class="footer-secondary__list__item--link"><strong>inscricoes@qisat.com.br</strong></a></li>
                                                    </ul>
                                            </li>

                                            <li>
                                                <ul class="footer-secondary__list">
                                                        <li class="footer-secondary__list__item">
                                                            <span class="footer-secondary__list__item--brand-text">Uma empresa</span>
                                                            <a href="https://www.altoqi.com.br" class="footer-secondary__list__item--brand" target="_blank" alt="AltoQi" title="AltoQi" ><img class="footer-secondary__list__item--brand-image" src="/img/brand_altoqi-small-light.svg" alt="AltoQi" title="AltoQi" ></a>
                                                        </li>
                                                 </ul>
                                            </li>
                                        </ul>
                                </div> <!-- ///row -->
                        </div> <!-- footer-secondary -->

                    </footer> <!-- footer-container -->

    <?= $this->Html->script('paginas-favoritas.js') ?>

    <script type="text/javascript" src="js/es6-promise.js"></script>
    <script type="text/javascript" src="js/cross-storage-master/client.js"></script>
    <script type="text/javascript">
        var storage = new CrossStorageClient('https://website.qisat.local/hub.html');

        storage.onConnect()
        .then(function() {
        return storage.get('token', 'user');
        }).then(function(res) {
        console.log(res); // ['foo', 'bar']
        })['catch'](function(err) {
        console.log(err);
        });

    </script>
</body>
</html>
