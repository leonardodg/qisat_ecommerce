<?php
namespace WebService\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmValidacaoRecaptchaFixture
 *
 */
class EcmValidacaoRecaptchaFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_validacao_recaptcha';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'action' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'controller' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'plugin' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'action_controller_plugin' => ['type' => 'unique', 'columns' => ['action', 'controller', 'plugin'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
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
            'action' => 'Lorem ipsum dolor sit amet',
            'controller' => 'Lorem ipsum dolor sit amet',
            'plugin' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
