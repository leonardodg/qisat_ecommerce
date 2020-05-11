<?php
namespace WebService\Model\Entity;

use Cake\ORM\Entity;

/**
 * MdlCertificate Entity.
 *
 * @property int $id * @property int $course * @property string $name * @property string $intro * @property int $introformat * @property bool $emailteachers * @property string $emailothers * @property bool $savecert * @property bool $reportcert * @property int $delivery * @property int $requiredtime * @property string $certificatetype * @property string $orientation * @property string $borderstyle * @property string $bordercolor * @property string $printwmark * @property int $printdate * @property int $datefmt * @property bool $printnumber * @property int $printgrade * @property int $gradefmt * @property int $printoutcome * @property string $printhours * @property int $printteacher * @property string $customtext * @property string $printsignature * @property string $printseal * @property int $timecreated * @property int $timemodified */
class MdlCertificate extends Entity
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
