<?php
namespace Produto\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Produto\Model\Entity\EcmProdutoInfo;

/**
 * EcmProdutoInfo Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmProduto * @property \Cake\ORM\Association\HasMany $EcmProdutoInfoArquivos * @property \Cake\ORM\Association\HasMany $EcmProdutoInfoConteudo */
class EcmProdutoInfoTable extends Table
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

        $this->table('ecm_produto_info');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmProduto', [
            'foreignKey' => 'ecm_produto_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmProduto'
        ]);
        $this->hasMany('EcmProdutoInfoConteudo', [
            'foreignKey' => 'ecm_produto_info_id',
            'className' => 'Produto.EcmProdutoInfoConteudo'
        ]);
        $this->hasMany('EcmProdutoInfoFaq', [
            'foreignKey' => 'ecm_produto_info_id',
            'className' => 'Produto.EcmProdutoInfoFaq'
        ]);
        $this->hasMany('EcmProdutoInfoArquivos', [
            'foreignKey' => 'ecm_produto_info_id',
            'className' => 'Produto.EcmProdutoInfoArquivos'
        ]);
        $this->EcmProdutoInfoArquivos->belongsTo('EcmImagem', [
            'foreignKey' => 'ecm_imagem_id',
            'className' => 'Imagem.EcmImagem'
        ]);
        $this->EcmProdutoInfoArquivos->belongsTo('EcmProdutoInfoArquivosTipos', [
            'foreignKey' => 'ecm_produto_info_arquivos_tipos_id',
            'className' => 'Produto.EcmProdutoInfoArquivosTipos'
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
            ->allowEmpty('titulo');
        $validator
            ->allowEmpty('chamada');
        $validator
            ->allowEmpty('persona');
        $validator
            ->allowEmpty('descricao');
        $validator
            ->integer('qtd_aulas')            ->allowEmpty('qtd_aulas');
        $validator
            ->integer('tempo_acesso')            ->allowEmpty('tempo_acesso');
        $validator
            ->integer('tempo_aula')            ->allowEmpty('tempo_aula');
        $validator
            ->integer('carga_horaria')            ->allowEmpty('carga_horaria');
        $validator
            ->boolean('material')            ->allowEmpty('material');
        $validator
            ->boolean('certificado_digital')            ->allowEmpty('certificado_digital');
        $validator
            ->boolean('certificado_impresso')            ->allowEmpty('certificado_impresso');
        $validator
            ->boolean('forum')            ->allowEmpty('forum');
        $validator
            ->boolean('tira_duvidas')            ->allowEmpty('tira_duvidas');
        $validator
            ->boolean('mobile')            ->allowEmpty('mobile');
        $validator
            ->boolean('software_demo')            ->allowEmpty('software_demo');
        $validator
            ->boolean('simulador')            ->allowEmpty('simulador');
        $validator
            ->allowEmpty('disponibilidade');
        $validator
            ->allowEmpty('metatag_titulo');
        $validator
            ->allowEmpty('metatag_key');
        $validator
            ->allowEmpty('metatag_descricao');
        $validator
            ->allowEmpty('url');
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
        $rules->add($rules->existsIn(['ecm_produto_id'], 'EcmProduto'));
        return $rules;
    }
}
