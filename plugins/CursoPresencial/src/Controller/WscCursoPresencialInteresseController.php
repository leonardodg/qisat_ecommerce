<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 23/08/2016
 * Time: 08:34
 */

namespace CursoPresencial\Controller;


use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Validation\Validator;

class WscCursoPresencialInteresseController extends WscController
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
    * Função reponsável por inserir interesse em curso presencial
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
    * {
    *  nome: (opcional se usuario_logado for true),
    *  email: (opcional se email for true),
    *  telefone: (opcional se telefone for true),
     * edicao_curso_presencial: (opcional se produto for informado),
     * produto:(opcional se edicao_curso_presencial for informado),
     * usuario_logado:(opcional, true ou false)
    * }
    *
    * Retornos:
    * 1- {'sucesso':true}
    * 2- {'sucesso':false, 'mensagem': 'Este Web Service não aceita esse tipo de requisição'}
    * 3- {'sucesso':false, 'mensagem': 'Parâmetro assunto_email não informado'}
    * 4- {'sucesso':false, 'mensagem': 'Parâmetro corpo_email não informado'}
    * 5- {'sucesso':false, 'mensagem': 'Erro ao salvar repasse'}
    * 6- {'sucesso':false, 'mensagem': 'Não foi possível inserir o repasse'}
    * 7- {'sucesso':false, 'mensagem': 'Parâmetro entidade incorreto'}
    * 8- {'sucesso':false, 'mensagem': 'Entidade não encontrada'}
    *
    * */
    public function salvar(){
        $retorno = ['sucesso' => false, 'mensagem' => __('Este Web Service não aceita esse tipo de requisição')];
        if ($this->request->is('post')) {

            $validaDados = $this->validarDados();
            if(is_array($validaDados)) {
                $retorno = $validaDados;
            }else{
                $this->loadModel('CursoPresencial.EcmCursoPresencialInteresse');

                $interesse = $this->EcmCursoPresencialInteresse->newEntity();

                $interesse->set('ecm_curso_presencial_turma_id', $this->request->data('edicao_curso_presencial'));
                $interesse->set('ecm_produto_id', $this->request->data('produto'));

                if($this->request->data('usuario_logado')){
                    $interesse->set('mdl_user_id', $this->Auth->user('id'));
                }else{
                    $interesse->set('nome', $this->request->data('nome'));
                    $interesse->set('email', $this->request->data('email'));
                    $interesse->set('telefone', $this->request->data('telefone'));
                }

                if ($this->EcmCursoPresencialInteresse->save($interesse)) {
                    $retorno = ['sucesso' => true];
                }else{
                    $retorno = ['sucesso' => false, 'mensagem' => __('Não foi possível inserir o interesse')];
                }
            }
        }

        $this->set(compact('retorno'));
    }

    protected function validarDados(){
        $nome = $this->request->data('nome');
        $email = $this->request->data('email');
        $telefone = $this->request->data('telefone');
        $edicaoCursoPresencial = $this->request->data('edicao_curso_presencial');
        $produto = $this->request->data('produto');
        $usuarioLogado = $this->request->data('usuario_logado');

        if ($usuarioLogado && is_null($this->Auth->user())) {
            return ['sucesso' => false, 'mensagem' => __('Usuário deve fazer login')];
        }

        if (!$usuarioLogado && is_null($nome)) {
            return ['sucesso' => false, 'mensagem' => __('Parâmetro nome não informado e parâmetro usuario_logado é false')];
        }

        if (!$usuarioLogado && is_null($email)) {
            return ['sucesso' => false, 'mensagem' => __('Parâmetro email não informado e parâmetro usuario_logado é false')];
        }else{
            $validator = new Validator();
            $validator->email('email');
            $errors = $validator->errors($this->request->data());

            if(!empty($errors)){
                return ['sucesso' => false, 'mensagem' => __('Parâmetro email inválido')];
            }
        }

        if (!$usuarioLogado && is_null($telefone)) {
            return ['sucesso' => false, 'mensagem' => __('Parâmetro telefone não informado e parâmetro usuario_logado é false')];
        }

        if (is_null($edicaoCursoPresencial) && is_null($produto)) {
            return ['sucesso' => false, 'mensagem' => __('Parâmetro edicao_curso_presencial e produto não informados. Informe pelo menos um')];
        }

        if (!is_null($edicaoCursoPresencial)) {
            $this->loadModel('EcmCursoPresencialTurma');

            if(!$this->EcmCursoPresencialTurma->exists(['id' => $edicaoCursoPresencial]))
                return ['sucesso' => false, 'mensagem' => __('Edição do curso presencial não encontrada')];
        }

        if (!is_null($produto)) {
            $this->loadModel('Produto.EcmProduto');

            if(!$this->EcmProduto->exists(['id' => $produto]))
                return ['sucesso' => false, 'mensagem' => __('Produto não encontrado')];
        }
    }

}