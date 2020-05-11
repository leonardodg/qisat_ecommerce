<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Validation\Validator;

/**
 * EcmLogAcao Controller
 *
 * @property \App\Model\Table\EcmLogAcaoTable $EcmLogAcao */
class EcmLogAcaoController extends AppController
{

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

            $usuario = $this->request->query('usuario');
            $tabela = $this->request->query('tabela_pesquisa');
            $acao = $this->request->query('acao_pesquisa');
            $dataInicio = $this->request->query('data_inicio');
            $dataFim = $this->request->query('data_fim');

            if (strlen(trim($usuario)) > 0) {
                $condition["concat(MdlUser.firstname, ' ', MdlUser.lastname) LIKE"] = '%'.$usuario.'%';
            }

            if (strlen(trim($tabela)) > 0) {
                $condition['tabela LIKE'] = '%'.$tabela.'%';
            }

            if (strlen(trim($acao)) > 0) {
                $condition['acao'] = $acao;
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
        }

        $this->paginate = [
            'contain' => ['MdlUser'],
            'conditions' => $condition
        ];

        $ecmLogAcao = $this->paginate($this->EcmLogAcao);

        $listaAcao = $this->EcmLogAcao->find('list',
                        ['keyField' => 'acao', 'valueField' => 'acao'])->groupBy('acao');
        $listaAcao = current($listaAcao->toArray());

        foreach($listaAcao as $acao){
            $optionAcao[$acao] = $acao;
        }

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmLogAcao', 'optionAcao'));
        $this->set('_serialize', ['ecmLogAcao']);
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
