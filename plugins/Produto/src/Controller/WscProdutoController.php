<?php

namespace Produto\Controller;

use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Produto\Model\Entity\EcmProduto;
use Produto\Model\Entity\EcmTipoProduto;

class WscProdutoController extends WscController
{
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    public function listar()
    {
        $this->loadModel('Produto.EcmProduto');

        $retorno = $this->EcmProduto->find('all', [
            'fields' => [
                'id', 'nome', 'preco', 'sigla', 'habilitado', 'visivel', 'parcela'
            ],
            'contain' => [
                'EcmImagem' => ['fields' => [
                    'src', 'type' => 'descricao', 'EcmProdutoEcmImagem.ecm_produto_id'
                ]],
                'EcmTipoProduto' => [
                    'fields' => [
                        'id', 'nome', 'EcmProdutoEcmTipoProduto.ecm_produto_id', 'ordem' => 'EcmProdutoEcmTipoProduto.ordem'
                    ]
                ],
                'EcmCursoPresencialTurma' => function ($q) {
                    return $q->autoFields(false)
                        ->select(['id','vagas_total','vagas_preenchidas','valor','valor_produto','ecm_produto_id'])
                        ->innerJoinWith('EcmCursoPresencialData', function ($q) {
                            return $q->contain(['EcmCursoPresencialLocal.MdlCidade.MdlEstado'])
                                ->where(['EcmCursoPresencialData.datainicio > NOW()']);
                        })
                        ->where(['status' => 'Ativo'])
                        ->group(['EcmCursoPresencialTurma.id']);
                },
                'EcmProdutoInfo' => [
                    'fields' => ['id', 'titulo', 'url', 'metatag_key'],
                    'joinType' => 'INNER',
                    'EcmProdutoInfoConteudo' => [
                        'fields' => ['titulo', 'descricao', 'ecm_produto_info_id'],
                        'joinType' => 'INNER'
                    ]
                ]
            ],
            'conditions' => ['EcmProduto.visivel' => 'true', 'EcmProduto.habilitado' => 'true'],
            'order' => [ 'EcmProduto.id' => 'DESC' ]
        ]);


        

        $this->loadModel('Promocao.EcmPromocao');
        $this->loadModel('Carrinho.EcmCarrinho');
        $carrinho = $this->EcmCarrinho->newEntity();

        $userId = $this->request->session()->read('Auth.User.id');

        foreach($retorno as $produto) {
            $promocoes = $this->EcmPromocao->buscaPromocoesAtivasUsuario($produto, $userId);
            $desconto = $carrinho->verificarDesconto($produto, $promocoes);
            if(is_array($desconto)){
                foreach($desconto as $key => $value)
                    $produto->$key = $value;
                $produto->promocao->datainicio = strtotime($produto->promocao->datainicio->format('Y-m-d 00:00:00'));
                $produto->promocao->datafim = strtotime($produto->promocao->datafim->format('Y-m-d 23:59:59'));
            }

            $produto['imagens'] = $produto['ecm_imagem'];
            unset($produto['ecm_imagem']);
            $produto['categorias'] = $produto['ecm_tipo_produto'];
            unset($produto['ecm_tipo_produto']);
            $produto['info'] = $produto['ecm_produto_info'];
            unset($produto['ecm_produto_info']);

            if(isset($produto['info']['ecm_produto_info_conteudo'])){
                if(!empty($produto['info']['ecm_produto_info_conteudo'])) {
                    $produto['info']['conteudo'] = $produto['info']['ecm_produto_info_conteudo'];
                    foreach ($produto['info']['conteudo'] as $conteudo) {
                        unset($conteudo['ecm_produto_info_id']);
                        $conteudo['descricao'] = html_entity_decode(strip_tags($conteudo['descricao']));
                    }
                }
                unset($produto['info']['ecm_produto_info_conteudo']);
            }

            foreach($produto['categorias'] as $categorias) {
                $categorias['parent'] = $categorias['ecm_tipo_produto_id'];
                unset($categorias['ecm_tipo_produto_id']);
                unset($categorias['_joinData']);

                if(!isset($produto['produtos']) &&
                    ($categorias['id'] == 17 || $categorias['id'] == 32 || $categorias['id'] == 42 ||
                        $categorias['id'] == 47)){
                    $produtos = $this->listaProdutosRelacionados($produto['id']);

                    $produto['produtos'] = $produtos;
                }
                if($categorias['id'] == 47) {
                    if($pacelas = $this->verificaParcelasTrilhas($produto)) {
                        if (empty($produto->valorTotal)) {
                            $produto->valorTotal = $produto->preco;
                        }
                        $produto->valor_parcelado = ($produto->valorTotal / $pacelas);
                        $produto->parcelas = intval($pacelas);
                    }
                }

            }

            if(!empty($produto['imagens'])) {
                foreach($produto['imagens'] as $imagem){
                    $url = $imagem->get('src');
                    $url = \Cake\Routing\Router::url('/webroot/upload/', true) . $url;
                    unset($imagem['_joinData']);

                    $imagem->set('src', $url);
                }
            }

            $this->EcmProduto->tratarEvento($produto);
        }

        $this->set(compact('retorno'));
    }

