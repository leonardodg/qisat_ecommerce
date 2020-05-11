<?php
namespace Carrinho\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmVendaStatus Entity.
 *
 * @property int $id * @property string $status * @property \Carrinho\Model\Entity\EcmVenda[] $ecm_venda */
class EcmVendaStatus extends Entity
{

    const STATUS_ANDAMENTO = 'Andamento';
    const STATUS_FINALIZADO = 'Finalizada';
    const STATUS_ESTORNO = 'Estorno';
    const STATUS_BOLETO_NAO_PAGO = 'Boleto Não Pago';
    const STATUS_CANCELADO = 'Cancelado';

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
