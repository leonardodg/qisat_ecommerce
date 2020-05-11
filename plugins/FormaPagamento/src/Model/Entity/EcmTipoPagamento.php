<?php
namespace FormaPagamento\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmTipoPagamento Entity.
 *
 * @property int $id * @property string $nome * @property string $habilitado * @property string $descricao * @property string $dataname * @property int $ecm_forma_pagamento_id * @property \FormaPagamento\Model\Entity\EcmFormaPagamento $ecm_forma_pagamento */
class EcmTipoPagamento extends Entity
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
