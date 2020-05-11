<?php
namespace Promocao\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmPromocao Entity.
 *
 * @property int $id * @property int $datainicio * @property int $datafim * @property float $descontovalor * @property float $descontoporcentagem * @property string $descricao * @property string $habilitado * @property string $arredondamento * @property \Promocao\Model\Entity\EcmAlternativeHost[] $ecm_alternative_host * @property \Promocao\Model\Entity\EcmProduto[] $ecm_produto */
class EcmPromocao extends Entity
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