    private function verificaParcelasTrilhas(EcmProduto $produto){
        $this->loadModel('FormaPagamento.EcmFormaPagamento');
        $this->loadModel('Configuracao.EcmConfig');

        $maximoNumeroParcela = $this->EcmConfig->find()->where(['nome' => 'maximo_numero_parcela'])->first();
        $valorMinimoParcela = $this->EcmConfig->find()->where(['nome' => 'valor_minimo_parcela'])->first();

        $formaPagamento = $this->EcmFormaPagamento->find()
            ->contain([
                'EcmTipoProduto' => function($q){
                    return $q->where(['EcmTipoProduto.id' => 47]);
                }
            ])
            ->where([
                'EcmFormaPagamento.tipo' => 'cartao_recorrencia',
                'EcmFormaPagamento.habilitado' => 'true'
            ])->first();

        $numeroParcelas = EcmProduto::verificaParcelasTrilhas(
            $produto, $formaPagamento, $maximoNumeroParcela, $valorMinimoParcela
        );

        return $numeroParcelas;
    }

    private function listaProdutosRelacionados($idProduto){
        $ecm_produto = $this->EcmProduto->find()->contain([
            'MdlCourse' => function ($q) use($idProduto) {
                return $q->contain(['EcmProduto' => function($q) use($idProduto){
                    return $q->select(['id', 'nome', 'preco', 'sigla'])
                        ->contain([
                        'EcmTipoProduto' => function($q) use($idProduto){
                            return $q->select(['id', 'nome']);
                        }
                    ])->where(['EcmProduto.refcurso' => 'true', 'EcmProduto.id !=' => $idProduto]);
                }])->order(["CONVERT(substring(shortname, POSITION('_C' IN shortname)+2), UNSIGNED INTEGER)"]);
            }
        ])->where(['EcmProduto.id' => $idProduto])->first();

        $listaProdutos = [];
        foreach($ecm_produto['mdl_course'] as $course){
            $produto = array_pop($course['ecm_produto']);
            if(!empty($produto['ecm_tipo_produto'])) {
                $produto['categorias'] = $produto['ecm_tipo_produto'];
                foreach($produto['categorias'] as $categoria){
                    unset($categoria['_joinData']);
                }
            }
            unset($produto['ecm_tipo_produto']);

            if(!empty($produto['ecm_imagem'])){
                $produto['imagens'] = $produto['ecm_imagem'];
                foreach($produto['imagens'] as $imagem)
                    $imagem['src'] = Router::url('upload/'.$imagem['src'], true);
            }
            unset($produto['ecm_imagem']);
            unset($produto['_joinData']);

            $listaProdutos[] = $produto;
        }

        return $listaProdutos;
    }

    public function get($id = null)
    {
        $retorno = [];
        if(!is_numeric($id)) {
            $id = $this->request->data('id');
        }
        if (is_numeric($id)) {
            $this->loadModel('Produto.EcmProduto');
            if ($this->EcmProduto->exists(['EcmProduto.id' => $id])) {
                $retorno = $this->EcmProduto->get($id, [
                    'fields' => [
                        'id', 'nome', 'preco', 'sigla', 'habilitado', 'visivel'
                    ],
                    'contain' => [
                        'EcmImagem' => ['fields' => [
                            'src', 'type' => 'descricao', 'EcmProdutoEcmImagem.ecm_produto_id'
                        ]]
                    ]
                ]);

                $retorno['imagens'] = $retorno['ecm_imagem'];
                unset($retorno['ecm_imagem']);
                foreach($retorno['imagens'] as $imagem)
                    $imagem['src'] = Router::url('upload/'.$imagem['src'], true);

                $this->loadModel('Promocao.EcmPromocao');
                $this->loadModel('Carrinho.EcmCarrinho');
                $carrinho = $this->EcmCarrinho->newEntity();

                $userId = $this->request->session()->read('Auth.User.id');

                $promocoes = $this->EcmPromocao->buscaPromocoesAtivasUsuario($retorno, $userId);
                $desconto = $carrinho->verificarDesconto($retorno, $promocoes);
                if(is_array($desconto)){
                    foreach($desconto as $key => $value)
                        $retorno->$key = $value;
                    $retorno->promocao->datainicio = strtotime($retorno->promocao->datainicio->format('Y-m-d 00:00:00'));
                    $retorno->promocao->datafim = strtotime($retorno->promocao->datafim->format('Y-m-d 23:59:59'));
                }

            }
        }
        $this->set(compact('retorno'));
    }

    protected function validaAlternativeHost()
    {
        $alternativeHostId = $this->request->session()->read('alternativeHostId');

        if(!is_null($alternativeHostId))
            return $alternativeHostId;

        return parent::validaAlternativeHost();
    }

