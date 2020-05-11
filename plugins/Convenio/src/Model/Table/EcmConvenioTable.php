<?php
namespace Convenio\Model\Table;

use App\Model\Table\Table;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Convenio\Model\Entity\EcmConvenio;

/**
 * EcmConvenio Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmConvenioTipoInstituicao * @property \Cake\ORM\Association\BelongsTo $EcmConvenioContrato * @property \Cake\ORM\Association\BelongsTo $MdlCidade * @property \Cake\ORM\Association\HasMany $EcmConvenioInteresse */
class EcmConvenioTable extends Table
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

        $this->table('ecm_convenio');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmConvenioTipoInstituicao', [
            'foreignKey' => 'ecm_convenio_tipo_instituicao_id',
            'joinType' => 'INNER',
            'className' => 'Convenio.EcmConvenioTipoInstituicao'
        ]);
        $this->belongsTo('EcmConvenioContrato', [
            'foreignKey' => 'ecm_convenio_contrato_id',
            'className' => 'Convenio.EcmConvenioContrato',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('MdlCidade', [
            'foreignKey' => 'mdl_cidade_id',
            'joinType' => 'LEFT',
            'className' => 'MdlCidade'
        ]);
        $this->MdlCidade->belongsTo('MdlEstado', [
            'foreignKey' => 'uf',
            'joinType' => 'LEFT',
            'className' => 'MdlEstado'
        ]);
        $this->hasMany('EcmConvenioInteresse', [
            'foreignKey' => 'ecm_convenio_id',
            'className' => 'Convenio.EcmConvenioInteresse'
        ]);
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'logo_instituicao'=>[
                'path' => 'webroot/upload/convenio/{time}',
                'fields' => [
                    'dir' => 'imagem_dir',
                    'size' => 'imagem_size',
                    'type' => 'imagem_type',
                ]
            ]
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
            ->requirePresence('nome_responsavel', 'create')            ->notEmpty('nome_responsavel');
        $validator
            ->allowEmpty('nome_coordenador');
        $validator
            ->requirePresence('nome_instituicao', 'create')            ->notEmpty('nome_instituicao');
        $validator
            ->allowEmpty('curso');
        $validator
            ->allowEmpty('disciplina');
        $validator
            ->allowEmpty('cargo');
        $validator
            ->email('email')            ->requirePresence('email', 'create')            ->notEmpty('email');
        $validator
            ->requirePresence('telefone', 'create')            ->notEmpty('telefone');

        $validator
            ->allowEmpty('mdl_cidade_id');
        /*$validator
            ->requirePresence('mdl_cidade_id', 'create')            ->notEmpty('mdl_cidade_id');*/

        $validator->provider('uploadImagem', \Josegonzalez\Upload\Validation\ImageValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);

        $validator->add('logo_instituicao', 'imagemBelowMaxWidth', [
            'rule' => ['isBelowMaxWidth', 500],
            'message' => 'Largura da imagem maior do que o permitido, máximo de 500px',
            'provider' => 'uploadImagem'
        ]);

        $validator->add('logo_instituicao', 'imagemBelowMaxHeight', [
            'rule' => ['isBelowMaxHeight', 500],
            'message' => 'Altura da imagem maior do que o permitido, máximo de 500px',
            'provider' => 'uploadImagem'
        ]);

        $validator->add('logo_instituicao', 'imagemBelowMaxSize', [
            'rule' => ['isBelowMaxSize', 200000],
            'message' => 'Tamanho do arquivo maior do que o permitido, máximo de 200 KB',
            'provider' => 'upload'
        ]);
        $validator->allowEmpty('logo_instituicao');

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
        $rules->add($rules->existsIn(['ecm_convenio_tipo_instituicao_id'], 'EcmConvenioTipoInstituicao'));
        $rules->add($rules->existsIn(['ecm_convenio_contrato_id'], 'EcmConvenioContrato'));
        $rules->add($rules->existsIn(['mdl_cidade_id'], 'MdlCidade'));
        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entityInterface, \ArrayObject $options){
        $entity = $event->data['entity'];

        if(is_null($entity->id)){
            $entity->set('data_registro', new \DateTime());
        }

        if(!is_null($entity->imagem_dir)) {
            if (!is_null($entity->id)) {
                $this->deletarArquivo($event->data['entity']);
            }
            $entity->logo = substr(strrchr($entity->imagem_dir, '/'), 1) . '/' . $entity->logo_instituicao;
        }
        return true;
    }

    public function afterDelete(Event $event, EntityInterface $entity, \ArrayObject $options){
        parent::afterDelete($event, $entity, $options);
        $this->deletarArquivo($event->data['entity']);
    }

    private function deletarArquivo(EcmConvenio $entity){
        if(!empty($entity->logo)) {
            $file = new File(WWW_ROOT . 'upload/convenio/' . $entity->logo);
            $file->delete();

            $folder = new Folder(WWW_ROOT . 'upload/convenio/' . substr($entity->logo, 0, strpos($entity->logo, '/')));
            if($folder->dirsize() == 0)
                $folder->delete();
        }
    }
}
