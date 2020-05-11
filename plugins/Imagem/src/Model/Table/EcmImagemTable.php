<?php
namespace Imagem\Model\Table;

use Cake\Filesystem\Folder;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Imagem\Model\Entity\EcmImagem;

/**
 * EcmImagem Model
 *
 * @property \Cake\ORM\Association\HasMany $EcmInstrutor * @property \Cake\ORM\Association\HasMany $EcmOperadoraPagamento * @property \Cake\ORM\Association\HasMany $EcmRedeSocial * @property \Cake\ORM\Association\BelongsToMany $EcmProduto */
class EcmImagemTable extends Table
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

        $this->table('ecm_imagem');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('EcmInstrutor', [
            'foreignKey' => 'ecm_imagem_id',
            'className' => 'Instrutor.EcmInstrutor'
        ]);
        $this->hasMany('EcmOperadoraPagamento', [
            'foreignKey' => 'ecm_imagem_id',
            'className' => 'FormaPagamento.EcmOperadoraPagamento'
        ]);
        $this->hasMany('EcmRedeSocial', [
            'foreignKey' => 'ecm_imagem_id',
            'className' => 'Configuracao.EcmRedeSocial'
        ]);
        $this->belongsToMany('EcmProduto', [
            'foreignKey' => 'ecm_imagem_id',
            'targetForeignKey' => 'ecm_produto_id',
            'joinTable' => 'ecm_produto_ecm_imagem',
            'className' => 'Produto.EcmProduto'
        ]);
        $this->hasMany('EcmProdutoEcmImagem', [
            'foreignKey' => 'ecm_imagem_id',
            'className' => 'Imagem.EcmProdutoEcmImagem'
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
            ->requirePresence('nome', 'create')            ->notEmpty('nome');
        $validator
            ->requirePresence('src', 'create')            ->notEmpty('src');
        $validator
            ->requirePresence('descricao', 'create')            ->notEmpty('descricao');
        return $validator;
    }

    /**
     * EnviarImagem method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function enviarImagem($requests, $plugin)
    {
        $imagem = [];
        foreach($requests as $key => $request){
            if(!empty($request['src']) || !empty($request['nome']['tmp_name'])){
                if(isset($request['id']) && empty($request['id']))
                    unset($request['id']);
                if(!empty($request['src'])) {
                    if($request['removido'] == 1 && file_exists(WWW_ROOT . 'upload/' . $request['src'])){
                        unlink(WWW_ROOT . 'upload/' . $request['src']);

                        $folder = new Folder(WWW_ROOT . 'upload/' . substr($request['src'], 0, strrpos($request['src'], '/')));
                        if($folder->dirsize() == 0)
                            $folder->delete();
                    }
                }
                if($request['nome']['size'] > 0) {
                    if (!file_exists(WWW_ROOT . 'upload/' . $plugin))
                        mkdir(WWW_ROOT . 'upload/' . $plugin);
                    $diretorio = $plugin . '/' . (time() + $key);
                    if (mkdir(WWW_ROOT . 'upload/' . $diretorio)) {
                        $request['src'] = $diretorio . '/' . $request['nome']['name'];
                        move_uploaded_file($request['nome']['tmp_name'], WWW_ROOT . 'upload/' . utf8_decode($request['src']));
                    }
                }
                if($request['removido'] == 0){
                    $nome = explode("/", $request['src']);
                    $request['nome'] = end($nome);
                    $imagem[] = $request;
                }
            }
        }
        return $imagem;
    }
}
