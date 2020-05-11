<?php
namespace Publicidade\Controller;
use Cake\Routing\Router;
use Carrinho\Model\Entity\EcmCarrinho;

/**
 * EcmPublicidade Controller
 *
 * @property \Publicidade\Model\Table\EcmPublicidadeTable $EcmPublicidade */
class EcmPublicidadeController extends AppController
{
    private $tipo = ['Catalogo' => 'Catalogo', 'Convite' => 'Convite', 'Publicidade' => 'Publicidade'];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('ElFinder');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $id = $this->request->data['id'];
            unset($this->request->data['id']);
            $this->loadModel('CursoPresencial.EcmCursoPresencialTurma');
            $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->find('all')->where(['ecm_produto_id' => $id])
                ->contain(['EcmCursoPresencialData'=>['EcmCursoPresencialLocal'=>['MdlCidade'=>['MdlEstado']]]])
            ->order(['id' => 'DESC']);
            echo json_encode($ecmCursoPresencialTurma);
        }

        $conditions = [];
        if(count($this->request->query)){
            if(isset($this->request->query['nome']) && !empty($this->request->query['nome'])){
                array_push($conditions, 'EcmPublicidade.nome LIKE "%'.$this->request->query['nome'].'%"');
            }
            if(isset($this->request->query['tipo']) && $this->request->query['tipo'] != "0"){
                array_push($conditions, 'EcmPublicidade.tipo = "'.$this->request->query['tipo'] . '"');
            }
            if(isset($this->request->query['ecm_produto_id']) && $this->request->query['ecm_produto_id'] != "0"){
                array_push($conditions, 'EcmPublicidade.ecm_produto_id = '.$this->request->query['ecm_produto_id']);
            }
            if(isset($this->request->query['habilitado']) && $this->request->query['habilitado'] != "2"){
                array_push($conditions, 'EcmPublicidade.habilitado = '.$this->request->query['habilitado']);
            }
        }

        $this->paginate = [
            'conditions' => $conditions
        ];
        $ecmPublicidade = $this->paginate($this->EcmPublicidade);

        $tipo[0] = 'Todos';
        $tipo = array_merge($tipo, $this->tipo);
        $habilitado = [2 => 'Todos', 1 => 'Sim', 0 => 'Não'];
        $ecmProduto = $this->EcmPublicidade->EcmProduto->find('list', ['keyField' => 'id', 'valueField' => 'nome'])->toArray();
        $ecmProduto[0] = 'Todos';
        ksort($ecmProduto);

        $this->loadModel('Configuracao.EcmConfig');
        $dominioPublicidade = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_publicidade'])->first()->valor;
        if (strpos($dominioPublicidade, 'http') === false)
            $dominioPublicidade = 'http://' . $dominioPublicidade;
        if(substr($dominioPublicidade, -1) != "/")
            $dominioPublicidade .= "/";

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmPublicidade', 'tipo', 'habilitado', 'ecmProduto', 'dominioPublicidade'));
        $this->set('_serialize', ['ecmPublicidade']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Publicidade id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null, $idturma = null)
    {
        $ecmPublicidade = $this->EcmPublicidade->get($id);

        $this->loadModel('Configuracao.EcmConfig');
        $host = 'https://'.$this->EcmConfig->find()->where(['nome' => 'dominio_acesso_site'])->first()->valor.'/';
        if(!$ecmPublicidade->habilitado)
            return $this->redirect($host.'cursos');

        if($ecmPublicidade->tipo == 'Catalogo'){
            $convenio_desconto['professor'] = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'convenio_desconto_professor'])->first()->valor;
            $convenio_desconto['aluno'] = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'convenio_desconto_aluno'])->first()->valor;
            $convenio_desconto['associado'] = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'convenio_desconto_associado'])->first()->valor;
            $produtos = $this->EcmPublicidade->EcmProduto->find('list', ['keyField' => 'id', 'valueField' => function ($e) {
                return $e; }])->contain(['EcmProdutoInfo' => ['fields' => ['url']]])->toArray();
            /*$this->loadModel('Convenio.EcmConvenio');
            $ecmConvenio = $this->EcmConvenio->find('all')->toArray();
            foreach($ecmConvenio as $ecmConvenio){
                $retornaValor[$ecmConvenio->id]['instituicaoconvenio'] = $ecmConvenio->nome_instituicao;
            }*/
            $this->loadModel('Entidade.EcmAlternativeHost');
            $ecmAlternativeHosts = $this->EcmAlternativeHost->find('list', ['keyField' => 'shortname',
                'valueField' => 'id'])->toArray();
            $this->loadModel('Promocao.EcmPromocao');
            $ecmPromocoes = $this->EcmPromocao->find('list', ['keyField' => 'id', 'valueField' => function ($e) {
                return $e; }])->contain(['EcmProduto' => ['fields' => ['id', 'EcmPromocaoEcmProduto.ecm_promocao_id']],
                'EcmAlternativeHost' => ['fields' => ['id', 'shortname', 'EcmPromocaoEcmAlternativeHost.ecm_promocao_id']]])
                ->where(['EcmPromocao.habilitado = "true"', 'EcmPromocao.datainicio <=' => date("Y-m-d"),
                    'EcmPromocao.datafim >=' => date("Y-m-d")])->toArray();
            foreach($ecmPromocoes as $ecmPromocao){
                unset($lista);
                foreach($ecmPromocao->ecm_alternative_host as $ecmAlternativeHost){
                    $lista[$ecmAlternativeHost['id']] = $ecmAlternativeHost['shortname'];
                }
                if(isset($lista)) $ecmPromocao->ecm_alternative_host = $lista;
                unset($lista);
                foreach($ecmPromocao->ecm_produto as $ecmProduto){
                    $lista[$ecmProduto['id']] = $ecmProduto['id'];
                }
                if(isset($lista)) $ecmPromocao->ecm_produto = $lista;
            }

            $nome_da_instituicao = 'Nome da instituição';

            $this->set(compact('nome_da_instituicao', 'produtos', 'ecmAlternativeHosts', 'ecmPromocoes', 'convenio_desconto'));
        } else if($ecmPublicidade->tipo == 'Convite'){
            $info_curso_presencial = $folder = $id;
            $linkConvite = Router::url('/upload/publicidade/' . $id . '/', true);

            $produtoid = $ecmPublicidade->ecm_produto_id;
            $this->loadModel('Produto.EcmProduto');
            $ecmProduto = $this->EcmProduto->get($produtoid, ['contain' => [
                'EcmProdutoInfo' => ['fields' => ['url']],
                'MdlCourse' => function($q) {
                    return $q->contain(['EcmProduto' => function ($q) {
                        return $q->matching('EcmTipoProduto', function ($q) {
                            return $q->where(['EcmTipoProduto.id' => 33])->orWhere(['EcmTipoProduto.id' => 41]);
                        })->select(['id', 'preco', 'moeda', 'tipo' => 'EcmTipoProduto.id']);
                    }])->select(['id']);
                }
            ]]);
            $nomeCurso = $ecmProduto->nome;
            switch($ecmProduto->moeda){
                case 'dolar':
                    $moeda = 'US$ ';
                    break;
                case 'euro':
                    $moeda = '€ ';
                    break;
                default:
                    $moeda = 'R$ ';
            }
            $linkCurso = $host.$ecmProduto['ecm_produto_info']['url']; // Link de referência no site QiSat

            $preview = $idturma != "/:action";
            if($preview){
                $this->loadModel('CursoPresencial.EcmCursoPresencialTurma');
                $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->get($idturma, ['contain'=>
                    ['EcmCursoPresencialData'=>['EcmCursoPresencialLocal'=>['MdlCidade'=>['MdlEstado']]],
                        'EcmInstrutor'=>['MdlUser'], 'EcmProduto']]);

                $uf = $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->mdl_estado->uf;
                $cidade = $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->nome;

                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                date_default_timezone_set('America/Sao_Paulo');
                $data = "";
                foreach ($ecmCursoPresencialTurma->ecm_curso_presencial_data as $curso_presencial_data) {
                    $data .= '<div class="dataehora">';
                    $data .= utf8_encode(ucwords(strftime('%A',$curso_presencial_data->datainicio->format('U'))));
                    $data .= utf8_encode(strftime(', %d de ',$curso_presencial_data->datainicio->format('U')));
                    $data .= utf8_encode(ucwords(strftime('%B',$curso_presencial_data->datainicio->format('U'))));
                    $data .= utf8_encode(strftime(' de %Y',$curso_presencial_data->datainicio->format('U'))) . '<br/>';

                    $data .= 'das ' . $curso_presencial_data->datainicio->format('H:i');
                    $data .= 'às ' . $curso_presencial_data->saidaintervalo->format('H:i');
                    $data .= 'e das ' . $curso_presencial_data->voltaintervalo->format('H:i');
                    $data .= 'às ' . $curso_presencial_data->datafim->format('H:i') . '</div>';
                }

                $endereco = $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->endereco;
                $ministrante = $ecmCursoPresencialTurma->ecm_instrutor[0]->mdl_user;
                $ministrante = $ministrante->firstname . " " . $ministrante->lastname;

                if($ecmCursoPresencialTurma->valor_produto == "false")
                    $ecmProduto->preco = $ecmCursoPresencialTurma->valor;

                $caragaHoraria = $ecmCursoPresencialTurma->carga_horaria;

                $id = $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->mdl_estado->id;
            } else {
                $uf = 'UF';
                $cidade = 'Cidade';
                $data = ' Datas e horarios do curso';
                $endereco = 'Endereço contendo rua, bairro e referência';
                $ministrante = 'Nome e sobrenome do ministrante';
                $caragaHoraria = 'Carga horária total';

                $id = $ecmPublicidade->id;
            }

            $this->loadModel('Promocao.EcmPromocao');

            if(is_null($ecmProduto->preco)){
                $produtosSerie_precos = [];
                $produtosSerie_precosPromocionais = [];
                $produtosSerie_moedas = [];
                $produtosSerie_datasFim = [];
                foreach($ecmProduto['mdl_course'] as $course){
                    foreach($course['ecm_produto'] as $produto) {
                        if($produto['tipo'] == 33){
                            $id = $produto['id'];
                            $ecmPromocoes = $this->EcmPromocao->find('all')->where(['EcmPromocao.habilitado = "true"',
                                'EcmPromocao.datainicio <=' => date("Y-m-d"), 'EcmPromocao.datafim >=' => date("Y-m-d")])
                                ->innerJoinWith('EcmProduto', function ($q) use ($id) {
                                    return $q->where(['EcmProduto.id' => $id]);
                                })
                                ->innerJoinWith('EcmAlternativeHost', function ($q) {
                                    return $q->where(['EcmAlternativeHost.shortname' => 'QiSat']);
                                })->toArray();
                            $desconto = EcmCarrinho::verificarDesconto($produto, $ecmPromocoes);
                            switch($ecmProduto->moeda){
                                case 'dolar':
                                    $produtosSerie_moedas[$id] = 'US$ ';
                                    break;
                                case 'euro':
                                    $produtosSerie_moedas[$id] = '€ ';
                                    break;
                                default:
                                    $produtosSerie_moedas[$id] = 'R$ ';
                            }
                            $produtosSerie_precos[$id] = number_format(round($produto['preco']), 2, ',', '.');
                            $produtosSerie_precosPromocionais[$id] = number_format(round($produto['preco']), 2, ',', '.');
                            if(is_array($desconto)){
                                $produtosSerie_datasFim[$id] = $desconto['promocao']->datafim;
                                $produtosSerie_precosPromocionais[$id] = number_format(round($desconto['valorTotal']), 2, ',', '.');
                            }
                        } else if($produto['tipo'] == 41){
                            $ecmProduto->preco = $produto['preco'];
                        }
                    }
                }
                $this->set(compact('produtosSerie_precos', 'produtosSerie_precosPromocionais',
                    'produtosSerie_moedas', 'produtosSerie_datasFim'));
            }
            $preco = $ecmProduto->preco;

            //$this->loadModel('Promocao.EcmPromocao');
            $ecmPromocoes = $this->EcmPromocao->find('all')->where(['EcmPromocao.habilitado = "true"',
                'EcmPromocao.datainicio <=' => date("Y-m-d"), 'EcmPromocao.datafim >=' => date("Y-m-d")])
                ->innerJoinWith('EcmProduto', function ($q) use ($produtoid) {
                    return $q->where(['EcmProduto.id' => $produtoid]);
                })
                ->innerJoinWith('EcmAlternativeHost', function ($q) {
                    return $q->where(['EcmAlternativeHost.shortname' => 'QiSat']);
                })->toArray();
            $desconto = EcmCarrinho::verificarDesconto($ecmProduto, $ecmPromocoes);
            $precoPromocional = $ecmProduto->preco;
            if(is_array($desconto)){
                $dataInicioPromocao = $desconto['promocao']->datainicio;
                $dataFimPromocao = $desconto['promocao']->datafim;
                $descontoValor = $desconto['promocao']->descontoTotal;
                $descontoPorcentagem = isset($desconto['promocao']->descontoporcentagem) ?
                    $desconto['promocao']->descontoporcentagem :
                    $desconto['promocao']->descontoTotal / $ecmProduto->preco * 100;
                $precoPromocional = $desconto['valorTotal'];
                $nuMaxParcelas = $ecmProduto->parcela;
                $desconto = $descontoValor;

                $this->set(compact('dataInicioPromocao', 'dataFimPromocao', 'descontoValor', 'descontoPorcentagem',
                    'nuMaxParcelas', 'desconto'));
            }
            $preco = number_format(round($preco), 2, ',', '.');
            $precoPromocional = number_format(round($precoPromocional), 2, ',', '.');

            $this->set(compact('info_curso_presencial', 'folder', 'produtoid', 'linkConvite', 'preview', 'uf',
                'cidade', 'data', 'endereco', 'ministrante', 'cargaHoraria', 'id', 'nomeCurso', 'linkCurso', 'preco',
                'moeda', 'precoPromocional'));
        }

        $this->set(compact('ecmPublicidade', 'host', 'ecmProduto'));
        $this->set('_serialize', ['ecmPublicidade']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmPublicidade = $this->EcmPublicidade->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['arquivo'] = $this->request->data['upload']['name'];
            if($this->request->data['tipo'] != 'Convite')
                unset($this->request->data['ecm_produto_id']);
            $ecmPublicidade = $this->EcmPublicidade->patchEntity($ecmPublicidade, $this->request->data);
            if ($this->EcmPublicidade->save($ecmPublicidade)) {
                if ($this->EcmPublicidade->enviarArquivo($this->request->data['upload'], $ecmPublicidade->id)) {
                    $this->Flash->success(__('The publicidade has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('A publicidade não pode ser enviada. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('The publicidade could not be saved. Please, try again.'));
            }
        }

        $this->loadModel('Configuracao.EcmConfig');
        $diretorioPublicidade = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'diretorio_publicidade'])->first()->valor;
        if(substr($diretorioPublicidade, -1) != "/")
            $diretorioPublicidade .= "/";

        $dominioPublicidade = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_publicidade'])->first()->valor;
        if (strpos($dominioPublicidade, 'http') === false)
            $dominioPublicidade = 'http://' . $dominioPublicidade;
        if(substr($dominioPublicidade, -1) != "/")
            $dominioPublicidade .= "/";

        $this->ElFinder->setUrl([
            $diretorioPublicidade,
            $dominioPublicidade
        ]);

        $this->ElFinder->connector("/campanhas/e-convites");

        $ecmProduto = $this->EcmPublicidade->EcmProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'nome']);
        $tipo = $this->tipo;
        $this->set(compact('ecmPublicidade', 'ecmProduto', 'tipo'));
        $this->set('_serialize', ['ecmPublicidade']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Publicidade id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmPublicidade = $this->EcmPublicidade->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $antigo = $this->request->data['arquivo'];
            if(!empty($this->request->data['upload']['name']))
                $this->request->data['arquivo'] = $this->request->data['upload']['name'];
            if(array_key_exists('tipo', $this->request->data) && $this->request->data['tipo'] != 'Convite')
                unset($this->request->data['ecm_produto_id']);
            $ecmPublicidade = $this->EcmPublicidade->patchEntity($ecmPublicidade, $this->request->data);
            if ($this->EcmPublicidade->save($ecmPublicidade)) {
                if ($this->EcmPublicidade->enviarArquivo($this->request->data['upload'], $ecmPublicidade->id, $antigo)) {
                    $this->Flash->success(__('The publicidade has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('A publicidade não pode ser enviada. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('The publicidade could not be saved. Please, try again.'));
            }
        }

        $this->loadModel('Configuracao.EcmConfig');
        $diretorioPublicidade = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'diretorio_publicidade'])->first()->valor;
        if(substr($diretorioPublicidade, -1) != "\\")
            $diretorioPublicidade .= "\\";

        $dominioPublicidade = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_publicidade'])->first()->valor;
        if (strpos($dominioPublicidade, 'http') === false)
            $dominioPublicidade = 'http://' . $dominioPublicidade;
        if(substr($dominioPublicidade, -1) != "/")
            $dominioPublicidade .= "/";

        $this->ElFinder->setUrl([
            $diretorioPublicidade,
            $dominioPublicidade
        ]);

        $path = str_replace("campanhas/e-convites/","",$ecmPublicidade->get('src'));
        $path = str_replace("/","\\",$path);
        $hash_path = rtrim(strtr(base64_encode($path), '+/=', '-_.'), '.');

        $this->ElFinder->connector("/campanhas/e-convites");

        $ecmProduto = $this->EcmPublicidade->EcmProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'nome']);
        $tipo = $this->tipo;
        $this->set(compact('ecmPublicidade', 'ecmProduto', 'tipo', 'hash_path'));
        $this->set('_serialize', ['ecmPublicidade']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Publicidade id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmPublicidade = $this->EcmPublicidade->get($id);
        if ($this->EcmPublicidade->delete($ecmPublicidade)) {
            if(file_exists(WWW_ROOT . 'upload/' . $id))
                $this->excluiDir(WWW_ROOT . 'upload/' . $id);
            $this->Flash->success(__('The publicidade has been deleted.'));
        } else {
            $this->Flash->error(__('The publicidade could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Função de exclusão recursiva de diretório e arquivos
     *
     * @param $Dir
     */
    function excluiDir($Dir){
        if ($dd = opendir($Dir)) {
            while (false !== ($Arq = readdir($dd))) {
                if($Arq != "." && $Arq != ".."){
                    $Path = "$Dir/$Arq";
                    if(is_dir($Path)){
                        $this->excluiDir($Path);
                    }elseif(is_file($Path)){
                        unlink($Path);
                    }
                }
            }
            closedir($dd);
        }
        rmdir($Dir);
    }

    /**
     * Arquivos method
     *
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function arquivos($id = null)
    {
        if($this->EcmPublicidade->exists(['id' => $id])){
            if(!file_exists(WWW_ROOT . 'upload/publicidade'))
                mkdir(WWW_ROOT.'upload/publicidade');

            $publicidade = $this->EcmPublicidade->get($id);

            $this->ElFinder->addAttributes([
                'pattern' => '/'.$publicidade->get('arquivo').'/',
                'hidden' => true,
                'locked' => true
            ]);

            $this->loadModel('Configuracao.EcmConfig');
            $diretorioPublicidade = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'diretorio_publicidade'])->first()->valor;

            $dominioPublicidade = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_publicidade'])->first()->valor;
            if (strpos($dominioPublicidade, 'http') === false)
                $dominioPublicidade = 'http://' . $dominioPublicidade;
            if(substr($dominioPublicidade, -1) != "/")
                $dominioPublicidade .= "/";

            $this->ElFinder->setUrl([
                $diretorioPublicidade,
                $dominioPublicidade
            ]);

            $this->ElFinder->connector("/" . $publicidade->get('src'));
        }else{
            $this->Flash->error(__('Publicidade não encontrada!'));
            return $this->redirect(['action' => 'index']);
        }
    }
}
