<?php
namespace WebService\Model\Entity;

use Cake\ORM\Entity;

/**
 * MdlUserEnrolment Entity.
 *
 * @property int $id * @property int $status * @property int $enrolid * @property \App\Model\Entity\MdlEnrol $mdl_enrol * @property int $userid * @property int $timestart * @property int $timeend * @property int $modifierid * @property int $timecreated * @property int $timemodified */
class MdlUserEnrolment extends Entity
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
