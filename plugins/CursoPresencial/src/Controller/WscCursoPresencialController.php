<?php

namespace CursoPresencial\Controller;

use App\Controller\WscController;
use Cake\Event\Event;

class WscCursoPresencialController extends WscController
{
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    public function listar()
    {
        $this->loadModel('CursoPresencial.EcmCursoPresencialTurma');

        $retorno = $this->EcmCursoPresencialTurma->find('all', [
            'contain' => [
                'EcmProduto' => ['fields' => ['curso' => 'EcmProduto.nome', 'preco' => 'preco']],
                'EcmCursoPresencialData' => function($q){
                    return $q->contain(['EcmCursoPresencialLocal.MdlCidade.MdlEstado'])
                        ->autoFields(true);
                },
                'EcmInstrutor' => function($q){
                    return $q->select(['EcmInstrutor.id'])
                        ->contain(['MdlUser' => ['fields' => [
                            'instrutor' => 'CONCAT(firstname," ",lastname)'
                        ]]]);
                }
            ]
        ])->notMatching('EcmCursoPresencialData', function ($q) {
            return $q->where(['EcmCursoPresencialData.datainicio <' => new \DateTime()]);
        })->where(['EcmCursoPresencialTurma.status' => 'Ativo']);

        foreach($retorno as $turma) {
            unset($turma['status']);
            $turma['produto'] = $turma['ecm_produto_id'];
            unset($turma['ecm_produto_id']);
            $turma['instrutores'] = $turma['ecm_instrutor'];
            unset($turma['ecm_instrutor']);
            //Valor relativo do produto
            if ($turma['valor_produto'] == "true") {
                $turma['valor'] = $turma['preco'];
            }
            unset($turma['valor_produto']);
            unset($turma['preco']);
            //data e local
            //timestart
            $turma['datainicio'] = $turma['ecm_curso_presencial_data'][0]['datainicio'];
            $turma['local'] = [];
            foreach ($turma['ecm_curso_presencial_data'] as $data) {
                //timeend
                $turma['datafim'] = $data['datafim'];
                $local = $data['ecm_curso_presencial_local'];
                //horarios
                $data['local'] = $data['ecm_curso_presencial_local_id'];
                unset($data['ecm_curso_presencial_local_id']);
                unset($data['ecm_curso_presencial_local']);
                unset($data['ecm_curso_presencial_turma_id']);
                $local['horarios'] = $data;
                //detalhes
                $local['detalhes'] = [];
                $local['detalhes']['local'] = $local['id'];
                $local['detalhes']['cidade'] = $local['mdl_cidade']['nome'];
                $local['detalhes']['estado'] = $local['mdl_cidade']['mdl_estado']['nome'];
                $local['detalhes']['uf'] = $local['mdl_cidade']['mdl_estado']['uf'];
                unset($local['nome']);
                unset($local['mdl_cidade']);
                unset($local['mdl_cidade_id']);
                $turma['local'][] = $local;
            }
            unset($turma['ecm_curso_presencial_data']);
        }

        $this->set(compact('retorno'));
    }

