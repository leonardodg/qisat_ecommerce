<?php
namespace Carrinho\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmVenda Entity.
 *
 * @property int $id * @property \Cake\I18n\Time $data * @property float $valor_parcelas * @property int $proposta * @property int $numero_parcelas * @property int $ecm_venda_status_id * @property \Carrinho\Model\Entity\EcmVendaStatus $ecm_venda_status * @property int $mdl_user_id * @property \Carrinho\Model\Entity\MdlUser $mdl_user * @property int $ecm_operadora_pagamento_id * @property \Carrinho\Model\Entity\EcmOperadoraPagamento $ecm_operadora_pagamento * @property int $ecm_tipo_pagamento_id * @property \Carrinho\Model\Entity\EcmTipoPagamento $ecm_tipo_pagamento * @property int $ecm_carrinho_id * @property \Carrinho\Model\Entity\EcmCarrinho $ecm_carrinho * @property \Carrinho\Model\Entity\EcmTransacao[] $ecm_transacao */
class EcmVenda extends Entity
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
