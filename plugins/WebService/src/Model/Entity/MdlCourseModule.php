<?php
namespace WebService\Model\Entity;

use Cake\ORM\Entity;

/**
 * MdlCourseModule Entity.
 *
 * @property int $id * @property int $course * @property int $module * @property int $instance * @property int $section * @property string $idnumber * @property int $added * @property int $score * @property int $indent * @property bool $visible * @property bool $visibleold * @property int $groupmode * @property int $groupingid * @property bool $completion * @property int $completiongradeitemnumber * @property bool $completionview * @property int $completionexpected * @property bool $showdescription * @property string $availability */
class MdlCourseModule extends Entity
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
