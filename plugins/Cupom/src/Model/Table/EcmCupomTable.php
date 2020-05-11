<?php
namespace Cupom\Model\Table;

use App\Model\Entity\MdlUser;
use App\Model\Table\Table;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cupom\Model\Entity\EcmCupom;
use Entidade\Model\Entity\EcmAlternativeHost;

/**
 * EcmCupom Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $EcmProduto * @property \Cake\ORM\Association\BelongsToMany $EcmTipoProduto * @property \Cake\ORM\Association\BelongsToMany $MdlUser * @property \Cake\ORM\Association\BelongsToMany $EcmAlternativeHost */
class EcmCupomTable extends Table
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

        $this->table('ecm_cupom');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsToMany('EcmProduto', [
            'foreignKey' => 'ecm_cupom_id',
            'targetForeignKey' => 'ecm_produto_id',
            'joinTable' => 'ecm_cupom_ecm_produto',
            'className' => 'Produto.EcmProduto',
            'joinType' => 'LEFT'
        ]);
        $this->belongsToMany('EcmTipoProduto', [
            'foreignKey' => 'ecm_cupom_id',
            'targetForeignKey' => 'ecm_tipo_produto_id',
            'joinTable' => 'ecm_cupom_ecm_tipo_produto',
            'className' => 'Cupom.EcmTipoProduto',
            'joinType' => 'LEFT'
        ]);
        $this->belongsToMany('MdlUser', [
            'foreignKey' => 'ecm_cupom_id',
            'targetForeignKey' => 'mdl_user_id',
            'joinTable' => 'ecm_cupom_mdl_user',
            'className' => 'MdlUser'
        ]);
        $this->belongsToMany('EcmAlternativeHost', [
            'foreignKey' => 'ecm_cupom_id',
            'targetForeignKey' => 'ecm_alternative_host_id',
            'joinTable' => 'ecm_cupom_ecm_alternative_host',
            'className' => 'Entidade.EcmAlternativeHost',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('EcmCarrinhoItem', [
            'foreignKey' => 'ecm_cupom_id',
            'className' => 'Carrinho.EcmCarrinhoItem'
        ]);
        $this->hasMany('EcmCupomCampanha', [
            'foreignKey' => 'ecm_cupom_id',
            'className' => 'Cupom.EcmCupomCampanha'
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
            ->date('datainicio',['dmy'])            ->requirePresence('datainicio', 'create')            ->notEmpty('datainicio');
        $validator
            ->date('datafim',['dmy'])            ->requirePresence('datafim', 'create')            ->notEmpty('datafim');
        $validator
            ->allowEmpty('chave');
        $validator
            ->decimal('descontovalor')            ->allowEmpty('descontovalor');
        $validator
            ->decimal('descontoporcentagem')            ->allowEmpty('descontoporcentagem');
        $validator
            ->allowEmpty('descricao');
        $validator
            ->requirePresence('habilitado', 'create')            ->notEmpty('habilitado');
        $validator
            ->allowEmpty('numutilizacoes');
        $validator
            ->requirePresence('nome', 'create')            ->notEmpty('nome');
        $validator
            ->requirePresence('tipo', 'create')            ->notEmpty('tipo');
        $validator
            ->requirePresence('arredondamento', 'create')            ->notEmpty('arredondamento');
        $validator
            ->requirePresence('descontosobretabela', 'create')            ->notEmpty('descontosobretabela');
        return $validator;
    }

    /**
     * @param Event $options
     */
    public function beforeSave(Event $options){

        $entity = $options->data['entity'];

        $entity->descontovalor =  str_replace(',','.',str_replace('.','',$entity->descontovalor));
        $entity->descontoporcentagem =  str_replace(',','.',str_replace('.','',$entity->descontoporcentagem));

        if($entity->tipo == 'produto'){
            $entity->ecm_tipo_produto = array();
        }elseif($entity->tipo == 'tipo'){
            $entity->ecm_produto = array();
        }

        if($entity->tipo_aquisicao != 0){
            $entity->mdl_user = array();
        }

        $options->data['entity'] = $entity;
    }

    /**
     * @param MdlUser $usuario
     * @param EcmAlternativeHost|null $alternativeHost
     * @param null $tipoCupom
     * @param null $chave
     * @return array
     */
    public function buscarCupons(MdlUser $usuario = null, EcmAlternativeHost $alternativeHost = null, $tipoCupom = null, $chave = null){
        $dataAtual = new \DateTime();

        $cupons = $this->find('all')
            ->contain(['EcmTipoProduto', 'EcmProduto'])
            ->where([
                'EcmCupom.habilitado' => 'true',
                'EcmCupom.datainicio <=' => $dataAtual->format('Y-m-d'),
                'EcmCupom.datafim >=' => $dataAtual->format('Y-m-d')
            ])
            ->order(['EcmCupom.id' => 'DESC']);

        if(!is_null($tipoCupom))
            $cupons->andWhere(['EcmCupom.referencia' => $tipoCupom]);

        if(!is_null($alternativeHost))
            $cupons->matching('EcmAlternativeHost', function ($q) use ($alternativeHost) {
                return $q->where(['EcmAlternativeHost.id' => $alternativeHost->id]);
            });

        $cuponsCom = clone $cupons;
        if(is_null($chave)){
            $cupons->where(['EcmCupom.tipo_aquisicao' => EcmCupom::LOGIN_SEM_USUARIO]);
            $cupons = $cupons->toArray();

            if(!is_null($usuario) && !is_null($usuario->id)) {
                $cuponsCom->where(['EcmCupom.tipo_aquisicao' => EcmCupom::LOGIN_COM_USUARIO])
                    ->matching('MdlUser', function ($q) use ($usuario) {
                        return $q->where(['MdlUser.id' => $usuario->id]);
                    });
            } else {
                unset($cuponsCom);
            }
        } else {
            $cupons->where([
                'EcmCupom.chave' => $chave,
                'EcmCupom.tipo_aquisicao' => EcmCupom::CAMPANHA_SEM_EMAIL
            ]);
            $cupons = $cupons->toArray();

            if(!is_null($usuario) && !is_null($usuario->email)) {
                $cuponsCom->where([
                    'EcmCupom.tipo_aquisicao' => EcmCupom::CAMPANHA_COM_EMAIL,
                    'EcmCupom.chave' => $chave
                ])->matching('EcmCupomCampanha', function($q)use($usuario){
                    return $q->where(['EcmCupomCampanha.email' => $usuario->email]);
                });
            } else {
                unset($cuponsCom);
            }
        }

        if(isset($cuponsCom))
            $cupons = array_merge($cupons, $cuponsCom->toArray());

        $userid = null;
        if(!is_null($usuario) && !is_null($usuario->id))
            $userid = $usuario->id;

        $retorno = [];
        foreach($cupons as $cupom){
            if($this->verificarUso($cupom, $userid))
                array_push($retorno, $cupom);
        }
        return $retorno;
    }

    /**
     * @param $cupons
     * @param $ecmCarrinho
     * @param null $userid
     * @return null
     */
    public function buscarMelhorCupom($cupons, $ecmCarrinho, $userid=null, $item=null){
        $retorno = null;

        $ecmCarrinho = clone $ecmCarrinho;

        if(!is_null($item)){
            $item = clone $item;
            $ecmCarrinho->addItem($item);
        }

        if(is_null($userid) || $this->MdlUser->exists(['id' => $userid])){
            if(!is_null($ecmCarrinho) && !is_null($ecmCarrinho->ecm_carrinho_item)){
                $carrinho1 = null;

                foreach($cupons as $cupom){
                    $carrinho2 = $this->EcmCarrinhoItem->EcmCarrinho->newEntity();
                    $cupomInserido = false;
                    foreach($ecmCarrinho->ecm_carrinho_item as $item) {
                        $item = clone $item;
                        unset($item->ecm_cupom);
                        unset($item->ecm_cupom_id);

                        $item->set('valor_produto_desconto', $item->valor_produto);

                        $desconto = $carrinho2->verificarDesconto($item->ecm_produto, [], $cupom, null);
                        if (!is_null($desconto)) {
                            if (isset($desconto['cupom'])) {
                                $item->set('ecm_cupom', $desconto['cupom']);
                                $cupomInserido = true;
                            }
                            $item->set('valor_produto_desconto', $desconto['valorTotal']);
                        } else if (is_null($desconto)) {
                            unset($item->ecm_promocao);
                            $item->ecm_promocao_id = null;
                        }
                        $carrinho2->addItem($item);
                    }

                    if(is_null($carrinho1) || $carrinho2->calcularTotal() < $carrinho1->calcularTotal()){
                        $carrinho1 = $carrinho2;
                        if($cupomInserido)
                            $retorno = $cupom;
                    }
                }
            }
        }

        return $retorno;
    }

    /**
     * @param $cupom
     * @param null $userid
     * @return bool
     */
    public function verificarUso($cupom, $userid = null){
        $ecmCarrinho = $this->EcmCarrinhoItem->EcmCarrinho->find()
            ->matching('EcmCarrinhoItem', function($q)use($cupom){
                return $q->where(['EcmCarrinhoItem.ecm_cupom_id' => $cupom->id,
                                  'EcmCarrinhoItem.status LIKE "Adicionado"']);
            })->where(['EcmCarrinho.status LIKE "Finalizado"']);

        $total_utilizacoes = $ecmCarrinho->count();

        if(!is_null($userid)){
            $total_usuario = $ecmCarrinho->where(['EcmCarrinho.mdl_user_id' => $userid])->count();
            if(!is_null($cupom->numutilizacoes) && !is_null($cupom->numutilizacoesuser))
                return  $cupom->numutilizacoes > $total_utilizacoes &&
                        $cupom->numutilizacoesuser > $total_usuario;
        }

        if(!is_null($cupom->numutilizacoes))
            return $cupom->numutilizacoes > $total_utilizacoes;

        return true;
    }
}
