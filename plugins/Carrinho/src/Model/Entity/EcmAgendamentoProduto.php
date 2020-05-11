<?php
namespace Carrinho\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmAgendamentoProduto Entity.
 *
 * @property int $id * @property int $ecm_carrinho_item_id * @property \Carrinho\Model\Entity\EcmCarrinhoItem $ecm_carrinho_item * @property \Cake\I18n\Time $datainicio * @property int $duracao * @property string $pedido */
class EcmAgendamentoProduto extends Entity
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
