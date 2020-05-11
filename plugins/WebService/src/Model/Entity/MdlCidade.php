<?php
namespace WebService\Model\Entity;

use Cake\ORM\Entity;

/**
 * MdlCidade Entity.
 *
 * @property int $id * @property int $uf * @property string $nome * @property \WebService\Model\Entity\EcmConvenio[] $ecm_convenio * @property \WebService\Model\Entity\EcmCursoPresencialLocal[] $ecm_curso_presencial_local */
class MdlCidade extends Entity
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
