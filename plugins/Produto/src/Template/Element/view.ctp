<div class="ecmProdutoInfo view large-12 medium-12 columns content">
    <h3><?= h($ecmProdutoInfo->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Titulo') ?></th>
            <td><?= h($ecmProdutoInfo->titulo) ?></td>
        </tr>
        <tr>
            <th><?= __('Metatag Titulo') ?></th>
            <td><?= h($ecmProdutoInfo->metatag_titulo) ?></td>
        </tr>
        <tr>
            <th><?= __('Metatag Key') ?></th>
            <td><?= h($ecmProdutoInfo->metatag_key) ?></td>
        </tr>
        <tr>
            <th><?= __('Metatag Descricao') ?></th>
            <td><?= h($ecmProdutoInfo->metatag_descricao) ?></td>
        </tr>
        <tr>
            <th><?= __('Url') ?></th>
            <td><?= h($ecmProdutoInfo->url) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmProdutoInfo->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Qtd Aulas') ?></th>
            <td><?= $this->Number->format($ecmProdutoInfo->qtd_aulas) ?></td>
        </tr>
        <tr>
            <th><?= __('Tempo Acesso') ?></th>
            <td><?= $this->Number->format($ecmProdutoInfo->tempo_acesso) ?></td>
        </tr>
        <tr>
            <th><?= __('Tempo Aula') ?></th>
            <td><?= $this->Number->format($ecmProdutoInfo->tempo_aula) ?></td>
        </tr>
        <tr>
            <th><?= __('Carga Horaria') ?></th>
            <td><?= $this->Number->format($ecmProdutoInfo->carga_horaria) ?></td>
        </tr>
        <tr>
            <th><?= __('Material') ?></th>
            <td><?= $ecmProdutoInfo->material ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Certificado Digital') ?></th>
            <td><?= $ecmProdutoInfo->certificado_digital ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Certificado Impresso') ?></th>
            <td><?= $ecmProdutoInfo->certificado_impresso ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Forum') ?></th>
            <td><?= $ecmProdutoInfo->forum ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Tira Duvidas') ?></th>
            <td><?= $ecmProdutoInfo->tira_duvidas ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Mobile') ?></th>
            <td><?= $ecmProdutoInfo->mobile ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Software Demo') ?></th>
            <td><?= $ecmProdutoInfo->software_demo ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Simulador') ?></th>
            <td><?= $ecmProdutoInfo->simulador ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Disponibilidade') ?></th>
            <td><?= $ecmProdutoInfo->disponibilidade ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Chamada') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProdutoInfo->chamada)); ?>
    </div>
    <div class="row">
        <h4><?= __('Persona') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProdutoInfo->persona)); ?>
    </div>
    <div class="row">
        <h4><?= __('Descricao') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProdutoInfo->descricao)); ?>
    </div>
    <div class="related">
        <h4><?= __('Arquivos da Info') ?></h4>
        <?php if (!empty($ecmProdutoInfo->ecm_produto_info_arquivos)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Ecm Produto Info Id') ?></th>
                <th><?= __('Tipo') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Descricao') ?></th>
            </tr>
            <?php foreach ($ecmProdutoInfo->ecm_produto_info_arquivos as $ecmProdutoInfoArquivos): ?>
            <tr>
                <td><?= h($ecmProdutoInfoArquivos->id) ?></td>
                <td><?= h($ecmProdutoInfoArquivos->ecm_produto_info_id) ?></td>
                <td><?= h($ecmProdutoInfoArquivos->tipo) ?></td>
                <td><?= h($ecmProdutoInfoArquivos->nome) ?></td>
                <td><?= h($ecmProdutoInfoArquivos->descricao) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Conteúdos da Info') ?></h4>
        <?php if (!empty($ecmProdutoInfo->ecm_produto_info_conteudo)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Ecm Produto Info Id') ?></th>
                <th><?= __('Titulo') ?></th>
                <th><?= __('Descricao') ?></th>
            </tr>
            <?php foreach ($ecmProdutoInfo->ecm_produto_info_conteudo as $ecmProdutoInfoConteudo): ?>
            <tr>
                <td><?= h($ecmProdutoInfoConteudo->id) ?></td>
                <td><?= h($ecmProdutoInfoConteudo->ecm_produto_info_id) ?></td>
                <td><?= h($ecmProdutoInfoConteudo->titulo) ?></td>
                <td><?= h($ecmProdutoInfoConteudo->descricao) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
