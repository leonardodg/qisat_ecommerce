<?php

namespace WebService\Controller;

use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Routing\Router;

class WscCertificateController extends WscController
{
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
    * Função reponsável por listar os certificados de um usuario
    * Deve ser feito requisições do tipo GET:
    * http://{host}/web-service/wsc-certificate/listar/$id
    *
    * Retornos:
    * 1- {'sucesso': true, certificado: lista de certificados}
    * 2- {'sucesso': false, 'mensagem': 'Usuário não informado'}
    *
    * */
    public function listar($id = null){
        $retorno = ['sucesso' => false, 'mensagem' => __('Usuário não encontrado')];
        if(!is_numeric($id))
            $id = $this->request->data('id');
        if(!is_numeric($id))
            $id = $this->request->session()->read('Auth.User.id');
        if(!is_null($id)) {
            $this->loadModel('WebService.MdlCertificate');
            $mdlCertificate = $this->MdlCertificate->find('all')->select(['course'])
                ->contain(['MdlCertificateIssues' => function ($q) use ($id) {
                    return $q->where(['userid' => $id])->select(['datasolicitacao' => 'MdlCertificateIssues.timecreated',
                        'token' => 'code', 'nota' => 'nota']);
                }, 'MdlCourse' => function($q){ return $q->select(['certificate_name' => 'fullname',
                        'shortname' => 'shortname', 'cargahoraria' => 'timeaccesssection']);
                }])->toList();

            $this->loadModel('Configuracao.EcmConfig');
            $moodle = $this->EcmConfig->find()->where(['nome'=>'dominio_acesso_moodle'])->first()->valor;

            $this->loadModel('WebService.MdlCourseModules');

            foreach($mdlCertificate as $certificate){
                $module = $this->MdlCourseModules->find()->where(['course'=>$certificate->course])
                    ->contain(['MdlModules' => function($q){
                        return $q->where(['name' => 'certificate']);
                    }])->first();

                if(!is_null($module)) {
                    $module = $module->id;
                    $certificate['links'] = [];
                    if (strpos($certificate->certificatetype, 'Certificado') === false) {
                        $certificate['digital'] = false;
                        $certificate['links']['default'] = 'http://' . $moodle . '/mod/certificate/view.php?id=' . $module . '&action=get';
                    } else {
                        $certificate['digital'] = true;
                        $certificate['links']['digital'] = 'http://' . $moodle . '/mod/certificate/view.php?id=' . $module . '&action=get';
                        $certificate['links']['default'] = 'http://' . $moodle . '/mod/certificate/view.php?id=' . $module;
                    }
                }else{
                    $certificate['links'] = null;
                }

                $imagem = $this->getImagemProduto($certificate->course);
                $certificate['imagem'] = null;
                if(!is_null($imagem))
                    $certificate['imagem'] = $imagem;
            }

            $retorno = ['sucesso' => true, 'certificado' => $mdlCertificate];
        }
        $this->set(compact('retorno'));
    }

    private function getImagemProduto($idCurso){
        $this->loadModel('Produto.EcmProduto');

        $produto =  $this->EcmProduto
            ->find()
            ->select(['EcmImagem.src'])
            ->matching('MdlCourse')
            ->matching('EcmImagem')
            ->where([
                'EcmProduto.refcurso' => 'true',
                'MdlCourse.id' => $idCurso,
                'EcmImagem.descricao' => 'Imagens - Capa'
            ])
            ->first();

        if(!is_null($produto))
            return Router::url('upload/'.$produto->_matchingData["EcmImagem"]->src, true);

        return null;

    }
}