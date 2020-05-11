<?php
namespace Convenio\Controller;

use Cake\Filesystem\Folder;
use Convenio\Controller\AppController;

/**
 * EcmConvenioContrato Controller
 *
 * @property \Convenio\Model\Table\EcmConvenioContratoTable $EcmConvenioContrato */
class EcmConvenioContratoController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('ElFinder');
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Convenio Contrato id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmConvenioContrato = $this->EcmConvenioContrato->get($id, [
            'contain' => ['EcmConvenio']
        ]);

        $this->set('ecmConvenioContrato', $ecmConvenioContrato);
        $this->set('_serialize', ['ecmConvenioContrato']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Convenio Contrato id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function contrato($id = null)
    {
        $this->loadModel('Convenio.EcmConvenio');
        $ecmConvenio = $this->EcmConvenio->get($id, [
            'contain' => ['EcmConvenioContrato']
        ]);

        $ecmConvenioContrato = $ecmConvenio->get('ecm_convenio_contrato');

        $inicializarElFinder = false;

        if(is_null($ecmConvenioContrato))
            $ecmConvenioContrato = $this->EcmConvenioContrato->newEntity();

        if ($this->request->is(['patch', 'post', 'put']) && is_null($this->request->data('upload'))
            && !$this->request->is('ajax')) {

            $this->request->data['data_inicio_convenio'] .= ' 00:00:00';
            $this->request->data['data_fim_convenio'] .= ' 00:00:00';
            $inicializarElFinder = true;
            $ecmConvenioContrato = $this->EcmConvenioContrato->patchEntity($ecmConvenioContrato, $this->request->data);

            $salvarDados = true;
            if($ecmConvenioContrato->get('contrato_ativo') == 'true' || $ecmConvenioContrato->get('contrato_assinado') == 'true'){
                $pasta = new Folder(WWW_ROOT . 'upload/convenio/contrato/' . $id);

                if(count($pasta->find('.*\.pdf', true)) == 0){
                    $salvarDados = false;
                    $this->Flash->error(__('Atenção: Para ativar um contrato ou marcar como assinado deve ser feito o upload de um contrato no formato pdf!'));
                }
            }

            if($salvarDados) {
                if ($ecmConvenioContrato = $this->EcmConvenioContrato->save($ecmConvenioContrato)) {

                    $ecmConvenio->set('ecm_convenio_contrato_id', $ecmConvenioContrato->get('id'));
                    $this->EcmConvenio->save($ecmConvenio);

                    $this->Flash->success(__('Contrato salvo com sucesso'));
                    return $this->redirect(['controller' => '', 'action' => 'index']);
                } else {
                    $this->Flash->error(__('Ocorreu um erro ao salvar o contrato. Por favor tente novamente!'));
                }
            }
        }
        if(!file_exists(WWW_ROOT . 'upload/convenio/contrato'))
            mkdir(WWW_ROOT.'upload/convenio/contrato');

        $this->ElFinder->setUploadAllow(['application/pdf']);
        $this->ElFinder->connector('convenio/contrato/' . $id, $inicializarElFinder);

        $this->set(compact('ecmConvenioContrato'));
        $this->set('_serialize', ['ecmConvenioContrato']);
    }
}
