<?php
namespace Configuracao\Controller;

use Cake\Validation\Validator;
use Configuracao\Controller\AppController;

/**
 * EcmConfig Controller
 *
 * @property \Configuracao\Model\Table\EcmConfigTable $EcmConfig */
class ParcelaController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->helpers = array('JqueryMask');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->loadModel('Configuracao.EcmConfig');

        $valorMinimo = $this->EcmConfig->find('all')
            ->where(['nome' => 'valor_minimo_parcela'])->first();

        $minimoParcelas = $this->EcmConfig->find('all')
            ->where(['nome' => 'maximo_numero_parcela'])->first();

        $errors = array();
        $ecmConfig = $this->EcmConfig->newEntity();

        if ($this->request->is(['patch', 'post', 'put'])) {

            $validator = $this->validarDados();
            $errors = $validator->errors($this->request->data());

            if(empty($errors)) {
                $data = $this->request->data;
                $valorMinimoSalvar = [
                    'nome' => 'valor_minimo_parcela',
                    'valor' => $data['valor_minimo_parcela']
                ];

                $minimoParcelasSalvar = [
                    'nome' => 'maximo_numero_parcela',
                    'valor' => $data['maximo_numero_parcela']
                ];

                if (is_null($valorMinimo)) {
                    $valorMinimo = $this->EcmConfig->newEntity();
                }

                if (is_null($minimoParcelas)) {
                    $minimoParcelas = $this->EcmConfig->newEntity();
                }

                $valorMinimo = $this->EcmConfig->patchEntity($valorMinimo, $valorMinimoSalvar);
                $minimoParcelas = $this->EcmConfig->patchEntity($minimoParcelas, $minimoParcelasSalvar);


                if ($this->EcmConfig->save($valorMinimo) && $this->EcmConfig->save($minimoParcelas)) {
                    $this->Flash->success(__('Configurações salvas com sucesso'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Ocorreu um erro ao salvar as configurações!'));
                }
            }else{
                $ecmConfig->errors($errors);
            }
        }

        $valorMinimo = is_null($valorMinimo)? '' : $valorMinimo->valor;
        $minimoParcelas = is_null($minimoParcelas)? '' : $minimoParcelas->valor;

        $this->set(compact('ecmConfig'));
        $this->set('_serialize', ['ecmConfig']);
        $this->set(compact('valorMinimo', 'minimoParcelas','errors'));
    }

    private function validarDados(){
        $validator = new Validator();

        $validator->decimal('valor_minimo_parcela')
            ->notEmpty('valor_minimo_parcela');

        $validator->integer('maximo_numero_parcela')
            ->notEmpty('valor_minimo_parcela');

        return $validator;
    }



}
