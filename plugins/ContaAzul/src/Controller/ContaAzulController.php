<?php

namespace ContaAzul\Controller;

use ContaAzul\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Http\Client;
use App\Model\Entity\MdlUser;


class ContaAzulController extends AppController
{

    const CLIENT_ID = "61QQDkBxXyvF7Pqz7MPyoDoEX3HO7rpM";
    const CLIENT_SECRET = "wdM1L9hQIGYIwldyiE6cPBfbJZTPizIy";
    const REDIRECT_URI = "https://ecommerce-local.qisat.com.br/conta-azul/retorno";
    const STATE_KEY = "chave123";
    const URI = "https://api.contaazul.com";

    public function initialize()
    {
        $this->loadModel('Configuracao.EcmConfig');

        parent::initialize();

        $this->convert_date = datefmt_create(
            'pt_BR', 
            \IntlDateFormatter::FULL, 
            \IntlDateFormatter::FULL, 
            date_default_timezone_get(),// 'UTC ,  
            \IntlDateFormatter::GREGORIAN, 
            "y-MM-dd'T'H:mm:ss.SSS'Z'"
        );
    

        $this->token_access = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'conta_azul_token_access'])->first();
        $this->token_refresh = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'conta_azul_token_refresh'])->first();
        $this->token_data = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'conta_azul_token_datacreate'])->first();
        $newAuth = $auth = $check = false;

        if($this->token_access->valor and $this->token_data->valor){
            $dateAtual =  new \DateTime();
            $dateCreate =  new \DateTime($this->token_data->valor);

            if( ($dateAtual->getTimestamp() - $dateCreate->getTimestamp())>3600 ){
                if($this->token_refresh->valor){
                    $newAuth = true;
                    $auth = $this->getAuth($this->token_refresh->valor, true);
                }else{
                    $check = true;
                }
            }else{
                $auth = true;
            }
        }else{
            $check = true;
        }

        if($check && $this->request->url != 'conta-azul'){
            $this->Flash->error(__('Fazer Login!'));
            return $this->redirect(['controller' => false, 'action' => 'index']);
        }

        if($auth){
            if($newAuth){
                $this->token_access = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'conta_azul_token_access'])->first();
                $this->token_refresh = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'conta_azul_token_refresh'])->first();
                $this->token_data = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'conta_azul_token_datacreate'])->first();
            }
        }else if($this->request->url != 'conta-azul'){
            $this->Flash->error(__('Fazer Login!'));
            return $this->redirect(['controller' => false, 'action' => 'index']);
        }else{
            $this->Flash->error(__('Fazer Login!'));
        }
    }
    
    public function index()
    {
        $query = [ 'redirect_uri' => self::REDIRECT_URI, 'client_id' => self::CLIENT_ID, 'scope' => 'sales', 'state' => self::STATE_KEY ];
        $login = (array_key_exists('login', $this->request->query)) ? $this->request->query['login'] : NULL;
        $refresh = (array_key_exists('refresh', $this->request->query)) ? $this->request->query['refresh'] : NULL;
        $redirect = (array_key_exists('redirect', $this->request->query)) ? $this->request->query['redirect'] : NULL;

        if($login){

            try {

                $http = new Client();
                $response = $http->get( self::URI.'/auth/authorize', $query );

                if($response->isRedirect()){
                    $location = str_replace('http://', 'https://', $response->headers['Location']);
                    return $this->redirect($location);
                }else{
                    $this->Flash->error(__('Falha Requisição de Login (Authorize)'));
                }

            } catch (\Exception $e) {
                $this->Flash->error(__('Falha Requisição de Login (Exception - Authorize)'));
            }
        }

        if($refresh){
            if($this->token_refresh->valor){
                $newAuth = true;
                $auth = $this->getAuth($this->token_refresh->valor, true);

                if($auth && $redirect)
                    $this->redirect($redirect);
            }else{
                $this->Flash->error(__('Sem Token Para Refresh (Fazer Login)'));
            }
        }

        $token_access = $this->token_access->valor;
        $token_refresh = $this->token_refresh->valor;
        $token_data = $this->token_data->valor;

        $this->set(compact('token_access', 'token_refresh', 'token_data'));
        $this->set('_serialize', ['token_access', 'token_refresh', 'token_data']);

    }

    public function getAuth($code, $refresh = false)
    {

        $data = ($refresh == false) ? [ 'grant_type' => 'authorization_code', 'redirect_uri' => self::REDIRECT_URI, 'code' => $code ] : [ 'grant_type' => 'refresh_token', 'redirect_uri' => self::REDIRECT_URI, 'refresh_token' => $code ] ;
        $accessToken = base64_encode(self::CLIENT_ID.':'.self::CLIENT_SECRET);

        try {

            $http = new Client(['headers' => ['Authorization' => 'Basic '.$accessToken]]);
            $response = $http->post( self::URI.'/oauth2/token', $data, ['type' =>  'json']);

            if($response->isOk()){
                
                $result = $response->json;
                $date =  new \DateTime();
                $date = $date->format('Y-m-d H:i:s');

                $this->token_access->set('valor', $result['access_token']);
                $this->token_refresh->set('valor', $result['refresh_token']);
                $this->token_data->set('valor',$date);

                $this->EcmConfig->save($this->token_access);
                $this->EcmConfig->save($this->token_refresh);
                $this->EcmConfig->save($this->token_data);

                return true;
            }else{
                $result = $response->json;

                if($response->code == 400){
        
                    $this->Flash->error(__('Falha Requisição Refresh Token (400)'));
        
                    if($result){
                        if(array_key_exists('error_description', $result))
                            $this->Flash->error(__($result["error_description"]));
                        if(array_key_exists('error', $result))
                            $this->Flash->error(__($result["error"]));
                        
                        if(array_key_exists('code', $result))
                            $this->Flash->error(__($result["code"]));
    
                        if(array_key_exists('message', $result))
                            $this->Flash->error(__($result["message"]));
                            
                    }

                }else{
                    $this->Flash->error(__('Falha Requisição Refresh Token ('.$response->code.')'));

                    if($result){
                        if(array_key_exists('error_description', $result))
                            $this->Flash->error(__($result["error_description"]));
                        if(array_key_exists('error', $result))
                            $this->Flash->error(__($result["error"]));
                        
                        if(array_key_exists('code', $result))
                            $this->Flash->error(__($result["code"]));
    
                        if(array_key_exists('message', $result))
                            $this->Flash->error(__($result["message"]));
                    }
                }

                $this->token_access->set('valor', '');
                $this->token_refresh->set('valor', '');
                $this->token_data->set('valor', '');

                $this->EcmConfig->save($this->token_access);
                $this->EcmConfig->save($this->token_refresh);
                $this->EcmConfig->save($this->token_data);
            }

        } catch (\Exception $e) {
            $this->Flash->error(__('Falha Requisição Refresh Token! (Exception)'));
        }

        return false;
    }

    public function callback()
    {
        $code = $this->request->query('code');
        $state = $this->request->query('state');

        if($code && $state && $state == self::STATE_KEY){
            $this->getAuth($code);
        }else if($access_token && $refresh_token){
            $this->Flash->error(__('Falha na Autenticação! ( Retorno sem Dados )'));
        }else{
            $this->Flash->error(__('Falha na Autenticação! ( Retorno sem Dados )'));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function listServices()
    {
        $this->loadModel('Produto.EcmProduto');

        $del = MdlUser::verificarPermissao('delService', $this->request->controller, $this->request->plugin, $this->request->session()->read('Auth.User.permissoes'));
        $set = MdlUser::verificarPermissao('setService', $this->request->controller, $this->request->plugin, $this->request->session()->read('Auth.User.permissoes'));

        try {

            $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);
            $response = $http->get( self::URI.'/v1/services', [ 'size' => 400], ['type' =>  'json']);

            if($response->isOk()){
                $services = $response->json;

                if($services){
                    $services = array_map( function( $val ) {

                                    $produto = $this->EcmProduto->find()
                                    ->select(['id', 'sigla', 'conta_azul'])
                                    ->where(['conta_azul' => $val['id']])
                                    ->first();

                                    $val['produto'] = ($produto) ? $produto->sigla : null;
                                    return $val;
                                }, $services);
                }

            }else if($response->code == 401){
                $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/list-services']]);
            }else{
                $this->Flash->error(__('Falha na Requisição - Lista Serviços'));
            }

        } catch (\Exception $e) {
            $this->Flash->error(__('Falha na Requisição - Lista Serviços (Exception)'));
        }

        $this->set(compact('services', 'del', 'set'));
        $this->set('_serialize', ['services', 'del', 'set']);
    }

    public function listClients()
    {
        $this->loadModel('MdlUser');

        $del = MdlUser::verificarPermissao('delService', $this->request->controller, $this->request->plugin, $this->request->session()->read('Auth.User.permissoes'));
        $set = MdlUser::verificarPermissao('setService', $this->request->controller, $this->request->plugin, $this->request->session()->read('Auth.User.permissoes'));

        try {

            $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);
            $response = $http->get( self::URI.'/v1/customers', [], ['type' =>  'json']);

            if($response->isOk()){
                $clients = $response->json;

                if($clients){

                    $clients = array_map( function( $val ) {

                                    $client = $this->MdlUser->find()
                                                            ->select(['id', 'idnumber'])
                                                            ->contain(['MdlUserDados' => ['fields' => [ 'contaAzul' => 'MdlUserDados.conta_azul']]])
                                                            ->where(['MdlUserDados.conta_azul' => $val['id']])
                                                            ->first();

                                    $val['chave'] = false;
                                    $val['uid'] = false;

                                    if(!($client)){

                                        if(strlen($val['document']) == 11)
                                            $val['document'] = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/","$1.$2.$3-$4",$val['document']);
                                        else
                                            $val['document'] = preg_replace("/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})/","$1.$2.$3/$4-$5", $val['document']);

                                        $client = $this->MdlUser->find()
                                                        ->select(['id', 'idnumber'])
                                                        ->contain(['MdlUserDados' => ['fields' => [ 'cpfcnpj' => 'MdlUserDados.numero']]])
                                                        ->where(['MdlUserDados.numero' => $val['document']])
                                                        ->first();

                                        $val['vincular'] = ($client) ? $client->id : false;
                                    }else{
                                        $val['vincular'] = false;
                                        $val['chave'] = $client->idnumber;
                                        $val['uid'] =  $client->id;

                                        if(strlen($val['document']) == 11)
                                            $val['document'] = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/","$1.$2.$3-$4",$val['document']);
                                        else
                                            $val['document'] = preg_replace("/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})/","$1.$2.$3/$4-$5", $val['document']);

                                    }

                                    
                                    
                                    return $val;
                                }, $clients);
                }
            }else if($response->code == 401){
                $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/list-clients']]);
            }else{
                $this->Flash->error(__('Falha na Requisição - Lista Clientes'));
            }

        } catch (\Exception $e) {
            $this->Flash->error(__('Falha na Requisição - Lista Clientes (Exception)'));
        }

        $this->set(compact('clients', 'del', 'set'));
        $this->set('_serialize', ['clients', 'del', 'set']);
    }

    public function listProducts()
    {

        $this->loadModel('Produto.EcmProduto');
        $this->loadModel('Produto.EcmProdutoEcmAplicacao');

        $del = MdlUser::verificarPermissao('delService', $this->request->controller, $this->request->plugin, $this->request->session()->read('Auth.User.permissoes'));
        $set = MdlUser::verificarPermissao('setService', $this->request->controller, $this->request->plugin, $this->request->session()->read('Auth.User.permissoes'));
        $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);

        try {
            $response = $http->get( self::URI.'/v1/products', [ 'size' => 300], ['type' =>  'json']);
        } catch (\Exception $e) {
            $this->Flash->error(__('Falha na Requisição - Lista Produtos (Exception)'));
        }
        
        if(isset($response) && $response->isOk()){
            $products = $response->json;

            if($products){
                $products = array_map( function( $val ) {

                                            $query = $this->EcmProdutoEcmAplicacao->find()
                                                                                    ->contain(['EcmProdutoAplicacao', 'EcmProduto'])
                                                                                    ->where(['EcmProdutoEcmAplicacao.conta_azul_produto' => $val['id']]);
                                                            
                                            $query->formatResults(function (\Cake\Collection\CollectionInterface $results) {
                                                return $results->map(function ($row) {
                                                    $codigo_tw = $this->EcmProduto->encriptCodigotw($row);
                                                    $row->descricao = $codigo_tw['descricao'];
                                                    $row->codigo_tw = $codigo_tw['codigo_tw'];
                                                    return $row;
                                                });
                                            });

                                            $app = $query->first();
                                            $val['produto'] = ($app) ? $app->ecm_produto->sigla : null;
                                            return $val;
                                            
                                        }, $products);

                    // $products = array_filter($products, function( $val ) {
                    //                             return ($val['category']['id'] == '5770cb25-8d5d-47dc-a62f-9f5bee7ecdfe') ? true : false;
                    //                         });

                                                
            }

        }else if($response->code == 401){
            $this->Flash->error(__('Falha Autenticação! (Token Access)'));
            return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/list-products']]);
        }else{
            $this->Flash->error(__('Falha na Requisição - Lista Produtos'));
        }

        $this->set(compact('products', 'del', 'set'));
        $this->set('_serialize', ['products', 'del', 'set']);
    }

    public function exportClients($ids = null, $return = null)
    {
        $this->loadModel('MdlUser');
        $this->loadModel('MdlUserDados');

        $mensagens = [];
        $retorno = [];
        $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);

        if(isset($ids)) 
            $ids = is_array($ids) ? $ids : [$ids];
        else if(array_key_exists('ids', $this->request->data))
            $ids = json_decode($this->request->data['ids']);
        else
            $mensagens[] = __('Paramentros não informados!');

        foreach ($ids as $id) {
            $usuario = null;
            $result = [ 'id' => $id, 'sucesso' => false, 'mensagens' => []];

            try{
                $usuario = $this->MdlUser->get($id, [ 'contain' => ['MdlUserDados', 'MdlUserEndereco'] ]);
            }catch(RecordNotFoundException $e){
                $result['mensagens'][] = __('Cliente (ID:'.$id.') não localizados!');
            }catch(\Exception $e){
                $result['mensagens'][] = __('Cliente (ID:'.$id.')  não localizados!');
            }

            if($usuario){
                $dados = $this->getDataUser($usuario);
                $response = null;

                try {
                    // GET - VERIFICAR SE JÁ TEM RELAÇÃO
                    if($usuario->mdl_user_dado && $usuario->mdl_user_dado['conta_azul']){
                        $response = $http->get( self::URI.'/v1/customers/'.$usuario->mdl_user_dado['conta_azul'], [], ['type' =>  'json']);
                    }

                    if($response && $response->isOk()){
                        // UPGRADE
                        $response = $http->put( self::URI.'/v1/customers/'.$usuario->mdl_user_dado['conta_azul'], json_encode($dados), ['type' =>  'json']);
                    }else if($response && $response->code == 401){
                        // Redireciona & cancela Ação
                        $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                        return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/list-clients']]);
                    }else{
                        // CREATE
                        $response = $http->post( self::URI.'/v1/customers', json_encode($dados), ['type' =>  'json']);
                    }

                } catch (\Exception $e) {
                    $result['mensagens'][] = __('Falha na Requisição - Exportar Clientes (Exception) - (ID:'.$id.')');
                }

                if($response){
                    if($response->isOk()){
                        $client = $response->json;

                        if($client['id']){
                            $usuario->mdl_user_dado->set('conta_azul', $client['id']);
                            if($this->MdlUserDados->save($usuario->mdl_user_dado)){
                                $result['sucesso'] = true;
                                $result['mensagens'][] = ($usuario->mdl_user_dado && $usuario->mdl_user_dado['conta_azul']) ? __('Cliente Conta Azul ATUALIZADO com sucesso!  (ID:'.$id.' - '.$client['id'].') ') : __('Cliente Conta Azul CRIADO com sucesso! (ID:'.$id.' - '.$client['id'].') ') ;
                            }else{
                                $result['mensagens'][] = __('Falha para atualizar os Dados! (ID:'.$id.' - '.$client['id'].')');
            
                                if($usuario->errors()){
                                    
                                    foreach( $usuario->errors() as $key => $errors){
                                        if(is_array($errors)){
                                            foreach($errors as $error){
                                                $result['mensagens'][] = $key.':'.$error;
                                            }
                                        }else{
                                            $result['mensagens'][] = $errors;
                                        }
                                    }
                                }
                            }
                        }

                    }else{
                        $response = $response->json;
                        $result['mensagens'][] = __('Falha na Requisição - Exportar Clientes (ID:'.$id.')');

                        if($response){
                            if(array_key_exists('error_description', $response))
                                $result['mensagens'][] = __($response["error_description"]);
                            if(array_key_exists('error', $result))
                                $result['mensagens'][] = __($response["error"]);
                            
                            if(array_key_exists('code', $result))
                                $result['mensagens'][] =  __($response["code"]);

                            if(array_key_exists('message', $result))
                                $result['mensagens'][] =  __($response["message"]);
                                
                        }
                    }
                }else
                    $result['mensagens'][] =  __('Atualização não executada! (ID:'.$id.')');
            }

            $retorno[] = $result;
        }
        
        if($return)
            return $retorno;
        else if ($this->request->is('ajax')) {

            $this->RequestHandler->renderAs($this, 'json');
            $this->response->type('application/json');
            $this->set('_serialize', true);
            $this->set(compact('retorno'));
            echo json_encode($retorno);
            die;

        }else{
            if($retorno[0]['sucesso'])
                $this->Flash->success(__('Usuário Exportado para ContaAzul com Sucesso!'));
            else if(count($retorno[0]['mensagens']) || count($mensagens) ){
                foreach($retorno[0]['mensagens'] as $error){
                    $this->Flash->error($error);
                }

                foreach($mensagens as $error){
                    $this->Flash->error($error);
                }
            }else
                $this->Flash->error(__('FALHA!'));

            return $this->redirect( [ 'controller'=>'ContaAzul', 'action' => 'listClients'] );

        }
    }

    public function exportProducts($ids = null, $return = null)
    {
        $this->loadModel('Produto.EcmProduto');
        $this->loadModel('Produto.EcmProdutoEcmAplicacao');

        $mensagens = [];
        $retorno = [];
        $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);

        if(isset($ids)) 
            $ids = is_array($ids) ? $ids : [$ids];
        else if(array_key_exists('ids', $this->request->data))
            $ids = json_decode($this->request->data['ids']);
        else
            $mensagens[] = __('Paramentros não informados!');

        foreach ($ids as $id) {
            $produto = null;
            $result = [ 'id' => $id, 'sucesso' => false, 'mensagens' => []];

            try{

                $query = $this->EcmProdutoEcmAplicacao->find()
                                                    ->contain(['EcmProdutoAplicacao', 'EcmProduto'])
                                                    ->where(['EcmProdutoEcmAplicacao.id' => $id]);
                                                    

                $query->formatResults(function (\Cake\Collection\CollectionInterface $results) {
                                        return $results->map(function ($row) {
                                                                $codigo_tw = $this->EcmProduto->encriptCodigotw($row);
                                                                $row->codigo_tw = $codigo_tw['codigo_tw'];
                                                                $row->descricao = $codigo_tw['descricao'];
                                                            return $row;
                                                        });
                                        });

                $produto = $query->first();

            }catch(RecordNotFoundException $e){
                $result['mensagens'][] = __('Produto (ID:'.$id.') não localizados!');
            }catch(\Exception $e){
                $result['mensagens'][] = __('Produto (ID:'.$id.')  não localizados!');
            }

            if($produto){
                $dados = [];
                $response = null;
                
                $dados['name'] = $produto->get('codigo_tw');
                $dados['value'] = floatval($produto->get('vl_sugerido'));
                $dados['cost'] = floatval(0.0);
                $dados['category_id'] = '5770cb25-8d5d-47dc-a62f-9f5bee7ecdfe';

                try {
                    $response = $http->post( self::URI.'/v1/products', json_encode($dados), ['type' =>  'json']);
                } catch (\Exception $e) {
                    $result['mensagens'][] = __('Falha na Requisição - Exportar Produto (Exception) - (ID:'.$id.')');
                }

                if($response){
                    if($response->isOk()){
                        $product = $response->json;
                        $result['id'] = $id;

                        if($product['id']){
                            $result['uid'] = $product['id'];
                            $produto->set('conta_azul_produto', $product['id']);

                            if($this->EcmProdutoEcmAplicacao->save($produto)){
                                $result['sucesso'] = true;
                                $result['mensagens'][] = __('Produto Conta Azul CADASTRADO com sucesso! (ID:'.$id.' - '. $product['id'].' )');
                            }else{
                                $result['mensagens'][] = __('Falha AO CADASTRAR Produto! (ID:'.$id.' - '. $product['id'].' )');
            
                                if($produto->errors()){
                                    
                                    foreach( $produto->errors() as $key => $errors){
                                        if(is_array($errors)){
                                            foreach($errors as $error){
                                                $result['mensagens'][] = $key.':'.$error;
                                            }
                                        }else{
                                            $result['mensagens'][] = $errors;
                                        }
                                    }
                                }
                            }
                        }

                    }else{
                        $response = $response->json;
                        $result['mensagens'][] = __('Falha na Requisição - Exportar Produto (ID:'.$id.')');

                        if($response){
                            if(array_key_exists('error_description', $response))
                                $result['mensagens'][] = __($response["error_description"]);
                            if(array_key_exists('error', $response))
                                $result['mensagens'][] = __($response["error"]);
                            
                            if(array_key_exists('code', $response))
                                $result['mensagens'][] =  __($response["code"]);

                            if(array_key_exists('message', $response))
                                $result['mensagens'][] =  __($response["message"]);
                                
                        }
                    }
                }else
                    $result['mensagens'][] =  __('Atualização não executada! (ID:'.$id.')');
            }

            $retorno[] = $result;
        }

        if($return)
            return $retorno;
        else if ($this->request->is('ajax')) {

            $this->RequestHandler->renderAs($this, 'json');
            $this->response->type('application/json');
            $this->set('_serialize', true);
            $this->set(compact('retorno'));
            echo json_encode($retorno);
            die;

        }else{
            if($retorno[0]['sucesso'])
                $this->Flash->success(__('Produto Exportado para ContaAzul com Sucesso!'));
            else if(count($retorno[0]['mensagens']) || count($mensagens) ){
                foreach($retorno[0]['mensagens'] as $error){
                    $this->Flash->error($error);
                }

                foreach($mensagens as $error){
                    $this->Flash->error($error);
                }
            }else
                $this->Flash->error(__('FALHA!'));

            return $this->redirect( [ 'controller'=>'ContaAzul', 'action' => 'listProducts'] );
        }
    }


    public function exportServices($ids = null, $return = null)
    {
        $this->loadModel('Produto.EcmProduto');

        $mensagens = [];
        $retorno = [];
        $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);

        if(isset($ids)) 
            $ids = is_array($ids) ? $ids : [$ids];
        else if(array_key_exists('ids', $this->request->data))
            $ids = json_decode($this->request->data['ids']);
        else
            $mensagens[] = __('Paramentros não informados!');

        foreach ($ids as $id) {
            $service = null;
            $result = [ 'id' => $id, 'sucesso' => false, 'mensagens' => []];

            try{
                $service = $this->EcmProduto->get($id);
            }catch(RecordNotFoundException $e){
                $result['mensagens'][] = __('Serviço (ID:'.$id.') não localizados!');
            }catch(\Exception $e){
                $result['mensagens'][] = __('Serviço (ID:'.$id.')  não localizados!');
            }

            if($service){
                $dados = [];
                $response = null;
                
                $dados['name'] = $service->get('sigla') .' - '. $service->get('nome');
                $dados['value'] = floatval($service->get('preco'));
                $dados['cost'] = floatval(0.0);

                try {
                    $response = $http->post( self::URI.'/v1/services', json_encode($dados), ['type' =>  'json']);
                } catch (\Exception $e) {
                    $result['mensagens'][] = __('Falha na Requisição - Exportar Serviço (Exception) - (ID:'.$id.')');
                }

                if($response){
                    if($response->isOk()){
                        $product = $response->json;
                        $result['id'] = $id;

                        if($product['id']){
                            $result['uid'] = $product['id'];
                            $service->set('conta_azul', $product['id']);
                            if($this->EcmProduto->save($service)){
                                $result['sucesso'] = true;
                                $result['mensagens'][] = __('Serviço Conta Azul CADASTRADO com sucesso! (ID:'.$id.' - '. $product['id'].' )');
                            }else{
                                $result['mensagens'][] = __('Falha AO CADASTRAR Serviço! (ID:'.$id.' - '. $product['id'].' )');
            
                                if($service->errors()){
                                    
                                    foreach( $service->errors() as $key => $errors){
                                        if(is_array($errors)){
                                            foreach($errors as $error){
                                                $result['mensagens'][] = $key.':'.$error;
                                            }
                                        }else{
                                            $result['mensagens'][] = $errors;
                                        }
                                    }
                                }
                            }
                        }

                    }else{
                        $response = $response->json;
                        $result['mensagens'][] = __('Falha na Requisição - Exportar Serviço (ID:'.$id.')');

                        if($response){
                            if(array_key_exists('error_description', $response))
                                $result['mensagens'][] = __($response["error_description"]);
                            if(array_key_exists('error', $result))
                                $result['mensagens'][] = __($response["error"]);
                            
                            if(array_key_exists('code', $result))
                                $result['mensagens'][] =  __($response["code"]);

                            if(array_key_exists('message', $result))
                                $result['mensagens'][] =  __($response["message"]);
                                
                        }
                    }
                }else
                    $result['mensagens'][] =  __('Atualização não executada! (ID:'.$id.')');
            }

            $retorno[] = $result;
        }

        if($return)
            return $retorno;
        else if ($this->request->is('ajax')) {

            $this->RequestHandler->renderAs($this, 'json');
            $this->response->type('application/json');
            $this->set('_serialize', true);
            $this->set(compact('retorno'));
            echo json_encode($retorno);
            die;

        }else{
            if($retorno[0]['sucesso'])
                $this->Flash->success(__('Produto Exportado para ContaAzul com Sucesso!'));
            else if(count($retorno[0]['mensagens']) || count($mensagens) ){
                foreach($retorno[0]['mensagens'] as $error){
                    $this->Flash->error($error);
                }

                foreach($mensagens as $error){
                    $this->Flash->error($error);
                }
            }else
                $this->Flash->error(__('FALHA!'));

            return $this->redirect( [ 'controller'=>'ContaAzul', 'action' => 'listProducts'] );
        }
    }

    public function exportSales()
    {
        $this->loadModel('Produto.EcmProduto');
        $this->loadModel('Produto.EcmProdutoEcmAplicacao');
        $this->loadModel('MdlUser');
        $this->loadModel('MdlUserDados');
        $this->loadModel('Carrinho.EcmRecorrencia');
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Carrinho.EcmVenda');
        $this->loadModel('Vendas.DbaVendas');
        $this->loadModel('Vendas.DbaVendasProdutos');
        $this->loadModel('Vendas.DbaVendasServicos');

        $retorno = [];
        $send = [];
        $mensagens   = [ 'error' => [], 'sucesso' => []];
        $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);
        $vendas = null;
        $pedidos = null;
        
        if(array_key_exists('ids', $this->request->data) || array_key_exists('pedidos', $this->request->data)){
            $vendas = json_decode($this->request->data['ids']);
            $pedidos = json_decode($this->request->data['pedidos']);
        }else
            $mensagens['error'] = __('Paramentros não informados!');

        if(count($vendas) > 0){
            $usersExport = $this->EcmVenda->find('list', [
                                                'keyField' => 'mdl_user.id',
                                                'valueField' => 'mdl_user_dado.conta_azul'
                                            ])
                                        ->contain(['MdlUser' => ['MdlUserDados'=> ['joinType' => 'LEFT']]])
                                        ->where(['EcmVenda.id in'=> $vendas, 'MdlUserDados.conta_azul is null']) 
                                        ->toArray();


            $servicesExport = $this->EcmProduto->find('list',[
                                            'keyField' => 'id',
                                            'valueField' => 'conta_azul',
                    'groupField' => function ($e) {

                                    $tipos  = [];
                                    $filter = array_filter($e->get('ecm_tipo_produto'), 
                                                            function($tipo) use (&$tipos) {
                                                                $aux = in_array($tipo->id, [2,10,16,17,32,33,41,47]);
                                                                if($aux) $tipos[ $tipo->id ] = $tipo->nome ;
                                                                return $aux;
                                                          });

                                                         return (  array_key_exists(10, $tipos)
                                                                    || array_key_exists(16, $tipos)
                                                                    || array_key_exists(17, $tipos) 
                                                                    || array_key_exists(32, $tipos) 
                                                                    || array_key_exists(33, $tipos) 
                                                                    || array_key_exists(41, $tipos) 
                                                                    || array_key_exists(47, $tipos) 
                                                                    || array_key_exists(2, $tipos) ) 
                                                                    ? 'service' : 'produto';
                                   
                                }
                                            ])
                    ->select(['EcmProduto.id'])
                    ->contain(['EcmTipoProduto' => ['fields' => [ 'tipo' => 'distinct EcmTipoProduto.id',  'id', 'nome', 'EcmProdutoEcmTipoProduto.ecm_produto_id'], 'conditions' => ['EcmProdutoEcmTipoProduto.ecm_tipo_produto_id in'=> [2,10,16,17,32,33,41,47]]]]) 
                    ->matching('EcmCarrinhoItem.EcmCarrinho.EcmVenda', function($q) use ($vendas){
                        return $q->where(['EcmVenda.id in'=> $vendas]);
                    })
                    ->where(['EcmProduto.conta_azul is null'])
                    ->toArray();
        }
        
        if(count($pedidos) > 0){
            $usersExport2 = $this->DbaVendas->find('list', [
                                            'keyField' => 'mdl_user.id',
                                            'valueField' => 'mdl_user_dado.conta_azul'
                                        ])
                                    ->contain(['MdlUser' => ['MdlUserDados'=> ['joinType' => 'LEFT']]])
                                    ->where(['DbaVendas.pedido in'=> $pedidos, 'MdlUserDados.conta_azul is null']) 
                                    ->toArray();

            $servicesExport2 = $this->DbaVendasServicos->find('list',[ 'keyField' => 'ecm_produto.id', 'valueField' => 'ecm_produto.id'])
                                                        ->contain(['EcmProduto'])
                                                        ->where(['DbaVendasServicos.dba_vendas_pedido in' => $pedidos, 'EcmProduto.conta_azul is null'])
                                                        ->toArray();

            $usersExport = (isset($usersExport)) ? array_replace($usersExport, $usersExport2) : $usersExport2;
        }

        if(count($usersExport)>0){
            $usersExport = array_keys($usersExport);
            $resultClients = $this->exportClients($usersExport, true);
            
            foreach ($resultClients as $resExport) {
                $status = ($resExport['sucesso']) ? 'sucesso' : 'error';
                foreach ($resExport['mensagens'] as $msg) {
                    $mensagens[$status][] = $msg;
                }
            }
        }

        if(isset($servicesExport) && count($servicesExport)>0 && array_key_exists('service',$servicesExport) && count($servicesExport['service'])>0){
            $servicesExport = array_keys($servicesExport['service']);

            if(isset($servicesExport2) && count($servicesExport2) > 0)
                $servicesExport = array_replace($servicesExport, $servicesExport2);

        }else if(isset($servicesExport2) && count($servicesExport2) > 0)
            $servicesExport = $servicesExport2;
        else
            $servicesExport = [];

        if(count($servicesExport)>0){
            $resultServices = $this->exportServices($servicesExport, true);

            foreach ($resultServices as $resExport) {
                $status = ($resExport['sucesso']) ? 'sucesso' : 'error';
                foreach ($resExport['mensagens'] as $msg) {
                    $mensagens[$status][] = $msg;
                }
            }
        }

        // if(isset($productsExport) && count($productsExport)>0){
        //     $productsExport = array_keys($productsExport);
        //     $resultProducts = $this->exportProducts($productsExport, true);

        //     foreach ($resultProducts as $resExport) {
        //         $status = ($resExport['sucesso']) ? 'sucesso' : 'error';
        //         foreach ($resExport['mensagens'] as $msg) {
        //             $mensagens[$status][] = $msg;
        //         }
        //     }
        // }

        // PREPARAR DADOS DA VENDA ECOMMERCE PARA ENVIAR
        if(isset($vendas)){
            foreach ($vendas as $id) {
                $dados = [];
                $venda = null;
                $result = [ 'id' => $id, 'sucesso' => false, 'mensagens' => []];
                $complete = true;

                try{

                    $venda = $this->EcmVenda->get($id, [
                                                        'contain' => [
                                                                        'MdlUser' => ['MdlUserDados'],
                                                                        'EcmTipoPagamento'=> ['EcmFormaPagamento'], 
                                                                        'EcmVendaStatus',
                                                                        'EcmCarrinho' => 
                                                                        
                                                                        [
                                                                            'EcmAlternativeHost', 
                                                                            'EcmCarrinhoItem' => [ 'EcmProduto' => ['EcmTipoProduto'], 
                                                                            'EcmCarrinhoItemEcmProdutoAplicacao' => ['EcmProdutoEcmAplicacao' => ['EcmProduto']],
                                                                            'EcmCarrinhoItemMdlCourse' ]
                                                                         ]
                                                                    ]
                                                    ]);

                }catch(RecordNotFoundException $e){
                    $result['mensagens'][] = __('Venda não LOCALIZADA! (ID '.$id.')');
                    $complete = false;
                }catch(\Exception $e){
                    $result['mensagens'][] = __('Buscar Venda (Exception) (ID:'.$id.') ');
                    $complete = false;
                }

                if($venda){
                    if($venda->ecm_venda_status->status == 'Finalizada'){
                        if(is_null($venda->conta_azul)){

                            $dados['number'] = $id;
                            $dados['emission'] = $this->convert_date->format($venda->get('data')->getTimestamp()); 
                            $dados['status'] = "COMMITTED";
                            $products = [];
                            $services = [];
                            $payment  = [];
                            $installments = [];
                            $carrinho = $venda->get('ecm_carrinho');
                            $user = $venda->get('mdl_user');
                            $total_produtos = 0.00;
                            $total_servicos = 0.00;

                            if($user && $user->mdl_user_dado && $user->mdl_user_dado->conta_azul)
                                $dados['customer_id'] = $user->mdl_user_dado->conta_azul;
                            else{
                                $complete = false;
                                $result['mensagens'][] = __(' Usuário ('.$user->id.') sem Cadastro no ContaAzul - VENDA (ID:'.$id.') NÃO CADASTRADA! ');
                            }

                            foreach($carrinho->ecm_carrinho_item as $item){
                                if($item->status == 'Adicionado'){

                                    $item_produto = $item->get('ecm_produto');
                                    $pacoteAltoQi = array_filter($item_produto->get('ecm_tipo_produto'), function($tipo){ return $tipo->id == 58; }); // produto AltoQi
                                    $produtoAltoQi = array_filter($item_produto->get('ecm_tipo_produto'), function($tipo){ return $tipo->id == 48; }); // produto AltoQi
                                    $item_apps = $item->get('ecm_carrinho_item_ecm_produto_aplicacao');
                                    $item_cursos = $item->get('ecm_carrinho_item_mdl_course');
                                    $this->EcmVenda->EcmCarrinho->EcmCarrinhoItem->setProductsInCourse($item);
                                    if(count($pacoteAltoQi) > 0){
                                        $this->EcmVenda->EcmCarrinho->EcmCarrinhoItem->setAppsInPackageAltoQi($item);
                                    }

                                    if( count($item_cursos) == 0  && count($produtoAltoQi) == 0 ) {

                                        if($item_produto->conta_azul){
                                            $services[] = [
                                                            'description' => $item_produto->sigla, 
                                                            'quantity' => $item->quantidade,
                                                            'service_id' => $item_produto->conta_azul,
                                                            'value' =>  $item->valor_produto_desconto
                                                        ];

                                            $total_servicos += ($item->quantidade * $item->valor_produto_desconto);

                                        }else{
                                            $complete = false;
                                            $result['mensagens'][] = __(' Serviço ('.$item_produto->sigla.') sem Cadastro no ContaAzul - VENDA (ID:'.$id.') NÃO CADASTRADA! ');
                                        }

                                    }else if(count($item_cursos) > 0){
                                        foreach( $item->course_products as $item_curso ){
                                            $item_produto = $item_curso->get('ecm_produto');

                                            if($item_produto->conta_azul){
                                                $services[] = [
                                                                'description' => $item_produto->sigla, 
                                                                'quantity' => $item_curso->quantidade,
                                                                'service_id' => $item_produto->conta_azul,
                                                                'value' =>  $item_curso->valor_produto_desconto
                                                            ];

                                                $total_servicos += ($item_curso->quantidade * $item_curso->valor_produto_desconto);
                                            }else{
                                                $complete = false;
                                                $result['mensagens'][] = __(' Serviço ('.$item_produto->sigla.') sem Cadastro no ContaAzul - VENDA (ID:'.$id.') NÃO CADASTRADA! ');
                                            }
                                        }
                                    }
                                    
                                    if(count($item_apps) > 0 ){
                                        foreach($item_apps as $item_app){
                                            $ecm_produto_ecm_app = $item_app->get('ecm_produto_ecm_aplicacao');
                                            $app = $ecm_produto_ecm_app->get('ecm_produto_aplicacao');
                                            $app_produto = $ecm_produto_ecm_app->get('ecm_produto');

                                            if( !is_null($item_app->valor) && $item_app->valor > 0 ){

                                                $codigo_tw = $this->EcmProduto->encriptCodigotw($ecm_produto_ecm_app);
                                                $ecm_produto_ecm_app->codigo_tw = $codigo_tw['codigo_tw'];

                                                if(  $app->lincenca == 'INDET' &&  $ecm_produto_ecm_app->conta_azul_produto && $ecm_produto_ecm_app->codigo_tw){

                                                      $products[] = [
                                                                    'description' => $ecm_produto_ecm_app->codigo_tw, 
                                                                    'quantity' => $item->quantidade,
                                                                    'product_id' => $ecm_produto_ecm_app->conta_azul_produto,
                                                                    'value' =>  $item_app->valor
                                                                    ];
                                                    
                                                        $total_produtos += ($item->quantidade * $item_app->valor);

                                                    }else if(  ($app->lincenca != 'INDET' && !is_null($ecm_produto_ecm_app->conta_azul_servico)) || (!is_null($ecm_produto_ecm_app->conta_azul_servico) && $app->aplicacao == 'FLEX' )){

                                                    $services[] = [
                                                        'description' => $ecm_produto_ecm_app->codigo_tw, 
                                                        'quantity' => $item->quantidade,
                                                        'service_id' => $ecm_produto_ecm_app->conta_azul_servico,
                                                        'value' =>  $item_app->valor
                                                        ];

                                                    $total_produtos += ($item->quantidade * $item_app->valor);

                                                } else {
                                                    $complete = false;
                                                    $result['mensagens'][] = __(' Produto ('.$ecm_produto_ecm_app->codigo_tw.') sem Cadastro no ContaAzul - VENDA (ID:'.$id.') NÃO CADASTRADA! ');
                                                }
                                            }
                                        }
                                    }
                                  
                                }
                            }
                            
                            if(count($products) > 0)
                                $dados['products'] = $products;

                            if(count($services) > 0)
                                $dados['services'] = $services;

                            $iss_p = $total_produtos * 0.02; // 2% valor ISS para produtos ( desenvolvimento de Softwares )
                            $iss_s = $total_servicos * 0.03; // 3% valor ISS para serviços ( Treinamento )
                            $iss_total = $iss_p + $iss_s;
                            $iss = ($iss_total/($total_produtos+$total_servicos))*100;
                            $dados['notes'] = 'Cálculo ISS: R$'.   number_format($iss_total, 2, '.', '') . ' ('.  number_format($iss, 2, '.', ''). '%)';
                    
                            if($venda->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->tipo == 'cartao_recorrencia'){
                
                                $payment['type'] = 'TIMES';
                                $recorrencias = $this->EcmRecorrencia->find('all')
                                                                    ->contain(['EcmTipoPagamento' => ['EcmFormaPagamento'], 'EcmVenda', 'EcmTransacao' =>['EcmTipoPagamento' => ['EcmFormaPagamento']] ])
                                                                    ->where(['EcmRecorrencia.ecm_venda_id' => $id]);

                                $venc_data = [];
                                $pago = false;
                                $data_compra = \DateTime::createFromFormat('Y-m-d', $venda->data->format('Y-m-d')); 
                                $datanow = new \DateTime();

                                for ($i=1; $i <= $venda->numero_parcelas ; $i++) { 
                                    $data_compra->modify('+1 month');
                                    $valor = ($i==1)? $carrinho->calcularParcela($venda->numero_parcelas, true) : $carrinho->calcularParcela($venda->numero_parcelas);
                                    
                                    if($data_compra > $datanow)
                                        array_push($installments, [ 'number'=> $i, 'value' => $valor, 'due_date' => $this->convert_date->format($data_compra->getTimestamp()), 'status' => 'PENDING' ]);
                                    else
                                        array_push($installments, [ 'number'=> $i, 'value' => $valor, 'due_date' => $this->convert_date->format($data_compra->getTimestamp())]);
                                }

                                foreach ($recorrencias as $rec) {
                                    if(count($rec->ecm_transacao)){
                                        array_map(function($trans) use (&$pago){ 
                                                        $status = $trans->getStatus($trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller); 
                                                            if($status=='paga' || $status=='cancelada') 
                                                                $pago = true; 
                                                    }, $rec->ecm_transacao);
                                    }
                                }

                                if($pago)
                                    $payment['installments'] = $installments;
                
                            }else if($venda->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->tipo == 'boleto'){
                                
                                $payment['type'] = 'CASH';
                                $boletos = $this->EcmVenda->EcmVendaBoleto->find('all')
                                                                            ->select(['EcmVendaBoleto.id',  'EcmVendaBoleto.parcela', 'EcmVendaBoleto.data', 'EcmVendaBoleto.status'])
                                                                            ->where(['EcmVendaBoleto.ecm_venda_id' => $id]);
                                                                            // STATUS DO BOLETO DE SER PAGO
                
                                // MELHORAR BOLETO - PARA MAIS BOLETOS POR VENDA
                                if($boletos->count() == 1){
                                        foreach ($boletos as $boleto) {
                                            array_push($installments, [
                                                                        'number'=> $boleto->parcela, 
                                                                        'value' => $carrinho->calcularTotal(), // VALOR DEVE SER NO BOLETO
                                                                        'due_date' => $this->convert_date->format($boleto->get('data')->getTimestamp()) ]);
                                        }
                                }
                
                                $payment['installments'] = $installments;
                
                            }else if($venda->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->tipo == 'cartao' || $venda->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->tipo == 'checkout'){
                                
                                $payment['type'] = 'TIMES';                    
                                $transacoes = $this->EcmTransacao->find('all')
                                                                ->contain(['EcmTipoPagamento' => ['EcmFormaPagamento']])
                                                                ->where([ 'EcmTransacao.ecm_venda_id' => $id ]);
                
                                foreach ($transacoes as $trans) {
                                    $trans_status = $trans->getStatus($trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller);

                                    if( $trans_status == 'paga' || $trans_status == 'estorno'){
                
                                        $status = ( $trans_status == 'estorno' ) ? 'CANCELED': '';
                                        $data_envio = \DateTime::createFromFormat('Y-m-d', $trans->data_envio->format('Y-m-d')); 
                                        $datanow = new \DateTime();
                
                                        for ($i=0; $i < $venda->numero_parcelas; $i++) { 
                                            $data_envio->modify('+1 month');

                                            if($i > 0) { 
                                                $valor = $venda->get('ecm_carrinho')->calcularParcela($venda->numero_parcelas) ;
                                            }else{
                                                $valor = $venda->get('ecm_carrinho')->calcularParcela($venda->numero_parcelas, true) ;
                                                $data_envio->modify('+2 day');
                                            }
                                            
                                            $dados_venc = [ 'number'=> ($i+1), 'value' => $valor, 'due_date' => $this->convert_date->format($data_envio->getTimestamp())];

                                            if(empty($status) && $data_envio > $datanow)
                                                $status = 'PENDING';
                
                                            if(!empty($status))
                                                $dados_venc['status'] = $status;
                
                                            array_push($installments, $dados_venc);

                                            if($i == 0) 
                                                $data_envio->modify('-2 day');
                
                                        }
                                    }
                                }
                                
                                $payment['installments'] = $installments;
                            }else{
                                $complete = false;
                                $result['mensagens'][] = __('SEM FORMA DE PAGAMENTO2! (ID '.$venda->id.')');
                            }
                    
                            $dados['payment'] = $payment;

                        }else{
                            $complete = false;
                            $result['mensagens'][] = __('Venda JÁ CADASTRADA! (ID '.$venda->conta_azul.')');
                        }
                    }else{
                        $complete = false;
                        $result['mensagens'][] = __('Venda NÃO FINALIZADA! (ID '.$venda->id.')');
                    }
                }

                if($complete)
                    $send[$id] = $dados;

                $retorno[$id] = $result;
            }
        }

        // PREPARAR DADOS DOS PEDIDOS TOP PARA ENVIAR
        if(isset($pedidos)){
            foreach ($pedidos as $id) {

                $venda = $this->DbaVendas->get($id, [ 'contain' => [
                                                                'MdlUser' => ['MdlUserDados'=> ['joinType' => 'LEFT']],
                                                                'DbaVendasProdutos' => ['EcmProduto', 'sort' => ['DbaVendasProdutos.sigla' => 'DESC']], 
                                                                'DbaVendasServicos' => ['EcmProduto']
                                                        ]]);
                $dados = [];
                $result = [ 'id' => $venda->pedido, 'sucesso' => false, 'mensagens' => []];
                $complete = true;

                if(is_null($venda->conta_azul)){

                    $dados['number'] = $venda->pedido;
                    $dados['emission'] = $this->convert_date->format($venda->get('data_venda')->getTimestamp()); 
                    $dados['status'] = "COMMITTED";
                    $products = [];
                    $services = [];
                    $countItens = 0;
                    $somaItens = 0;
                    $somaVenc = 0;
                    $total_produtos = 0.00;
                    $total_servicos = 0.00;
                    $payment  = [];
                    $installments = [];
                    $user = $venda->get('mdl_user');

                    if($user && $user->mdl_user_dado && $user->mdl_user_dado->conta_azul)
                        $dados['customer_id'] = $user->mdl_user_dado->conta_azul;
                    else{
                        $complete = false;
                        $result['mensagens'][] = __(' Usuário ('.$user->id.') sem Cadastro no ContaAzul - VENDA (ID:'.$venda->pedido.') NÃO CADASTRADA! ');
                    }

                    $this->DbaVendas->searchProductsApps($venda);

                    foreach ($venda->dba_vendas_produtos as $dba_prod) {

                        if(isset($dba_prod->ecm_produto_ecm_aplicacao) && count($dba_prod->ecm_produto_ecm_aplicacao) > 0){

                            foreach ($dba_prod->ecm_produto_ecm_aplicacao as $app) {
                                if($dba_prod->valor > 0){
                                    if($venda->tipo == 'VENDI' && $app->conta_azul_produto){
                                        $countItens++;
                                        $somaItens += $dba_prod->valor;
                                        $products[] = [
                                                        'description' => $app->codigo_tw, 
                                                        'quantity' => 1,
                                                        'product_id' => $app->conta_azul_produto,
                                                        'value' =>  $dba_prod->valor
                                                    ];

                                        $total_produtos += $dba_prod->valor;

                                    }else if($app->conta_azul_servico){
                                        $countItens++;
                                        $somaItens += $dba_prod->valor;
                                        $services[] = [
                                            'description' =>  $app->codigo_tw,
                                            'quantity' => 1,
                                            'service_id' =>  $app->conta_azul_servico,
                                            'value' =>  $dba_prod->valor
                                        ];

                                        $total_produtos += $dba_prod->valor;

                                    }else{
                                        $complete = false;
                                        $result['mensagens'][] = __(' Aplicação ('.$app->codigo_tw.') sem Cadastro no ContaAzul - PROPOSTA ('.$venda->pedido.') NÃO CADASTRADA! ');
                                    }
                                }
                            }
                        }
                    }

                    foreach ($venda->dba_vendas_servicos as $servico) {
                        if($servico->valor > 0){
                            if($servico->ecm_produto->conta_azul){
                                $countItens++;
                                $somaItens += $servico->valor;
                                $services[] = [
                                                'description' => $servico->ecm_produto->sigla, 
                                                'quantity' => 1,
                                                'service_id' => $servico->ecm_produto->conta_azul,
                                                'value' => $servico->valor
                                            ];

                                // precisa separar o suportes ainda
                                $total_servicos += $servico->valor;
                            }else{
                                $complete = false;
                                $result['mensagens'][] = __(' Serviço ('.$servico->sigla.') sem Cadastro no ContaAzul - PROPOSTA ('.$venda->pedido.') NÃO CADASTRADA! ');
                            }
                        }
                    }

                    $payment['type'] =  ($venda->parcelas > 1) ? 'TIMES' : 'CASH';
                    $venc_data = [];
                    $data_compra = \DateTime::createFromFormat('Y-m-d', $venda->data_venda->format('Y-m-d')); 
                    $datanow = new \DateTime();
					$valor = ($venda->valor / $venda->parcelas);

                    for ($i=1; $i <= $venda->parcelas ; $i++) { 
                        $data_compra->modify('+1 month');
                        $somaVenc += $valor;
                        
                        if($data_compra > $datanow)
                            array_push($installments, [ 'number'=> $i, 'value' => $valor, 'due_date' => $this->convert_date->format($data_compra->getTimestamp()), 'status' => 'PENDING' ]);
                        else
                            array_push($installments, [ 'number'=> $i, 'value' => $valor, 'due_date' => $this->convert_date->format($data_compra->getTimestamp())]);
                    }

                    $payment['installments'] = $installments;
                    $dados['payment'] = $payment;

                    // Incluir valor do frete no valor do itens do produto
                    if($venda->valor_frete > 0 && ($somaVenc != $somaItens) && $countItens > 0){


                            $valor  = floor(($venda->valor_frete / $countItens) * 100) * .01;
                            $diferenca = $venda->valor_frete - ($valor * $countItens);

                            if($diferenca > 0){
                                $countItens = 0;

                                if(count($products) > 0){
                                    $products = array_map( function( $pro ) use ($valor, &$countItens, $diferenca) {

                                        if($countItens == 0){
                                            $valor = $valor + $diferenca;
                                            $countItens++;
                                        }

                                        $pro['value'] +=  number_format($valor, 2, '.', '');
                                        return $pro;
                                    }, $products);
                                }
    
                                if(count($services) > 0){
                                    $services = array_map( function( $serv ) use ($valor, &$countItens, $diferenca) {

                                        if($countItens == 0){
                                            $valor = $valor + $diferenca;
                                            $countItens++;
                                        }

                                        $serv['value'] +=  number_format($valor, 2, '.', '');
                                        return $serv;
                                    }, $services);
                                }

                            }else{
                                $valor = ($venda->valor_frete / $countItens);
                            
                                if(count($products) > 0){
                                    $products = array_map( function( $pro ) use ($valor) {
                                        $pro['value'] +=  number_format($valor, 2, '.', '');
                                        return $pro;
                                    }, $products);
                                }
    
                                if(count($services) > 0){
                                    $services = array_map( function( $serv ) use ($valor) {
                                        $serv['value'] +=  number_format($valor, 2, '.', '');
                                        return $serv;
                                    }, $services);
                                }
                            }
                    }

                    if(count($products) > 0)
                        $dados['products'] = $products;

                    if(count($services) > 0)
                        $dados['services'] = $services;

                    $iss_p = $total_produtos * 0.02; // 2% valor ISS para produtos ( desenvolvimento de Softwares )
                    $iss_s = $total_servicos * 0.03; // 3% valor ISS para serviços ( Treinamento )
                    $iss_total = $iss_p + $iss_s;
                    $iss = ($iss_total/($total_produtos+$total_servicos))*100;
                    $dados['notes'] = 'Cálculo ISS: R$'.   number_format($iss_total, 2, '.', '') . ' ('.  number_format($iss, 2, '.', ''). '%)';

                }else{
                    $complete = false;
                    $result['mensagens'][] = __('PROPOSTA JÁ CADASTRADA! (Proposta '.$venda->pedido.' - UID '.$venda->conta_azul.')');
                }

                if($complete)
                    $send[$venda->pedido] = $dados;

                $retorno[$venda->pedido] = $result;
            }
        }

        // echo '<br> mensagens:';
        // var_dump($mensagens);

        // echo '<pre> send:';
        // var_dump($send);

        // echo '<br> retorno:';
        // var_dump($retorno);
        // die;

        // ENVIAR VENDA ECOMMERCE E/OU PEDIDOS TOP
        foreach ($send as $id => $dados) {
            $result = $retorno[$id];
            $dba = false;

            try {
                $response = $http->post( self::URI.'/v1/sales/', json_encode($dados), ['type' =>  'json']);
            } catch (\Exception $e) {
                $result['mensagens'][] = __('Falha na Requisição - Exportar Venda/Proposta (Exception - ID:'.$id.')');
            }

            try{
                $venda = $this->EcmVenda->get($id);
            }catch(RecordNotFoundException $e){
                $venda = $this->DbaVendas->get($id);
                $dba = true;
            }
    
            if($response && $response->isOk()){
                $sale = $response->json;

                if($sale['id']){
                    $result['uid'] = $sale['id'];

                    $venda->set('conta_azul', $sale['id']);

                    if($dba) 
                        $save = $this->DbaVendas->save($venda);
                    else
                        $save = $this->EcmVenda->save($venda);

                    if($save){
                        $result['sucesso'] = true;
                        $result['mensagens'][] = __('Venda/Proposta ContaAzul CADASTRADA com sucesso! (ID:'.$id.' - '. $sale['id'].' )');
                    }else{
                        $result['mensagens'][] = __('Falha AO CADASTRAR VENDA! (ID:'.$id.' - '. $sale['id'].' )');
    
                        if($venda->errors()){
                            
                            foreach( $venda->errors() as $key => $errors){
                                if(is_array($errors)){
                                    foreach($errors as $error){
                                        $result['mensagens'][] = $key.':'.$error;
                                    }
                                }else{
                                    $result['mensagens'][] = $errors;
                                }
                            }
                        }
                    }
                }
                
            }else if($response && $response->code == 401){
                $result['mensagens'][] = __('Falha na Requisição - Exportar Venda (Token Access - Ação Cancelada) - (ID:'.$id.')');
            }else{
                $resp = $response->json;
                $result['mensagens'][] = __('Falha na Requisição - Exportar Venda (ID:'.$id.')');
    
                if($resp){
                    if(array_key_exists('error_description', $resp))
                        $result['mensagens'][] = __($resp["error_description"]);
                    if(array_key_exists('error', $resp))
                        $result['mensagens'][] = __($resp["error"]);
    
                    if(array_key_exists('message', $resp))
                        $result['mensagens'][] = __($resp["message"]);
                        
                }
            }

            $retorno[$id] = $result;
        }

        // SHOW MENSAGENS
        foreach ($mensagens['sucesso'] as $msg) {
            $this->Flash->success($msg);
        }

        foreach ($mensagens['error'] as $msg) {
            $this->Flash->error($msg);
        }

        foreach ($retorno as $result) {
            if($result['sucesso']){
                foreach ($result['mensagens'] as $msg) {
                    $this->Flash->success($msg);
                }
            }else{
                foreach ($result['mensagens'] as $msg) {
                    $this->Flash->error($msg);
                }
            }
        }

        return $this->redirect(\Cake\Routing\Router::url([
                                        'controller' => false,
                                        'plugin' => 'Vendas',
                                        'action' => 'index'
                                        ]));

    }

    private function getDataUser($user)
    {
        $dados = [];
        if(!is_null($user)){
            $dados['name'] = $user->firstname.' '.$user->lastname;
            $dados['email'] = $user->email;

            if($user->mdl_user_dado['tipousuario'])
                $dados['person_type'] = ($user->mdl_user_dado['tipousuario'] == 'fisico') ? 'NATURAL' : 'LEGAL';
            
            if($user->mdl_user_dado['numero'])
                $dados['document'] = preg_replace("/[^0-9]/", "", $user->mdl_user_dado['numero']);

            if($user->mdl_user_dado['phone1'])
                $dados['mobile_phone'] = $user->mdl_user_dado['phone1'];

            if($user->mdl_user_dado['tipo_inscricao_estadual']){
                if($dados['state_registration_type'] == 'Contribuinte'){
                    $dados['state_registration_type'] = 'CONTRIBUTOR';
                }else if($dados['state_registration_type'] == 'Contribuinte Isento'){
                    $dados['state_registration_type'] = 'IMMUNE_CONTRIBUTOR';
                }else if($dados['state_registration_type'] == 'Nao Contribuinte'){
                    $dados['state_registration_type'] = 'NO_CONTRIBUTOR';
                }
            }

            if($user->mdl_user_dado['numero_inscricao_estadual'])
                $dados['state_registration_number'] = $user->mdl_user_dado['numero_inscricao_estadual'];

            if($user->mdl_user_dado['numero_inscricao_municipal'])
                $dados['city_registration_number'] = $user->mdl_user_dado['numero_inscricao_municipal'];

            if($user->mdl_user_endereco){
                $endereco = [];

                if($user->mdl_user_endereco['cep'])
                    $endereco['zip_code'] = $user->mdl_user_endereco['cep'];
                
                if($user->address)
                    $endereco['street'] = $user->address;

                if($user->mdl_user_endereco['number'])
                    $endereco['number'] = $user->mdl_user_endereco['number'];
                
                if($user->mdl_user_endereco['complement'])
                    $endereco['complement'] = $user->mdl_user_endereco['complement'];
            
                if($user->mdl_user_endereco['district'])
                    $endereco['neighborhood'] = $user->mdl_user_endereco['district'];
                
                if(count($endereco) > 0)
                    $dados['address'] = $endereco;
            }
        }
        return $dados;
    }

    public function delService($id)
    {
        $this->loadModel('Produto.EcmProduto');
        $this->loadModel('Produto.EcmProdutoEcmAplicacao');

        if($id){

            try {

                $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);
                $response = $http->delete( self::URI.'/v1/services/'.$id);

                if($response->code == 204){

                    $produto = $this->EcmProduto->find()
                                                    ->select(['id', 'sigla', 'conta_azul'])
                                                    ->where(['conta_azul' => $id])
                                                    ->first();

                    if($produto){
                        $produto->set('conta_azul', NULL);
                        $this->EcmProduto->save($produto);
                    }

                    $produtos = $this->EcmProdutoEcmAplicacao->find()
                                                            ->contain(['EcmProdutoAplicacao'])
                                                            ->where(['conta_azul_servico' => $id])
                                                            ->toArray();

                    if($produtos && count($produtos) > 0){
                        foreach ($produtos as $produto) {
                            $produto->set('conta_azul_servico', NULL);
                            $this->EcmProdutoEcmAplicacao->save($produto);
                        }
                    }

                    $this->Flash->success(__('Serviço Conta Azul Excluido com sucesso!'));
                }else if($response->code == 404){
                    $this->Flash->error(__('Serviço Conta Azul não encontrado!'));
                }else if($response->code == 401){
                    $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                    return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/list-services']]);
                }else{
                    $this->Flash->error(__('Falha na Requisição - Excluir Serviço'));
                }
    
            } catch (\Exception $e) {
                $this->Flash->error(__('Falha na Requisição - Excluir Serviço (Exception)'));
            }

        }else{
            $this->Flash->error(__('Sem Parametro para Ação!'));
        }

        return $this->redirect(['action' => 'listServices']);
    }

    public function delProduct($id)
    {
        $this->loadModel('Produto.EcmProduto');
        $this->loadModel('Produto.EcmProdutoEcmAplicacao');

        if($id){

            try {

                $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);
                $response = $http->delete( self::URI.'/v1/products/'.$id);

                if($response->code == 204){

                    $produtos = $this->EcmProdutoEcmAplicacao->find()
                                                            ->contain(['EcmProdutoAplicacao'])
                                                            ->where(['conta_azul_produto' => $id])
                                                            ->toArray();

                    if($produtos && count($produtos) > 0){
                        foreach ($produtos as $produto) {
                            $produto->set('conta_azul_produto', NULL);
                            $this->EcmProdutoEcmAplicacao->save($produto);
                        }
                    }

                    $this->Flash->success(__('Produto Conta Azul Excluido com sucesso!'));
                }else if($response->code == 404){
                    $this->Flash->error(__('Produto Conta Azul não encontrado!'));
                }else if($response->code == 401){
                    $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                    return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/list-products']]);
                }else{
                    $this->Flash->error(__('Falha na Requisição - Excluir Produto'));
                }
    
            } catch (\Exception $e) {
                $this->Flash->error(__('Falha na Requisição - Excluir Produto (Exception)'));
            }

        }else{
            $this->Flash->error(__('Sem Parametro para Ação!'));
        }

        return $this->redirect(['action' => 'listProducts']);
    }

    public function delClient($id)
    {
        $this->loadModel('MdlUser');
        $this->loadModel('MdlUserDados');

        if($id){

            try {

                $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);
                $response = $http->delete( self::URI.'/v1/customers/'.$id);

                if($response->code == 204){

                    $client = $this->MdlUser->find()
                                            ->select(['id', 'idnumber'])
                                            ->select($this->MdlUser->MdlUserDados)
                                            ->contain(['MdlUserDados'])
                                            ->where(['MdlUserDados.conta_azul' => $id])
                                            ->first();

                    if($client){
                        $client->mdl_user_dado->set('conta_azul', NULL);
                        
                        if($this->MdlUserDados->save($client->mdl_user_dado))
                            $this->Flash->success(__('Relação deletada com Sucesso!'));
                        else{
                            $this->Flash->error(__('Falha para atualizar os Dados!'));
        
                            if($client->errors()){
                                $error_msg = [];
                                foreach( $client->errors() as $key => $errors){
                                    if(is_array($errors)){
                                        foreach($errors as $error){
                                            $error_msg[] = $key.':'.$error;
                                        }
                                    }else{
                                        $error_msg[] = $errors;
                                    }
                                }
        
                                if(!empty($error_msg)){
                                    $this->Flash->error(
                                        __("Favor verificar os erro(s):".implode($error_msg))
                                    );
                                }
                            }
                        }

                    }

                    $this->Flash->success(__('Cliente Conta Azul Excluido com sucesso!'));
                }else if($response->code == 404){
                    $this->Flash->error(__('Cliente Conta Azul não encontrado!'));
                }else if($response->code == 401){
                    $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                    return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/list-clients']]);
                }else{
                    $this->Flash->error(__('Falha na Requisição - Excluir Cliente'));
                }
    
            } catch (\Exception $e) {
                $this->Flash->error(__('Falha na Requisição - Excluir Cliente (Exception)'));
            }

        }else{
            $this->Flash->error(__('Sem Parametro para Ação!'));
        }

        return $this->redirect(['action' => 'listClients']);
    }

    public function setService($id = null)
    {
        $this->loadModel('Produto.EcmProduto');
        $this->loadModel('Produto.EcmProdutoEcmAplicacao');
        
        $dados = [];
        $servico = NULL;
        
        $where = ['EcmProduto.habilitado' => 'true', 'EcmProduto.conta_azul is null' ];
        $name = (array_key_exists('name', $this->request->data)) ? $this->request->data['name'] : NULL;
        $value = (array_key_exists('value', $this->request->data)) ? ($this->request->data['value']) : NULL;
        $cost = (array_key_exists('cost', $this->request->data)) ? ($this->request->data['cost']) : NULL;
        $curso = (array_key_exists('curso', $this->request->data)) ? ($this->request->data['curso']) : NULL;
        $produtos = (array_key_exists('produtos', $this->request->data)) ? ($this->request->data['produtos']) : NULL;
        
        $http = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token_access->valor]]);

        if($id){
            $where = ['EcmProduto.habilitado' => 'true'];

            try {
                $response = $http->get( self::URI.'/v1/services/'.$id);
            } catch (\Exception $e) {
                $this->Flash->error(__('Falha na Requisição - Buscar Serviço (Exception)'));
            }
    
            if($response->isOk()){
                $servico = $response->json;

                if($this->request->is('post')){

                    $data = [];
                    $update = false;
                    if($servico['name'] != $name){
                        $data['name'] = $name;
                        $update = true;
                    }
                    if($servico['value'] != $value){
                        $data['value'] = $value;
                        $update = true;
                    }
                    if($servico['cost'] != $cost){
                        $data['cost'] = $cost;
                        $update = true;
                    }
                    if($update){
                        try {
                            $response = $http->put( self::URI.'/v1/services/'.$id, json_encode($data), ['type' =>  'json']);
                        } catch (\Exception $e) {
                            $this->Flash->error(__('Falha na Requisição - Atualizar Serviço'));
                        }

                        if($response->isOk()){
                            $this->Flash->success(__('Serviço Conta Azul Atualizado com sucesso!'));
                        }else if($response->code == 401){
                            $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                            return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/service/'.$id]]);
                        }else{
                            $result = $response->json;
                            if($result){
                                if( $result["error_description"])
                                    $this->Flash->error(__($result["error_description"]));
                                else if( $result["error"])
                                    $this->Flash->error(__($result["error"]));
                                else
                                    $this->Flash->error(__('Falha na Requisição - Atualizar Serviço'));
                            }else{
                                $this->Flash->error(__('Falha na Requisição - Atualizar Serviço'));
                            }
                        }
                    }

                    /// BEGIN ATUALIZAR RELAÇÃO COM CURSOS
                    if($curso)
                        $curso = $this->EcmProduto->get($curso);

                    $prodOld = $this->EcmProduto->find()
                                                ->select(['id', 'sigla', 'conta_azul'])
                                                ->where(['conta_azul' => $id])
                                                ->first();

                    if(!$prodOld && $curso){
                        $curso->set('conta_azul', $id);
                        $this->EcmProduto->save($curso);
                    }elseif ($prodOld && $produto && ($curso->id != $prodOld->id)){
                        $prodOld->set('conta_azul', NULL);
                        $this->EcmProduto->save($prodOld);
                        $curso->set('conta_azul', $id);
                        $this->EcmProduto->save($curso);
                    }else if($prodOld && !$curso){
                        $prodOld->set('conta_azul', null);
                        $this->EcmProduto->save($prodOld);
                    }
                    /// END ATUALIZAR RELAÇÃO COM CURSOS
                    
                    /// BEGIN ATUALIZAR RELAÇÃO COM PRODUTO ALTOQI (SERVIÇOS LTEMP)
                    if(isset($produtos) && count($produtos['_ids']) > 0)
                        $produtos = $this->EcmProdutoEcmAplicacao->find('all')->where(['id in' => $produtos['_ids']])->toList();

                    $prodOld = $this->EcmProdutoEcmAplicacao->find('all')->where(['EcmProdutoEcmAplicacao.conta_azul_servico' => $id])->toList();

                    if(count($prodOld) == 0 && count($produtos) > 0){
                        foreach ($produtos as $app) {
                            $app->set('conta_azul_servico', $id);
                            $this->EcmProdutoEcmAplicacao->save($app);
                        }
                    }elseif (count($prodOld) > 0 && count($produtos) > 0){

                        $prodsAdd = [];
                        $prodsDel = [];

                        foreach ($produtos as $app) {
                            $check = array_filter($prodOld, function($prod) use ($app){ return $prod->id == $app->id; });
                            if(count($check) == 0)
                                array_push($prodsAdd, $app);
                        }

                        foreach ($prodOld as $app) {
                            $check = array_filter($produtos, function($prod) use ($app){ return $prod->id == $app->id; });
                            if(count($check) == 0)
                                array_push($prodsDel, $app);
                        }

                        foreach ($prodsAdd as $app) {
                            $app->set('conta_azul_servico', $id);
                            $this->EcmProdutoEcmAplicacao->save($app);
                        }

                        foreach ($prodsDel as $app) {
                            $app->set('conta_azul_servico', NULL);
                            $this->EcmProdutoEcmAplicacao->save($app);
                        }
                    }else if(count($prodOld) > 0 && count($produto) == 0){
                        foreach ($prodOld as $app) {
                            $app->set('conta_azul_servico', null);
                            $this->EcmProdutoEcmAplicacao->save($app);
                        }
                    }
                    /// END ATUALIZAR RELAÇÃO COM PRODUTO ALTOQI (SERVIÇOS LTEMP)
                    return $this->redirect(['action' => 'listServices']);
                }else{
                    $curso = $this->EcmProduto->find()
                                                ->select(['id', 'sigla', 'conta_azul'])
                                                ->where(['conta_azul' => $id])
                                                ->first();

                    $setSelects = $this->EcmProdutoEcmAplicacao->find('list')
                                                                ->where(['conta_azul_servico' => $id])
                                                                ->toArray();
                    
                    $setSelects = json_encode(array_values($setSelects));
                }
            }else if($response->code == 404){
                $this->Flash->error(__('Serviço Conta Azul não encontrado!'));
                return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/list-services']]);
            }else if($response->code == 401){
                $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/service/'.$id]]);
            }else{
                $result = $response->json;

                $this->Flash->error(__('Falha na Requisição - Buscar Serviço'));

                if($result){
                    if(array_key_exists('error_description', $result))
                        $this->Flash->error(__($result["error_description"]));
                    if(array_key_exists('error', $result))
                        $this->Flash->error(__($result["error"]));
                    
                    if(array_key_exists('code', $result))
                        $this->Flash->error(__($result["code"]));

                    if(array_key_exists('message', $result))
                        $this->Flash->error(__($result["message"]));
                        
                }

                return $this->redirect(['action' => 'listServices']);
            }

        }else if($this->request->is('post')){
            $data = [ 'name' => $name, 'value' => $value, 'cost' => $cost];

            try {
                $response = $http->post( self::URI.'/v1/services/', json_encode($data), ['type' =>  'json']);
            } catch (\Exception $e) {
                $this->Flash->error(__('Falha na Requisição Criar Serviço! (Exception)'));
            }

            if($response->isOk()){
                $servico = $response->json;
                if($curso)
                    $curso = $this->EcmProduto->get($curso);

                if($curso && $servico['id']){
                    $curso->set('conta_azul', $servico['id']);
                    $this->EcmProduto->save($curso);
                }

                if(isset($produtos) && count($produtos['_ids']) > 0){
                    $produtos = $this->EcmProdutoEcmAplicacao->find('all')->where(['id in' => $produtos['_ids']]);

                    if(count($produtos) > 0){
                        foreach ($produtos as $app) {
                            $app->set('conta_azul_servico', $servico['id']);
                            $this->EcmProdutoEcmAplicacao->save($app);
                        }
                    }
                }

                $this->Flash->success(__('Serviço Conta Azul Criado com sucesso!'));
                return $this->redirect(['action' => 'listServices']);
            }else if($response->code == 401){
                $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/service']]);
            }else{
                $result = $response->json;
                $this->Flash->error(__('Falha na Requisição - Criar Serviço'));

                if($result){
                    if(array_key_exists('error_description', $result))
                        $this->Flash->error(__($result["error_description"]));
                    if(array_key_exists('error', $result))
                        $this->Flash->error(__($result["error"]));
                    
                    if(array_key_exists('code', $result))
                        $this->Flash->error(__($result["code"]));

                    if(array_key_exists('message', $result))
                        $this->Flash->error(__($result["message"]));
                        
                }
            }
        }

        $cursos = $this->EcmProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'sigla',
                                    'groupField' => function ($e) {

                                                    $tipos  = [];
                                                    $filter = array_filter($e->get('ecm_tipo_produto'), 
                                                                            function($tipo) use (&$tipos) {
                                                                                $aux = in_array($tipo->id, [2,10,16,17,32,33,41,47]);
                                                                                if($aux) $tipos[ $tipo->id ] = $tipo->nome ;
                                                                                return $aux;
                                                                            });

                                                    if(array_key_exists(10, $tipos))
                                                        $retorno = $tipos[10];
                                                    else if(array_key_exists(16, $tipos))
                                                        $retorno = $tipos[16];
                                                    else if(array_key_exists(17, $tipos))
                                                        $retorno = $tipos[17];
                                                    else if(array_key_exists(32, $tipos))
                                                        $retorno = $tipos[32];
                                                    else if(array_key_exists(33, $tipos))
                                                        $retorno = $tipos[33];
                                                    else if(array_key_exists(41, $tipos))
                                                        $retorno = $tipos[41];
                                                    else if(array_key_exists(47, $tipos))
                                                        $retorno = $tipos[47];
                                                    else if(array_key_exists(2, $tipos))
                                                        $retorno = $tipos[2];
                                                    else
                                                        // $retorno = 'Sem Categoria';
                                                        $retorno = implode(",",$tipos);

                                                    return $retorno;
                                                   
                                                }

            ])
            ->contain(['EcmTipoProduto' => ['fields' => [ 'tipo' => 'distinct EcmTipoProduto.id',  'id', 'nome', 'EcmProdutoEcmTipoProduto.ecm_produto_id'], 'conditions' => ['EcmProdutoEcmTipoProduto.ecm_tipo_produto_id in'=> [2,10,16,17,32,33,41,47]]]]) 
            ->where($where)
            ->group('EcmProduto.id')
            ->order(['EcmProduto.sigla' => 'ASC'])
            ->toArray();

        $produtos = $this->EcmProdutoEcmAplicacao->find('list', ['keyField' => 'id', 'valueField' => function ($e) {
                                                                $codigo_tw = $this->EcmProduto->encriptCodigotw($e);
                                                                return $codigo_tw['codigo_tw'];
                                                        }])
                                                ->contain(['EcmProduto', 'EcmProdutoAplicacao'])
                                                ->where(['EcmProduto.habilitado' => 'true'])
                                                ->order(['EcmProduto.id' => 'DESC'])
                                                ->toArray();

        $this->set(compact('servico', 'cursos', 'curso', 'produtos', 'setSelects'));
        $this->set('_serialize', ['servico', 'cursos', 'curso', 'produtos', 'setSelects']);
    }

    /**
     * PRODUTOS CONTA AZUL
     * 
     *   name :String - The name of the product
     *   value :Number - The sell value of the product
     *   cost :Number - The cost value of the product
     *   code :String (optional) - The code of the product, a organization field that accepts any string
     *   barcode :String (optional) - The barcode of the product - 'EAN'
     *   available_stock :Number (optional) - The quantity of available products in stock
     *   ncm_code :String (optional) - The NCM code of the product
     *   cest_code :String (optional) - The CEST code of the product
     *   net_weight :Number (optional) - The net weight of the product
     *   gross_weight :Number (optional) - The gross weight of the product
     *   category_id :String (optional) - The category's id of the product
     * 
     */
    public function setProduct($id = null)
    {
        $this->loadModel('Produto.EcmProduto');
        $this->loadModel('Produto.EcmProdutoEcmAplicacao');
        
        $data = $dados = [];
        $cats = $product = NULL;
        $where = ['EcmProduto.habilitado' => 'true'];
        $produtos = (array_key_exists('produtos', $this->request->data)) ? ($this->request->data['produtos']) : NULL;

        if($this->request->data && count($this->request->data) > 0){
            $data['name'] = $this->request->data['name'];
            $data['value'] = floatval($this->request->data['value']);
            $data['cost'] = floatval($this->request->data['cost']);

            if($this->request->data['code'])
                $data['code'] = $this->request->data['code'];
            if($this->request->data['barcode'])
                $data['barcode'] = $this->request->data['barcode'];
            if($this->request->data['available_stock'])
                $data['available_stock'] = floatval($this->request->data['available_stock']);
            if($this->request->data['ncm_code'])
                $data['ncm_code'] = $this->request->data['ncm_code'];
            if($this->request->data['cest_code'])
                $data['cest_code'] = $this->request->data['cest_code'];
            if($this->request->data['net_weight'])
                $data['net_weight'] = floatval($this->request->data['net_weight']);
            if($this->request->data['gross_weight'])
                $data['gross_weight'] = floatval($this->request->data['gross_weight']);
            if($this->request->data['category_id'])
                $data['category_id'] = $this->request->data['category_id'];
        }

        $http = new Client(['headers' => ['Authorization' => 'Bearer '. $this->token_access->valor]]);

        try {
            $response = $http->get( self::URI.'/v1/product-categories');
        } catch (\Exception $e) {
            $this->Flash->error(__('Falha na Requisição - Buscar Categorias (Exception)'));
        }

        if(isset($response) && $response->isOk()){
            $cats = $response->json;
            $cats = array_map( function( $cat ) {
                return ['value' => $cat['id'], 'text' => $cat['name']];
            }, $cats);
        }else if(isset($response) && $response->code == 401){
            $this->Flash->error(__('Falha Autenticação! (Token Access)'));
            return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/product/'.$id]]);
        }else if(isset($response)){
            $result = $response->json;
            $this->Flash->error(__('Falha na Requisição - Buscar Categorias'));

            if($result){
                if(array_key_exists('error_description', $result))
                    $this->Flash->error(__($result["error_description"]));
                if(array_key_exists('error', $result))
                    $this->Flash->error(__($result["error"]));
                
                if(array_key_exists('code', $result))
                    $this->Flash->error(__($result["code"]));

                if(array_key_exists('message', $result))
                    $this->Flash->error(__($result["message"]));
                    
            }
        }

        if($id){

            try {
                $response = $http->get( self::URI.'/v1/products/'.$id);
            } catch (\Exception $e) {
                $this->Flash->error(__('Falha na Requisição - Buscar Produto (Exception)'));
            }
    
            if($response->isOk()){
                $product = $response->json;

                if($this->request->is('post')){

                    $update = false;
                    foreach ($data as $key => $value){
                        if( array_key_exists($key, $product) && ($product[$key] != $data[$key] )){
                            $dados[$key] = $data[$key];
                            $update = true;
                        }
                    }

                    if( array_key_exists('category_id', $data)){
                        if(!array_key_exists('category', $product)){
                            $dados['category_id'] = $data['category_id'];
                            $update = true;
                        }else if( $product['category']['id'] != $data['category_id']){
                            $dados['category_id'] = $data['category_id'];
                            $update = true;
                        }
                    }
    
                    if($update){
                        try {
                            $response = $http->put( self::URI.'/v1/products/'.$id, json_encode($dados), ['type' =>  'json']);
                        } catch (\Exception $e) {
                            $this->Flash->error(__('Falha na Requisição para Atualizar!'));
                        }

                        if($response->isOk()){
                            $this->Flash->success(__('Produto Conta Azul Atualizado com sucesso!'));
                        }else{
                            $this->Flash->error(__('Falha na Requisição para Atualizar!'));
                        }
                    }

                    if(isset($produtos) && count($produtos['_ids']) > 0)
                        $produtos = $this->EcmProdutoEcmAplicacao->find('all')->where(['id in' => $produtos['_ids']])->toList();
                    $prodOld = $this->EcmProdutoEcmAplicacao->find('all')->where(['EcmProdutoEcmAplicacao.conta_azul_produto' => $id])->toList();

                    if(count($prodOld) == 0 && count($produtos) > 0){
                        foreach ($produtos as $app) {
                            $app->set('conta_azul_produto', $id);
                            $this->EcmProdutoEcmAplicacao->save($app);
                        }
                    }elseif (count($prodOld) > 0 && count($produtos) > 0){

                        $prodsAdd = [];
                        $prodsDel = [];

                        foreach ($produtos as $app) {
                            $check = array_filter($prodOld, function($prod) use ($app){ return $prod->id == $app->id; });
                            if(count($check) == 0)
                                array_push($prodsAdd, $app);
                        }

                        foreach ($prodOld as $app) {
                            $check = array_filter($produtos, function($prod) use ($app){ return $prod->id == $app->id; });
                            if(count($check) == 0)
                                array_push($prodsDel, $app);
                        }

                        foreach ($prodsAdd as $app) {
                            $app->set('conta_azul_produto', $id);
                            $this->EcmProdutoEcmAplicacao->save($app);
                        }

                        foreach ($prodsDel as $app) {
                            $app->set('conta_azul_produto', NULL);
                            $this->EcmProdutoEcmAplicacao->save($app);
                        }
                    }else if(count($prodOld) > 0 && count($produto) == 0){
                        foreach ($prodOld as $app) {
                            $app->set('conta_azul_produto', null);
                            $this->EcmProdutoEcmAplicacao->save($app);
                        }
                    }
                    
                    return $this->redirect(['controller' => false, 'action' => 'listProducts' ]);
                    
                }else{
                    $setSelects = $this->EcmProdutoEcmAplicacao->find('list')
                                                                ->where(['conta_azul_produto' => $id])
                                                                ->toArray();
                    
                    $setSelects = json_encode(array_values($setSelects));
                }
            }else if($response->code == 404){
                $this->Flash->error(__('Produto Conta Azul não encontrado!'));
                return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/list-products']]);
            }else if($response->code == 401){
                $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/product/'.$id]]);
            }else{
                $result = $response->json;
                $this->Flash->error(__('Falha na Requisição - Buscar Produto'));

                if($result){
                    if(array_key_exists('error_description', $result))
                        $this->Flash->error(__($result["error_description"]));
                    if(array_key_exists('error', $result))
                        $this->Flash->error(__($result["error"]));
                    
                    if(array_key_exists('code', $result))
                        $this->Flash->error(__($result["code"]));
    
                    if(array_key_exists('message', $result))
                        $this->Flash->error(__($result["message"]));
                        
                }

                return $this->redirect(['action' => 'listProducts']);
            }

        }else if($this->request->is('post')){

            try {
                $response = $http->post( self::URI.'/v1/products/', json_encode($data), ['type' =>  'json']);
            } catch (\Exception $e) {
                $this->Flash->error(__('Falha na Requisição - Criar Produto (Exception)'));
            }

            if($response->isOk()){
                $result = $response->json;
                if(isset($produtos) && count($produtos['_ids']) > 0){
                    $produtos = $this->EcmProdutoEcmAplicacao->find('all')->where(['id in' => $produtos['_ids']]);

                    if(count($produtos) > 0){
                        foreach ($produtos as $app) {
                            $app->set('conta_azul_produto', $result['id']);
                            $this->EcmProdutoEcmAplicacao->save($app);
                        }
                    }

                    $this->Flash->success(__('Produto Conta Azul Criado com sucesso! ('. $result['id'] . ')'));
                    return $this->redirect(['controller' => false, 'action' => 'listProducts']);
                }
            }else if($response->code == 401){
                $this->Flash->error(__('Falha Autenticação! (Token Access)'));
                return $this->redirect(['action' => 'index', '?'=>['refresh' => true, 'redirect'=> '/conta-azul/list-products']]);
            }else{
                $result = $response->json;
                $this->Flash->error(__('Falha na Requisição - Criar Produto'));

                if($result){
                    if(array_key_exists('error_description', $result))
                        $this->Flash->error(__($result["error_description"]));
                    if(array_key_exists('error', $result))
                        $this->Flash->error(__($result["error"]));
                    
                    if(array_key_exists('code', $result))
                        $this->Flash->error(__($result["code"]));
    
                    if(array_key_exists('message', $result))
                        $this->Flash->error(__($result["message"]));
                        
                }
            }
        }

        $produtos = $this->EcmProdutoEcmAplicacao->find('list', ['keyField' => 'id', 'valueField' => function ($e) {
                                                                $codigo_tw = $this->EcmProduto->encriptCodigotw($e);
                                                                return $codigo_tw['codigo_tw'];
                                                        }])
                                                ->contain(['EcmProduto', 'EcmProdutoAplicacao'])
                                                ->where($where)
                                                ->order(['EcmProduto.id' => 'DESC'])
                                                ->toArray();
        

        $this->set(compact('product', 'produtos', 'cats', 'setSelects'));
        $this->set('_serialize', ['product', 'produtos', 'cats', 'setSelects']);
    }
}
