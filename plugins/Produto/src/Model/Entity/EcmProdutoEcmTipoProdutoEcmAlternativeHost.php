<?php
namespace Produto\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmProdutoEcmTipoProdutoEcmAlternativeHost Entity.
 *
 * @property int $id * @property int $ecm_produto_tipo_produto_id * @property \Produto\Model\Entity\EcmProdutoEcmTipoProduto $ecm_produto_ecm_tipo_produto * @property int $ecm_alternative_host_id * @property \Produto\Model\Entity\EcmAlternativeHost $ecm_alternative_host * @property int $ordem */
class EcmProdutoEcmTipoProdutoEcmAlternativeHost extends Entity
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
