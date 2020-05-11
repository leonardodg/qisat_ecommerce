<?php
namespace FormaPagamento\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmOperadoraPagamento Entity.
 *
 * @property int $id * @property string $nome * @property string $dataname * @property string $descricao * @property string $habilitado * @property int $ecm_imagem_id * @property \FormaPagamento\Model\Entity\EcmImagem $ecm_imagem * @property int $ecm_forma_pagamento_id * @property \FormaPagamento\Model\Entity\EcmFormaPagamento $ecm_forma_pagamento */
class EcmOperadoraPagamento extends Entity
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
        'id' => true,
    ];
}
