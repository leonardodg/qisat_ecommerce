<?php

namespace Publicidade\Controller;

use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Carrinho\Model\Entity\EcmCarrinho;

class WscPublicidadeController extends WscController
{
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Publicidade.EcmPublicidade');
    }

    public function catalogo($id = null)
    {
        $retorno = ['sucesso' => false, 'mensagem' => 'Catálogo não encontrado ou não habilitado'];
        $ecmPublicidade = $this->EcmPublicidade->get($id);
        if($ecmPublicidade->habilitado && $ecmPublicidade->tipo == 'Catalogo'){
            $this->loadModel('Configuracao.EcmConfig');
            $host = 'https://'.$this->EcmConfig->find()->where(['nome' => 'dominio_acesso_site'])->first()->valor.'/';

            $convenio_desconto['professor'] = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'convenio_desconto_professor'])->first()->valor;
            $convenio_desconto['aluno'] = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'convenio_desconto_aluno'])->first()->valor;
            $convenio_desconto['associado'] = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'convenio_desconto_associado'])->first()->valor;

            $produtos = $this->EcmPublicidade->EcmProduto->find('list', ['keyField' => 'id', 'valueField' => function ($e) {
                return $e; }])->contain(['EcmProdutoInfo' => ['fields' => ['url' => 'url']]])->toArray();

            $this->loadModel('Entidade.EcmAlternativeHost');
            $ecmAlternativeHosts = $this->EcmAlternativeHost->find('list', ['keyField' => 'shortname',
                'valueField' => 'id'])
                ->where(['codigoorigemaltoqi IS NOT NULL'])
                ->toArray();

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

            /**
             * Laços de repetição para tratamento dos valores de todos os cursos, para todas as entidades
             */
            foreach($produtos as $produto){
                foreach($ecmAlternativeHosts as $sigla => $idEntidade){
                    $produto->$sigla = new \stdClass();
                    $promocoes = [];
                    foreach($ecmPromocoes as $promocao){
                        if(array_key_exists($produto->id, $promocao->ecm_produto) &&
                                array_key_exists($idEntidade, $promocao->ecm_alternative_host))
                            $promocoes[] = $promocao;
                    }
                    $descontos = \Carrinho\Model\Entity\EcmCarrinho::verificarDesconto($produto, $promocoes);
                    if(isset($descontos)){
                        if(isset($descontos['valorTotal'])){
                            $produto->$sigla->valordesconto = number_format(round($descontos['valorTotal']), 2, ',', '.');
                        }
                        if($descontos['promocao']['descontoporcentagem'] == 0){
                            $produto->$sigla->porcentagem = round($descontos['valorTotal'] / $produto->preco * 100);
                        } else {
                            $produto->$sigla->porcentagem = $descontos['promocao']['descontoporcentagem'];
                        }
                    }
                    $produto->$sigla->valortabela = number_format(round($produto->preco), 2, ',', '.');
                }
            }

            $retorno = [
                'sucesso' => true,
                'publicidade' => $ecmPublicidade,
                'nome_da_instituicao' => $nome_da_instituicao,
                'host' => $host,
                'convenio_desconto' => $convenio_desconto,
                'hosts' => $ecmAlternativeHosts,
                'promocoes' => $ecmPromocoes,
                'produtos' => $produtos
            ];
        }
        $this->set(compact('retorno'));
    }

    public function convite($id = null, $idTurma = null)
    {
        $retorno = ['sucesso' => false, 'mensagem' => 'Convite não encontrado ou não habilitado'];
        $ecmPublicidade = $this->EcmPublicidade->get($id);
        if($ecmPublicidade->habilitado && $ecmPublicidade->tipo == 'Convite') {
            $this->loadModel('Configuracao.EcmConfig');
            $host = 'https://'.$this->EcmConfig->find()->where(['nome' => 'dominio_acesso_site'])->first()->valor.'/';

            $hostEcommerce = 'https://'.$this->EcmConfig->find()->where(['nome' => 'dominio_acesso_webservice'])->first()->valor.'/';

            $linkConvite = Router::url($ecmPublicidade->src . '/' . $ecmPublicidade->arquivo . '?turma=' . $idTurma, true);

            $produtoid = $ecmPublicidade->ecm_produto_id;
            $this->loadModel('Produto.EcmProduto');
            $ecmProduto = $this->EcmProduto->get($produtoid, ['contain' => [
                'EcmProdutoInfo' => ['fields' => ['url']],
                'MdlCourse' => function ($q) {
                    return $q->contain(['EcmProduto' => function ($q) {
                        return $q->matching('EcmTipoProduto', function ($q) {
                            return $q->where(['EcmTipoProduto.id' => 33])->orWhere(['EcmTipoProduto.id' => 41]);
                        })->select(['id', 'preco', 'moeda', 'tipo' => 'EcmTipoProduto.id']);
                    }])->select(['id']);
                }
            ]]);
            $nomeCurso = $ecmProduto->nome;
            switch ($ecmProduto->moeda) {
                case 'dolar':
                    $moeda = 'US$ ';
                    break;
                case 'euro':
                    $moeda = '€ ';
                    break;
                default:
                    $moeda = 'R$ ';
            }
            $linkCurso = $host . $ecmProduto['ecm_produto_info']['url'];

            if ($idTurma != "/:action") {
                $this->loadModel('CursoPresencial.EcmCursoPresencialTurma');
                $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->get($idTurma, ['contain' =>
                    ['EcmCursoPresencialData' => ['EcmCursoPresencialLocal' => ['MdlCidade' => ['MdlEstado']]],
                        'EcmInstrutor' => ['MdlUser', 'EcmImagem'], 'EcmProduto']]);

                $uf = $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->mdl_estado->uf;
                $cidade = $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->nome;

                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                date_default_timezone_set('America/Sao_Paulo');

                $data = [];
                foreach ($ecmCursoPresencialTurma->ecm_curso_presencial_data as $curso_presencial_data) {
                    $presencial                 = new \stdClass();
                    $presencial->dia            = $curso_presencial_data->datainicio->format('d');
                    $presencial->mes            = $curso_presencial_data->datainicio->format('m');
                    $presencial->ano            = $curso_presencial_data->datainicio->format('Y');
                    $presencial->datainicio     = $curso_presencial_data->datainicio->format('H:i');
                    $presencial->saidaintervalo = $curso_presencial_data->saidaintervalo->format('H:i');
                    $presencial->voltaintervalo = $curso_presencial_data->voltaintervalo->format('H:i');
                    $presencial->datafim        = $curso_presencial_data->datafim->format('H:i');
                    $data[]                     = $presencial;
                }

                $endereco = $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->endereco;
                $instrutor = new \stdClass();
                $instrutor->nome = $ecmCursoPresencialTurma->ecm_instrutor[0]->mdl_user->firstname .
                    " " . $ecmCursoPresencialTurma->ecm_instrutor[0]->mdl_user->lastname;
                $instrutor->formacao = $ecmCursoPresencialTurma->ecm_instrutor[0]->formacao;
                $instrutor->descricao = $ecmCursoPresencialTurma->ecm_instrutor[0]->descricao;

                $instrutor->foto = "#";
                if(isset($ecmCursoPresencialTurma->ecm_instrutor[0]->ecm_imagem))
                    $instrutor->foto = $hostEcommerce . 'upload/' . $ecmCursoPresencialTurma->ecm_instrutor[0]->ecm_imagem->src;

                if ($ecmCursoPresencialTurma->valor_produto == "false")
                    $ecmProduto->preco = $ecmCursoPresencialTurma->valor;

                $cargaHoraria = $ecmCursoPresencialTurma->carga_horaria;
            } else {
                $uf = 'UF';
                $cidade = 'Cidade';
                $data = ' Datas e horarios do curso';
                $endereco = 'Endereço contendo rua, bairro e referência';
                $instrutor = 'Nome e sobrenome do instrutor';
                $cargaHoraria = 'Carga horária total';
            }

            $this->loadModel('Promocao.EcmPromocao');

            if (is_null($ecmProduto->preco)) {
                $produtosSerie_precos = [];
                $produtosSerie_precosPromocionais = [];
                $produtosSerie_moedas = [];
                $produtosSerie_datasFim = [];
                foreach ($ecmProduto['mdl_course'] as $course) {
                    foreach ($course['ecm_produto'] as $produto) {
                        if ($produto['tipo'] == 33) {
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
                            switch ($ecmProduto->moeda) {
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
                            if (is_array($desconto)) {
                                $produtosSerie_datasFim[$id] = $desconto['promocao']->datafim;
                                $produtosSerie_precosPromocionais[$id] = number_format(round($desconto['valorTotal']), 2, ',', '.');
                            }
                        } else if ($produto['tipo'] == 41) {
                            $ecmProduto->preco = $produto['preco'];
                        }
                    }
                }
                $this->set(compact('produtosSerie_precos', 'produtosSerie_precosPromocionais',
                    'produtosSerie_moedas', 'produtosSerie_datasFim'));
            }
            $preco = $ecmProduto->preco;

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
            if (is_array($desconto)) {
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

            $retorno = [
                'sucesso' => true,
                'publicidade' => $ecmPublicidade,
                'host' => $host,
                'linkConvite' => $linkConvite,
                'uf' => $uf,
                'cidade' => $cidade,
                'data' => $data,
                'endereco' => $endereco,
                'instrutor' => $instrutor,
                'cargaHoraria' => $cargaHoraria,
                'nomeCurso' => $nomeCurso,
                'linkCurso' => $linkCurso,
                'preco' => $preco,
                'moeda' => $moeda,
                'precoPromocional' => $precoPromocional,
                'produto' => $ecmProduto
            ];
        }
        $this->set(compact('retorno'));
    }
}