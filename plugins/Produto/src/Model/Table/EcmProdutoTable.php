<?php
namespace Produto\Model\Table;

use App\Model\Table\Table;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use Produto\Model\Entity\EcmProduto;

/**
 * EcmProduto Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $EcmTipoProduto * @property \Cake\ORM\Association\BelongsToMany $MdlCourse */
class EcmProdutoTable extends Table
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

        $this->table('ecm_produto');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->belongsToMany('EcmTipoProduto', [
            'foreignKey' => 'ecm_produto_id',
            'targetForeignKey' => 'ecm_tipo_produto_id',
            'joinTable' => 'ecm_produto_ecm_tipo_produto',
            'className' => 'Produto.EcmTipoProduto'
        ]);
        $this->belongsToMany('MdlCourse', [
            'foreignKey' => 'ecm_produto_id',
            'targetForeignKey' => 'mdl_course_id',
            'joinTable' => 'ecm_produto_mdl_course',
            'className' => 'WebService.MdlCourse'
        ]);
        $this->MdlCourse->hasMany('MdlEnrol', [
            'foreignKey' => 'courseid',
            'joinType' => 'INNER',
            'className' => 'Produto.MdlEnrol'
        ]);
        $this->MdlCourse->belongsToMany('EcmProduto', [
            'foreignKey' => 'mdl_course_id',
            'targetForeignKey' => 'ecm_produto_id',
            'joinTable' => 'ecm_produto_mdl_course',
            'className' => 'Produto.EcmProduto'
        ]);
        $this->belongsToMany('EcmImagem', [
            'foreignKey' => 'ecm_produto_id',
            'targetForeignKey' => 'ecm_imagem_id',
            'joinTable' => 'ecm_produto_ecm_imagem',
            'className' => 'Produto.EcmImagem'
        ]);
        $this->hasMany('EcmCursoPresencialTurma', [
            'className' => 'CursoPresencial.EcmCursoPresencialTurma'
        ]);
        $this->hasOne('EcmProdutoPacote', [
            'joinType' => 'LEFT',
            'className' => 'Produto.EcmProdutoPacote'
        ]);
        $this->hasOne('EcmProdutoPrazoExtra', [
            'joinType' => 'LEFT',
            'className' => 'Produto.EcmProdutoPrazoExtra'
        ]);
        $this->hasOne('EcmProdutoInfo', [
            'joinType' => 'LEFT',
            'className' => 'Produto.EcmProdutoInfo'
        ]);
        $this->belongsToMany('EcmInstrutor', [
            'foreignKey' => 'ecm_produto_id',
            'targetForeignKey' => 'ecm_instrutor_id',
            'joinTable' => 'ecm_instrutor_ecm_produto',
            'className' => 'Instrutor.EcmInstrutor'
        ]);
        $this->belongsToMany('EcmPromocao', [
            'foreignKey' => 'ecm_produto_id',
            'targetForeignKey' => 'ecm_promocao_id',
            'joinTable' => 'ecm_promocao_ecm_produto',
            'className' => 'Promocao.EcmPromocao'
        ]);

        $this->hasMany('EcmProdutoMdlCourse', [
            'className' => 'Produto.EcmProdutoMdlCourse'
        ]);
        $this->EcmProdutoMdlCourse->belongsTo('MdlCourse', [
            'className' => 'WebService.MdlCourse'
        ]);

        $this->hasMany('EcmProdutoEcmProduto', [
            'className' => 'Produto.EcmProdutoEcmProduto'
        ]);
        $this->EcmProdutoEcmProduto->belongsTo('EcmProduto', [
            'foreignKey' => 'ecm_produto_relacionamento_id',
            'className' => 'Produto.EcmProdutoEcmProduto'
        ]);
        $this->hasOne('MdlFase', [
            'className' => 'Produto.MdlFase'
        ]);
        $this->hasMany('EcmCarrinhoItem', [
            'className' => 'Carrinho.EcmCarrinhoItem'
        ]);
        $this->hasMany('EcmProdutoEcmAplicacao', [
            'className' => 'Produto.EcmProdutoEcmAplicacao'
        ]);
        $this->belongsToMany('EcmProdutoAplicacao', [
            'foreignKey' => 'ecm_produto_id',
            'targetForeignKey' => 'ecm_aplicacao_id',
            'joinTable' => 'ecm_produto_ecm_aplicacao',
            'className' => 'Produto.EcmProdutoAplicacao'
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
            ->requirePresence('sigla', 'create')            ->notEmpty('sigla')            ->add('sigla', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);
        $validator->allowEmpty('referencia')
            ->add('referencia', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'Esse valor já está sendo utilizado'
            ]);

        /*$validator->requirePresence('enrolperiod', 'create');
        $validator->notEmpty('enrolperiod');*/

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
        $rules->add($rules->isUnique(['sigla']));
        $rules->add($rules->isUnique(['referencia']));
        return $rules;
    }

    public function buscaIdProdutoTipos(){
        $sql = "SELECT tp.id, tp.nome,COUNT(ptp.ecm_produto_id) AS produtos
                FROM ecm_tipo_produto tp
                INNER JOIN ecm_produto_ecm_tipo_produto ptp ON tp.id = ptp.ecm_tipo_produto_id
                INNER JOIN ecm_produto p ON p.id = ptp.ecm_produto_id
                INNER JOIN ecm_produto_info pin ON pin.ecm_produto_id = p.id
                WHERE (p.habilitado = 'true' AND p.visivel = 'true')
                GROUP BY tp.id
                ORDER BY tp.id ASC;";

        $conexao = ConnectionManager::get('default');
        return $conexao->execute($sql)->fetchAll('assoc');
    }

    public function tratarEvento($produto){
        if(isset($produto->ecm_curso_presencial_turma)){
            if(!empty($produto->ecm_curso_presencial_turma)){
                $produto->eventos = $produto->ecm_curso_presencial_turma;

                foreach($produto->eventos as $presencial) {

                    if(isset($presencial->ecm_instrutor)) {
                        if(!empty($presencial->ecm_instrutor)) {
                            $presencial->instrutor = $presencial->ecm_instrutor;
                            foreach($presencial->instrutor as $instrutor){
                                if(isset($instrutor->ecm_imagem)){
                                    $instrutor->imagem = $instrutor->ecm_imagem;
                                    unset($instrutor->ecm_imagem);
                                    $instrutor->imagem->src = Router::url('upload/'.$instrutor->imagem->src, true);
                                }
                            }
                        }
                        unset($presencial->ecm_instrutor);
                    }

                    if(!empty($presencial->ecm_curso_presencial_data)) {
                        $primeiroElemento =  current($presencial->ecm_curso_presencial_data);
                        $ultimoElemento =  end($presencial->ecm_curso_presencial_data);

                        $dataInicio = new \DateTime($primeiroElemento->datainicio->format('Y-m-d H:i:s'));
                        $presencial->data_inicio = $dataInicio->getTimestamp();

                        $dataFim = new \DateTime($ultimoElemento->datafim->format('Y-m-d H:i:s'));
                        $presencial->data_fim = $dataFim->getTimestamp();

                        $presencial->cidade = $primeiroElemento->ecm_curso_presencial_local->mdl_cidade;

                        $presencial->cidade->estado = $presencial->cidade->mdl_estado;

                        $presencial->local = $presencial->ecm_curso_presencial_data;
                        unset($presencial->ecm_curso_presencial_data);

                        foreach($presencial->local as $local){
                            $local->local = $local->ecm_curso_presencial_local;
                            $local->datainicio = new \DateTime($local->datainicio->format('Y-m-d H:i:s'));
                            $local->data_inicio = $local->datainicio->getTimestamp();
                            $local->datafim = new \DateTime($local->datafim->format('Y-m-d H:i:s'));
                            $local->data_fim = $local->datafim->getTimestamp();
                            unset($local->datainicio);
                            unset($local->datafim);

                            if(!empty($local->saidaintervalo)) {
                                $local->saidaintervalo = new \DateTime($local->saidaintervalo->format('Y-m-d H:i:s'));
                                $local->saida_intervalo = $local->saidaintervalo->getTimestamp();
                                unset($local->saidaintervalo);
                            }

                            if(!empty($local->voltaintervalo)) {
                                $local->voltaintervalo = new \DateTime($local->voltaintervalo->format('Y-m-d H:i:s'));
                                $local->volta_intervalo = $local->voltaintervalo->getTimestamp();
                                unset($local->voltaintervalo);
                            }

                            $local->local->endereco = nl2br(trim($local->local->endereco));

                            $local->local->cidade = $local->local->mdl_cidade;
                            $local->local->cidade->estado = $local->local->mdl_cidade->mdl_estado;
                            unset($local->ecm_curso_presencial_local);
                            unset($local->local->mdl_cidade);
                            unset($local->local->cidade->mdl_estado);
                        }
                    }
                }
            }
            unset($produto->ecm_curso_presencial_turma);
        }
    }

    public function validationRemoveValidationEnrolperiod(\Cake\Validation\Validator $validator) {
        $validator = $this->validationDefault($validator);
        $validator->remove('enrolperiod');

        return $validator;
    }

    /**
     * Parametros: data{codigo, aplicacao, software, modulos_linha, tabela, conexao, licenca, ativacao, especiais}, valor
     * Retorno: codigo_tw, descricao
     */
    public function encriptCodigotw($aplicacao, $valor = false, $data = []){
        // Objeto aplicacao sofreu alteração, então esta comparação não funciona
        if (isset($aplicacao->ecm_produto_mdl_course)) {
            return [
                'codigo_tw' => $aplicacao->shortname, 
                'descricao' => $aplicacao->fullname, 
                'valor_unitario' => $this->calcularProdutoAltoqi($aplicacao, [], $data)
            ];
        }

        if($aplicacao->ecm_produto_aplicacao['aplicacao'] == "SERVIÇO") {
            return [
                'codigo_tw'      => $aplicacao->ecm_produto_aplicacao['codigo'],
                'descricao'      => ucwords($aplicacao->ecm_produto_aplicacao['tecnologia']),
                'valor_unitario' => 0.00
            ];
        } else if($aplicacao->ecm_produto_aplicacao['aplicacao'] == "MODULO"){
            $codigo = substr($aplicacao->ecm_produto_aplicacao['software'], 13);
        }else{
            $codigo = substr($aplicacao->ecm_produto_aplicacao['aplicacao'], 0, 2) . substr($aplicacao->ecm_produto_aplicacao['software'], 0, 3) .
                "-" . substr($aplicacao->ecm_produto_aplicacao['modulos_linha'], 0, 2);
        }

        $codigo .= "-" . substr($aplicacao->ecm_produto_aplicacao['tabela'], 0, 2) .
                         substr($aplicacao->ecm_produto_aplicacao['conexao'],   0, 1) .
                         substr($aplicacao->ecm_produto_aplicacao['licenca'],   0, 2) .
                         substr($aplicacao->ecm_produto_aplicacao['ativacao'],  0, 1);

        if(strtolower($aplicacao->ecm_produto_aplicacao['especiais']) == 'upgrade'){
            if($aplicacao->ecm_produto_aplicacao['licenca'] == 'LTEMP'){
                if(strlen($aplicacao->ecm_produto_aplicacao['mudanca_de_aplicacao']) == 2)
                    preg_match_all('/([A-Z]{1}+)/', $aplicacao->ecm_produto_aplicacao['mudanca_de_aplicacao'], $mudanca_de_aplicacao);
                else
                    preg_match_all('/(PL|[A-Z]{1}+)/', $aplicacao->ecm_produto_aplicacao['mudanca_de_aplicacao'], $mudanca_de_aplicacao);
            }else{
                if($aplicacao->ecm_produto_aplicacao['licenca'] == 'LANUAL' || $aplicacao->ecm_produto_aplicacao['codigo']=='QIB')
                    preg_match_all('/([0-9]+|[A-Z]+)/', $aplicacao->ecm_produto_aplicacao['mudanca_de_aplicacao'], $mudanca_de_aplicacao);
                else
                    preg_match_all('/([0-9]+|[B-U]+)/', $aplicacao->ecm_produto_aplicacao['mudanca_de_aplicacao'], $mudanca_de_aplicacao);
            }
            $mudanca_de_aplicacao = array_shift($mudanca_de_aplicacao);

            $mudanca_de_aplicacao[2] = substr($aplicacao->ecm_produto_aplicacao['aplicacao'], 0,
                $aplicacao->ecm_produto_aplicacao['aplicacao']=='PLENA'||$aplicacao->ecm_produto_aplicacao['codigo']=='QIB'?2:1);
            if($aplicacao->ecm_produto_aplicacao['licenca'] == 'LTEMP') {
                $mudanca_de_aplicacao[3] = substr($aplicacao->ecm_produto_aplicacao['modulos_linha'], 4, 5);
            } else {
                $mudanca_de_aplicacao[3] = substr($aplicacao->edicao, 2);
            }

            $codigo .= "-" . substr($aplicacao->ecm_produto_aplicacao['especiais'], 0, 1) . "-" .
                $mudanca_de_aplicacao[0] . $mudanca_de_aplicacao[1];
                
        }else{
            $codigo .= substr($aplicacao->ecm_produto_aplicacao['especiais'], 0, 1);
        }

        $codigo = preg_replace('/\s/', "", trim($codigo));

        $prefixo = str_replace('18', substr($aplicacao->edicao, 2), $aplicacao->ecm_produto_aplicacao['codigo']);
        if($aplicacao->ecm_produto_aplicacao['aplicacao'] != "MODULO" || preg_match("(33|41)", $aplicacao->ecm_produto_aplicacao['codigo']))
            $prefixo .= substr($aplicacao->edicao, 2);

        $codigo = $prefixo . "-" . $codigo;

        if(strpos($aplicacao->ecm_produto_aplicacao['codigo'], 'EB0') === false){
            $descricao = $aplicacao->ecm_produto_aplicacao['tecnologia'];
            if (strpos($codigo, 'QIB') !== false && $aplicacao->edicao != 2018)
                $descricao .= " " . substr($aplicacao->ecm_produto_aplicacao['modulos_linha'], 5);
            else if (strpos($codigo, 'QIB') === false)
                $descricao .= " " . $aplicacao->ecm_produto_aplicacao['aplicacao'];
        }else{
            $descricao = $aplicacao->ecm_produto_aplicacao['aplicacao'].' '.$aplicacao->ecm_produto_aplicacao['descricao'];
        }

        $descricao .= " [" .
            $aplicacao->ecm_produto_aplicacao['software']  . "-" .
            substr($aplicacao->ecm_produto_aplicacao['modulos_linha'], 5)  . "-" .
            $aplicacao->ecm_produto_aplicacao['tabela']    . "-" .
            $aplicacao->ecm_produto_aplicacao['conexao']   . "-" .
            $aplicacao->ecm_produto_aplicacao['licenca']   . "-" .
            $aplicacao->ecm_produto_aplicacao['ativacao']  . "-" .
            $aplicacao->ecm_produto_aplicacao['especiais'];

        if($aplicacao->ecm_produto_aplicacao['especiais'] == 'UPGRADE'){
            $descricao .= ' DE: ' . $this->mudanca_de_aplicacao($mudanca_de_aplicacao[0]) .
                ' ' . $this->mudanca_de_aplicacao($mudanca_de_aplicacao[1], true) .
                ' PARA: ' . $this->mudanca_de_aplicacao($mudanca_de_aplicacao[2]) .
                ' ' . $this->mudanca_de_aplicacao(substr($aplicacao->edicao, 2), true);
        }
        $descricao .= "]";

        $retorno = ['codigo_tw' => $codigo, 'descricao' => $descricao];

        if($valor){
            if($aplicacao->ecm_produto_aplicacao['especiais'] == 'UPGRADE' && 
            strtoupper(substr($aplicacao->ecm_produto_aplicacao['mudanca_de_aplicacao'],0,1)) != 'F')
                $retorno['valor_unitario'] = $this->upgradeProdutoAltoqi($aplicacao, [], $data)['valor_total'];
            else
                $retorno['valor_unitario'] = $this->calcularProdutoAltoqi($aplicacao, [], $data);
        }

        return $retorno;
    }

    private function mudanca_de_aplicacao($sigla, $modulo = false){
        $retorno = '';
        switch ($sigla){
            case 'L':
                $retorno = $modulo ? 'LIGHT' : 'LITE';
                break;
            case 'F':
                $retorno = 'FLEX';
                break;
            case 'B':
            case 'BA':
                $retorno = 'BASIC';
                break;
            case 'P':
                $retorno = 'PRO';
                break;
            case 'PL':
                $retorno = 'PLENA';
                break;
            case 'E':
                $retorno = 'ESSENCIAL';
                break;
            case 'T':
                $retorno = 'TOP';
                break;
            case '09':
            case '10':
                $retorno = 'V' . $sigla;
                break;
            case '18':
            case '19':
            case '20':
                $retorno = 'V';
            case '17':
                $retorno .= '20' . $sigla;
                break;
        }
        return $retorno;
    }

    /**
     * Parametros: codigo_tw
     * Retorno: produto_aplicacao
     */
    public function decriptCodigotw($codigo){

        $codigos = explode('-', $codigo);

        if(count($codigos) == 1) {
            $where = ['codigo' => $codigo];
        } else {
            if(strlen($codigos[1]) < 4 && strpos($codigos[0], 'EB0') !== false){
                $aplicacao = 'MODULO';
                $codigos[1] = 'MÓDULO TIPO ' . $codigos[1];
            }else{
                $aplicacao = substr($codigos[1], 0, 2);
                $codigos[1] = substr($codigos[1], -2);
                $edicao = '20' . substr($codigos[0], -2);
            }

            if (strlen($codigos[0]) > 5){
                $edicao = '20' . substr($codigos[0], -2);
                $codigos[0] = substr($codigos[0], 0, 5);
            }

            if (strpos($codigos[0], 'EB0') !== false){
                if(strlen($codigos[1]) > 3){
                    $codigos[3] = $codigos[2];
                    $codigos[2] = "07";
                }
            } else {
                $codigos[0] = substr($codigos[0], 0, 3);
            }

            $tabela    = substr($codigos[3], 0, 2);
            $conexao   = substr($codigos[3], 2, 1);
            $licenca   = substr($codigos[3], 3, 2);
            $ativacao  = substr($codigos[3], 5, 1);

            if(empty($codigos[4])){
                $especiais = substr($codigos[3], 6, 1);
            } else {
                $especiais = 'U';
                $mudanca_de_aplicacao = array_pop($codigos);
            }

            $where = [
                'EcmProdutoAplicacao.codigo LIKE "%'       . $codigos[0] . '%"',
                'EcmProdutoAplicacao.software LIKE "%'     . $codigos[1] . '%"',
                'EcmProdutoAplicacao.aplicacao LIKE "'     . $aplicacao  . '%"',
                'EcmProdutoAplicacao.modulos_linha LIKE "' . $codigos[2] . '%"',
                'EcmProdutoAplicacao.tabela LIKE "'        . $tabela     . '%"',
                'EcmProdutoAplicacao.conexao LIKE "'       . $conexao    . '%"',
                'EcmProdutoAplicacao.licenca LIKE "'       . $licenca    . '%"',
                'EcmProdutoAplicacao.ativacao LIKE "'      . $ativacao   . '%"',
                'EcmProdutoAplicacao.especiais LIKE "'     . $especiais  . '%"'
            ];

            if(isset($mudanca_de_aplicacao)){
                $where['EcmProdutoAplicacao.mudanca_de_aplicacao'] = $mudanca_de_aplicacao;
            }

            if(isset($edicao)){
                $where['edicao'] = $edicao;
            }
        }

        return $this->EcmProdutoEcmAplicacao->find()
            ->contain(['EcmProdutoAplicacao' => function($q){
                return $q->select([
                    'codigo'                => 'codigo',
                    'software'              => 'software',
                    'aplicacao'             => 'aplicacao',
                    'modulos_linha'         => 'modulos_linha',
                    'tabela'                => 'tabela',
                    'conexao'               => 'conexao',
                    'licenca'               => 'licenca',
                    'ativacao'              => 'ativacao',
                    'especiais'             => 'especiais',
                    'mudanca_de_aplicacao'  => 'mudanca_de_aplicacao'
                ]);
            }])
            ->where($where)->first();
    }

    /**
     * Parametros: produto principal(***), modulos adicionais(***), pontos de rede
     * *** licenca, sugerido, codigo, modulos_linha, aplicacao ***
     * Retorno: valor unitario total do produto
     */
    public function calcularProdutoAltoqi($app, $modulos = [], $data = []){
        array_unshift($modulos, $app);

        $total = 0;
        $rede = array_key_exists('rede', $data) && $data['rede'] > 1 ? $data['rede'] : 1;
        if(!array_key_exists('licenca', $data))
            $data['licenca'] = $app->ecm_produto_aplicacao->licenca;

        foreach ($modulos as $modulo) {
            if ($data['licenca'] == 'LTEMP' || $data['licenca'] == 'LANUAL') { 
                $valor = 0;
                if(!array_key_exists('edicao', $data))
                    $data['edicao'] = 19;

                if (isset($modulo->ecm_produto_mdl_course)) {
                    if (isset($modulo->ecm_produto_mdl_course[0]) && is_null($modulo->ecm_produto_mdl_course[0]->preco)) {
                        foreach ($modulo->ecm_produto as $produto) {
                            if ($produto->refcurso == 'true')
                                $valor = $produto->preco; 
                        }
                    } else if (isset($modulo->ecm_produto_mdl_course[0]))
                        $valor = $modulo->ecm_produto_mdl_course[0]->preco;
                } else if (strpos($modulo->ecm_produto_aplicacao->codigo, 'EB') !== false) {
                    $valores = [
                        18 => [
                            '02 - LIGHT'       => ['BASIC' => 145, 'PRO' => 300],
                            '03 - ESSENCIAL'   => ['BASIC' => 175, 'PRO' => 350, 'PLENA' => 500],
                            '04 - TOP'         => ['BASIC' => 195, 'PRO' => 400, 'PLENA' => 550],
                            '05 - ALVENARIA'   => ['BASIC' =>  50, 'PRO' => 100, 'PLENA' => 150],
                            '06 - PRÉ-MOLDADO' => ['BASIC' =>  50, 'PRO' => 100, 'PLENA' => 150]
                        ], 19 => [
                            '02 - LIGHT'       => ['BASIC' => 145, 'PRO' => 225, 'PLENA' => 295], 
                            '03 - ESSENCIAL'   => ['BASIC' => 165, 'PRO' => 265, 'PLENA' => 295],
                            '04 - TOP'         => ['BASIC' => 195, 'PRO' => 295, 'PLENA' => 345],
                            '05 - ALVENARIA'   => ['BASIC' => 105, 'PRO' => 105, 'PLENA' => 105],
                            '06 - PRÉ-MOLDADO' => ['BASIC' =>  79, 'PRO' =>  79, 'PLENA' =>  79]
                        ], 20 => [
                            '02 - LIGHT'       => ['BASIC' => 155, 'PRO' => 240, 'PLENA' => 315], 
                            '03 - ESSENCIAL'   => ['BASIC' => 175, 'PRO' => 285, 'PLENA' => 315],
                            '04 - TOP'         => ['BASIC' => 205, 'PRO' => 315, 'PLENA' => 365],
                            '05 - ALVENARIA'   => ['BASIC' => 110, 'PRO' => 110, 'PLENA' => 110],
                            '06 - PRÉ-MOLDADO' => ['BASIC' =>  85, 'PRO' =>  85, 'PLENA' =>  85]
                        ]
                    ];

                    if($modulo->ecm_produto_aplicacao->aplicacao == "FLEX")
                        $valor = $modulo->vl_sugerido;
                    else if(array_key_exists($modulo->ecm_produto_aplicacao->modulos_linha, $valores[$data['edicao']]))
                        $valor = $valores[$data['edicao']][$modulo->ecm_produto_aplicacao->modulos_linha][$modulo->ecm_produto_aplicacao->aplicacao];
                } else {
                    $modulos_ltemp = ['Light' => 2, 'Essencial' => 3, 'Top' => 4];
                    $modulo->modulos_linha_num = (int) filter_var($modulo->ecm_produto_aplicacao->modulos_linha, FILTER_SANITIZE_NUMBER_INT);
                    if ($data['edicao'] == '18') {
                        if($modulo->modulos_linha_num > 19 && $modulo->modulos_linha_num < 35){
                            $valores = [
                                1 => ['BASIC' => 145, 'PLENA' => 275],
                                2 => ['BASIC' => 195, 'PLENA' => 325],
                                3 => ['BASIC' => 220, 'PLENA' => 375],
                                4 => ['BASIC' => 245, 'PLENA' => 425]
                            ];
                            $modulos_linha = intval($modulo->ecm_produto_aplicacao->modulos_linha);
                            $linha = 4;
                            if ($modulos_linha <= 23) {
                                $linha = 1;
                            } else if ($modulos_linha <= 29) {
                                $linha = 2;
                            } else if ($modulos_linha <= 33) {
                                $linha = 3;
                            }
    
                            $valor = $valores[$linha][$modulo->ecm_produto_aplicacao->aplicacao];
                        }
                    } else if ($modulo->modulos_linha_num > 1 && $modulo->modulos_linha_num < 5) {//01 - STANDARD|05 - ALVENARIA
                        $valores = [
                            19 => [
                                2 => ['BASIC' => 140, 'PRO' => 185, 'PLENA' => 215], 
                                3 => ['BASIC' => 180, 'PRO' => 230, 'PLENA' => 260],
                                4 => ['BASIC' => 215, 'PRO' => 265, 'PLENA' => 295]
                            ], 20 => [
                                2 => ['BASIC' => 150, 'PRO' => 200, 'PLENA' => 230], 
                                3 => ['BASIC' => 190, 'PRO' => 245, 'PLENA' => 285],
                                4 => ['BASIC' => 230, 'PRO' => 275, 'PLENA' => 315]
                            ]
                        ];

                        if($modulo->ecm_produto_aplicacao->aplicacao == "FLEX")
                            $valor = $modulo->vl_sugerido;
                        else{
                            //$valor = $valores[$data['edicao']][$modulos_ltemp[$data['modulos_ltemp']]][$modulo->ecm_produto_aplicacao->aplicacao];
                            $modulos_ltemp = ['02 - LIGHT' => 2, '03 - ESSENCIAL' => 3, '04 - TOP' => 4];
                            $valor = $valores[$data['edicao']][$modulos_ltemp[$modulo->ecm_produto_aplicacao->modulos_linha]][$modulo->ecm_produto_aplicacao->aplicacao];
                        }
                    }
                }
                if ($rede > 1) {
                    switch ($rede) {
                        case 2:
                        case 3:
                            $desconto = 0.50;
                            break;
                        case 4:
                        case 5:
                        case 6:
                            $desconto = 0.60;
                            break;
                        case 7:
                        case 8:
                            $desconto = 0.65;
                            break;
                        case 9:
                            $desconto = 0.675;
                            break;
                        default:
                            $desconto = 0.70;
                            break;
                    }
                    $valor += (($valor * ($rede - 1)) * (1 - $desconto));
                }

                if(isset($modulo->ecm_produto_aplicacao) && $modulo->ecm_produto_aplicacao->aplicacao != "FLEX")
                    $valor = $valor * 12;

                if (array_key_exists('especiais', $data) && $data['especiais'] == "renova" &&
                    array_key_exists('tempo-renova', $data) && $data['tempo-renova'] > 0
                ) {
                    switch ($data['tempo-renova']) {
                        case 4:
                            $desconto = 0.25;
                            break;
                        case 3:
                            $desconto = 0.20;
                            break;
                        case 2:
                            $desconto = 0.15;
                            break;
                        default:
                            $desconto = 0.10;
                            break;
                    }
                    $total += ($valor - ($valor * $desconto)) * $data['tempo-renova'];
                } else {
                    $total += $valor;
                }
            } else {
                if (isset($modulo->ecm_produto_mdl_course)) {
                    $total += $modulo->ecm_produto_mdl_course[0]->preco;
                } else {
                    $rede_descontos = [1 => 1, 5 => 0.45, 10 => 0.3, 20 => 0.25, 30 => 0.2, 40 => 0.15, 100 => 0.1];
                    $qtde_anterior = 0;
                    foreach ($rede_descontos as $qtde => $desconto) {
                        if ($qtde_anterior < $rede) {
                            if ($qtde < $rede) {
                                $qtde_atual = $qtde - $qtde_anterior;
                            } else {
                                $qtde_atual = $rede - $qtde_anterior;
                            }
                            $total += $modulo->sugerido * $desconto * $qtde_atual;
                            $qtde_anterior = $qtde;
                        }
                    }
                }
            }
        }

        return $total;
    }

    public function upgradeProdutoAltoqi($app, $modulos = [], $data = []){
        $meses_faltantes = 12;

        if ($app->ecm_produto_aplicacao->licenca == 'LTEMP') {
            $upapp = [$app->ecm_produto_aplicacao->aplicacao, 'LITE', 'LITE', 'LITE', 'BASIC', 'BASIC', 'PRO'];
            $data['up-app'] = $upapp[$data['up-app']];

            $upmod = [$app->modulos_linha, 'LIGHT', 'LIGHT', 'ESSENCIAL'];
            $data['up-mod'] = $upmod[$data['up-mod']];

            $ecmProdutoAplicacao = $this->EcmProdutoEcmAplicacao->find()->contain(['EcmProdutoAplicacao'])
                                                ->where([
                                                    'EcmProdutoAplicacao.aplicacao' => $app->ecm_produto_aplicacao->aplicacao,
                                                    'EcmProdutoAplicacao.modulos_linha LIKE "%' . $app->ecm_produto_aplicacao->modulos_linha . '"',
                                                    'EcmProdutoAplicacao.especiais LIKE "ativa%"',
                                                    'EcmProdutoAplicacao.tabela' => 'STD'
                                                ])->first();

            $ecmProdutoAplicacaoAntiga = $this->EcmProdutoEcmAplicacao->find()->contain(['EcmProdutoAplicacao'])
                                                ->where([
                                                    'EcmProdutoAplicacao.aplicacao' => $data['up-app'],
                                                    'EcmProdutoAplicacao.modulos_linha LIKE "%' . $data['up-mod'] . '"',
                                                    'EcmProdutoAplicacao.especiais LIKE "ativa%"',
                                                    'EcmProdutoAplicacao.tabela' => 'STD'
                                                ])->first();

            $ecmProdutoAplicacao->valor       = $this->calcularProdutoAltoqi($ecmProdutoAplicacao, [], $data);
            $ecmProdutoAplicacaoAntiga->valor = $this->calcularProdutoAltoqi($ecmProdutoAplicacaoAntiga, [], $data);

            $meses_faltantes = 12 - $data['tempo-up'];
            $credito = ($ecmProdutoAplicacaoAntiga->valor / 12) * $meses_faltantes;
            $credito_faltantes = (($ecmProdutoAplicacao->valor / 12) * $meses_faltantes) - $credito;
        } else {
            $credito_faltantes = $this->calcularProdutoAltoqi($app, $modulos, $data);
        }

        return ['valor_total'     => $credito_faltantes,
            'valor_parcelado' => ($credito_faltantes / $meses_faltantes),
            'qtd_parcelas'    => $meses_faltantes];
    }
}
