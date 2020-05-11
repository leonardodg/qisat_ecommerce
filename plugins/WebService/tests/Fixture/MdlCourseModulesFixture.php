<?php
namespace WebService\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MdlCourseModulesFixture
 *
 */
class MdlCourseModulesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'course' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'module' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'instance' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'section' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'idnumber' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'added' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'score' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'indent' => ['type' => 'integer', 'length' => 5, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'visible' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'visibleold' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'groupmode' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'groupingid' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'completion' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'completiongradeitemnumber' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'completionview' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'completionexpected' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'showdescription' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'availability' => ['type' => 'text', 'length' => 4294967295, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'mdl_courmodu_vis_ix' => ['type' => 'index', 'columns' => ['visible'], 'length' => []],
            'mdl_courmodu_cou_ix' => ['type' => 'index', 'columns' => ['course'], 'length' => []],
            'mdl_courmodu_mod_ix' => ['type' => 'index', 'columns' => ['module'], 'length' => []],
            'mdl_courmodu_ins_ix' => ['type' => 'index', 'columns' => ['instance'], 'length' => []],
            'mdl_courmodu_idncou_ix' => ['type' => 'index', 'columns' => ['idnumber', 'course'], 'length' => []],
            'mdl_courmodu_gro_ix' => ['type' => 'index', 'columns' => ['groupingid'], 'length' => []],
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
            'course' => 1,
            'module' => 1,
            'instance' => 1,
            'section' => 1,
            'idnumber' => 'Lorem ipsum dolor sit amet',
            'added' => 1,
            'score' => 1,
            'indent' => 1,
            'visible' => 1,
            'visibleold' => 1,
            'groupmode' => 1,
            'groupingid' => 1,
            'completion' => 1,
            'completiongradeitemnumber' => 1,
            'completionview' => 1,
            'completionexpected' => 1,
            'showdescription' => 1,
            'availability' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
        ],
    ];
}
