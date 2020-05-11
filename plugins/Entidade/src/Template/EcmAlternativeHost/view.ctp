<div class="ecmAlternativeHost col-md-12">
    <h3><?= h($ecmAlternativeHost->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Host') ?></th>
            <td><?= h($ecmAlternativeHost->host) ?></td>
        </tr>
        <tr>
            <th><?= __('Shortname') ?></th>
            <td><?= h($ecmAlternativeHost->shortname) ?></td>
        </tr>
        <tr>
            <th><?= __('Fullname') ?></th>
            <td><?= h($ecmAlternativeHost->fullname) ?></td>
        </tr>
        <tr>
            <th><?= __('Path') ?></th>
            <td><?= h($ecmAlternativeHost->path) ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($ecmAlternativeHost->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Googleanalytics') ?></th>
            <td><?= h($ecmAlternativeHost->googleanalytics) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmAlternativeHost->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Codigoorigemaltoqi') ?></th>
            <td><?= $this->Number->format($ecmAlternativeHost->codigoorigemaltoqi) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Ecm Carrinho') ?></h4>
        <?php if (!empty($ecmAlternativeHost->ecm_carrinho)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Data') ?></th>
                <th><?= __('Mdl User Id') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Edicao') ?></th>
                <th><?= __('Ecm Alternative Host Id') ?></th>
                <th><?= __('Mdl User Modified Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmAlternativeHost->ecm_carrinho as $ecmCarrinho): ?>
            <tr>
                <td><?= h($ecmCarrinho->id) ?></td>
                <td><?= h($ecmCarrinho->data) ?></td>
                <td><?= h($ecmCarrinho->mdl_user_id) ?></td>
                <td><?= h($ecmCarrinho->status) ?></td>
                <td><?= h($ecmCarrinho->edicao) ?></td>
                <td><?= h($ecmCarrinho->ecm_alternative_host_id) ?></td>
                <td><?= h($ecmCarrinho->mdl_user_modified_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EcmCarrinho', 'action' => 'view', $ecmCarrinho->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EcmCarrinho', 'action' => 'edit', $ecmCarrinho->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EcmCarrinho', 'action' => 'delete', $ecmCarrinho->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmCarrinho->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Ecm Cupom') ?></h4>
        <?php if (!empty($ecmAlternativeHost->ecm_cupom)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Todos Usuarios') ?></th>
                <th><?= __('Datainicio') ?></th>
                <th><?= __('Datafim') ?></th>
                <th><?= __('Chave') ?></th>
                <th><?= __('Descontovalor') ?></th>
                <th><?= __('Descontoporcentagem') ?></th>
                <th><?= __('Descricao') ?></th>
                <th><?= __('Habilitado') ?></th>
                <th><?= __('Numutilizacoes') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Tipo') ?></th>
                <th><?= __('Referencia') ?></th>
                <th><?= __('Arredondamento') ?></th>
                <th><?= __('Ecm Alternative Host Id') ?></th>
                <th><?= __('Descontosobretabela') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmAlternativeHost->ecm_cupom as $ecmCupom): ?>
            <tr>
                <td><?= h($ecmCupom->id) ?></td>
                <td><?= h(\Cupom\Model\Entity\EcmCupom::TIPOS_AQUISICOES[$ecmCupom->tipo_aquisicao]) ?></td>
                <td><?= h($ecmCupom->datainicio) ?></td>
                <td><?= h($ecmCupom->datafim) ?></td>
                <td><?= h($ecmCupom->chave) ?></td>
                <td><?= h($ecmCupom->descontovalor) ?></td>
                <td><?= h($ecmCupom->descontoporcentagem) ?></td>
                <td><?= h($ecmCupom->descricao) ?></td>
                <td><?= h($ecmCupom->habilitado) ?></td>
                <td><?= h($ecmCupom->numutilizacoes) ?></td>
                <td><?= h($ecmCupom->nome) ?></td>
                <td><?= h($ecmCupom->tipo) ?></td>
                <td><?= h($ecmCupom->referencia) ?></td>
                <td><?= h($ecmCupom->arredondamento) ?></td>
                <td><?= h($ecmCupom->ecm_alternative_host_id) ?></td>
                <td><?= h($ecmCupom->descontosobretabela) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EcmCupom', 'action' => 'view', $ecmCupom->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EcmCupom', 'action' => 'edit', $ecmCupom->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EcmCupom', 'action' => 'delete', $ecmCupom->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmCupom->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Ecm Produto Ecm Tipo Produto') ?></h4>
        <?php if (!empty($ecmAlternativeHost->ecm_produto_ecm_tipo_produto_ecm_alternative_host)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Produto') ?></th>
                <th><?= __('Tipo do Produto') ?></th>
                <th><?= __('Ordem') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmAlternativeHost->ecm_produto_ecm_tipo_produto_ecm_alternative_host as $ecmProdutoEcmTipoProdutoEcmAlternativeHost): ?>
                <?php $ecmProdutoEcmTipoProduto = $ecmProdutoEcmTipoProdutoEcmAlternativeHost->ecm_produto_ecm_tipo_produto; ?>
                <tr>
                    <td><?= h($ecmProdutoEcmTipoProduto->id) ?></td>
                    <td><?= h($ecmProdutoEcmTipoProduto->ecm_produto_id) ?></td>
                    <td><?= h($ecmProdutoEcmTipoProduto->ecm_tipo_produto_id) ?></td>
                    <td><?= h($ecmProdutoEcmTipoProduto->ordem) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['controller' => 'EcmProdutoEcmTipoProduto', 'action' => 'view', $ecmProdutoEcmTipoProduto->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['controller' => 'EcmProdutoEcmTipoProduto', 'action' => 'edit', $ecmProdutoEcmTipoProduto->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['controller' => 'EcmProdutoEcmTipoProduto', 'action' => 'delete', $ecmProdutoEcmTipoProduto->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmProdutoEcmTipoProduto->id)]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Ecm Promocao') ?></h4>
        <?php if (!empty($ecmAlternativeHost->ecm_promocao)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Datainicio') ?></th>
                <th><?= __('Datafim') ?></th>
                <th><?= __('Descontovalor') ?></th>
                <th><?= __('Descontoporcentagem') ?></th>
                <th><?= __('Descricao') ?></th>
                <th><?= __('Habilitado') ?></th>
                <th><?= __('Arredondamento') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmAlternativeHost->ecm_promocao as $ecmPromocao): ?>
            <tr>
                <td><?= h($ecmPromocao->id) ?></td>
                <td><?= h($ecmPromocao->datainicio) ?></td>
                <td><?= h($ecmPromocao->datafim) ?></td>
                <td><?= h($ecmPromocao->descontovalor) ?></td>
                <td><?= h($ecmPromocao->descontoporcentagem) ?></td>
                <td><?= h($ecmPromocao->descricao) ?></td>
                <td><?= h($ecmPromocao->habilitado) ?></td>
                <td><?= h($ecmPromocao->arredondamento) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EcmPromocao', 'action' => 'view', $ecmPromocao->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EcmPromocao', 'action' => 'edit', $ecmPromocao->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EcmPromocao', 'action' => 'delete', $ecmPromocao->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmPromocao->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Mdl User') ?></h4>
        <?php if (!empty($ecmAlternativeHost->mdl_user)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Auth') ?></th>
                <th><?= __('Confirmed') ?></th>
                <th><?= __('Policyagreed') ?></th>
                <th><?= __('Deleted') ?></th>
                <th><?= __('Suspended') ?></th>
                <th><?= __('Mnethostid') ?></th>
                <th><?= __('Username') ?></th>
                <th><?= __('Password') ?></th>
                <th><?= __('Idnumber') ?></th>
                <th><?= __('Firstname') ?></th>
                <th><?= __('Lastname') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Emailstop') ?></th>
                <th><?= __('Icq') ?></th>
                <th><?= __('Skype') ?></th>
                <th><?= __('Yahoo') ?></th>
                <th><?= __('Aim') ?></th>
                <th><?= __('Msn') ?></th>
                <th><?= __('Phone1') ?></th>
                <th><?= __('Phone2') ?></th>
                <th><?= __('Institution') ?></th>
                <th><?= __('Department') ?></th>
                <th><?= __('Address') ?></th>
                <th><?= __('City') ?></th>
                <th><?= __('Country') ?></th>
                <th><?= __('Lang') ?></th>
                <th><?= __('Calendartype') ?></th>
                <th><?= __('Theme') ?></th>
                <th><?= __('Timezone') ?></th>
                <th><?= __('Firstaccess') ?></th>
                <th><?= __('Lastaccess') ?></th>
                <th><?= __('Lastlogin') ?></th>
                <th><?= __('Currentlogin') ?></th>
                <th><?= __('Lastip') ?></th>
                <th><?= __('Secret') ?></th>
                <th><?= __('Picture') ?></th>
                <th><?= __('Url') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Descriptionformat') ?></th>
                <th><?= __('Mailformat') ?></th>
                <th><?= __('Maildigest') ?></th>
                <th><?= __('Maildisplay') ?></th>
                <th><?= __('Autosubscribe') ?></th>
                <th><?= __('Trackforums') ?></th>
                <th><?= __('Timecreated') ?></th>
                <th><?= __('Timemodified') ?></th>
                <th><?= __('Trustbitmask') ?></th>
                <th><?= __('Imagealt') ?></th>
                <th><?= __('Lastnamephonetic') ?></th>
                <th><?= __('Firstnamephonetic') ?></th>
                <th><?= __('Middlename') ?></th>
                <th><?= __('Alternatename') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmAlternativeHost->mdl_user as $mdlUser): ?>
            <tr>
                <td><?= h($mdlUser->id) ?></td>
                <td><?= h($mdlUser->auth) ?></td>
                <td><?= h($mdlUser->confirmed) ?></td>
                <td><?= h($mdlUser->policyagreed) ?></td>
                <td><?= h($mdlUser->deleted) ?></td>
                <td><?= h($mdlUser->suspended) ?></td>
                <td><?= h($mdlUser->mnethostid) ?></td>
                <td><?= h($mdlUser->username) ?></td>
                <td><?= h($mdlUser->password) ?></td>
                <td><?= h($mdlUser->idnumber) ?></td>
                <td><?= h($mdlUser->firstname) ?></td>
                <td><?= h($mdlUser->lastname) ?></td>
                <td><?= h($mdlUser->email) ?></td>
                <td><?= h($mdlUser->emailstop) ?></td>
                <td><?= h($mdlUser->icq) ?></td>
                <td><?= h($mdlUser->skype) ?></td>
                <td><?= h($mdlUser->yahoo) ?></td>
                <td><?= h($mdlUser->aim) ?></td>
                <td><?= h($mdlUser->msn) ?></td>
                <td><?= h($mdlUser->phone1) ?></td>
                <td><?= h($mdlUser->phone2) ?></td>
                <td><?= h($mdlUser->institution) ?></td>
                <td><?= h($mdlUser->department) ?></td>
                <td><?= h($mdlUser->address) ?></td>
                <td><?= h($mdlUser->city) ?></td>
                <td><?= h($mdlUser->country) ?></td>
                <td><?= h($mdlUser->lang) ?></td>
                <td><?= h($mdlUser->calendartype) ?></td>
                <td><?= h($mdlUser->theme) ?></td>
                <td><?= h($mdlUser->timezone) ?></td>
                <td><?= h($mdlUser->firstaccess) ?></td>
                <td><?= h($mdlUser->lastaccess) ?></td>
                <td><?= h($mdlUser->lastlogin) ?></td>
                <td><?= h($mdlUser->currentlogin) ?></td>
                <td><?= h($mdlUser->lastip) ?></td>
                <td><?= h($mdlUser->secret) ?></td>
                <td><?= h($mdlUser->picture) ?></td>
                <td><?= h($mdlUser->url) ?></td>
                <td><?= h($mdlUser->description) ?></td>
                <td><?= h($mdlUser->descriptionformat) ?></td>
                <td><?= h($mdlUser->mailformat) ?></td>
                <td><?= h($mdlUser->maildigest) ?></td>
                <td><?= h($mdlUser->maildisplay) ?></td>
                <td><?= h($mdlUser->autosubscribe) ?></td>
                <td><?= h($mdlUser->trackforums) ?></td>
                <td><?= h($mdlUser->timecreated) ?></td>
                <td><?= h($mdlUser->timemodified) ?></td>
                <td><?= h($mdlUser->trustbitmask) ?></td>
                <td><?= h($mdlUser->imagealt) ?></td>
                <td><?= h($mdlUser->lastnamephonetic) ?></td>
                <td><?= h($mdlUser->firstnamephonetic) ?></td>
                <td><?= h($mdlUser->middlename) ?></td>
                <td><?= h($mdlUser->alternatename) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'MdlUser', 'action' => 'view', $mdlUser->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'MdlUser', 'action' => 'edit', $mdlUser->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'MdlUser', 'action' => 'delete', $mdlUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mdlUser->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Ecm Imagem') ?></h4>
        <?php if (!empty($ecmAlternativeHost->ecm_imagem)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Src') ?></th>
                <th><?= __('Descricao') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmAlternativeHost->ecm_imagem as $ecmImagem): ?>
            <tr>
                <td><?= h($ecmImagem->id) ?></td>
                <td><?= h($ecmImagem->nome) ?></td>
                <td><?= h($ecmImagem->src) ?></td>
                <td><?= h($ecmImagem->descricao) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EcmImagem', 'action' => 'view', $ecmImagem->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EcmImagem', 'action' => 'edit', $ecmImagem->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EcmImagem', 'action' => 'delete', $ecmImagem->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmImagem->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
