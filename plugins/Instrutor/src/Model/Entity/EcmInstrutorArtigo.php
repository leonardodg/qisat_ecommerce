<?php
namespace Instrutor\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmInstrutorArtigo Entity.
 *
 * @property int $id * @property int $ecm_instrutor_id * @property \Instrutor\Model\Entity\EcmInstrutor $ecm_instrutor * @property string $titulo * @property string $link */
class EcmInstrutorArtigo extends Entity
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