    public function destaques()
    {
        $retorno = [];
        $alternativeHostId = 1;

        if(!is_array($alternativeHostId)) {
            $this->loadModel('Produto.EcmProduto');
            $retorno = $this->EcmProduto->find('all', [
                'limit' => 3,
                'fields' => [
                    'id', 'nome', 'preco', 'sigla', 'habilitado', 'visivel', 'parcela',
                    'ordem' => 'EcmProdutoEcmTipoProdutoEcmAlternativeHost.ordem'
                ],
                'contain' => [
                    'EcmImagem' => [
                        'fields' => [
                            'src', 'type' => 'descricao', 'EcmProdutoEcmImagem.ecm_produto_id'
                        ]
                    ],
                    'EcmProdutoInfo' => [
                        'fields' => [
                            'id', 'titulo', 'chamada', 'persona', 'descricao', 'url'
                        ],
                        'EcmProdutoInfoArquivos',
                        'EcmProdutoInfoConteudo' => [
                            'fields' => ['EcmProdutoInfoConteudo.id', 'ecm_produto_info_id', 'titulo', 'descricao']
                        ]
                    ],
                    'EcmTipoProduto'
                ],
                'conditions' => ['EcmProduto.habilitado' => 'true', 'EcmProduto.visivel' => 'true',
                    'EcmProdutoEcmTipoProdutoEcmAlternativeHost.ecm_alternative_host_id' => $alternativeHostId,
                    'EcmTipoProduto.nome' => 'Destaque'],
                'order' => ['EcmProdutoEcmTipoProdutoEcmAlternativeHost.ordem ASC']
            ])->innerJoin(['EcmProdutoEcmTipoProduto' => 'ecm_produto_ecm_tipo_produto'],
                'EcmProduto.id = EcmProdutoEcmTipoProduto.ecm_produto_id')
            ->innerJoin(['EcmTipoProduto' => 'ecm_tipo_produto'],
                'EcmProdutoEcmTipoProduto.ecm_tipo_produto_id = EcmTipoProduto.id')
            ->innerJoin(['EcmProdutoEcmTipoProdutoEcmAlternativeHost' => 'ecm_produto_ecm_tipo_produto_ecm_alternative_host'],
                'EcmProdutoEcmTipoProduto.id = EcmProdutoEcmTipoProdutoEcmAlternativeHost.ecm_produto_ecm_tipo_produto_id')
            ->toArray();

            $this->loadModel('Promocao.EcmPromocao');
            $this->loadModel('Carrinho.EcmCarrinho');
            $carrinho = $this->EcmCarrinho->newEntity();

            $userId = $this->request->session()->read('Auth.User.id');

            foreach($retorno as $produto) {
                $produto['imagens'] = $produto['ecm_imagem'];
                unset($produto['ecm_imagem']);
                foreach($produto['imagens'] as $imagem)
                    $imagem['src'] = Router::url('upload/'.$imagem['src'], true);
                $produto['info'] = $produto['ecm_produto_info'];
                unset($produto['ecm_produto_info']);
                $promocoes = $this->EcmPromocao->buscaPromocoesAtivasUsuario($produto, $userId);
                $desconto = $carrinho->verificarDesconto($produto, $promocoes);
                if(is_array($desconto)){
                    foreach($desconto as $key => $value)
                        $produto->$key = $value;
                    $produto->promocao->datainicio = strtotime($produto->promocao->datainicio->format('Y-m-d 00:00:00'));
                    $produto->promocao->datafim = strtotime($produto->promocao->datafim->format('Y-m-d 23:59:59'));
                }

                foreach($produto->ecm_tipo_produto as $tipoproduto){
                    if($tipoproduto->id == 32)
                        $produto->produtos = $this->listaProdutosRelacionados($produto['id']);
                    elseif($tipoproduto->id == 47) {

                        if ($pacelas = $this->verificaParcelasTrilhas($produto)) {
                            if (empty($produto->valorTotal)) {
                                $produto->valorTotal = $produto->preco;
                            }
                            $produto->valor_parcelado = ($produto->valorTotal / $pacelas);
                            $produto->parcelas = intval($pacelas);
                        }
                    }
                }

                $produto->categorias = $produto->ecm_tipo_produto;
                unset($produto->ecm_tipo_produto);

                foreach($produto->categorias as $tipo){
                    unset($tipo->ecm_tipo_produto_id);
                    unset($tipo->_joinData);
                    unset($tipo->theme);
                    unset($tipo->categoria);
                }
            }
        }

        $this->set(compact('retorno'));
    }

    public function getInfoUrl($id = null)
    {
        $retorno = ['sucesso' => false];
        if (is_null($id))
            $id = $this->request->data('id');
        if (!is_null($id)) {
            $this->loadModel('Produto.EcmProdutoInfo');
            $ecmProdutoInfo = $this->EcmProdutoInfo->find('all', [
                'conditions' => ['ecm_produto_id' => $id], 'fields' => ['url']
            ])->first();
            if(isset($ecmProdutoInfo))
                $retorno = ['sucesso' => true, 'url' => $ecmProdutoInfo->url];
        }
        $this->set(compact('retorno'));
    }

