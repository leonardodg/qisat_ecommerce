<?php
namespace CursoPresencial\Controller;

use Cake\Validation\Validator;
use CursoPresencial\Controller\AppController;

/**
 * EcmCursoPresencialInteresse Controller
 *
 * @property \CursoPresencial\Model\Table\EcmCursoPresencialInteresseTable $EcmCursoPresencialInteresse */
class EcmCursoPresencialInteresseController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->helpers = array('JqueryUI','JqueryMask');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $condition = [];

        $validator = $this->validarDados();
        $errors = $validator->errors($this->request->query);

        if (empty($errors)) {

            $nome = $this->request->query('nome_pesquisa');
            $email = $this->request->query('email_pesquisa');
            $produto = $this->request->query('ecm_produto_id');
            $dataInicio = $this->request->query('data_inicio');
            $dataFim = $this->request->query('data_fim');

            $this->request->data = $this->request->query;

            if (strlen(trim($nome)) > 0) {
                $condition[] = [
                    'OR'=>[
                            "EcmCursoPresencialInteresse.nome LIKE" => '%'.$nome.'%',
                            "concat(MdlUser.firstname, ' ', MdlUser.lastname) LIKE" => '%'.$nome.'%'
                        ]

                ];
            }

            if (strlen(trim($email)) > 0) {
                $condition[] = [
                    'OR'=>[
                            'EcmCursoPresencialInteresse.email LIKE' => '%'.$email.'%',
                            'MdlUser.email LIKE' => '%'.$email.'%'
                        ]
                ];
            }

            if (strlen(trim($dataInicio)) == 10) {
                $dataInicio = \DateTime::createFromFormat('d/m/Y', $dataInicio);
                $dataInicio->setTime(0, 0, 0);

                $condition['data >='] = $dataInicio->format('Y-m-d H:i:s');
            }

            if (strlen(trim($dataFim)) == 10) {
                $dataFim = \DateTime::createFromFormat('d/m/Y', $dataFim);
                $dataFim->setTime(23, 59, 59);

                $condition['data <='] = $dataFim->format('Y-m-d H:i:s');
            }

            if(strlen(trim($produto)) > 0){
                $condition[] = [
                    'OR'=>[
                        'EcmCursoPresencialInteresse.ecm_produto_id' => $produto,
                        'EcmCursoPresencialTurma.ecm_produto_id' => $produto
                    ]
                ];
            }
        }

        $this->paginate = [
            'contain' => [
                'EcmCursoPresencialTurma'=>[
                    'EcmProduto',
                    'EcmCursoPresencialData'=>['EcmCursoPresencialLocal' => ['MdlCidade'=>['MdlEstado']]]
                ],
                'EcmProduto', 'MdlUser'
            ],
            'conditions' => $condition
        ];
        $ecmCursoPresencialInteresse = $this->paginate($this->EcmCursoPresencialInteresse);


        $interesse = $this->EcmCursoPresencialInteresse->newEntity();

        $ecmProduto = $this->EcmCursoPresencialInteresse->EcmProduto
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
            ->matching('EcmTipoProduto')
            ->where(['EcmTipoProduto.nome' => 'Presencial']);

        $this->set(compact('ecmCursoPresencialInteresse', 'interesse', 'ecmProduto'));
        $this->set('_serialize', ['ecmCursoPresencialInteresse']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Curso Presencial Interesse id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmCursoPresencialInteresse = $this->EcmCursoPresencialInteresse->get($id, [
            'contain' => [
                'EcmCursoPresencialTurma'=>[
                    'EcmProduto',
                    'EcmCursoPresencialData'=>['EcmCursoPresencialLocal' => ['MdlCidade'=>['MdlEstado']]]
                ],
                'EcmProduto', 'MdlUser'
            ]
        ]);

        $this->set('ecmCursoPresencialInteresse', $ecmCursoPresencialInteresse);
        $this->set('_serialize', ['ecmCursoPresencialInteresse']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmCursoPresencialInteresse = $this->EcmCursoPresencialInteresse->newEntity();
        if ($this->request->is('post')) {
            $ecmCursoPresencialInteresse = $this->EcmCursoPresencialInteresse->patchEntity($ecmCursoPresencialInteresse, $this->request->data);
            if ($this->EcmCursoPresencialInteresse->save($ecmCursoPresencialInteresse)) {
                $this->Flash->success(__('Interesse salvo com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar o interesse. Por favor tente novamente!'));
            }
        }

        $ecmProduto = $this->EcmCursoPresencialInteresse->EcmProduto
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
            ->matching('EcmTipoProduto')
            ->where(['EcmTipoProduto.nome' => 'Presencial']);

        $this->set(compact('ecmCursoPresencialInteresse', 'ecmProduto'));
        $this->set('_serialize', ['ecmCursoPresencialInteresse']);
        $this->set('titulo',__('Novo Interesse em Curso Presencial'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Curso Presencial Interesse id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmCursoPresencialInteresse = $this->EcmCursoPresencialInteresse->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmCursoPresencialInteresse = $this->EcmCursoPresencialInteresse->patchEntity($ecmCursoPresencialInteresse, $this->request->data);
            if ($this->EcmCursoPresencialInteresse->save($ecmCursoPresencialInteresse)) {
                $this->Flash->success(__('Interesse salvo com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar o interesse. Por favor tente novamente!'));
            }
        }
        $ecmProduto = $this->EcmCursoPresencialInteresse->EcmProduto
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
            ->matching('EcmTipoProduto')
            ->where(['EcmTipoProduto.nome' => 'Presencial']);

        $this->set(compact('ecmCursoPresencialInteresse', 'ecmProduto'));
        $this->set('_serialize', ['ecmCursoPresencialInteresse']);
        $this->set('titulo',__('Editar Interesse em Curso Presencial'));
        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Curso Presencial Interesse id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmCursoPresencialInteresse = $this->EcmCursoPresencialInteresse->get($id);
        if ($this->EcmCursoPresencialInteresse->delete($ecmCursoPresencialInteresse)) {
            $this->Flash->success(__('Interesse excluído com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir o interesse. Por favor tente novamente!'));
        }
        return $this->redirect(['action' => 'index']);
    }

    private function validarDados(){
        $validator = new Validator();

        $validator
            ->date('data_inicio',['dmy'])->requirePresence('data_inicio', 'create')->allowEmpty('data_inicio');
        $validator
            ->date('data_fim',['dmy'])->requirePresence('data_fim', 'create')->allowEmpty('data_fim');

        return $validator;
    }
}
