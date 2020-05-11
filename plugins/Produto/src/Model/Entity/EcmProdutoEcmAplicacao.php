<?php
namespace Produto\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmProdutoEcmAplicacao Entity.
 *
 * @property int $id * @property int $ecm_produto_id * @property \Produto\Model\Entity\EcmProduto $ecm_produto * @property int $ecm_aplicacao_id * @property \Produto\Model\Entity\EcmAplicacao $ecm_aplicacao * @property int $edicao * @property string $codigo_tw * @property int $ativo * @property \Produto\Model\Entity\EcmCarrinhoItemEcmProdutoAplicacao[] $ecm_carrinho_item_ecm_produto_aplicacao */
class EcmProdutoEcmAplicacao extends Entity
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