    public function get($id = null)
    {
        $retorno = [];
        if(!is_numeric($id)) {
            $id = $this->request->data('id');
        }
        if (is_numeric($id)) {
            $this->loadModel('CursoPresencial.EcmCursoPresencialTurma');

            if ($this->EcmCursoPresencialTurma->exists(['EcmCursoPresencialTurma.id' => $id])) {
                $retorno = $this->EcmCursoPresencialTurma->get($id, [
                    'contain' => [
                        'EcmProduto' => ['fields' => ['curso' => 'EcmProduto.nome', 'preco' => 'preco']],
                        'EcmCursoPresencialData' => function($q){
                            return $q->contain(['EcmCursoPresencialLocal.MdlCidade.MdlEstado'])
                                ->autoFields(true);
                        },
                        'EcmInstrutor' => function($q){
                            return $q->select(['EcmInstrutor.id'])
                                ->contain(['MdlUser' => ['fields' => [
                                    'instrutor' => 'CONCAT(firstname," ",lastname)'
                                ]]]);
                        }
                    ]
                ]);

                unset($retorno['status']);
                $retorno['produto'] = $retorno['ecm_produto_id'];
                unset($retorno['ecm_produto_id']);
                $retorno['instrutores'] = $retorno['ecm_instrutor'];
                unset($retorno['ecm_instrutor']);
                //Valor relativo do produto
                if($retorno['valor_produto'] == "true"){
                    $retorno['valor'] = $retorno['preco'];
                }
                unset($retorno['valor_produto']);
                unset($retorno['preco']);
                //data e local
                //timestart
                $retorno['datainicio'] = $retorno['ecm_curso_presencial_data'][0]['datainicio'];
                $retorno['local'] = [];
                foreach($retorno['ecm_curso_presencial_data'] as $data){
                    //timeend
                    $retorno['datafim'] = $data['datafim'];
                    $local = $data['ecm_curso_presencial_local'];
                    //horarios
                    $data['local'] =  $data['ecm_curso_presencial_local_id'];
                    unset($data['ecm_curso_presencial_local_id']);
                    unset($data['ecm_curso_presencial_local']);
                    unset($data['ecm_curso_presencial_turma_id']);
                    $local['horarios'] = $data;
                    //detalhes
                    $local['detalhes'] = [];
                    $local['detalhes']['local'] = $local['id'];
                    $local['detalhes']['cidade'] = $local['mdl_cidade']['nome'];
                    $local['detalhes']['estado'] = $local['mdl_cidade']['mdl_estado']['nome'];
                    $local['detalhes']['uf'] = $local['mdl_cidade']['mdl_estado']['uf'];
                    unset($local['nome']);
                    unset($local['mdl_cidade']);
                    unset($local['mdl_cidade_id']);
                    $retorno['local'][] = $local;
                }
                unset($retorno['ecm_curso_presencial_data']);
            }
        }
        $this->set(compact('retorno'));
    }

    public function estado($estado = null)
    {
        $retorno = [];
        if($estado == "/:action"){
            $estado = $this->request->data('estado');
        }
        if (isset($estado) && !empty($estado)) {
            $this->loadModel('CursoPresencial.EcmCursoPresencialTurma');

            $retorno = $this->EcmCursoPresencialTurma->find('all', ['contain' => [
                    'EcmProduto' => ['fields' => ['curso' => 'EcmProduto.nome', 'preco' => 'preco']],
                    'EcmInstrutor' => function($q){
                        return $q->select(['EcmInstrutor.id'])
                            ->contain(['MdlUser' => ['fields' => [
                                'instrutor' => 'CONCAT(firstname," ",lastname)'
                            ]]]);
                    }
                ]])->innerJoinWith('EcmCursoPresencialData', function ($q) use ($estado) {
                    return $q->contain(['EcmCursoPresencialLocal' => ['MdlCidade' =>
                        ['MdlEstado' => function ($q) use ($estado) {
                            return $q->where(['MdlEstado.uf' => $estado])
                                ->orWhere(['MdlEstado.nome' => $estado]);
                        }]
                    ]]);
                })
                ->notMatching('EcmCursoPresencialData', function ($q) {
                    return $q->where(['EcmCursoPresencialData.datainicio <' => new \DateTime()]);
                })
                ->where(['EcmCursoPresencialTurma.status' => 'Ativo'])
                ->group('EcmCursoPresencialTurma.id')->toArray();

            foreach($retorno as $key => $turma){
                if(count($turma->ecm_curso_presencial_data) == 0){
                    unset($retorno[$key]);
                }
            }
            sort($retorno);

            foreach($retorno as $turma) {
                unset($turma['status']);
                $turma['produto'] = $turma['ecm_produto_id'];
                unset($turma['ecm_produto_id']);
                $turma['instrutores'] = $turma['ecm_instrutor'];
                unset($turma['ecm_instrutor']);
                //Valor relativo do produto
                if ($turma['valor_produto'] == "true") {
                    $turma['valor'] = $turma['preco'];
                }
                unset($turma['valor_produto']);
                unset($turma['preco']);
                //data e local
                //timestart
                $turma['datainicio'] = $turma['ecm_curso_presencial_data'][0]['datainicio'];
                $turma['local'] = [];
                foreach ($turma['ecm_curso_presencial_data'] as $data) {
                    //timeend
                    $turma['datafim'] = $data['datafim'];
                    $local = $data['ecm_curso_presencial_local'];
                    //horarios
                    $data['local'] = $data['ecm_curso_presencial_local_id'];
                    unset($data['ecm_curso_presencial_local_id']);
                    unset($data['ecm_curso_presencial_local']);
                    unset($data['ecm_curso_presencial_turma_id']);
                    $local['horarios'] = $data;
                    //detalhes
                    $local['detalhes'] = [];
                    $local['detalhes']['local'] = $local['id'];
                    $local['detalhes']['cidade'] = $local['mdl_cidade']['nome'];
                    $local['detalhes']['estado'] = $local['mdl_cidade']['mdl_estado']['nome'];
                    $local['detalhes']['uf'] = $local['mdl_cidade']['mdl_estado']['uf'];
                    unset($local['nome']);
                    unset($local['mdl_cidade']);
                    unset($local['mdl_cidade_id']);
                    $turma['local'][] = $local;
                }
                unset($turma['ecm_curso_presencial_data']);
            }
        }
        $this->set(compact('retorno'));
    }

