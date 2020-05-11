<?php
namespace FormaPagamento\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmFormaPagamento Entity.
 *
 * @property int $id * @property string $nome * @property string $dataname * @property string $descricao * @property string $habilitado * @property int $parcelas * @property \FormaPagamento\Model\Entity\EcmOperadoraPagamento[] $ecm_operadora_pagamento * @property \FormaPagamento\Model\Entity\EcmTipoPagamento[] $ecm_tipo_pagamento */
class EcmFormaPagamento extends Entity
{
    const TIPO_BOLETO = 'boleto';
    const TIPO_CARTAO = 'cartao';
    const TIPO_ONLINE = 'online';
    const TIPO_CARTAO_RECORRENCIA = 'cartao_recorrencia';

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
