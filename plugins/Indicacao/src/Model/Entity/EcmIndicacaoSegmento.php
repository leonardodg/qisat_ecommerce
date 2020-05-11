<?php
namespace Indicacao\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmIndicacaoSegmento Entity.
 *
 * @property int $id * @property string $segmento * @property \Indicacao\Model\Entity\EcmIndicacaoCurso[] $ecm_indicacao_curso */
class EcmIndicacaoSegmento extends Entity
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