    public function cidade($cidade = null)
    {
        $retorno = [];
        if($cidade == "/:action"){
            $cidade = $this->request->data('cidade');
        }
        if (isset($cidade) && !empty($cidade)) {
            $this->loadModel('CursoPresencial.EcmCursoPresencialTurma');

            $retorno = $this->EcmCursoPresencialTurma->find('all', ['contain' => [
                'EcmProduto' => ['fields' => ['curso' => 'EcmProduto.nome', 'preco' => 'preco']],
                'EcmInstrutor' => function($q){
                    return $q->select(['EcmInstrutor.id'])
                        ->contain(['MdlUser' => ['fields' => [
                            'instrutor' => 'CONCAT(firstname," ",lastname)'
                        ]]]);
                }
            ]])->innerJoinWith('EcmCursoPresencialData', function ($q) use ($cidade) {
                    return $q->contain(['EcmCursoPresencialLocal' =>
                        ['MdlCidade' => function ($q) use ($cidade) {
                            return $q->where(['MdlCidade.nome' => $cidade])->contain(['MdlEstado']);
                        }]
                    ]);
                })
                ->notMatching('EcmCursoPresencialData', function ($q) {
                    return $q->where(['EcmCursoPresencialData.datainicio <' => new \DateTime()]);
                })
                ->where(['EcmCursoPresencialTurma.status' => 'Ativo'])
                ->group('EcmCursoPresencialTurma.id')->toArray();

            foreach($retorno as $key => $turma){
                if(count($turma->ecm_curso_presencial_data) == 0){
                    unset($retorno[$key]);
                }
            }
            sort($retorno);

            foreach($retorno as $turma) {
                unset($turma['status']);
                $turma['produto'] = $turma['ecm_produto_id'];
                unset($turma['ecm_produto_id']);
                $turma['instrutores'] = $turma['ecm_instrutor'];
                unset($turma['ecm_instrutor']);
                //Valor relativo do produto
                if ($turma['valor_produto'] == "true") {
                    $turma['valor'] = $turma['preco'];
                }
                unset($turma['valor_produto']);
                unset($turma['preco']);
                //data e local
                //timestart
                $turma['datainicio'] = $turma['ecm_curso_presencial_data'][0]['datainicio'];
                $turma['local'] = [];
                foreach ($turma['ecm_curso_presencial_data'] as $data) {
                    //timeend
                    $turma['datafim'] = $data['datafim'];
                    $local = $data['ecm_curso_presencial_local'];
                    //horarios
                    $data['local'] = $data['ecm_curso_presencial_local_id'];
                    unset($data['ecm_curso_presencial_local_id']);
                    unset($data['ecm_curso_presencial_local']);
                    unset($data['ecm_curso_presencial_turma_id']);
                    $local['horarios'] = $data;
                    //detalhes
                    $local['detalhes'] = [];
                    $local['detalhes']['local'] = $local['id'];
                    $local['detalhes']['cidade'] = $local['mdl_cidade']['nome'];
                    $local['detalhes']['estado'] = $local['mdl_cidade']['mdl_estado']['nome'];
                    $local['detalhes']['uf'] = $local['mdl_cidade']['mdl_estado']['uf'];
                    unset($local['nome']);
                    unset($local['mdl_cidade']);
                    unset($local['mdl_cidade_id']);
                    $turma['local'][] = $local;
                }
                unset($turma['ecm_curso_presencial_data']);
            }
        }
        $this->set(compact('retorno'));
    }

    public function estadosEdicoes(){
        $this->loadModel('CursoPresencial.EcmCursoPresencialTurma');

        $retorno = $this->EcmCursoPresencialTurma->buscaEstadoPeloCurso(new \DateTime());

        $this->set(compact('retorno'));
    }
}