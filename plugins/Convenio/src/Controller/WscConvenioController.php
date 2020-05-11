<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 19/09/2016
 * Time: 08:11
 */

namespace Convenio\Controller;


use App\Auth\AESPasswordHasher;
use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\Mailer\MailerAwareTrait;
use Cake\Routing\Router;
use Repasse\Model\Entity\EcmRepasse;

class WscConvenioController extends WscController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

    }

    /*
    * Função responsável por listar todas as instituições conveniadas ativas
    * Deve ser feito requisições do tipo POST, com parâmetros opcionais:
    * http://{host}/convenio/wsc-convenio/instituicoes-conveniadas
    *
    * Retornos:
    * 1- {'sucesso': true, 'ecmConvenio'}
    * 2- {'sucesso': false, 'mensagem': Requisição do tipo POST necessaria}
    *
    * */
    public function instituicoesConveniadas(){
        //$retorno = ['sucesso' => false, 'mensagem' => 'Requisição do tipo POST necessaria'];
        //if ($this->request->is('post')) {
            $this->loadModel('Convenio.EcmConvenio');

            $dataAtual = new \DateTime();

            $ecmConvenio = $this->EcmConvenio->find('all')
                ->where([
                    'ecm_convenio_contrato_id IS NOT NULL',
                    'EcmConvenioContrato.contrato_ativo' => 'true',
                    'EcmConvenioContrato.contrato_assinado' => 'true',
                    'EcmConvenioContrato.data_inicio_convenio <= ' => $dataAtual->format('Y-m-d'),
                    'EcmConvenioContrato.data_fim_convenio >= ' => $dataAtual->format('Y-m-d')
                ])
                ->select(['id', 'nome_instituicao', 'logo',
                    'data_fim_convenio' => 'EcmConvenioContrato.data_fim_convenio',
                    'typeid' => 'EcmConvenioTipoInstituicao.id',
                    'id_estado' => 'MdlEstado.nome',
                    'nome_estado' => 'MdlEstado.nome',
                    'uf_estado' => 'MdlEstado.uf'
                ])
                ->contain([
                    'EcmConvenioContrato',
                    'EcmConvenioTipoInstituicao',
                    'MdlCidade'=>['MdlEstado']
                ]);

            if (isset($this->request->data['cidade'])) {
                $ecmConvenio->contain(['MdlCidade' => function($q){
                    return $q->where(['nome' => $this->request->data['cidade']]);
                }]);
            } else if (isset($this->request->data['estado'])) {
                $ecmConvenio->contain(['MdlCidade.MdlEstado' => function($q){
                    return $q->where(['MdlEstado.nome' => $this->request->data['estado']]);
                }]);
            }

            foreach ($ecmConvenio as $convenio){

                $logo = Router::url('/img/default-convenio.gif', true);

                if(!is_null($convenio->logo)) {
                    $file = new File(WWW_ROOT.'/upload/convenio/'.$convenio->logo);
                    if($file->exists()) {
                        $logo = Router::url('/upload/convenio/', true) . $convenio->logo;
                    }
                }

                $convenio->logo = $logo;

                $convenio->timeend = $convenio->data_fim_convenio->format('U');
                unset($convenio->data_fim_convenio);
            }

            $retorno = ['sucesso' => true, 'ecmConvenio' => $ecmConvenio];
        //}
        $this->set(compact('retorno'));
    }

    /*
    * Função responsável por listar todos os cursos e todos os descontos dos convenios
    * Deve ser feito requisições do tipo GET, sem parâmetros:
    * http://{host}/convenio/wsc-convenio/desconto-convenio
    *
    * Retornos:
    * 1- {'sucesso': true, 'ecmProduto': lista de produtos dividida por tipo com valores e descontos}
    *
    * */
    public function descontoConvenio(){
        $this->loadModel('Configuracao.EcmConfig');
        $desconto = $this->EcmConfig->find('list', ['keyField' => 'nome', 'valueField' => 'valor'])
            ->where(['nome' => 'convenio_desconto_professor'])
            ->orWhere(['nome' => 'convenio_desconto_aluno'])
            ->orWhere(['nome' => 'convenio_desconto_associado'])->toArray();

        $this->loadModel('Produto.EcmProduto');
        $ecmProduto = $this->EcmProduto->find('all')->where(['EcmProduto.habilitado' => 'true',
                'EcmProduto.preco IS NOT NULL'])
            ->innerJoinWith('EcmTipoProduto', function ($q) {
                return $q->where(['EcmTipoProduto.nome' => 'Cursos Software'])
                    ->orWhere(['EcmTipoProduto.nome' => 'Cursos Teóricos']);
            })
            ->select(['EcmProduto.id', 'EcmProduto.nome', 'EcmProduto.preco', 'tipo_produto' => 'EcmTipoProduto.nome'])->toArray();

        $dataAtual = new \DateTime();
        $this->loadModel('Promocao.EcmPromocao');
        $ecmPromocao = $this->EcmPromocao->find('all')->contain(['EcmAlternativeHost' => function ($q) {
            return $q->where(['shortname' => 'CREA%']);
        }])->where(['habilitado' => 'true', 'datainicio <=' => $dataAtual->format('Y-m-d'),
            'datafim >=' => $dataAtual->format('Y-m-d')])->orderDesc('descontoporcentagem')->first();

        $retorno = ['sucesso' => true];
        foreach($ecmProduto as $produto){
            $produto->valorProfessor = $produto->preco - $produto->preco * ($desconto['convenio_desconto_professor'] / 100);
            $produto->valorAluno = $produto->preco - $produto->preco * ($desconto['convenio_desconto_aluno'] / 100);
            $produto->valorAssociado = $produto->preco - $produto->preco * ($desconto['convenio_desconto_associado'] / 100);

            if(!is_null($ecmPromocao)) {
                $produto->valorCREAs = $produto->preco - $produto->preco * ($ecmPromocao->descontoporcentagem / 100);
            }else{
                $produto->valorCREAs = $produto->preco;
            }

            $retorno[$produto->tipo_produto == 'Cursos Software' ? 'software' : 'teoricos'][$produto->id] = $produto;
        }

        $this->set(compact('retorno'));
    }

    /*
    * Função reponsável por adicionar um interesse de um cliente em um convenio
    * Deve ser feito requisições do tipo POST:
    * http://{host}/convenio/wsc-convenio/add-interesse
    *
    * Retornos:
    * 1- {'sucesso':true, 'mensagem': 'Interesse registrado com sucesso'}
    * 2- {'sucesso':false, 'mensagem': 'O Interesse não pode ser registrado'}
    * 3- {'sucesso':false, 'mensagem': 'Informe os parâmetros do Interesse'}
    * 4- {'sucesso':false, 'mensagem': 'Informe o parâmetro Nome'}
    * 5- {'sucesso':false, 'mensagem': 'Informe o parâmetro Telefone'}
    * 6- {'sucesso':false, 'mensagem': 'Informe o parâmetro Email'}
    * 7- {'sucesso':false, 'mensagem': 'Informe o id do convênio'}
    *
    * */
    public function addInteresse(){
        $retorno = ['sucesso' => false, 'mensagem' => 'O Interesse não pode ser registrado'];

        if(empty($this->request->data)) {
            $retorno = ['sucesso' => false, 'mensagem' => 'Informe os parâmetros do Interesse'];
        } else if (!isset($this->request->data['nome'])) {
            $retorno['mensagem'] = 'Informe o parâmetro Nome';
        } else if (!isset($this->request->data['telefone'])) {
            $retorno['mensagem'] = 'Informe o parâmetro Telefone';
        } else if (!isset($this->request->data['email'])) {
            $retorno['mensagem'] = 'Informe o parâmetro Email';
        } else if (!isset($this->request->data['id']) && !isset($this->request->data['ecm_convenio_id'])) {
            $retorno['mensagem'] = 'Informe o id do convênio';
        } else {
            if (isset($this->request->data['id'])) {
                $this->request->data['ecm_convenio_id'] = $this->request->data['id'];
                unset($this->request->data['id']);
            }
            if (!isset($this->request->data['data_registro']))
                $this->request->data['data_registro'] = new \DateTime();

            $this->loadModel('Convenio.EcmConvenioInteresse');
            $ecmConvenioInteresse = $this->EcmConvenioInteresse->newEntity();
            if ($this->request->is('post')) {
                $ecmConvenioInteresse = $this->EcmConvenioInteresse->patchEntity($ecmConvenioInteresse, $this->request->data);
                if ($ecmConvenioInteresse = $this->EcmConvenioInteresse->save($ecmConvenioInteresse)) {
                    $retorno = ['sucesso' => true, 'mensagem' => 'Interesse registrado com sucesso'];
                    $this->loadModel('Convenio.EcmConvenio');

                    $convenio = $this->EcmConvenio->get($ecmConvenioInteresse->ecm_convenio_id);
                    $ecmConvenioInteresse->set('ecm_convenio', $convenio);

                    if($mail = $this->getMailer('Convenio.WscConvenio')->send('solicitacaoDescontoConvenio', [$ecmConvenioInteresse])){
                        $this->loadModel('Repasse.EcmRepasse');
                        $repasse = $this->EcmRepasse->newEntity();

                        $assunto_email = __('QiSat | Registro de Interesse no Convênio');
                        $corpo_email = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                        
                        $repasse->set('corpo_email', $corpo_email);
                        $repasse->set('assunto_email', $assunto_email);
                        $repasse->set('status', EcmRepasse::STATUS_NAO_ATENDIDO);
                        $repasse->set('ecm_alternative_host_id', 1);

                        $repasse->set('data_registro', new \DateTime());
                        
                        if($ecmRepasseOrigem = $this->EcmRepasse->EcmRepasseOrigem->find()->where(['LOWER(origem)' => 'Site QiSat'])->first())
                            $repasse->set('ecm_repasse_origem_id', $ecmRepasseOrigem->id);

                        if($ecmRepasseCategoria = $this->EcmRepasse->EcmRepasseCategorias->find()->where(['LOWER(categoria)' => 'Interesse no Convenio'])->first())
                            $repasse->set('ecm_repasse_categorias_id', $ecmRepasseCategoria->id);

                        $this->EcmRepasse->save($repasse);
                    }
                }
            }
        }

        $this->set(compact('retorno'));
    }

    /*
     * Função reponsável por inserir convênios
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
     * {
     *  ecm_convenio_tipo_instituicao_id: 1,
     *  mdl_cidade_id: 11139,
     *  nome_responsavel: (nome_responsavel),
     *  nome_coordenador: (nome_coordenador),
     *  nome_instituicao: (nome_instituicao),
     *  curso: (curso),
     *  disciplina: (disciplina),
     *  cargo: (cargo),
     *  email: (email),
     *  telefone: (telefone)
     * }
     *
     * Retornos:
     * 1- {'sucesso':true}
     * 2- {'sucesso':false, 'mensagem': 'Este Web Service não aceita esse tipo de requisição'}
     * 5- {'sucesso':false, 'mensagem': 'Erro ao salvar repasse'}
     * 6- {'sucesso':false, 'mensagem': 'Não foi possível inserir o convênio'}
     *
     * */
    public function inserir(){
        $retorno = ['sucesso' => false, 'mensagem' => __('Este Web Service não aceita esse tipo de requisição')];
        if ($this->request->is('post')) {
            $this->loadModel('Convenio.EcmConvenio');

            $this->request->data["ecm_convenio_tipo_instituicao_id"] = $this->EcmConvenio->
                EcmConvenioTipoInstituicao->find()->where(['id' =>
                    $this->request->data["ecm_convenio_tipo_instituicao_id"]])->first()->id;

            $convenio = $this->EcmConvenio->newEntity();
            $convenio = $this->EcmConvenio->patchEntity($convenio, $this->request->data);
            $erros = $convenio->errors();

            if(empty($erros)){
                if ($convenio = $this->EcmConvenio->save($convenio)) {

                    $retorno = ['sucesso' => true];

                    if($convenio->get('ecm_convenio_tipo_instituicao_id') != 3) {
                        $aesHash = new AESPasswordHasher();
                        $hash = base64_encode($aesHash->hash((string)$convenio->get('id')));

                        $retorno['link'] = Router::url('contrato-convenio/' . $hash, true);
                    }

                    if($mail = $this->getMailer('Convenio.WscConvenio')->send('solicitacaoConvenio', [$convenio])){
                        $this->loadModel('Repasse.EcmRepasse');
                        $repasse = $this->EcmRepasse->newEntity();

                        $assunto_email = __('QiSat | Preenchimento de Termo de Adesão');
                        $corpo_email = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                        
                        $repasse->set('corpo_email', $corpo_email);
                        $repasse->set('assunto_email', $assunto_email);
                        $repasse->set('status', EcmRepasse::STATUS_NAO_ATENDIDO);
                        $repasse->set('ecm_alternative_host_id', 1);

                        $repasse->set('data_registro', new \DateTime());
                        
                        if($ecmRepasseOrigem = $this->EcmRepasse->EcmRepasseOrigem->find()->where(['LOWER(origem)' => 'Site QiSat'])->first())
                            $repasse->set('ecm_repasse_origem_id', $ecmRepasseOrigem->id);

                        if($ecmRepasseCategoria = $this->EcmRepasse->EcmRepasseCategorias->find()->where(['LOWER(categoria)' => 'Solicitacao de Convenio'])->first())
                            $repasse->set('ecm_repasse_categorias_id', $ecmRepasseCategoria->id);

                        $this->EcmRepasse->save($repasse);
                    }


                }else{
                    $retorno = ['sucesso' => false, 'mensagem' => __('Não foi possível inserir o convênio')];
                }
            }else{
                $retorno = $this->validarDados($erros);
            }
        }

        $this->set(compact('retorno'));
    }

    private function validarDados($errors){
        $chave = key($errors);
        $validacao = key(current($errors));

        if($validacao == '_required'){
            return ['sucesso' => false, 'mensagem' => __('Parâmetro '.$chave.' não informado')];
        }else{
            return ['sucesso' => false, 'mensagem' => __('Parâmetro '.$chave.' incorreto')];
        }
    }
}