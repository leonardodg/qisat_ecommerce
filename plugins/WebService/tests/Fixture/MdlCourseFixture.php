<?php
namespace WebService\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MdlCourseFixture
 *
 */
class MdlCourseFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'mdl_course';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'category' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'sortorder' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fullname' => ['type' => 'string', 'length' => 254, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'shortname' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'idnumber' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'summary' => ['type' => 'text', 'length' => 4294967295, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'summaryformat' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'format' => ['type' => 'string', 'length' => 21, 'null' => false, 'default' => 'topics', 'comment' => '', 'precision' => null, 'fixed' => null],
        'showgrades' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'newsitems' => ['type' => 'integer', 'length' => 5, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'startdate' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'marker' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'maxbytes' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'legacyfiles' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'showreports' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'visible' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'visibleold' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'groupmode' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'groupmodeforce' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'defaultgroupingid' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'lang' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'calendartype' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'theme' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'timecreated' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'timemodified' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'requested' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'enablecompletion' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'completionnotify' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'cacherev' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'timeaccesssection' => ['type' => 'integer', 'length' => 6, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'mdl_cour_cat_ix' => ['type' => 'index', 'columns' => ['category'], 'length' => []],
            'mdl_cour_idn_ix' => ['type' => 'index', 'columns' => ['idnumber'], 'length' => []],
            'mdl_cour_sho_ix' => ['type' => 'index', 'columns' => ['shortname'], 'length' => []],
            'mdl_cour_sor_ix' => ['type' => 'index', 'columns' => ['sortorder'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'category' => 1,
            'sortorder' => 1,
            'fullname' => 'Lorem ipsum dolor sit amet',
            'shortname' => 'Lorem ipsum dolor sit amet',
            'idnumber' => 'Lorem ipsum dolor sit amet',
            'summary' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'summaryformat' => 1,
            'format' => 'Lorem ipsum dolor s',
            'showgrades' => 1,
            'newsitems' => 1,
            'startdate' => 1,
            'marker' => 1,
            'maxbytes' => 1,
            'legacyfiles' => 1,
            'showreports' => 1,
            'visible' => 1,
            'visibleold' => 1,
            'groupmode' => 1,
            'groupmodeforce' => 1,
            'defaultgroupingid' => 1,
            'lang' => 'Lorem ipsum dolor sit amet',
            'calendartype' => 'Lorem ipsum dolor sit amet',
            'theme' => 'Lorem ipsum dolor sit amet',
            'timecreated' => 1,
            'timemodified' => 1,
            'requested' => 1,
            'enablecompletion' => 1,
            'completionnotify' => 1,
            'cacherev' => 1,
            'timeaccesssection' => 1
        ],
    ];
}
