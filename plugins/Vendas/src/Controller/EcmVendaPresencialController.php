<?php
namespace Vendas\Controller;

use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use Vendas\Controller\AppController;

/**
 * EcmVendaPresencial Controller
 *
 * @property \Vendas\Model\Table\EcmVendaPresencialTable $EcmVendaPresencial */
class EcmVendaPresencialController extends AppController
{

    use MailerAwareTrait;

    public function initialize(){
        $this->loadModel('Carrinho.EcmCarrinho');
        $this->loadModel('Carrinho.EcmCarrinhoItem');
        $this->loadModel('CursoPresencial.EcmCursoPresencialTurma');
        parent::initialize();
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $where = [];
        $model = isset($this->request->query['model']) ? $this->request->query['model'] : null;
        $page = isset($this->request->query['page']) ? $this->request->query['page'] : 0;

        if(count($this->request->query)){
            if(isset($this->request->query['produto']) && intval($this->request->query['produto'])){
                array_push($where, 'EcmProduto.id='.$this->request->query['produto']);
            }
            if(isset($this->request->query['status'])){
                if($this->request->query['status'] == '1'){
                    array_push($where, 'EcmCursoPresencialData.datainicio > NOW()');
                } else if($this->request->query['status'] == '2'){
                    array_push($where, 'EcmCursoPresencialData.datafim <= NOW()');
                }
            }
        }

        if(empty($where))
            $where[] = 'EcmCursoPresencialData.datainicio > NOW()';

        $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->find('all')
            ->contain(['EcmProduto'])
            ->matching('EcmCursoPresencialData', function ($q) {
                return $q->contain(['EcmCursoPresencialLocal']);
            })
            ->where($where)
            ->group('EcmCursoPresencialTurma.id')
            ->orderDesc('EcmCursoPresencialData.datainicio');

        if($model != 'EcmCursoPresencialTurma'){
            unset($this->request->query['page']);
        }

        $ecmCursoPresencialTurma = $this->paginate($ecmCursoPresencialTurma);
        foreach($ecmCursoPresencialTurma as $turma) {
            $turma->vagas_preenchidas = $this->EcmCarrinhoItem->totalVagasUtilizadasCursoPresencial($turma);
        }

        $cursos = $this->EcmCursoPresencialTurma->EcmProduto->find('list', ['limit' => 200,
            'keyField' => 'id', 'valueField' => 'sigla'])
            ->leftJoin(['EcmProdutoEcmTipoProduto' => 'ecm_produto_ecm_tipo_produto'],
                'EcmProdutoEcmTipoProduto.ecm_produto_id = EcmProduto.id')
            ->leftJoin(['EcmTipoProduto' => 'ecm_tipo_produto'],
                'EcmTipoProduto.id = EcmProdutoEcmTipoProduto.ecm_tipo_produto_id')
            ->where(['EcmTipoProduto.id' => 10])
            ->orderAsc('EcmProduto.sigla')
            ->toArray();
        $cursos[0] = "Todos os cursos presenciais";
        ksort($cursos);

        $status = array(1 => 'Não Iniciado', 0 => 'Todos', 2 => 'Iniciado');

        $this->set(compact('ecmCursoPresencialTurma', 'cursos', 'status'));
        $this->set('_serialize', ['ecmCursoPresencialTurma']);

        /********** Reservas Pendentes **********/
        if($model != 'EcmVendaPresencial')
            unset($this->request->query['page']);
        else
            $this->request->query['page'] = $page;

        $ecmVendaPresencial = $this->EcmVendaPresencial->find()->where(['status' => 'Reservado']);
        $ecmVendaPresencial = $this->paginate($ecmVendaPresencial);

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmVendaPresencial'));
        $this->set('_serialize', ['ecmVendaPresencial']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $ecmVendaPresencial = $this->EcmVendaPresencial->newEntity();
        $ecmCursoPresencialTurma = $this->EcmVendaPresencial->EcmCursoPresencialTurma->find()
            ->contain(['EcmProduto', 'EcmCursoPresencialData' => ['EcmCursoPresencialLocal' => ['MdlCidade' => ['MdlEstado']]]])
            ->where(['EcmCursoPresencialTurma.id' => $id])->first();
        $ecmCursoPresencialTurma->vagas_preenchidas = $this->EcmCarrinhoItem->totalVagasUtilizadasCursoPresencial($ecmCursoPresencialTurma);
        if ($this->request->is('post')) {
            $this->request->data['ecm_curso_presencial_turma_id'] = $id;
            $ecmVendaPresencial = $this->EcmVendaPresencial->patchEntity($ecmVendaPresencial, $this->request->data);
            $ecmVendaPresencial->data = new \DateTime();
            $ecmVendaPresencial->mdl_user_id = $this->request->session()->read('Auth.User.id');
            unset($ecmVendaPresencial->vagas_total);
            if ($this->EcmVendaPresencial->save($ecmVendaPresencial)) {
                $ecmCursoPresencialTurma->vagas_total = $this->request->data['vagas_total'];
                $ecmCursoPresencialTurma->vagas_preenchidas += $ecmVendaPresencial->quantidade_reserva;
                if ($this->EcmVendaPresencial->EcmCursoPresencialTurma->save($ecmCursoPresencialTurma)) {
                    $this->Flash->success(__('A(s) inscrição(ões) foi salva.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Erro ao salvar a quantidade de vagas. Por favor, tente novamente.'));
                }
            } else {
                $this->Flash->error(__('Erro ao salvar a(s) inscrição(ões). Por favor, tente novamente.'));
            }
        }

        $ecmCarrinho = $this->EcmCarrinho->find()->contain(['MdlUser'])
            ->contain(['EcmCarrinhoItem' => function($q) use ($ecmCursoPresencialTurma) {
                return $q->where(['EcmCarrinhoItem.ecm_curso_presencial_turma_id' => $ecmCursoPresencialTurma->id]);
            }])
            ->where(['EcmCarrinho.status' => 'Em Aberto']);

        $this->set(compact('ecmVendaPresencial', 'ecmCursoPresencialTurma', 'ecmCarrinho'));
        $this->set('_serialize', ['ecmVendaPresencial']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Venda Presencial id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmVendaPresencial = $this->EcmVendaPresencial->get($id);
        $ecmCursoPresencialTurma = $this->EcmVendaPresencial->EcmCursoPresencialTurma->find()
            ->contain(['EcmProduto', 'EcmCursoPresencialData' => ['EcmCursoPresencialLocal' => ['MdlCidade' => ['MdlEstado']]]])
            ->where(['EcmCursoPresencialTurma.id' => $ecmVendaPresencial->ecm_curso_presencial_turma_id])->first();
        $ecmCursoPresencialTurma->vagas_preenchidas = $this->EcmCarrinhoItem->totalVagasUtilizadasCursoPresencial($ecmCursoPresencialTurma);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmVendaPresencial = $this->EcmVendaPresencial->patchEntity($ecmVendaPresencial, $this->request->data);
            $ecmVendaPresencial->data = new \DateTime();
            $ecmVendaPresencial->mdl_user_id = $this->request->session()->read('Auth.User.id');
            unset($ecmVendaPresencial->vagas_total);
            if ($this->EcmVendaPresencial->save($ecmVendaPresencial)) {
                $ecmCursoPresencialTurma->vagas_total = $this->request->data['vagas_total'];
                $ecmCursoPresencialTurma->vagas_preenchidas += $ecmVendaPresencial->quantidade_reserva;
                if ($this->EcmVendaPresencial->EcmCursoPresencialTurma->save($ecmCursoPresencialTurma)) {
                    $this->Flash->success(__('A(s) inscrição(ões) foi salva.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Erro ao salvar a quantidade de vagas. Por favor, tente novamente.'));
                }
            } else {
                $this->Flash->error(__('Erro ao salvar a(s) inscrição(ões). Por favor, tente novamente.'));
            }
        }

        $ecmCarrinho = $this->EcmCarrinho->find()->contain(['MdlUser'])
            ->contain(['EcmCarrinhoItem' => function($q) use ($ecmCursoPresencialTurma) {
                return $q->where(['EcmCarrinhoItem.ecm_curso_presencial_turma_id' => $ecmCursoPresencialTurma->id]);
            }])
            ->where(['EcmCarrinho.status' => 'Em Aberto']);

        $this->set(compact('ecmVendaPresencial', 'ecmCursoPresencialTurma', 'ecmCarrinho'));
        $this->set('_serialize', ['ecmVendaPresencial']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Venda Presencial id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmVendaPresencial = $this->EcmVendaPresencial->get($id);
        if ($this->EcmVendaPresencial->delete($ecmVendaPresencial)) {
            $this->Flash->success(__('The ecm venda presencial has been deleted.'));
        } else {
            $this->Flash->error(__('The ecm venda presencial could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Lista Vendas method
     *
     * @param string|null $id Ecm Venda Presencial id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function listaVendas($id = null)
    {
        $ecmVendaPresencial = $this->EcmVendaPresencial->find()
            ->where(['EcmVendaPresencial.ecm_curso_presencial_turma_id' => $id]);
        $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->get($id, [
            'contain' => ['EcmProduto', 'EcmCursoPresencialData' => ['EcmCursoPresencialLocal' => ['MdlCidade' => ['MdlEstado']]]]
        ]);
        $ecmCarrinho = $this->EcmCarrinho->find()->contain(['MdlUser'])
            ->contain(['EcmCarrinhoItem' => function($q) use ($id) {
                return $q->where([
                    'EcmCarrinhoItem.ecm_curso_presencial_turma_id' => $id,
                    'EcmCarrinhoItem.status' => EcmCarrinhoItem::STATUS_ADICIONADO
                ]);
            }])
            ->matching('EcmCarrinhoItem', function($q) use ($id) {
                return $q->where([
                    'EcmCarrinhoItem.ecm_curso_presencial_turma_id' => $id,
                    'EcmCarrinhoItem.status' => EcmCarrinhoItem::STATUS_ADICIONADO
                ]);
            })
            ->contain(['EcmVenda' => [
                'foreignKey' => false,
                'queryBuilder' => function ($q) {
                    return $q->where(['EcmCarrinho.id = EcmVenda.ecm_carrinho_id'])
                        ->contain(['EcmVendaBoleto']);
                }
            ]]);

        $this->set(compact('ecmVendaPresencial', 'ecmCursoPresencialTurma', 'ecmCarrinho'));
        $this->set('_serialize', ['ecmVendaPresencial']);
    }

    /**
     * Lista Email method
     *
     * @param string|null $id Ecm Curso Presencial Email Confirmacao id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function listaEmail($id = null)
    {
        $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->get($id, [
            'contain' => ['EcmProduto', 'EcmCursoPresencialData' => ['EcmCursoPresencialLocal' => ['MdlCidade' => ['MdlEstado']]]]
        ]);

        if ($this->request->is('post')) {
            $this->loadModel('Vendas.EcmCursoPresencialEmailConfirmacao');
            $envioCorreto = true;
            if(!isset($this->request->data['venda']) && !isset($this->request->data['vendaPresencial'])){
                $this->Flash->error(__('Selecione um participante para enviar o e-mail.'));
            } else {
                $this->loadModel('Configuracao.EcmConfig');
                $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central'])->first()->valor;
                $local = $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local;
                $cidadeUF = $local->mdl_cidade->nome.'/'.$local->mdl_cidade->mdl_estado->uf;
                $datas = "";
                
                setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

                foreach ($ecmCursoPresencialTurma->ecm_curso_presencial_data as $data) {
                    $dataInicio = $data->datainicio;

                    $datas .= __($dataInicio->format('l')).', ';
                    $datas .= $dataInicio->format(' j ');
                    
                    //$datas .= __($dataInicio->format('F'));
                    $datas .= ucfirst(strftime('%B', $dataInicio->format('U')));

                    $datas .= $dataInicio->format(' Y \d\a\s H:i') . ' às ';
                    $datas .= $data->saidaintervalo->format('H:i') . ' e das ';
                    $datas .= $data->voltaintervalo->format('H:i') . ' às ';
                    $datas .= $data->datafim->format('H:i') . ' ';
                }

                $parametrosEmail = ['nomeCurso' => $ecmCursoPresencialTurma->ecm_produto->nome, 'cidadeUF' => $cidadeUF,
                    'data' => $datas, 'nomeLocal' => $local->nome, 'endereco' => $local->endereco];
                $listaParticipantes = "";
                $countParticipantes = 1;
                if (isset($this->request->data['venda'])) {
                    foreach ($this->request->data['venda'] as $venda) {
                        $toEmail = $this->request->data['email'.$venda];

                        $ecmCarrinho = $this->EcmCarrinho->find('all', ['fields' =>
                            ['MdlUser.idnumber', 'MdlUser.firstname', 'MdlUser.lastname']])
                            ->contain(['MdlUser'])
                            ->contain(['EcmVenda' => [
                                'foreignKey' => false,
                                'queryBuilder' => function ($q) use ($venda) {
                                    return $q->where(['EcmCarrinho.id = EcmVenda.ecm_carrinho_id']);
                                }
                            ]])
                            ->where(['EcmVenda.id' => $venda])->first();

                        $parametrosEmail['nomeUsuario'] = $ecmCarrinho->MdlUser->firstname .' '. $ecmCarrinho->MdlUser->lastname;
                        $params = [$fromEmail, $toEmail, $parametrosEmail];

                        if($this->getMailer('Vendas.EcmVendaPresencial')->send('confirmacaoEdicaoInscricao', $params)){

                            $listaParticipantes .= $countParticipantes++ . " - " . $ecmCarrinho->MdlUser->idnumber . " - " .
                                $ecmCarrinho->MdlUser->firstname . " - " . $ecmCarrinho->MdlUser->lastname . '<br/>';

                            $ecmCursoPresencialEmailConfirmacao = $this->EcmCursoPresencialEmailConfirmacao->newEntity();
                            $ecmCursoPresencialEmailConfirmacao->ecm_venda_id = $venda;
                            $ecmCursoPresencialEmailConfirmacao->enviado = 1;
                            $ecmCursoPresencialEmailConfirmacao->data_envio = new \DateTime();
                            if (!$this->EcmCursoPresencialEmailConfirmacao->save($ecmCursoPresencialEmailConfirmacao)) {
                                $envioCorreto = false;
                                $this->Flash->error(__('Erro ao registrar o envio do email.'));
                            }
                        }else{
                            $this->Flash->error(__('Erro ao enviar o email. Por favor, tente novamente.'));
                        }
                    }
                }
                if (isset($this->request->data['vendaPresencial'])) {
                    foreach ($this->request->data['vendaPresencial'] as $vendaPresencial) {
                        $toEmail = $this->request->data['email'.$vendaPresencial];
                        $ecmVendaPresencial = $this->EcmVendaPresencial->get($vendaPresencial);

                        $parametrosEmail['nomeUsuario'] = $ecmVendaPresencial->nome;

                        $params = [$fromEmail, $toEmail, $parametrosEmail];
                        if($this->getMailer('Vendas.EcmVendaPresencial')->send('confirmacaoEdicaoInscricao', $params)){
                            $listaParticipantes .= $countParticipantes++ . " - " . $ecmVendaPresencial->nome . '<br/>';

                            $ecmCursoPresencialEmailConfirmacao = $this->EcmCursoPresencialEmailConfirmacao->newEntity();
                            $ecmCursoPresencialEmailConfirmacao->ecm_venda_presencial_id = $vendaPresencial;
                            $ecmCursoPresencialEmailConfirmacao->enviado = 1;
                            $ecmCursoPresencialEmailConfirmacao->data_envio = new \DateTime();
                            if (!$this->EcmCursoPresencialEmailConfirmacao->save($ecmCursoPresencialEmailConfirmacao)) {
                                $envioCorreto = false;
                                $this->Flash->error(__('Erro ao registrar o envio do email.'));
                            }
                        }else{
                            $this->Flash->error(__('Erro ao enviar o email. Por favor, tente novamente.'));
                        }
                    }
                }
                if ($envioCorreto && (isset($this->request->data['venda']) || isset($this->request->data['vendaPresencial']))) {
                    $this->Flash->success(__('Os emails foram enviados com sucesso.'));
                }

                $this->loadModel('EcmGrupoPermissao');
                $cc = $fromEmail;
                $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
                $parametrosEmail['listaParticipantes'] = $listaParticipantes;
                $params = [$fromEmail, $adminEmail, $parametrosEmail, $cc];
                $this->getMailer('Vendas.EcmVendaPresencial')->send('confirmacaoEdicaoInscricaoAdmin', $params);
            }
        }

        $ecmCarrinho = $this->EcmCarrinho->find()->contain(['MdlUser'])
            ->contain(['EcmCarrinhoItem' => function($q) use ($id) {
                return $q->where(['EcmCarrinhoItem.ecm_curso_presencial_turma_id' => $id]);
            }])
            ->matching('EcmCarrinhoItem', function($q) use ($id) {
                return $q->where(['EcmCarrinhoItem.ecm_curso_presencial_turma_id' => $id]);
            })
            ->contain(['EcmVenda' => [
                'foreignKey' => false,
                'queryBuilder' => function ($q) {
                    return $q->where(['EcmCarrinho.id = EcmVenda.ecm_carrinho_id'])
                        ->contain(['EcmCursoPresencialEmailConfirmacao']);
                }
            ]]);

        $ecmVendaPresencial = $this->EcmVendaPresencial->find()
            ->contain(['EcmCursoPresencialEmailConfirmacao'])
            ->where(['EcmVendaPresencial.ecm_curso_presencial_turma_id' => $id, 'EcmVendaPresencial.status' => 'Vendido']);

        $this->set(compact('ecmCursoPresencialTurma', 'ecmCarrinho', 'ecmVendaPresencial'));
        $this->set('_serialize', ['ecmCursoPresencialTurma', 'ecmCarrinho', 'ecmVendaPresencial']);
    }
}