    public function getInfo($id = null)
    {
        $retorno = [];
        if(is_null($id)) {
            $id = $this->request->data('id');
        }
        if (!is_null($id)) {
            $this->loadModel('Produto.EcmProdutoInfo');

            $condition = ['EcmProdutoInfo.url' => $id];
            if(is_numeric($id))
                $condition = ['EcmProdutoInfo.ecm_produto_id' => $id];

            if ($this->EcmProdutoInfo->exists($condition)) {
                $retorno = $this->EcmProdutoInfo->find('all', [
                    'contain' => [
                        'EcmProdutoInfoConteudo' => function($q){
                            return $q->orderAsc('ordem');
                        },
                        'EcmProdutoInfoFaq',
                        'EcmProdutoInfoArquivos' => [
                            'EcmImagem'
                        ],
                        'EcmProduto' => [
                            'EcmTipoProduto',
                            'EcmImagem'
                        ]
                    ],
                    'conditions' => $condition
                ])->first();

                if(isset($retorno['ecm_produto']) && $retorno['ecm_produto']['visivel'] === 'true' ){
                    if(!empty($retorno['ecm_produto']))
                        $retorno['produto'] = $retorno['ecm_produto'];
                    unset($retorno['ecm_produto']);

                    if(!empty($retorno['ecm_produto_info_conteudo'])) {
                        $retorno['conteudos'] = $retorno['ecm_produto_info_conteudo'];
                        foreach($retorno['conteudos'] as $conteudos) {
                            $conteudos['info'] = $conteudos['ecm_produto_info_id'];
                            unset($conteudos['ecm_produto_info_id']);
                        }
                    }
                    unset($retorno['ecm_produto_info_conteudo']);

                    if(!empty($retorno['ecm_produto_info_arquivos'])) {
                        $retorno['files'] = $retorno['ecm_produto_info_arquivos'];
                        foreach ($retorno['files'] as $files) {
                            $files['info'] = $files['ecm_produto_info_id'];
                            unset($files['ecm_produto_info_id']);

                            if(!empty($files['path']))
                                $files['link'] = Router::url('upload/' .  $files['path'], true);

                            $files['tipo'] = $files['ecm_produto_info_arquivos_tipos_id'];
                            unset($files['ecm_produto_info_arquivos_tipos_id']);
                            unset($files['ecm_imagem']);
                            unset($files['ecm_imagem_id']);
                            unset($files['path']);
                        }
                    }
                    unset($retorno['ecm_produto_info_arquivos']);

                    if(!empty($retorno['ecm_produto_info_faq'])){
                        $retorno['faqs'] = $retorno['ecm_produto_info_faq'];
                        foreach($retorno['faqs'] as $faq) {
                            $faq['info'] = $faq['ecm_produto_info_id'];
                            unset($faq['ecm_produto_info_id']);
                        }
                    }
                    unset($retorno['ecm_produto_info_faq']);

                    if(!empty($retorno['produto']['ecm_tipo_produto']))
                        $retorno['produto']['categorias'] = $retorno['produto']['ecm_tipo_produto'];
                    unset($retorno['produto']['ecm_tipo_produto']);

                    if(!empty($retorno['produto']['ecm_imagem'])){
                        $retorno['produto']['imagens'] = $retorno['produto']['ecm_imagem'];
                        foreach($retorno['produto']['imagens'] as $imagem)
                            $imagem['src'] = Router::url('upload/'.$imagem['src'], true);
                    }
                    unset($retorno['produto']['ecm_imagem']);

                    $this->loadModel('Produto.EcmProduto');
                    $this->loadModel('Instrutor.EcmInstrutor');
                    foreach($retorno['produto']['categorias'] as $categorias){
                        switch($categorias['id']){
                            case 10:
                                $produto = $this->EcmProduto->find()->contain([
                                    'EcmCursoPresencialTurma' => function ($q) {
                                        return $q->innerJoinWith('EcmCursoPresencialData', function ($q) {
                                            return $q->contain(['EcmCursoPresencialLocal.MdlCidade.MdlEstado'])
                                                ->where(['EcmCursoPresencialData.datainicio > NOW()']);
                                        })->contain(['EcmInstrutor' => [
                                            'EcmImagem',
                                            'MdlUser' => ['fields' => ['nome' => 'CONCAT(firstname, " ", lastname)']],
                                            'conditions' => ['EcmInstrutor.ativo' => 1]
                                        ]])->where(['EcmCursoPresencialTurma.status' => 'Ativo'])
                                            ->group(['EcmCursoPresencialTurma.id']);
                                    }
                                ])->where(['EcmProduto.id' => $retorno['produto']['id']])->first();

                                $retorno['produto']['ecm_curso_presencial_turma'] = $produto['ecm_curso_presencial_turma'];

                                $this->EcmProduto->tratarEvento($retorno['produto']);
                                unset($retorno['ecm_produto']);
                                break;
                            case 17:
                            case 32:
                            case 42:
                            case 47:
                                $dataAtual = new \DateTime();
                                $ecm_produto = $this->EcmProduto->find()->contain([
                                    'MdlCourse' => function($q) use($retorno, $dataAtual){
                                        return $q->contain(['EcmProduto' => function($q) use($retorno, $dataAtual){
                                            return $q->contain(['EcmTipoProduto','EcmImagem',
                                                'EcmPromocao' =>[
                                                    'conditions' => [
                                                        'EcmPromocao.habilitado' => 'true',
                                                        'EcmPromocao.datainicio <=' => $dataAtual->format('Y-m-d'),
                                                        'EcmPromocao.datafim >=' => $dataAtual->format('Y-m-d')
                                                    ]
                                                ],
                                                'EcmInstrutor' => [
                                                    'EcmImagem',
                                                    'MdlUser' => ['fields' => ['nome' => 'CONCAT(firstname, " ", lastname)']],
                                                    'conditions' => ['EcmInstrutor.ativo' => 1]
                                                ],
                                                'EcmProdutoInfo' => function($q){
                                                    return $q->select([
                                                        'EcmProdutoInfo.titulo',
                                                        'EcmProdutoInfo.qtd_aulas',
                                                        'EcmProdutoInfo.tempo_acesso',
                                                        'EcmProdutoInfo.carga_horaria',
                                                        'EcmProdutoInfo.material',
                                                        'EcmProdutoInfo.certificado_digital',
                                                        'EcmProdutoInfo.certificado_impresso',
                                                        'EcmProdutoInfo.atestado_digital',
                                                        'EcmProdutoInfo.forum',
                                                        'EcmProdutoInfo.tira_duvidas',
                                                        'EcmProdutoInfo.mobile',
                                                        'EcmProdutoInfo.atestado_digital',
                                                        'EcmProdutoInfo.software_demo',
                                                        'EcmProdutoInfo.simulador',
                                                        'EcmProdutoInfo.disponibilidade',
                                                        'EcmProdutoInfo.metodologia',
                                                        'EcmProdutoInfo.descricao',
                                                        'EcmProdutoInfo.url',
                                                        'EcmProdutoInfo.id'
                                                    ])
                                                    ->contain([
                                                        'EcmProdutoInfoConteudo' => function($q){
                                                            return $q->orderAsc('ordem');
                                                        },
                                                        'EcmProdutoInfoArquivos' => function($q){
                                                            return $q->select([
                                                                'EcmProdutoInfoArquivos.link',
                                                                'EcmProdutoInfoArquivos.ecm_produto_info_id',
                                                                'tipo' => 'EcmProdutoInfoArquivosTipos.tipo',
                                                                'id_tipo' => 'EcmProdutoInfoArquivosTipos.id'
                                                            ])
                                                            ->contain(['EcmProdutoInfoArquivosTipos']);
                                                        }
                                                    ]);
                                                }
                                            ])->where(['EcmProduto.refcurso' => 'true']);
                                        }])->order(["CONVERT(substring(shortname, POSITION('_C' IN shortname)+2), UNSIGNED INTEGER)"]);
                                    }
                                ])->where(['EcmProduto.id' => $retorno['produto']['id']])->first();

                                if($categorias['id'] == 17){
                                    $pacote = $this->EcmProduto->EcmProdutoPacote
                                        ->find('all',
                                        [
                                            'fields'=>['enrolperiod']
                                        ])
                                        ->where(['ecm_produto_id' => $ecm_produto->get('id')])->first();
                                    $retorno->get('produto')->enrolperiod  = $pacote->get('enrolperiod');
                                }

                                $retorno['produto']['produtos'] = [];
                                foreach($ecm_produto['mdl_course'] as $course){
                                    if(!empty($course['ecm_produto'])) {
                                        $produto = array_pop($course['ecm_produto']);
                                        if (!empty($produto['ecm_tipo_produto']))
                                            $produto['categorias'] = $produto['ecm_tipo_produto'];
                                        unset($produto['ecm_tipo_produto']);

                                        if (!empty($produto['ecm_imagem'])) {
                                            $produto['imagens'] = $produto['ecm_imagem'];
                                            foreach ($produto['imagens'] as $imagem)
                                                $imagem['src'] = Router::url('upload/' . $imagem['src'], true);
                                        }
                                        unset($produto['ecm_imagem']);

                                        if (!empty($produto['ecm_instrutor'])) {
                                            $produto['instrutor'] = $produto['ecm_instrutor'];
                                            foreach ($produto['instrutor'] as $instrutor) {
                                                if (isset($instrutor['ecm_imagem'])) {
                                                    $instrutor['imagem'] = $instrutor['ecm_imagem'];
                                                    unset($instrutor['ecm_imagem']);
                                                    $instrutor['imagem']['src'] = Router::url('upload/' . $instrutor['imagem']['src'], true);
                                                }
                                            }
                                        }

                                        if (!is_null($produto['ecm_produto_info'])) {
                                            $produto['info'] = $produto['ecm_produto_info'];

                                            if (!empty($produto['ecm_produto_info']['ecm_produto_info_conteudo'])) {
                                                $produto['info']['conteudos'] = $produto['ecm_produto_info']['ecm_produto_info_conteudo'];
                                            }

                                            if (!empty($produto['ecm_produto_info']['ecm_produto_info_arquivos'])) {
                                                $produto['info']['files'] = $produto['ecm_produto_info']['ecm_produto_info_arquivos'];
                                                unset($produto['ecm_produto_info']['ecm_produto_info_arquivos']);
                                            }

                                            unset($produto['ecm_instrutor']);
                                            unset($produto['ecm_produto_info']);
                                            unset($produto['info']['ecm_produto_info_conteudo']);
                                            unset($produto['info']['ecm_produto_info_arquivos']);
                                        }
                                        $produto->tempo_aula = $course->timeaccesssection;

                                        $retorno['produto']['produtos'][] = $produto;
                                    }
                                }
                                break;
                            case 2:
                                $retorno['produto']['instrutor'] = $this->EcmInstrutor->find()
                                    ->select(['id', 'mdl_user_id', 'descricao', 'ecm_imagem_id', 'formacao', 'ativo'])
                                    ->contain(['EcmImagem' => ['fields' => ['id', 'nome', 'src', 'descricao']],
                                        'MdlUser' => ['fields' => ['nome' => 'CONCAT(firstname, " ", lastname)']]
                                    ])->where(['EcmInstrutor.ativo' => 1])
                                    ->matching('EcmProduto', function ($q)use($retorno) {
                                        return $q->where(['EcmProduto.id' => $retorno['produto']['id']]);
                                    })->toArray();

                                if(empty($retorno['produto']['instrutor'])){
                                    unset($retorno['produto']['instrutor']);
                                } else {
                                    foreach($retorno['produto']['instrutor'] as $instrutor){
                                        if(isset($instrutor['ecm_imagem'])){
                                            $instrutor['imagem'] = $instrutor['ecm_imagem'];
                                            unset($instrutor['ecm_imagem']);
                                            $instrutor['imagem']['src'] = Router::url('upload/'.$instrutor['imagem']['src'], true);
                                        }
                                    }
                                }
                                break;
                        }
                    }

                    $this->loadModel('Promocao.EcmPromocao');
                    $this->loadModel('Carrinho.EcmCarrinho');
                    $carrinho = $this->EcmCarrinho->newEntity();

                    $userId = $this->request->session()->read('Auth.User.id');
                    $promocoes = $this->EcmPromocao->buscaPromocoesAtivasUsuario($retorno['produto'], $userId);

                    $desconto = $carrinho->verificarDesconto($retorno['produto'], $promocoes);
                    if(is_array($desconto)){
                        foreach($desconto as $key => $value)
                            $retorno->$key = $value;
                        $retorno->promocao->datainicio = strtotime($retorno->promocao->datainicio->format('Y-m-d 00:00:00'));
                        $retorno->promocao->datafim = strtotime($retorno->promocao->datafim->format('Y-m-d 23:59:59'));
                    }

                    if(EcmTipoProduto::verificarTipoProduto($retorno['produto']['categorias'], 47)) {
                        if($pacelas = $this->verificaParcelasTrilhas($retorno->produto)) {
                            if (empty($retorno->valorTotal)) {
                                $retorno->valorTotal = $retorno->produto->preco;
                            }

                            $retorno->valorParcelado = ($retorno->valorTotal / $pacelas);
                            $retorno->parcelas = intval($pacelas);
                        }
                    }

                    $retorno['dependencias'] = $this->verificarFaseAltoQiLab($retorno['produto']['id']);

                    $retorno['formato'] = [];
                    $formato = ['aulas' => 'qtd_aulas', 'tempo_acesso' => 'tempo_acesso',
                        'tempo_aula' => 'tempo_aula', 'carga_horaria' => 'carga_horaria',
                        'material' => 'material', 'certificado_digital' => 'certificado_digital',
                        'certificado_impresso' => 'certificado_impresso',
                        'atestado_digital' => 'atestado_digital', 'forum' => 'forum',
                        'tira_duvidas' => 'tira_duvidas', 'mobile' => 'mobile',
                        'software_demo' => 'software_demo', 'simulador' => 'simulador',
                        'disponibilidade' => 'disponibilidade',
                        'metodologia' => 'metodologia'];
                    foreach ($formato as $key => $value) {
                        if(is_null($value)){
                            $retorno['formato'][$key] = $value;
                        }else{
                            $retorno['formato'][$key] = $retorno[$value];
                            unset($retorno[$value]);
                        }
                    }

                    $retorno['seo'] = [];
                    $seo = ['title' => 'metatag_titulo', 'keywords' => 'metatag_key',
                        'description' => 'metatag_descricao', 'url' => 'url'];
                    foreach ($seo as $key => $value) {
                        $retorno['seo'][$key] = $retorno[$value];
                        unset($retorno[$value]);
                    }

                    if(isset($retorno['produto']['eventos'])){
                        foreach($retorno['produto']['eventos'] as $evento_produto){
                            $evento = clone $evento_produto;
                            $evento->set('ecm_produto', $retorno['produto']);
                            $evento_produto['vagas_preenchidas'] = $this->EcmCarrinho->EcmCarrinhoItem->totalVagasUtilizadasCursoPresencial($evento);
                        }
                    }
                } else {
                    $retorno = ['sucesso' => false, 'mensagem' => 'Produto não visivel'];
                }

            }
        }

        $this->set(compact('retorno'));
    }

