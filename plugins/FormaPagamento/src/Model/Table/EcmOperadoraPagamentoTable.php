<?php
namespace FormaPagamento\Model\Table;

use App\Model\Table\Table;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use FormaPagamento\Model\Entity\EcmOperadoraPagamento;
use Cake\Filesystem\FileFile;

/**
 * EcmOperadoraPagamento Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmImagem * @property \Cake\ORM\Association\BelongsTo $EcmFormaPagamento */
class EcmOperadoraPagamentoTable extends Table
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

        $this->table('ecm_operadora_pagamento');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmImagem', [
            'foreignKey' => 'ecm_imagem_id',
            'className' => 'FormaPagamento.EcmImagem'
        ]);
        $this->belongsTo('EcmFormaPagamento', [
            'foreignKey' => 'ecm_forma_pagamento_id',
            'className' => 'FormaPagamento.EcmFormaPagamento'
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'imagem'=>[
                'path' => 'webroot/upload/forma-pagamento/{time}',
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
        $validator->provider('uploadImagem', \Josegonzalez\Upload\Validation\ImageValidation::class);
        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);

        $validator
            ->integer('id')            ->allowEmpty('id', 'create');
        $validator
            ->requirePresence('nome', 'create')            ->notEmpty('nome')            ->add('nome', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);
        $validator
            ->allowEmpty('dataname');
        $validator
            ->allowEmpty('descricao');
        $validator
            ->requirePresence('habilitado', 'create')            ->notEmpty('habilitado');
        $validator
            ->allowEmpty('ecm_imagem_id');
        $validator
            ->notEmpty('imagem');

        $validator->add('imagem', 'imagemBelowMaxWidth', [
            'rule' => ['isBelowMaxWidth', 52],
            'message' => 'Largura da imagem maior do que o permitido, máximo de 52px',
            'provider' => 'uploadImagem'
        ]);

        $validator->add('imagem', 'imagemBelowMaxHeight', [
            'rule' => ['isBelowMaxHeight', 52],
            'message' => 'Altura da imagem maior do que o permitido, máximo de 52px',
            'provider' => 'uploadImagem'
        ]);

        $validator->add('imagem', 'imagemBelowMaxSize', [
            'rule' => ['isBelowMaxSize', 100000],
            'message' => 'Tamanho do arquivo maior do que o permitido, máximo de 100 KB',
            'provider' => 'upload'
        ]);
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
        $rules->add($rules->isUnique(['nome']));
        $rules->add($rules->existsIn(['ecm_imagem_id'], 'EcmImagem'));
        $rules->add($rules->existsIn(['ecm_forma_pagamento_id'], 'EcmFormaPagamento'));
        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options){
        $entity = $event->data['entity'];

        if(isset($entity->imagem) && !is_null($entity->id)
            &&is_object($entity->ecm_imagem)){
            $this->deletarArquivo($event->data['entity']);
        }
    }

    public function afterDelete(Event $event, EntityInterface $entity, \ArrayObject $options){
        parent::afterDelete($event, $entity, $options);
        $this->deletarArquivo($event->data['entity']);
    }

    private function deletarArquivo(EcmOperadoraPagamento $entity){
        $src = str_replace('/'.$entity->ecm_imagem->nome,'',$entity->ecm_imagem->src);

        $file = new File(WWW_ROOT.'upload/'.$entity->ecm_imagem->nome);
        $file->delete();

        $folder = new Folder(WWW_ROOT . 'upload/' . $src);
        if(empty($folder->find()))
            $folder->delete();
    }
}