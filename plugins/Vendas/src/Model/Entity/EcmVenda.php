<?php
namespace Vendas\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmVenda Entity.
 *
 * @property int $id * @property \Cake\I18n\Time $data * @property float $valor_parcelas * @property int $pedido * @property bool $pedido_status * @property int $numero_parcelas * @property int $ecm_venda_status_id * @property \Vendas\Model\Entity\EcmVendaStatus $ecm_venda_status * @property int $mdl_user_id * @property \Vendas\Model\Entity\MdlUser $mdl_user * @property int $ecm_operadora_pagamento_id * @property \Vendas\Model\Entity\EcmOperadoraPagamento $ecm_operadora_pagamento * @property int $ecm_tipo_pagamento_id * @property \Vendas\Model\Entity\EcmTipoPagamento $ecm_tipo_pagamento * @property int $ecm_carrinho_id * @property \Vendas\Model\Entity\EcmCarrinho $ecm_carrinho * @property \Vendas\Model\Entity\EcmCursoPresencialEmailConfirmacao[] $ecm_curso_presencial_email_confirmacao * @property \Vendas\Model\Entity\EcmTransacao[] $ecm_transacao * @property \Vendas\Model\Entity\EcmVendaBoleto[] $ecm_venda_boleto */
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