    /**
     * @param $idProduto
     * @return array|bool
     */
    private function verificarFaseAltoQiLab($idProduto){
        $this->loadModel('Produto.MdlFase');
        $mdlFase = $this->MdlFase->find()->where(['ecm_produto_id' => $idProduto])->first();
        if(!is_null($mdlFase)){
            if(!is_null($mdlFase->mdl_fase_id)) {
                $mdlFase2 = $this->MdlFase->get($mdlFase->mdl_fase_id);
                $ecmProduto2 = $this->verificarFaseAltoQiLab($mdlFase2->ecm_produto_id);
                $idUser = $this->Auth->user('id');

                if (!$this->MdlFase->MdlFaseConclusao->exists(['mdl_fase_id' => $mdlFase->mdl_fase_id, 'mdl_user_id' => $idUser])) {
                    $ecmProduto = $this->MdlFase->EcmProduto->find()
                        ->select(['id', 'nome', 'preco', 'parcela'])
                        ->contain(['EcmProdutoInfo' => ['fields' => ['url' => 'url']],
                            'EcmProdutoEcmProduto' => [
                                'EcmProduto' => [
                                    'fields' => ['EcmProduto.id', 'nome', 'preco', 'parcela'],
                                    'EcmProdutoInfo' => ['fields' => ['url']],
                                    'EcmTipoProduto' => [
                                        'fields' => ['EcmProdutoEcmTipoProduto.ecm_produto_id', 'nome'],
                                        'conditions' => ['EcmTipoProduto.id' => 51]
                                    ]
                                ]]])
                        ->where(['EcmProduto.id' => $mdlFase2->ecm_produto_id])->first();

                    $ecmProduto->provas = [];
                    foreach ($ecmProduto->ecm_produto_ecm_produto as $produto) {
                        if (!empty($produto->ecm_produto->ecm_tipo_produto)) {
                            if (!is_null($produto->ecm_produto->ecm_produto_info)) {
                                $produto->ecm_produto->url = $produto->ecm_produto->ecm_produto_info->url;
                            }
                            unset($produto->ecm_produto->ecm_tipo_produto);
                            unset($produto->ecm_produto->ecm_produto_info);
                            $ecmProduto->provas[] = $produto->ecm_produto;
                        }
                    }
                    unset($ecmProduto->ecm_produto_ecm_produto);

                    if ($ecmProduto2) return array_merge($ecmProduto2, [$ecmProduto]);

                    return [$ecmProduto];
                }
            }
        }
        return false;
    }

