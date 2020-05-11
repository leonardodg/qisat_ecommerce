<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 26/08/2016
 * Time: 08:56
 */

namespace WebService\Controller;


use App\Controller\WscController;
use Cake\Event\Event;

class WscCursoController extends WscController
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
    * Função reponsável por retornar o status de um ou mais cursos de um usuário
    * Deve ser feito requisições do tipo GET, informando os seguintes parâmetros:
    * http://{host}/web-service/wsc-curso/status-curso/{id-usuario}/{id-curso}
    *
    * Se o id-curso não for informado será retornado uma lista de cursos do usuário informado no parâmetro
    *
    * Retornos:
    * 1- {'sucesso':true, 'curso':'dados do curso'}
    * 2- {'sucesso':false, 'mensagem': 'Informe os parâmetros de consulta'}
    * 3- {'sucesso':false, 'mensagem': 'Parâmetro assunto_email não informado'}
    * 4- {'sucesso':false, 'mensagem': 'Registro não encontrado'}
    *
    * */
    public function statusCurso($idUsuario = null, $idCurso = null){
        $this->loadModel('WebService.MdlCourse');

        $retorno = ['sucesso' => false, 'mensagem' => __('Informe os parâmetros de consulta')];

        if(!is_null($idUsuario)){

            $result = $this->MdlCourse->buscaStatusCursoUsuario($idUsuario, $idCurso);

            $retorno = ['sucesso' => false, 'mensagem' => __('Registro não encontrado')];

            if(!is_null($result)){
                $statusCurso = null;

                if(!is_null($idCurso)) {
                    $statusCurso = $this->getStatusCurso($result, $idUsuario, $idCurso);
                }else{
                    foreach($result as $status) {
                        $status = (object) $status;
                        $statusCurso[] = $this->getStatusCurso($status, $idUsuario, $status->idcurso);
                    }
                }

                $retorno = ['sucesso' => true,'curso' => $statusCurso];
            }
        }

        $this->set(compact('retorno'));
    }

    /*Função para obter o objeto de retorno da requisição de status do curso
     * */
    private function getObject(){
        $statusCurso = new \stdClass();
        $statusCurso->agendado = false;
        $statusCurso->nu_prorrogacoes = "";
        $statusCurso->data_prorrogacao = "";
        $statusCurso->dias_prorrogados = "";
        $statusCurso->finalizado = false;
        $statusCurso->finalizadoem = "";
        $statusCurso->usuariobloqueado = false;
        $statusCurso->cursobloqueado = false;
        $statusCurso->prazoilimitado = false;
        $statusCurso->naohabilitado = false;
        $statusCurso->semaceite = false;
        $statusCurso->expirahoje = false;
        $statusCurso->diasrestantes = "";
        $statusCurso->expirado = false;
        $statusCurso->expirouem = "";
        $statusCurso->datainicio = "";
        $statusCurso->datafim = "";
        $statusCurso->turma = "";
        $statusCurso->inicio_descricao = "";
        $statusCurso->situacao = "";
        $statusCurso->situacao_descricao = "";
        $statusCurso->recursos_total = 0;
        $statusCurso->recursos_finalizados = 0;
        $statusCurso->andamento = 0;
        $statusCurso->papel = '';
        $statusCurso->nome = null;
        $statusCurso->imagem = null;

        return $statusCurso;
    }

    private function getStatusCurso($result, $idUsuario, $idCurso){
        $statusCurso = $this->getObject();
        $statusCurso->papel = $result->role_shortname;
        $statusCurso->nome = $result->fullname;

        if(!is_null($result->imagem)) {
            $statusCurso->imagem = \Cake\Routing\Router::url('/webroot/upload/', true).$result->imagem;
        }

        $agendado = $result->timestart > time();
        $statusCurso->agendado = $agendado;

        if($agendado){
            $statusCurso->situacao = "agendado";
        }

        $numeroProrrogacoes = $this->MdlCourse->buscaNumeroProrrogacoes($idUsuario, $idCurso);

        $statusCurso->nu_prorrogacoes = $numeroProrrogacoes;

        $prorrogacoes = $this->MdlCourse->buscaProrrogacoesCurso($idUsuario, $idCurso);
        if($prorrogacoes = current($prorrogacoes)){
            $statusCurso->data_prorrogacao = $prorrogacoes['data_prorrogacao'];
            $statusCurso->dias_prorrogados = $prorrogacoes['nu_dias_prorrogacao'];
        }

        $dias = ($result->timeend - time())/3600/24;
        $diasint = ceil($dias);
        $dias = ceil($dias);

        $bloqueado = false;

        if($this->MdlCourse->usuarioBloqueado($idUsuario)){
            $bloqueado = true;
            $statusCurso->situacao = "usuario_bloqueado";
        }

        $situacao = '';
        if (isset($result->maxtime)) {
            $situacao .= __('Finalizado em').' '.date("d/m/Y",$result->maxtime);
            $statusCurso->finalizado = true;
            $statusCurso->finalizadoem = $result->maxtime;
            $statusCurso->situacao = "finalizado";
        }

        if ($result->timeend == 0) {
            $statusCurso->situacao = "prazo_ilimitado";
            if ($bloqueado) {
                $situacao .= __('Usuário bloqueado');
                $statusCurso->usuariobloqueado = true;
                $statusCurso->situacao = "usuario_bloqueado";
            } else if ($result->status == 1) {
                $situacao .= __('Curso bloqueado');
                $statusCurso->cursobloqueado = true;
                $statusCurso->situacao = "curso_bloqueado";
            }
            $situacao .= __('Prazo ilimitado');
            $statusCurso->prazoilimitado = true;

            if ($agendado) {
                $situacao .= __('Curso agendado para').' '.date("d/m/Y", $result->timestart);
            }

        } else {
            if ($bloqueado) {
                $situacao .= __('Usuário bloqueado');
                $statusCurso->usuariobloqueado = true;
                $statusCurso->situacao = "usuario_bloqueado";
            } else if ($result->status == 1) {
                $situacao .= __('Curso bloqueado');
                $statusCurso->cursobloqueado = true;
                $statusCurso->situacao = "curso_bloqueado";
            } else if ($result->role_shortname == "naohabilitado") {
                $situacao .= __('Não habilitado');
                $statusCurso->naohabilitado = true;
                $statusCurso->situacao = "nao_habilitado";
            } else if ($result->role_shortname == "semaceite") {
                $situacao .= __('aguardando aceite de contrato');
                $statusCurso->semaceite = true;
                $statusCurso->situacao = "sem_aceite";
            }

            if (date("d/m/Y",$result->timeend) == date("d/m/Y", time())) {
                $situacao .= __('até').' '.date("d/m/Y",$result->timeend).' '.__('expira hoje');
                $statusCurso->expirahoje = true;
                $statusCurso->situacao = "liberado";
            } else if ($agendado) {
                $situacao .= __('Curso agendado para').' '.date("d/m/Y",$result->timestart);
                $statusCurso->situacao = "agendado";
            } else if ($diasint > 0) {
                $situacao .= __('ate').' '.date("d/m/Y",$result->timeend);
                if ($diasint == 1) {
                    $situacao .= __('Resta') . ' ' . $dias . ' '. __('dia');
                }else {
                    $situacao .= __('Restam') . ' ' . $dias . ' ' . __('dias');
                }
                $statusCurso->diasrestantes = $diasint;
                $statusCurso->situacao = "liberado";
            } else {
                if($result->timeend == 0){
                    $situacao .= __('Prazo ilimitado');
                    $statusCurso->prazoilimitado = true;
                    $statusCurso->situacao = "prazo_ilimitado";
                } else {
                    $situacao .= __('Expirou em').date("d/m/Y",$result->timeend);

                    $statusCurso->expirado = true;
                    $statusCurso->expirouem = $result->timeend;
                    if($statusCurso->situacao == "") {
                        $statusCurso->situacao = "expirado";
                    }
                }
            }
        }

        if ($agendado) {
            $inicio = __('Curso agendado');
        } else {
            $inicio = __('Inicio em').' '.date("d/m/Y", $result->timestart);
        }

        $statusCurso->datainicio = $result->timestart;
        $statusCurso->datafim = $result->timeend;

        if(isset($result->sigla_turma)) {
            $turma = $result->sigla_turma;
        }else{
            $turma = __('Sem turma');
        }

        $statusCurso->inicio_descricao = $inicio;
        $statusCurso->turma = $turma;
        $statusCurso->situacao_descricao = $situacao;

        $result = $this->MdlCourse->buscaStatusRecursos($idUsuario, $idCurso);

        if(!is_null($result)){
            $statusCurso->recursos_total = $result->recursos_total;
            $statusCurso->recursos_finalizados = $result->recursos_finalizados;
            $statusCurso->andamento = $result->andamento;
        }

        return $statusCurso;
    }

    /*
    * Função reponsável por listar os cursos com seus valores reais e de convenio
    * Deve ser feito requisições do tipo GET:
    * http://{host}/web-service/wsc-curso/convenio
    *
    * Retornos:
    * 1- {'sucesso':true, software:lista de cursos de software, teoricos:lista de cursos teoricos}
    *
    * */
    public function convenio(){
        $this->loadModel('Configuracao.EcmConfig');
        $convenio_desconto_professor = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'convenio_desconto_professor'])->first()->valor;
        $convenio_desconto_aluno = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'convenio_desconto_aluno'])->first()->valor;
        $convenio_desconto_associado = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'convenio_desconto_associado'])->first()->valor;

        $this->loadModel('Produto.EcmProduto');
        $ecmProduto = $this->EcmProduto->find('all')->contain(['EcmTipoProduto' => ['conditions' => [
            'OR' => [['nome' => 'Cursos Software'], ['nome' => 'Cursos Teóricos']]
        ]]]);

        $software = [];
        $teoricos = [];
        foreach ($ecmProduto as $produto) {
            if(!empty($produto->ecm_tipo_produto)){
                $curso = [
                    'Curso' => $produto->nome,
                    'Valor' => number_format(round($produto->preco), 2, ',', '.'),
                    'Professor' => number_format(round($produto->preco-($produto->preco*$convenio_desconto_professor/100)), 2, ',', '.'),
                    'Aluno' => number_format(round($produto->preco-($produto->preco*$convenio_desconto_aluno/100)), 2, ',', '.'),
                    'Associado' => number_format(round($produto->preco-($produto->preco*$convenio_desconto_associado/100)), 2, ',', '.')
                ];
                if($produto->ecm_tipo_produto[0]->nome == "Cursos Software"){
                    $software[$produto->id] = $curso;
                }else if($produto->ecm_tipo_produto[0]->nome == "Cursos Teóricos"){
                    $teoricos[$produto->id] = $curso;
                }
            }
        }

        $retorno = ['sucesso' => true, 'software' => $software, 'teoricos' => $teoricos];

        $this->set(compact('retorno'));
    }

    /*
    * Função responsável por listar os cursos do usuario e suas situações
    * Deve ser feito requisições do tipo GET, informando os seguintes parâmetros:
    * http://{host}/web-service/wsc-curso/listar/{id do usuario}
    *
    * Retornos:
    * 1- {'sucesso':true, cursos:lista de cursos}
    * 2- {'sucesso':false, 'mensagem': 'Usuário não encontrado'}
    *
    * */
    public function listar($id = null){
        $retorno = ['sucesso' => false, 'mensagem' => __('Usuário não encontrado')];
        if(is_null($id))
            $id = $this->request->data('email');
        if(is_null($id))
            $id = $this->request->session()->read('Auth.User.id');
        if(!is_null($id)) {
            $this->loadModel('WebService.MdlCourse');
            $mdlCourseStatus = $this->MdlCourse->buscaStatusCursoUsuario($id);

            $this->loadModel('Configuracao.EcmConfig');
            $dominio_moodle = $this->EcmConfig->find()->where(['nome' => 'dominio_acesso_moodle'])->first()->valor;

            foreach($mdlCourseStatus as $course){
                if(is_array($course)){
                    $object = new \stdClass();
                    foreach ($course as $key => $value)
                        $object->$key = $value;
                    $course = $object;
                }
                $mdlCourse[$course->idcurso] = $this->getStatusCurso($course, $id, $course->idcurso);
                $mdlCourse[$course->idcurso]->link = 'http://'.$dominio_moodle.'/course/view.php?id='.$course->idcurso;
            }

            $retorno = ['sucesso' => true, 'cursos' => $mdlCourse];
        }

        $this->set(compact('retorno'));
    }
}