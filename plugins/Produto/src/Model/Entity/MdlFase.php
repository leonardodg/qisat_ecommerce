<?php
namespace Produto\Model\Entity;

use Cake\ORM\Entity;

/**
 * MdlFase Entity.
 *
 * @property int $id * @property string $descricao * @property float $valor_carga_horaria * @property int $enrolperiod * @property int $enrol_period_finish * @property int $ecm_produto_id * @property \Produto\Model\Entity\EcmProduto $ecm_produto * @property \Produto\Model\Entity\MdlGroup[] $mdl_groups * @property \Produto\Model\Entity\MdlCourse[] $mdl_course */
class MdlFase extends Entity
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