    public function listarInfos()
    {
        $this->loadModel('Produto.EcmProdutoInfo');
        $retorno = $this->EcmProdutoInfo->find('all', [
            'contain' => [
                'EcmProdutoInfoArquivos',
                'EcmProdutoInfoConteudo' => function($q){
                    return $q->orderAsc('ordem');
                }
            ]
        ]);

        foreach($retorno as $info){
            $info['conteudos'] = $info['ecm_produto_info_conteudo'];
            unset($info['ecm_produto_info_conteudo']);
            foreach($info['conteudos'] as $conteudos) {
                $conteudos['info'] = $conteudos['ecm_produto_info_id'];
                unset($conteudos['ecm_produto_info_id']);
            }

            $info['files'] = $info['ecm_produto_info_arquivos'];
            unset($info['ecm_produto_info_arquivos']);
            foreach($info['files'] as $files) {
                $files['info'] = $files['ecm_produto_info_id'];
                unset($files['ecm_produto_info_id']);
            }

            $info['formato'] = [];
            $formato = ['aulas' => 'qtd_aulas', 'tempo_acesso' => 'tempo_acesso',
                'tempo_aula' => 'tempo_aula', 'carga_horaria' => 'carga_horaria',
                'material' => 'material', 'certificado_digital' => 'certificado_digital',
                'certificado_impresso' => 'certificado_impresso',
                'atestado_digital' => NULL, 'forum' => 'forum',
                'tira_duvidas' => 'tira_duvidas', 'mobile' => 'mobile',
                'software_demo' => 'software_demo', 'simulador' => 'simulador',
                'disponibilidade' => 'disponibilidade'];
            foreach ($formato as $key => $value) {
                if(is_null($value)){
                    $info['formato'][$key] = $value;
                }else{
                    $info['formato'][$key] = $info[$value];
                    unset($info[$value]);
                }
            }

            $info['seo'] = [];
            $seo = ['title' => 'metatag_titulo', 'keywords' => 'metatag_key',
                'description' => 'metatag_descricao', 'url' => 'url'];
            foreach ($seo as $key => $value) {
                $info['seo'][$key] = $info[$value];
                unset($info[$value]);
            }
        }

        $this->set(compact('retorno'));
    }

