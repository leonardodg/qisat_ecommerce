<?php
namespace Carrinho\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmRecorrencia Entity.
 *
 * @property int $id * @property string $id_integracao * @property string $estabelecimento * @property int $status * @property int $transacao_status * @property string $capturar * @property int $mdl_user_id * @property \Carrinho\Model\Entity\MdlUser $mdl_user * @property float $valor * @property int $ecm_tipo_pagamento_id * @property \Carrinho\Model\Entity\EcmTipoPagamento $ecm_tipo_pagamento * @property int $ecm_operadora_pagamento_id * @property \Carrinho\Model\Entity\EcmOperadoraPagamento $ecm_operadora_pagamento * @property int $quantidade_cobrancas * @property int $ecm_venda_id * @property \Carrinho\Model\Entity\EcmVenda $ecm_venda * @property \Cake\I18n\Time $data_envio * @property \Cake\I18n\Time $data_retorno * @property \Cake\I18n\Time $data_aprovacao_operadora * @property \Cake\I18n\Time $data_primeira_cobranca * @property int $numero_cobranca_total * @property int $numero_cobranca_restantes * @property int $autorizacao * @property int $codigo_transacao_operadora * @property string $mensagem * @property string $mensagem_venda * @property string $erro * @property string $teste * @property string $ip * @property string $numero_comprovante_venda * @property \Cake\I18n\Time $data_campainha */
class EcmRecorrencia extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
