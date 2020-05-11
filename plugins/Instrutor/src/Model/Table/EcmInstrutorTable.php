<?php
namespace Instrutor\Model\Table;

use App\Model\Table\Table;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Instrutor\Model\Entity\EcmInstrutor;

/**
 * EcmInstrutor Model
 *
 * @property \Cake\ORM\Association\BelongsTo $MdlUser * @property \Cake\ORM\Association\BelongsTo $EcmImagem * @property \Cake\ORM\Association\HasMany $EcmInstrutorArtigo * @property \Cake\ORM\Association\HasMany $EcmInstrutorRedeSocial * @property \Cake\ORM\Association\BelongsToMany $EcmProduto * @property \Cake\ORM\Association\BelongsToMany $EcmInstrutorArea*/
class EcmInstrutorTable extends Table
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

        $this->table('ecm_instrutor');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'INNER',
            'className' => 'Instrutor.MdlUser'
        ]);
        $this->belongsTo('EcmImagem', [
            'foreignKey' => 'ecm_imagem_id',
            'className' => 'Instrutor.EcmImagem'
        ]);
        $this->hasMany('EcmInstrutorArtigo', [
            'foreignKey' => 'ecm_instrutor_id',
            'className' => 'Instrutor.EcmInstrutorArtigo'
        ]);

        $this->hasMany('EcmInstrutorRedeSocial', [
            'foreignKey' => 'ecm_instrutor_id',
            'className' => 'Instrutor.EcmInstrutorRedeSocial'
        ]);
        $this->belongsToMany('EcmRedeSocial', [
            'foreignKey' => 'ecm_instrutor_id',
            'targetForeignKey' => 'ecm_rede_social_id',
            'joinTable' => 'ecm_instrutor_rede_social',
            'className' => 'Instrutor.EcmRedeSocial'
        ]);

        $this->hasMany('EcmInstrutorEcmProduto', [
            'foreignKey' => 'ecm_instrutor_id',
            'className' => 'Instrutor.EcmInstrutorEcmProduto'
        ]);
        $this->belongsToMany('EcmProduto', [
            'foreignKey' => 'ecm_instrutor_id',
            'targetForeignKey' => 'ecm_produto_id',
            'joinTable' => 'ecm_instrutor_ecm_produto',
            'className' => 'Produto.EcmProduto'
        ]);

        $this->belongsToMany('EcmInstrutorArea', [
            'foreignKey' => 'ecm_instrutor_id',
            'targetForeignKey' => 'ecm_instrutor_area_id',
            'joinTable' => 'ecm_instrutor_ecm_instrutor_area',
            'className' => 'Instrutor.EcmInstrutorArea'
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'imagem'=>[
                'path' => 'webroot/upload/instrutor/{time}',
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
        $validator->dateTime('data_publicacao',['dmY'])
            ->allowEmpty('data_publicacao', 'create');
        $validator->dateTime('data_modificacao',['dmY'])
            ->allowEmpty('data_modificacao', 'create');
        $validator
            ->requirePresence('mdl_user_id', 'create')            ->notEmpty('mdl_user_id');
        $validator
            ->allowEmpty('imagem');

        $validator->add('imagem', 'imagemBelowMaxWidth', [
            'rule' => ['isBelowMaxWidth', 500],
            'message' => 'Largura da imagem maior do que o permitido, máximo de 500px',
            'provider' => 'uploadImagem'
        ]);

        $validator->add('imagem', 'imagemBelowMaxHeight', [
            'rule' => ['isBelowMaxHeight', 500],
            'message' => 'Altura da imagem maior do que o permitido, máximo de 500px',
            'provider' => 'uploadImagem'
        ]);

        $validator->add('imagem', 'imagemBelowMaxSize', [
            'rule' => ['isBelowMaxSize', 400000],
            'message' => 'Tamanho do arquivo maior do que o permitido, máximo de 400 KB',
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
        $rules->add($rules->existsIn(['mdl_user_id'], 'MdlUser'));
        $rules->add($rules->existsIn(['ecm_imagem_id'], 'EcmImagem'));
        return $rules;
    }

    public function beforeSave(Event $event){
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

    private function deletarArquivo(EcmInstrutor $entity){
        $src = str_replace('/'.$entity->ecm_imagem->nome,'',$entity->ecm_imagem->src);

        $file = new File(WWW_ROOT.'upload/'.$src.'/'.$entity->ecm_imagem->nome);
        $file->delete();

        $folder = new Folder(WWW_ROOT.'upload/'.$src);
        if($folder->dirsize() == 0)
            $folder->delete();
    }
}