    public function pacotes()
    {
        $this->loadModel('Produto.EcmProduto');
        $retorno = $this->EcmProduto->find('all', [
            'fields' => ['id', 'nome', 'preco', 'sigla', 'habilitado', 'visivel'],
            'contain' => [
                'EcmImagem' => ['fields' => [
                    'src', 'type' => 'descricao', 'EcmProdutoEcmImagem.ecm_produto_id'
                ]],
                'MdlCourse' => function($q){
                    return $q->select(['id'])
                        ->contain(['EcmProduto' => function($q){
                            return $q->select(['id', 'nome', 'preco', 'sigla', 'habilitado', 'visivel'])
                                ->order(['EcmProduto.id' => 'ASC'])
                                ->contain(['EcmTipoProduto' => function($q){
                                    return $q->select(['id', 'EcmProdutoEcmTipoProduto.ecm_produto_id']);
                                }])
                                ->where(['EcmProduto.refcurso' => 'true']);
                        }]);
                }
            ]
        ])->order(['EcmProduto.id' => 'ASC'])
            ->innerJoin(['EcmProdutoEcmTipoProduto' => 'ecm_produto_ecm_tipo_produto'],
                'EcmProduto.id = EcmProdutoEcmTipoProduto.ecm_produto_id')
            ->innerJoin(['EcmTipoProduto' => 'ecm_tipo_produto'],
                'EcmProdutoEcmTipoProduto.ecm_tipo_produto_id = EcmTipoProduto.id')
            ->where(['EcmTipoProduto.nome' => 'Pacotes']);

        foreach($retorno as $produto) {
            $produto['imagens'] = $produto['ecm_imagem'];
            unset($produto['ecm_imagem']);
            $produtos = [];
            foreach($produto['mdl_course'] as $course){
                foreach($course['ecm_produto'] as $pro){
                    $produtos[] = $pro;
                }
            }
            $produto['produto'] = $produtos;
            unset($produto['mdl_course']);
        }

        $this->set(compact('retorno'));
    }

