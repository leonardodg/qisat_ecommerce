<?php
namespace Convenio\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Repasse\Model\Entity\EcmRepasseUserData;


/**
 * EcmRepasseUserDataTable Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmRepasse */
class EcmRepasseUserDataTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('ecm_repasse_user_data');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->belongsTo('EcmRepasse');
    }
}
