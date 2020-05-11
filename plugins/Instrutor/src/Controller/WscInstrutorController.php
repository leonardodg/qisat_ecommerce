<?php

namespace Instrutor\Controller;

use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Network\Response;

class WscInstrutorController extends WscController
{
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->response->cache('-1 minute', '+30 days');
        $this->response->expires('+30 days');
        $this->response->sharable(true, 3600);
        $this->set('_serialize', true);
    }

    public function listar()
    {
        $this->loadModel('Instrutor.EcmInstrutor');

        $retorno = $this->EcmInstrutor->find('all',
            [
                'fields' => [
                    'EcmInstrutor.id', 'chave' => 'MdlUser.idnumber', 'nome' => 'CONCAT(MdlUser.firstname, " ", MdlUser.lastname)',
                    'EcmImagem.src', 'EcmInstrutor.formacao'
                ],
            ])
            ->contain([
                'MdlUser', 'EcmImagem', 'EcmInstrutorArea',
                'EcmProduto' => function($q){
                    return $q->select(['id', 'nome', 'preco', 'sigla', 'habilitado', 'visivel'])
                            ->contain(['EcmTipoProduto' => ['fields' => [
                                'id', 'EcmProdutoEcmTipoProduto.ecm_produto_id'
                            ]],
                            'EcmImagem' => ['fields' => [
                                'src', 'type' => 'descricao', 'EcmProdutoEcmImagem.ecm_produto_id'
                            ]]]);
                    }])
            ->where(['EcmInstrutor.ativo' => 1])
            ->toList();

        $listaAreas = $this->EcmInstrutor->EcmInstrutorArea->find('all');

        $arrayAreas = [];
        foreach($listaAreas as $area){
            $chave = $area->get('descricao');

            $chave = preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $chave ) );
            $chave = strtolower($chave);

            $arrayAreas[$chave] = 0;
        }

        foreach ($retorno as $user) {

            if(!empty($user->get('ecm_imagem'))) {
                $url = $user->get('ecm_imagem')->get('src');
                $user->imagem = \Cake\Routing\Router::url('/webroot/upload/', true) . $url;
            }
            unset($user->ecm_imagem);

            $user->produto = $user->get('ecm_produto');

            foreach ($user->produto as $produto) {
                $produto->categorias = $produto->get('ecm_tipo_produto');
                unset($produto->ecm_tipo_produto);
            }

            $user->areas = $arrayAreas;
            if(!empty($user->get('ecm_instrutor_area'))) {
                foreach ($user->get('ecm_instrutor_area') as $area) {
                    $chave = strtolower($area->get('descricao'));

                    if (array_key_exists($chave, $user->areas)) {
                        $user->areas[$chave] = 1;
                    }
                }
            }
            unset($user->ecm_instrutor_area);
        }

        $this->set(compact('retorno'));
    }

    public function get($id = null)
    {
        if(!is_numeric($id)) {
            $id = $this->request->data('id');
        }

        $this->loadModel('Instrutor.EcmInstrutor');

        $retorno = $this->EcmInstrutor->find('all',
            [
                'fields' => [
                    'EcmInstrutor.id', 'chave' => 'MdlUser.idnumber', 'nome' => 'CONCAT(MdlUser.firstname, " ", MdlUser.lastname)',
                    'EcmImagem.src', 'EcmInstrutor.descricao', 'EcmInstrutor.formacao', 'EcmInstrutor.ativo'
                ],
            ])
            ->contain([
                'MdlUser', 'EcmImagem', 'EcmInstrutorRedeSocial' => ['EcmRedeSocial'],
                'EcmInstrutorArtigo',
                'EcmProduto' => function($q){
                    return $q->select(['id', 'nome', 'preco', 'sigla'])
                        ->contain([
                            'EcmProdutoInfo' => [
                                'fields' => ['url_info' => 'EcmProdutoInfo.url']
                            ],
                            'EcmImagem' => function($q){
                                return $q->select([ 'src', 'type' => 'descricao', 'EcmProdutoEcmImagem.ecm_produto_id'])
                                    ->where(['descricao' => 'Imagens - Capa']);
                            }
                        ]);
                }])
            ->where(['EcmInstrutor.id' => $id])->first();

        if(!empty($retorno->get('ecm_imagem'))) {
            $url = $retorno->get('ecm_imagem')->get('src');
            $retorno->imagem = \Cake\Routing\Router::url('/webroot/upload/', true) . $url;
        }
        unset($retorno->ecm_imagem);

        $retorno->produto = $retorno->get('ecm_produto');
        unset($retorno->ecm_produto);

        foreach ($retorno->produto as $produto) {
            unset($produto->_joinData);

            $produto->imagem = null;
            if(!empty($produto->get('ecm_imagem'))){
                $url = $produto->get('ecm_imagem')[0]->get('src');
                $produto->imagem = \Cake\Routing\Router::url('/webroot/upload/', true) . $url;
            }
            unset($produto->ecm_imagem);
        }

        $retorno->redes_sociais = [];

        foreach ($retorno->ecm_instrutor_rede_social as $redeSocial) {
            $redeSocialObj = new \stdClass();
            $redeSocialObj->descricao = $redeSocial->get('ecm_rede_social')->get('nome');
            $redeSocialObj->link = $redeSocial->get('link');

            $retorno->redes_sociais[] = $redeSocialObj;
        }

        $retorno->artigos = $retorno->ecm_instrutor_artigo;

        foreach($retorno->artigos as $artigo){
            if(!is_null($artigo->data_publicacao)){
                $dataPublicacao = new \DateTime($artigo->data_publicacao->format('Y-m-d H:i:s'));
                $artigo->data_publicacao = $dataPublicacao->getTimestamp();
            }

            if(!is_null($artigo->data_modificacao)){
                $dataModificacao = new \DateTime($artigo->data_modificacao->format('Y-m-d H:i:s'));
                $artigo->data_modificacao = $dataModificacao->getTimestamp();
            }
        }

        unset($retorno->ecm_instrutor_artigo);
        unset($retorno->ecm_instrutor_rede_social);

        $this->set(compact('retorno'));
    }

    public function top(){
        $this->loadModel('Instrutor.EcmInstrutor');

        $retorno = $this->EcmInstrutor->find('all',
            [
                'fields' => [
                    'EcmInstrutor.id', 'chave' => 'MdlUser.idnumber', 'nome' => 'CONCAT(MdlUser.firstname, " ", MdlUser.lastname)',
                    'EcmImagem.src', 'EcmInstrutor.descricao', 'EcmInstrutor.formacao'
                ],
            ])
            ->contain([
                'MdlUser', 'EcmImagem',
                'EcmInstrutorRedeSocial' => [
                    'fields' => [
                        'EcmInstrutorRedeSocial.id', 'EcmInstrutorRedeSocial.ecm_instrutor_id',
                        'EcmInstrutorRedeSocial.link', 'EcmRedeSocial.nome'
                    ],
                    'EcmRedeSocial'
                ],
                'EcmProduto' => function($q){
                    return $q->select(['id', 'nome', 'preco', 'sigla', 'habilitado', 'visivel'])
                        ->contain(['EcmTipoProduto' => ['fields' => [
                            'id', 'EcmProdutoEcmTipoProduto.ecm_produto_id'
                        ]],
                        'EcmImagem' => ['fields' => [
                            'src', 'type' => 'descricao', 'EcmProdutoEcmImagem.ecm_produto_id'
                        ]]]);
                }])
            ->where(['EcmInstrutor.ativo' => 1, 'EcmImagem.src IS NOT' => null ])
            ->order('RAND()')
            ->limit(10);

        foreach ($retorno as $user) {

            if(!empty($user->get('ecm_imagem'))) {
                $url = $user->get('ecm_imagem')->get('src');
                $user->imagem = \Cake\Routing\Router::url('/webroot/upload/', true) . $url;
            }
            unset($user->ecm_imagem);

            foreach ($user->get('ecm_instrutor_rede_social') as $redeSocial) {
                $redeSocial->descricao = $redeSocial->get('ecm_rede_social')->get('nome');
                $redeSocial->instrutor = $redeSocial->get('ecm_instrutor_id');
                unset($redeSocial->ecm_rede_social);
                unset($redeSocial->ecm_instrutor_id);
            }
            $user->redes_sociais = $user->get('ecm_instrutor_rede_social');
            unset($user->ecm_instrutor_rede_social);

            $user->produto = $user->get('ecm_produto');

            foreach ($user->produto as $produto) {
                $produto->categorias = $produto->get('ecm_tipo_produto');
                unset($produto->ecm_tipo_produto);
            }
        }

        $this->set(compact('retorno'));
    }
}