<?php
namespace Produto\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmTipoProduto Entity.
 *
 * @property int $id * @property string $nome * @property int $ecm_tipo_produto_id * @property int $ordem * @property string $habilitado * @property string $blocked * @property int $categoria * @property string $theme * @property \Produto\Model\Entity\EcmTipoProduto[] $ecm_tipo_produto * @property \Produto\Model\Entity\EcmProduto[] $ecm_produto */
class EcmTipoProduto extends Entity
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

    public static function verificarTipoProduto($listaTipos, $idTipo){
        foreach($listaTipos as $tipo){
            if($tipo->get('id') == $idTipo)
                return true;
        }
        return false;
    }
}
