<?php
namespace Repasse\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Repasse\Model\Entity\EcmRepasse;
use WebService\Util\WscAltoQi;

/**
 * EcmRepasse Model
 *
 * @property \Cake\ORM\Association\BelongsTo $MdlUser * @property \Cake\ORM\Association\BelongsTo $MdlUser * @property \Cake\ORM\Association\BelongsTo $EcmAlternativeHost * @property \Cake\ORM\Association\BelongsTo $EcmRepasseCategorias * @property \Cake\ORM\Association\BelongsTo $EcmRepasseOrigem */
class EcmRepasseTable extends Table
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

        $this->table('ecm_repasse');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'LEFT',
            'className' => 'Repasse.MdlUser'
        ]);
        $this->belongsTo('MdlUserModified', [
            'foreignKey' => 'mdl_usermodified_id',
            'className' => 'Repasse.MdlUser',
            'propertyName' => 'MdlUserModified'
        ]);
        $this->belongsTo('MdlUserCliente', [
            'foreignKey' => 'mdl_user_cliente_id',
            'className' => 'Repasse.MdlUser',
            'propertyName' => 'MdlUserCliente'
        ]);
        $this->belongsTo('EcmAlternativeHost', [
            'foreignKey' => 'ecm_alternative_host_id',
            'joinType' => 'INNER',
            'className' => 'Repasse.EcmAlternativeHost'
        ]);
        $this->belongsTo('EcmRepasseCategorias', [
            'foreignKey' => 'ecm_repasse_categorias_id',
            'className' => 'Repasse.EcmRepasseCategorias'
        ]);
        $this->belongsTo('EcmRepasseOrigem', [
            'foreignKey' => 'ecm_repasse_origem_id',
            'className' => 'Repasse.EcmRepasseOrigem'
        ]);

        $this->hasMany('EcmRepasseUserData', [
            'foreignKey' => 'ecm_repasse_id',
            'className' => 'Repasse.EcmRepasseUserData'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');
        $validator
            ->dateTime('data_registro')            ->requirePresence('data_registro', 'create')            ->notEmpty('data_registro');
        $validator
            ->requirePresence('status', 'create')            ->notEmpty('status');
/*
        $validator
            ->requirePresence('ecm_alternative_host_id', 'create')            ->notEmpty('ecm_alternative_host_id');
*/
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['mdl_user_id'], 'MdlUser'));
        $rules->add($rules->existsIn(['mdl_usermodified_id'], 'MdlUser'));
        $rules->add($rules->existsIn(['ecm_alternative_host_id'], 'EcmAlternativeHost'));
        $rules->add($rules->existsIn(['ecm_repasse_categorias_id'], 'EcmRepasseCategorias'));
        $rules->add($rules->existsIn(['ecm_repasse_origem_id'], 'EcmRepasseOrigem'));
        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options){
        $entity = $event->data['entity'];

        if(is_null($entity->id)){
            $entity->set('data_registro', new \DateTime());
        }else {
            $entity->set('data_modificacao', new \DateTime());
        }

        return true;
    }

    public function afterSave(Event $event, EntityInterface $entity, \ArrayObject $options){

        if($entity->get('enviar_email') == "1" || !is_null($entity->get('mdl_user_id')))
            $this->enviarEmail($entity);

        if(file_exists(ROOT . '/plugins/WebService/Util/WscAltoQi.php') && $entity->get('ecm_repasse_categorias_id') != 7){ // Novo Cadastro
            return $this->enviarRepasseAltoQi($entity);
        }

        return true;
    }

    private function enviarEmail($entity){
        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');

        $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $emailCentral = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;
        $email = new Email('default');

        $assunto_email = $entity->get('assunto_email');
        $corpo_email = $entity->get('corpo_email') . '<br><br>Observação: ' . $entity->get('observacao');

        if(is_null($corpo_email)){
            $corpo_email = $assunto_email;
            $assunto_email = 'Repasse de origem ' . ($entity->get('ecm_alternative_host_id') == 1 ? 'QiSat' : 'AltoQi');
        } else if(is_null($entity->get('assunto_email')))
            $assunto_email = 'Repasse de origem ' . ($entity->get('ecm_alternative_host_id') == 1 ? 'QiSat' : 'AltoQi');

        $email->from([$fromEmail => $fromEmailTitle])
            ->bcc($adminEmail)
            ->emailFormat('html')
            ->template('default')
            ->subject($assunto_email);

        if(is_null($entity->get('mdl_user_id'))){
            $email->to($emailCentral);
        } else {
            $atendente = $this->MdlUser->get($entity->get('mdl_user_id'));
            $email->to($atendente->email)
                ->cc($emailCentral);
        }

        if($entity->get('ecm_repasse_categorias_id') == 7){ // Novo Cadastro
            $mktEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_marketing'])->first()->valor;
            $email->addTo($mktEmail);
        }

        $email->send($corpo_email);
    }

    private function enviarRepasseAltoQi(EntityInterface $entity){
        $chamado = [
            "IdCategoriaContato" => isset($entity->IdCategoriaContato)?$entity->get("IdCategoriaContato"):1, // Comercial
            "IdTipoContato" => isset($entity->IdTipoContato)?$entity->get("IdTipoContato"):6, // Interno
            "Situacao" => $entity->get('observacao')
        ];

        $this->MdlUser = TableRegistry::get('MdlUser');
        if(!is_null($entity->get('mdl_user_cliente_id'))){
            $chaveCliente = $this->MdlUser->find()->where(['id' => $entity->get('mdl_user_cliente_id')])->first()->idnumber;
            $chamado["IdEntidade"] = $chaveCliente;
        }
        if(!is_null($entity->get('mdl_user_id'))){
            $chaveAtendente = $this->MdlUser->find()->where(['id' => $entity->get('mdl_user_id')])->first()->idnumber;
            $chamado["IdUsuario"] = $chaveAtendente;
        }

        return WscAltoQi::send('repasse/insert', ['chamado' => $chamado], false);
    }
}
