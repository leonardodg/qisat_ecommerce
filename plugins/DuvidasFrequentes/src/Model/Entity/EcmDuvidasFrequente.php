<?php
namespace DuvidasFrequentes\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmDuvidasFrequente Entity.
 *
 * @property int $id * @property string $titulo * @property string $url * @property int $ordem * @property \Cake\I18n\Time $timemodified */
class EcmDuvidasFrequente extends Entity
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
