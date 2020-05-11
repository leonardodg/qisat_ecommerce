<?php
namespace WebService\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MdlCertificateFixture
 *
 */
class MdlCertificateFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'mdl_certificate';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'course' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'intro' => ['type' => 'text', 'length' => 4294967295, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'introformat' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'emailteachers' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'emailothers' => ['type' => 'text', 'length' => 4294967295, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'savecert' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'reportcert' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'delivery' => ['type' => 'integer', 'length' => 3, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'requiredtime' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'certificatetype' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'orientation' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'borderstyle' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'fixed' => null],
        'bordercolor' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'fixed' => null],
        'printwmark' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'fixed' => null],
        'printdate' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'datefmt' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'printnumber' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'printgrade' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'gradefmt' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'printoutcome' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'printhours' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'printteacher' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'customtext' => ['type' => 'text', 'length' => 4294967295, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'printsignature' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'fixed' => null],
        'printseal' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'fixed' => null],
        'timecreated' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'timemodified' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
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
            'name' => 'Lorem ipsum dolor sit amet',
            'intro' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'introformat' => 1,
            'emailteachers' => 1,
            'emailothers' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'savecert' => 1,
            'reportcert' => 1,
            'delivery' => 1,
            'requiredtime' => 1,
            'certificatetype' => 'Lorem ipsum dolor sit amet',
            'orientation' => 'Lorem ip',
            'borderstyle' => 'Lorem ipsum dolor sit amet',
            'bordercolor' => 'Lorem ipsum dolor sit amet',
            'printwmark' => 'Lorem ipsum dolor sit amet',
            'printdate' => 1,
            'datefmt' => 1,
            'printnumber' => 1,
            'printgrade' => 1,
            'gradefmt' => 1,
            'printoutcome' => 1,
            'printhours' => 'Lorem ipsum dolor sit amet',
            'printteacher' => 1,
            'customtext' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'printsignature' => 'Lorem ipsum dolor sit amet',
            'printseal' => 'Lorem ipsum dolor sit amet',
            'timecreated' => 1,
            'timemodified' => 1
        ],
    ];
}