    public function presencial()
    {
        $this->loadModel('Produto.EcmProduto');
        $retorno = $this->EcmProduto->find('all', [
            'fields' => ['id', 'nome', 'preco', 'sigla', 'habilitado', 'visivel'],
            'contain' => [
                'EcmTipoProduto' => ['fields' => [
                    'id', 'nome', 'ordem', 'habilitado', 'blocked', 'ecm_tipo_produto_id',
                    'EcmProdutoEcmTipoProduto.ecm_produto_id'
                ]],
                'EcmImagem' => ['fields' => [
                    'src', 'type' => 'descricao', 'EcmProdutoEcmImagem.ecm_produto_id'
                ]],
            ]
        ])->innerJoin(['EcmProdutoEcmTipoProduto' => 'ecm_produto_ecm_tipo_produto'],
                'EcmProduto.id = EcmProdutoEcmTipoProduto.ecm_produto_id')
            ->innerJoin(['EcmTipoProduto' => 'ecm_tipo_produto'],
                'EcmProdutoEcmTipoProduto.ecm_tipo_produto_id = EcmTipoProduto.id')
            ->where(['EcmProduto.habilitado' => 'true', 'EcmProduto.visivel' => 'true',
                'EcmTipoProduto.nome' => 'Presencial']);

        foreach($retorno as $produto) {
            $produto['imagens'] = $produto['ecm_imagem'];
            unset($produto['ecm_imagem']);
            $produto['categorias'] = $produto['ecm_tipo_produto'];
            unset($produto['ecm_tipo_produto']);
            foreach($produto['categorias'] as $categorias) {
                $categorias['parent'] = $categorias['ecm_tipo_produto_id'];
                unset($categorias['ecm_tipo_produto_id']);
                $categorias['children'] = [];
            }
        }

        $this->set(compact('retorno'));
    }

    public function tipos()
    {
        $this->loadModel('Produto.EcmTipoProduto');
        $retorno = $this->EcmTipoProduto->find('all', ['fields' => [
            'id', 'nome', 'ordem', 'habilitado', 'blocked', 'parent' => 'ecm_tipo_produto_id'
        ]]);

        foreach($retorno as $tipo) {
            $tipo['children'] = [];
        }

        $this->set(compact('retorno'));
    }

    public function getTipo($id = null)
    {
        $retorno = [];
        if(!is_numeric($id)) {
            $id = $this->request->data('id');
        }
        if (is_numeric($id)) {
            $this->loadModel('Produto.EcmTipoProduto');

            if ($this->EcmTipoProduto->exists(['EcmTipoProduto.id' => $id])) {
                $retorno = $this->EcmTipoProduto->get($id, ['fields' => [
                    'id', 'nome', 'ordem', 'habilitado', 'blocked', 'parent' => 'ecm_tipo_produto_id'
                ]]);

                $retorno['children'] = [];
            }
        }

        $this->set(compact('retorno'));
    }

    public function categorias()
    {
        $this->loadModel('Produto.EcmTipoProduto');
        $retorno = $this->EcmTipoProduto->find('all', ['fields' => [
            'id', 'nome', 'ordem', 'habilitado', 'blocked', 'parent' => 'ecm_tipo_produto_id'
        ]])->order(['ecm_tipo_produto_id' => 'ASC', 'id' => 'ASC'])->toArray();

        foreach($retorno as $tipo){
            if($tipo->parent > 0){
                foreach($retorno as $produto){
                    if(!isset($produto->children)){
                        $produto->children = [];
                    }
                    if($tipo->parent == $produto->id){
                        $produto->children[] = $tipo;
                    }
                }
                reset($retorno);
            }
        }
        foreach($retorno as $key => $tipo){
            if($tipo->parent != "0"){
                unset($retorno[$key]);
            }
        }

        $this->set(compact('retorno'));
    }

    public function getCategoria($id = null)
    {
        $retorno = [];
        if(!is_numeric($id)) {
            $id = $this->request->data('id');
        }
        if (is_numeric($id)) {
            $this->loadModel('Produto.EcmTipoProduto');
            $tipoproduto = $this->EcmTipoProduto->find('all', ['fields' => [
                'id', 'nome', 'ordem', 'habilitado', 'blocked', 'parent' => 'ecm_tipo_produto_id'
            ]])->order(['ecm_tipo_produto_id' => 'ASC', 'id' => 'ASC'])->toArray();

            foreach ($tipoproduto as $tipo){
                if($tipo->id == $id){
                    $retorno = $tipo;
                }
                if ($tipo->parent > 0) {
                    foreach ($tipoproduto as $produto) {
                        if (!isset($produto->children)) {
                            $produto->children = [];
                        }
                        if ($tipo->parent == $produto->id) {
                            $produto->children[] = $tipo;
                        }
                    }
                    reset($tipoproduto);
                }
            }
        }
        $this->set(compact('retorno'));
    }

    public function produtosTiposId(){
        $this->loadModel('Produto.EcmProduto');

        $retorno = $this->EcmProduto->buscaIdProdutoTipos();

        $this->set(compact('retorno'));
    }

    /**
     * Url: /produto/wsc-produto/encript-codigotw
     * Parametros: codigo, aplicacao, software, modulos_linha, tabela, conexao, licenca, ativacao, especiais
     * Retorno: codigo_tw, descricao
     */
    public function encriptCodigotw(){
        $this->loadModel('Produto.EcmProduto');

        $retorno = $this->EcmProduto->encriptCodigotw($this->request->data);

        $this->set(compact('retorno'));
    }

    /**
     * Url: /produto/wsc-produto/decript-codigotw
     * Parametros: codigo
     * Retorno: produto_aplicacao
     */
    public function decriptCodigotw(){
        $this->loadModel('Produto.EcmProduto');

        $retorno = $this->EcmProduto->decriptCodigotw($this->request->data('codigo'));

        $this->set(compact('retorno'));
    }
}