<?php
namespace WebService\Model\Entity;

use Cake\ORM\Entity;

/**
 * MdlCourse Entity.
 *
 * @property int $id * @property int $category * @property int $sortorder * @property string $fullname * @property string $shortname * @property string $idnumber * @property string $summary * @property int $summaryformat * @property string $format * @property int $showgrades * @property int $newsitems * @property int $startdate * @property int $marker * @property int $maxbytes * @property int $legacyfiles * @property int $showreports * @property bool $visible * @property bool $visibleold * @property int $groupmode * @property int $groupmodeforce * @property int $defaultgroupingid * @property string $lang * @property string $calendartype * @property string $theme * @property int $timecreated * @property int $timemodified * @property bool $requested * @property bool $enablecompletion * @property bool $completionnotify * @property int $cacherev * @property int $timeaccesssection * @property \WebService\Model\Entity\EcmProduto[] $ecm_produto */
class MdlCourse extends Entity
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
