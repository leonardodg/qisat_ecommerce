<h3><?php
    $titulo = preg_split('/([A-Z][^A-Z]+)/', $this->name, -1 , PREG_SPLIT_DELIM_CAPTURE |  PREG_SPLIT_NO_EMPTY);
    echo __('Visualizar '.implode(" ", $titulo));
    ?></h3>
<div class="ecmProduto col-md-12">
    <h3><?= h($ecmProduto->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Nome') ?></th>
            <td><?= h($ecmProduto->nome) ?></td>
        </tr>
        <tr>
            <th><?= __('Sigla') ?></th>
            <td><?= h($ecmProduto->sigla) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmProduto->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Preco') ?></th>
            <td><?= $this->Number->format($ecmProduto->preco) ?></td>
        </tr>
        <tr>
            <th><?= __('Parcela') ?></th>
            <td><?= $this->Number->format($ecmProduto->parcela) ?></td>
        </tr>
        <tr>
            <th><?= __('Idtop') ?></th>
            <td><?= $this->Number->format($ecmProduto->idtop) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Refcurso') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProduto->refcurso)); ?>
    </div>
    <div class="row">
        <h4><?= __('Moeda') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProduto->moeda)); ?>
    </div>
    <div class="row">
        <h4><?= __('Habilitado') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProduto->habilitado)); ?>
    </div>
    <div class="row">
        <h4><?= __('Visivel') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProduto->visivel)); ?>
    </div>

    <div class="related">
        <h4><?= __('Tipos de Produto') ?></h4>
        <?php if (!empty($ecmProduto->ecm_tipo_produto)): ?>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th><?= __('Id') ?></th>
                    <th><?= __('Nome') ?></th>
                    <th><?= __('Tipo Produto') ?></th>
                    <th><?= __('Ordem') ?></th>
                    <th><?= __('Categoria') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                <?php foreach ($ecmProduto->ecm_tipo_produto as $ecmTipoproduto): ?>
                    <tr>
                        <td><?= h($ecmTipoproduto->id) ?></td>
                        <td><?= h($ecmTipoproduto->nome) ?></td>
                        <?php if($ecmTipoproduto->ecm_tipo_produto_id==0): ?>
                            <td>&nbsp;</td>
                        <?php else: ?>
                            <td><?= h($ecmTipoProdutoAll[$ecmTipoproduto->ecm_tipo_produto_id]) ?></td>
                        <?php endif; ?>
                        <td><?= h($ecmTipoproduto->ordem) ?></td>
                        <td><?= h($ecmTipoproduto->categoria) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['controller' => 'tipo-produto', 'action' => 'view', $ecmTipoproduto->id]) ?>
                            <?= $this->Html->link(__('Edit'), ['controller' => 'tipo-produto', 'action' => 'edit', $ecmTipoproduto->id]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($ecmProduto->ecm_produto_ecm_produto)): ?>
    <div class="ecmProduto col-md-12">
        <h4><?= __('Produtos Relacionados com esse produto') ?></h4>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Sigla') ?></th>
                <th><?= __('Habilitado') ?></th>
                <th><?= __('Visivel') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmProduto->ecm_produto_ecm_produto as $ecm_produto): ?>
                <tr>
                    <td><?= h($ecm_produto->ecm_produto['id']) ?></td>
                    <td><?= h($ecm_produto->ecm_produto['nome']) ?></td>
                    <td><?= h($ecm_produto->ecm_produto['sigla']) ?></td>
                    <td><?= h($ecm_produto->ecm_produto['habilitado']) ?></td>
                    <td><?= h($ecm_produto->ecm_produto['visivel']) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['controller' => '', 'action' => 'view', $ecm_produto->ecm_produto['id']]) ?>
                        <?= $this->Html->link(__('Edit'), ['controller' => '', 'action' => 'edit', $ecm_produto->ecm_produto['id']]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>

<?php if (!empty($ecmProduto->ecm_produto_pacote)): ?>
    <div class="ecmProduto col-md-12">
        <h3><?= h($ecmProduto->ecm_produto_pacote->id) ?></h3>
        <table class="vertical-table">
            <tr>
                <th><?= __('Periodo de inscrição') ?></th>
                <td><?= h($ecmProduto->ecm_produto_pacote->enrolperiod) ?></td>
            </tr>
            <tr>
                <th><?= __('Data de criação/modificação') ?></th>
                <td><?= h($ecmProduto->ecm_produto_pacote->timecreated) ?></td>
            </tr>
        </table>
    </div>
<?php endif; ?>

<?php if (!empty($ecmProduto->ecm_produto_prazo_extra)): ?>
    <div class="ecmProduto col-md-12">
        <h3><?= h($ecmProduto->ecm_produto_prazo_extra->id) ?></h3>
        <table class="vertical-table">
            <tr>
                <th><?= __('Periodo de inscrição') ?></th>
                <td><?= h($ecmProduto->ecm_produto_prazo_extra->enrolperiod) ?></td>
            </tr>
            <tr>
                <th><?= __('Data de criação/modificação') ?></th>
                <td><?= h($ecmProduto->ecm_produto_prazo_extra->timecreated) ?></td>
            </tr>
        </table>
    </div>
<?php endif; ?>

<?php if (!empty($ecmProduto->mdl_course)): ?>
<div class="ecmProduto col-md-12">
    <div class="related">
        <h4><?= __('Cursos') ?></h4>
        <?php if (!empty($ecmProduto->mdl_course)): ?>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th><?= __('Id') ?></th>
                    <th><?= __('Category') ?></th>
                    <th><?= __('Fullname') ?></th>
                    <th><?= __('Shortname') ?></th>
                </tr>
                <?php foreach ($ecmProduto->mdl_course as $mdlCourse): ?>
                    <tr>
                        <td><?= h($mdlCourse->id) ?></td>
                        <td><?= h($mdlCourse->category) ?></td>
                        <td><?= h($mdlCourse->fullname) ?></td>
                        <td><?= h($mdlCourse->shortname) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($ecmProduto->ecm_produto_info)): ?>
    <div class="ecmProdutoInfo view large-12 medium-12 columns content">
        <h3><?= h($ecmProduto->ecm_produto_info->id) ?></h3>
        <table class="vertical-table">
            <tr>
                <th><?= __('Titulo') ?></th>
                <td><?= h($ecmProduto->ecm_produto_info->titulo) ?></td>
            </tr>
            <tr>
                <th><?= __('Metatag Titulo') ?></th>
                <td><?= h($ecmProduto->ecm_produto_info->metatag_titulo) ?></td>
            </tr>
            <tr>
                <th><?= __('Metatag Key') ?></th>
                <td><?= h($ecmProduto->ecm_produto_info->metatag_key) ?></td>
            </tr>
            <tr>
                <th><?= __('Metatag Descricao') ?></th>
                <td><?= h($ecmProduto->ecm_produto_info->metatag_descricao) ?></td>
            </tr>
            <tr>
                <th><?= __('Url') ?></th>
                <td><?php $link = h('https://' . $dominioAcessoSite . '/' . $ecmProduto->ecm_produto_info->url) ?>
                    <a target="_blank" href="<?= h($link) ?>"><?= h($link) ?></a></td>
            </tr>
            <tr>
                <th><?= __('Id') ?></th>
                <td><?= $this->Number->format($ecmProduto->ecm_produto_info->id) ?></td>
            </tr>
            <tr>
                <th><?= __('Qtd Aulas') ?></th>
                <td><?= $this->Number->format($ecmProduto->ecm_produto_info->qtd_aulas) ?></td>
            </tr>
            <tr>
                <th><?= __('Tempo Acesso') ?></th>
                <td><?= $this->Number->format($ecmProduto->ecm_produto_info->tempo_acesso) ?></td>
            </tr>
            <tr>
                <th><?= __('Tempo Aula') ?></th>
                <td><?= $this->Number->format($ecmProduto->ecm_produto_info->tempo_aula) ?></td>
            </tr>
            <tr>
                <th><?= __('Carga Horaria') ?></th>
                <td><?= $this->Number->format($ecmProduto->ecm_produto_info->carga_horaria) ?></td>
            </tr>
            <tr>
                <th><?= __('Material') ?></th>
                <td><?= $ecmProduto->ecm_produto_info->material ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr>
                <th><?= __('Certificado Digital') ?></th>
                <td><?= $ecmProduto->ecm_produto_info->certificado_digital ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr>
                <th><?= __('Certificado Impresso') ?></th>
                <td><?= $ecmProduto->ecm_produto_info->certificado_impresso ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr>
                <th><?= __('Forum') ?></th>
                <td><?= $ecmProduto->ecm_produto_info->forum ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr>
                <th><?= __('Tira Duvidas') ?></th>
                <td><?= $ecmProduto->ecm_produto_info->tira_duvidas ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr>
                <th><?= __('Mobile') ?></th>
                <td><?= $ecmProduto->ecm_produto_info->mobile ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr>
                <th><?= __('Software Demo') ?></th>
                <td><?= $ecmProduto->ecm_produto_info->software_demo ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr>
                <th><?= __('Simulador') ?></th>
                <td><?= $ecmProduto->ecm_produto_info->simulador ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr>
                <th><?= __('Disponibilidade') ?></th>
                <td><?= $ecmProduto->ecm_produto_info->disponibilidade ? __('Yes') : __('No'); ?></td>
            </tr>
        </table>
        <div class="row">
            <h4><?= __('Chamada') ?></h4>
            <?= $this->Text->autoParagraph(h($ecmProduto->ecm_produto_info->chamada)); ?>
        </div>
        <div class="row">
            <h4><?= __('Persona') ?></h4>
            <?= $this->Text->autoParagraph($ecmProduto->ecm_produto_info->persona); ?>
        </div>
        <div class="row">
            <h4><?= __('Descricao') ?></h4>
            <?= $this->Text->autoParagraph($ecmProduto->ecm_produto_info->descricao); ?>
        </div>
        <div class="related">
            <h4><?= __('Arquivos da Info') ?></h4>
            <?php if (!empty($ecmProduto->ecm_produto_info->ecm_produto_info_arquivos)): ?>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th style="width:10%"><?= __('Id') ?></th>
                        <th><?= __('Nome') ?></th>
                        <th><?= __('Tipo') ?></th>
                        <th style="width:40%"><?= __('Link') ?></th>
                    </tr>
                    <?php foreach ($ecmProduto->ecm_produto_info->ecm_produto_info_arquivos as $ecmProduto->ecm_produto_infoArquivos): ?>
                        <tr>
                            <td><?= h($ecmProduto->ecm_produto_infoArquivos->id) ?></td>
                            <td><?= h($ecmProduto->ecm_produto_infoArquivos->nome) ?></td>
                            <td><?= h($ecmProduto->ecm_produto_infoArquivos->ecm_produto_info_arquivos_tipo['tipo']) ?></td>
                            <td>
                                <?php
                                if(empty($ecmProduto->ecm_produto_infoArquivos->path)){
                                    $link = $ecmProduto->ecm_produto_infoArquivos->link;
                                    $name = $ecmProduto->ecm_produto_infoArquivos->link;
                                }else{
                                    $link = '/upload/'.$ecmProduto->ecm_produto_infoArquivos->path;
                                    $name = $ecmProduto->ecm_produto_infoArquivos->path;
                                }
                                ?>
                                <a href="<?= h($link) ?>"><?= h($name) ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
        <div class="related">
            <h4><?= __('Conteúdos da Info') ?></h4>
            <?php if (!empty($ecmProduto->ecm_produto_info->ecm_produto_info_conteudo)): ?>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th style="width:12%"><?= __('Id') ?></th>
                        <th><?= __('Titulo') ?></th>
                        <th><?= __('Descricao') ?></th>
                    </tr>
                    <?php foreach ($ecmProduto->ecm_produto_info->ecm_produto_info_conteudo as $ecmProduto->ecm_produto_infoConteudo): ?>
                        <tr>
                            <td><?= h($ecmProduto->ecm_produto_infoConteudo->id) ?></td>
                            <td><?= h($ecmProduto->ecm_produto_infoConteudo->titulo) ?></td>
                            <td><?= $ecmProduto->ecm_produto_infoConteudo->descricao ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
        <div class="related">
            <h4><?= __('Produto Info Faq') ?></h4>
            <?php if (!empty($ecmProduto->ecm_produto_info->ecm_produto_info_faq)): ?>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th style="width:12%"><?= __('Id') ?></th>
                        <th><?= __('Titulo') ?></th>
                        <th><?= __('Descricao') ?></th>
                    </tr>
                    <?php foreach ($ecmProduto->ecm_produto_info->ecm_produto_info_faq as $ecmProduto->ecm_produto_infoFaq): ?>
                        <tr>
                            <td><?= h($ecmProduto->ecm_produto_infoFaq->id) ?></td>
                            <td><?= h($ecmProduto->ecm_produto_infoFaq->titulo) ?></td>
                            <td><?= $ecmProduto->ecm_produto_infoFaq->descricao ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>