<?php
namespace Carrinho\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use CursoPresencial\Model\Entity\EcmCursoPresencialTurma;
use Cake\ORM\TableRegistry;

/**
 * EcmCarrinhoItem Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmCarrinho * @property \Cake\ORM\Association\BelongsTo $EcmProduto * @property \Cake\ORM\Association\BelongsTo $EcmCursoPresencialTurma * @property \Cake\ORM\Association\BelongsTo $EcmPromocao */
class EcmCarrinhoItemTable extends Table
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

        $this->table('ecm_carrinho_item');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmCarrinho', [
            'foreignKey' => 'ecm_carrinho_id',
            'joinType' => 'INNER',
            'className' => 'Carrinho.EcmCarrinho'
        ]);
        $this->belongsTo('EcmProduto', [
            'foreignKey' => 'ecm_produto_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmProduto'
        ]);
        $this->belongsTo('EcmCursoPresencialTurma', [
            'foreignKey' => 'ecm_curso_presencial_turma_id',
            'className' => 'CursoPresencial.EcmCursoPresencialTurma'
        ]);
        $this->belongsTo('EcmPromocao', [
            'foreignKey' => 'ecm_promocao_id',
            'className' => 'Carrinho.EcmPromocao'
        ]);
        $this->belongsTo('EcmCupom', [
            'foreignKey' => 'ecm_cupom_id',
            'className' => 'Cupom.EcmCupom'
        ]);
        $this->hasMany('EcmCarrinhoItemEcmProdutoAplicacao', [
            'foreignKey' => 'ecm_carrinho_item_id',
            'className' => 'Carrinho.EcmCarrinhoItemEcmProdutoAplicacao'
        ]);
        $this->EcmCarrinhoItemEcmProdutoAplicacao->belongsTo('EcmProdutoEcmAplicacao', [
            'foreignKey' => 'ecm_produto_ecm_aplicacao_id',
            'className' => 'Produto.EcmProdutoEcmAplicacao'
        ]);
        $this->belongsToMany('EcmProdutoEcmAplicacao', [
            'foreignKey' => 'ecm_carrinho_item_id',
            'targetForeignKey' => 'ecm_produto_ecm_aplicacao_id',
            'joinTable' => 'ecm_carrinho_item_ecm_produto_aplicacao',
            'className' => 'Produto.EcmProdutoEcmAplicacao'
        ]);
        $this->hasMany('EcmCarrinhoItemMdlCourse', [
            'foreignKey' => 'ecm_carrinho_item_id',
            'className' => 'Carrinho.EcmCarrinhoItemMdlCourse'
        ]);
        $this->EcmCarrinhoItemMdlCourse->belongsTo('MdlCourse', [
            'foreignKey' => 'mdl_course_id',
            'className' => 'WebService.MdlCourse'
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
            ->decimal('valor_produto')            ->requirePresence('valor_produto', 'create')            ->notEmpty('valor_produto');
        $validator
            ->requirePresence('quantidade', 'create')            ->notEmpty('quantidade');
        $validator
            ->requirePresence('status', 'create')            ->notEmpty('status');
        $validator
            ->decimal('valor_produto_desconto')            ->allowEmpty('valor_produto_desconto');
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
        $rules->add($rules->existsIn(['ecm_carrinho_id'], 'EcmCarrinho'));
        $rules->add($rules->existsIn(['ecm_produto_id'], 'EcmProduto'));
        $rules->add($rules->existsIn(['ecm_curso_presencial_turma_id'], 'EcmCursoPresencialTurma'));
        $rules->add($rules->existsIn(['ecm_promocao_id'], 'EcmPromocao'));
        return $rules;
    }

    /**
     * Função responsável verificar o total de vagas de cursos presenciais adquiridas
     * Retorna o total de vagas de cursos presenciais adquiridas
     *
     * @param EcmCursoPresencialTurma $presencial
     * @param EcmCarrinho $carrinho
     *
     * @return int
     *
     */
    public function totalVagasUtilizadasCursoPresencial(EcmCursoPresencialTurma $presencial, EcmCarrinho $carrinho = null){
        $query = $this->find();

        $query->select(['vagas_utilizadas' => $query->func()->sum('EcmCarrinhoItem.quantidade')])
            ->innerJoin('ecm_carrinho', 'ecm_carrinho.id = EcmCarrinhoItem.ecm_carrinho_id')
            ->where([
                'ecm_carrinho.status !=' => 'Cancelado',
                'EcmCarrinhoItem.status' => 'Adicionado',
                'EcmCarrinhoItem.ecm_produto_id' => $presencial->get('ecm_produto')->get('id'),
                'EcmCarrinhoItem.ecm_curso_presencial_turma_id' => $presencial->get('id')
            ]);
        if(isset($carrinho)){
            if(!is_null($carrinho->get('id'))){
                $query->andWhere(['ecm_carrinho.id != ' => $carrinho->get('id')]);
            }
        }

        $vagas_utilizadas = $query->first()->vagas_utilizadas;

        $ecmVendaPresencial = TableRegistry::get('Vendas.EcmVendaPresencial')->find();
        $ecmVendaPresencial->select(['vagas_reservadas' => $query->func()->sum('quantidade_reserva')])
            ->where([
                'status !=' => 'Espera',
                'ecm_curso_presencial_turma_id' => $presencial->id
            ]);

        $vagas_reservadas = $ecmVendaPresencial->first()->vagas_reservadas;

        return (int)$vagas_utilizadas+(int)$vagas_reservadas;
    }

    /**
     * Procedimnento para buscar produtos da Entidade EcmCarrinhoItemMdlCourse
     * 
     *  @param EcmCarrinhoItem $item
     */
    public function setProductsInCourse(EcmCarrinhoItem $item){

        if(count($item->get('ecm_carrinho_item_mdl_course')) > 0){
            $this->MdlCourse = TableRegistry::get('WebService.MdlCourse');

            $item_curso_produtos = [];
            $item_produto = $item->get('ecm_produto');
            $labAltoQi = array_filter($item_produto->get('ecm_tipo_produto'), function($tipo){ return $tipo->id == 47; }); // produto AltoQi

            if(count($labAltoQi) == 0){
                foreach ($item->get('ecm_carrinho_item_mdl_course') as $item_mdl_course) {
                    $course_id = $item_mdl_course->mdl_course_id;

                    $mdlCourse = $this->MdlCourse->find('all')
                                ->contain(['EcmProduto' => function ($q) {
                                    return $q->where(['refcurso' => 'true']);
                                }])->where(['MdlCourse.id' => $course_id ])->first();

                    if($mdlCourse){
                        if(count($mdlCourse->get('ecm_produto')) > 0 and $item_mdl_course->valor > 0){
                            $i = clone $item;
                            $i->valor_produto_desconto = $item_mdl_course->valor;
                            $i->ecm_produto = $mdlCourse->ecm_produto[0];
                            array_push( $item_curso_produtos, $i);
                        }
                    }
                }
            }

            $item->course_products = $item_curso_produtos;
        }
    }

    /**
     * Procedimento para alterar aplicações da entidade EcmCarrinhoItemEcmProdutoAplicacao para Pacotes AltoQI
     * 
     *  @param EcmCarrinhoItem $item
     */
    public function setAppsInPackageAltoQi(EcmCarrinhoItem $item){

        $item_produto = $item->get('ecm_produto');
        $item_apps = $item->get('ecm_carrinho_item_ecm_produto_aplicacao');
        $pacoteAltoQi = array_filter($item_produto->get('ecm_tipo_produto'), function($tipo){ return $tipo->id == 58; }); // produto AltoQi

        if(count($item_apps) > 0 && count($pacoteAltoQi) > 0){
            $this->EcmProdutoEcmAplicacao = TableRegistry::get('Produto.EcmProdutoEcmAplicacao');
            foreach ($item_apps  as $item_app) {

                $prod_app_old = $this->EcmProdutoEcmAplicacao->get($item_app->ecm_produto_ecm_aplicacao_id);
                $prod_app_new = $this->EcmProdutoEcmAplicacao->find()
                                        ->contain(['EcmProduto'])
                                        ->where([
                                                'ecm_produto_id <>'=> $item_produto->id, 
                                                'ecm_aplicacao_id' => $prod_app_old->ecm_aplicacao_id, 
                                                'edicao' => $prod_app_old->edicao 
                                            ])->first();

                if($prod_app_new){
                    $item_app->ecm_produto_ecm_aplicacao_id = $prod_app_new->id;
                    $item_app->set('ecm_produto_ecm_aplicacao', $prod_app_new);
                }
            }
        }
    }
}
