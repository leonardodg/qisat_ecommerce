<?php
namespace Produto\Controller;

use Produto\Controller\AppController;

/**
 * EcmProdutoEcmTipoProdutoEcmAlternativeHost Controller
 *
 * @property \Produto\Model\Table\EcmProdutoEcmTipoProdutoEcmAlternativeHostTable $EcmProdutoEcmTipoProdutoEcmAlternativeHost */
class EcmProdutoEcmTipoProdutoEcmAlternativeHostController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function index()
    {
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $alternative_host = $this->request->query['alternative_host'];
            $tipo_produto = $this->request->query['tipo_produto'];
            $ecmProdutoEcmTipoProdutoEcmAlternativeHost = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost
                ->find('list',['limit' => 200,'keyField' => 'id','valueField' => 'ecm_produto_ecm_tipo_produto_id'])
                ->innerJoin(['EcmProdutoEcmTipoProduto'=>'ecm_produto_ecm_tipo_produto'],
                    'EcmProdutoEcmTipoProduto.id = EcmProdutoEcmTipoProdutoEcmAlternativeHost.ecm_produto_ecm_tipo_produto_id')
                ->innerJoin(['EcmTipoProduto'=>'ecm_tipo_produto'],
                    'EcmTipoProduto.id = EcmProdutoEcmTipoProduto.ecm_tipo_produto_id')
                ->where('ecm_alternative_host_id = ' . $alternative_host)
                ->where('EcmTipoProduto.id = ' . $tipo_produto)
                ->order(['EcmProdutoEcmTipoProdutoEcmAlternativeHost.ordem'=>'ASC']);

            print_r($ecmProdutoEcmTipoProdutoEcmAlternativeHost->toArray());
        } else if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['ecm_produto_ecm_tipo_produto_id'])){
                $ecm_alternative_host = (int)$this->request->data['ecm_alternative_host_id'];
                $ecm_produto_ecm_tipo_produto = explode(",", str_replace(".", "", $this->request->data['ecm_produto_ecm_tipo_produto_id']));
                $ordem = 1;

                $ids = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->find('list', ['limit' => 200,
                    'keyField' => 'ecm_produto_ecm_tipo_produto_id', 'valueField' => 'id'])
                    ->where(['ecm_alternative_host_id' => $ecm_alternative_host])->toArray();

                foreach ($ecm_produto_ecm_tipo_produto as $value) {
                    if (array_key_exists($value, $ids)) {
                        $EcmProdutoEcmTipoProdutoEcmAlternativeHost = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->get($ids[$value]);
                    } else {
                        $EcmProdutoEcmTipoProdutoEcmAlternativeHost = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->newEntity();
                        $EcmProdutoEcmTipoProdutoEcmAlternativeHost->ecm_produto_ecm_tipo_produto_id = (int)$value;
                        $EcmProdutoEcmTipoProdutoEcmAlternativeHost->ecm_alternative_host_id = $ecm_alternative_host;
                    }
                    $EcmProdutoEcmTipoProdutoEcmAlternativeHost->ordem = $ordem++;

                    if (!$this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->save($EcmProdutoEcmTipoProdutoEcmAlternativeHost)) {
                        $this->Flash->error(__('The ecm produto tipo produto ecm alternative host could not be saved. Please, try again.'));
                    }
                }

                $this->Flash->success(__('The ecm produto tipo produto ecm alternative host has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Entidade não selecionada. Favor, tente novamente.'));
            }
        }

        $ecmProdutoEcmTipoProduto = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->EcmProdutoEcmTipoProduto
            ->find('all', ['limit' => 200,'fields'=>['nome'=>'EcmProduto.nome',
                'id'=>'EcmProdutoEcmTipoProduto.id',
                'src'=>'EcmImagem.src',
                //'produto'=>'EcmProdutoEcmTipoProduto.ecm_produto_id',
                'tipo'=>'EcmProdutoEcmTipoProduto.ecm_tipo_produto_id']])
        ->innerJoin(['EcmTipoProduto'=>'ecm_tipo_produto'],
            'EcmProdutoEcmTipoProduto.ecm_tipo_produto_id = EcmTipoProduto.id')
            ->where(['OR'=>[['EcmTipoProduto.nome'=>'CAD'],['EcmTipoProduto.nome'=>'Elétrica'],
                    ['EcmTipoProduto.nome'=>'Estrutural'],['EcmTipoProduto.nome'=>'Gratuito'],
                    ['EcmTipoProduto.nome'=>'Hidráulica'],['EcmTipoProduto.nome'=>'Item da Série'],
                    ['EcmTipoProduto.nome'=>'Outros Cursos'],['EcmTipoProduto.nome'=>'Presencial'],
                    ['EcmTipoProduto.nome'=>'Séries']
            ]])
        ->innerJoin(['EcmProduto'=>'ecm_produto'],
            'EcmProdutoEcmTipoProduto.ecm_produto_id = EcmProduto.id')
            ->where(['AND'=>[['EcmProduto.habilitado'=>'true'],['visivel'=>'true']]])
        ->leftJoin(['EcmProdutoEcmImagem'=>'ecm_produto_ecm_imagem'],
            'EcmProduto.id = EcmProdutoEcmImagem.ecm_produto_id')
        ->leftJoin(['EcmImagem'=>'ecm_imagem'],
            'EcmProdutoEcmImagem.ecm_imagem_id = EcmImagem.id AND EcmImagem.descricao = "capa"');

        $ecmAlternativeHost = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->EcmAlternativeHost
            ->find('list', ['limit' => 200,'keyField' => 'id','valueField' => 'fullname'])->toArray();
        $ecmAlternativeHost[0] = "Selecione uma entidade";
        ksort($ecmAlternativeHost);

        $ecmTipoProduto = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->EcmProdutoEcmTipoProduto
            ->EcmTipoProduto->find('list', ['limit' => 200,'keyField' => 'id','valueField' => 'nome'])
            ->where(['OR'=>[['EcmTipoProduto.nome'=>'CAD'],['EcmTipoProduto.nome'=>'Elétrica'],
                ['EcmTipoProduto.nome'=>'Estrutural'],['EcmTipoProduto.nome'=>'Gratuito'],
                ['EcmTipoProduto.nome'=>'Hidráulica'],['EcmTipoProduto.nome'=>'Item da Série'],
                ['EcmTipoProduto.nome'=>'Outros Cursos'],['EcmTipoProduto.nome'=>'Presencial'],
                ['EcmTipoProduto.nome'=>'Séries']
            ]])->toArray();
        $ecmTipoProduto[0] = "Todos os tipos de produtos";
        ksort($ecmTipoProduto);

        $this->set(compact('ecmProdutoEcmTipoProduto','ecmAlternativeHost','ecmTipoProduto'));
    }

    /**
     * Destaques method
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function destaques()
    {

        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $alternative_host = $this->request->query['alternative_host'];
            $ecmProdutoEcmTipoProdutoEcmAlternativeHost = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost
                ->find('all',['limit' => 200,'fields' => ['produto'=>'EcmProdutoEcmTipoProduto.ecm_produto_id']])
                ->innerJoin(['EcmProdutoEcmTipoProduto'=>'ecm_produto_ecm_tipo_produto'],
                    'EcmProdutoEcmTipoProduto.id = EcmProdutoEcmTipoProdutoEcmAlternativeHost.ecm_produto_ecm_tipo_produto_id')
                ->innerJoin(['EcmTipoProduto'=>'ecm_tipo_produto'],
                    'EcmTipoProduto.id = EcmProdutoEcmTipoProduto.ecm_tipo_produto_id')
                ->where(['AND'=>['ecm_alternative_host_id = ' . $alternative_host],
                    ['EcmTipoProduto.nome'=>'Destaque']])
                ->order(['EcmProdutoEcmTipoProdutoEcmAlternativeHost.ordem'=>'ASC']);

            echo json_encode(compact('ecmProdutoEcmTipoProdutoEcmAlternativeHost'));
        } else if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['ecm_produto_ecm_tipo_produto_id'])){
                $ecm_alternative_host = (int)$this->request->data['ecm_alternative_host_id'];
                $ecm_produto_ecm_tipo_produto = explode(",", str_replace(".", "", $this->request->data['ecm_produto_ecm_tipo_produto_id']));
                $ordem = 1;

                $ids = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->find('list', ['limit' => 200,
                    'keyField' => 'ecm_produto_ecm_tipo_produto_id', 'valueField' => 'id'])
                    ->where(['ecm_alternative_host_id' => $ecm_alternative_host])->toArray();

                $success = true;
                foreach ($ecm_produto_ecm_tipo_produto as $value) {
                    if (array_key_exists($value, $ids)) {
                        $ecmProdutoEcmTipoProdutoEcmAlternativeHost = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->get($ids[$value]);
                        unset($ids[$value]);
                    } else {
                        $ecmProdutoEcmTipoProdutoEcmAlternativeHost = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->newEntity();
                        $ecmProdutoEcmTipoProdutoEcmAlternativeHost->ecm_produto_ecm_tipo_produto_id = (int)$value;
                        $ecmProdutoEcmTipoProdutoEcmAlternativeHost->ecm_alternative_host_id = $ecm_alternative_host;
                    }
                    $ecmProdutoEcmTipoProdutoEcmAlternativeHost->ordem = $ordem++;

                    if (!$this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->save($ecmProdutoEcmTipoProdutoEcmAlternativeHost) && $success) {
                        $success = false;
                        $this->Flash->error(__('Os dados não foram salvos, por favor tente novamente.'));
                    }
                }
                foreach ($ids as $id) {
                    $ecmProdutoEcmTipoProdutoEcmAlternativeHost = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->get($id);
                    if (!$this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->delete($ecmProdutoEcmTipoProdutoEcmAlternativeHost) && $success) {
                        $success = false;
                        $this->Flash->error(__('Os dados não foram salvos, por favor tente novamente.'));
                    }
                }
                if($success){
                    $this->Flash->success(__('Destaques salvos com sucesso'));
                    return $this->redirect(['action' => 'destaques']);
                }
            } else {
                $this->Flash->error(__('Entidade não selecionada. Favor, tente novamente.'));
            }
        }

        $ecmProdutoEcmTipoProduto = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->EcmProdutoEcmTipoProduto
            ->find('all', ['limit' => 200,'fields'=>['nome'=>'EcmProduto.nome',
                'id'=>'EcmProdutoEcmTipoProduto.id',
                'sigla'=>'EcmProduto.sigla',
                'produto'=>'EcmProduto.id',
                'src'=>'EcmImagem.src',
                'tipo'=>'EcmProdutoEcmTipoProduto.ecm_tipo_produto_id']])
            ->innerJoin(['EcmTipoProduto'=>'ecm_tipo_produto'],
                'EcmProdutoEcmTipoProduto.ecm_tipo_produto_id = EcmTipoProduto.id')
            ->where(['EcmTipoProduto.nome'=>'Destaque'])
            ->innerJoin(['EcmProduto'=>'ecm_produto'],
                'EcmProdutoEcmTipoProduto.ecm_produto_id = EcmProduto.id')
            ->where(['AND'=>[['EcmProduto.habilitado'=>'true'],['EcmProduto.visivel'=>'true']]])
            ->leftJoin(['EcmProdutoEcmImagem'=>'ecm_produto_ecm_imagem'],
                'EcmProduto.id = EcmProdutoEcmImagem.ecm_produto_id')
            ->leftJoin(['EcmImagem'=>'ecm_imagem'],
                'EcmProdutoEcmImagem.ecm_imagem_id = EcmImagem.id AND EcmImagem.descricao = "capa"')
            ->group('EcmProdutoEcmTipoProduto.ecm_produto_id');

        $ecmAlternativeHost = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->EcmAlternativeHost
            ->find('list', ['limit' => 200,'keyField' => 'id','valueField' => 'fullname'])->toArray();
        $ecmAlternativeHost[0] = "Selecione uma entidade";
        ksort($ecmAlternativeHost);

        $ecmProduto = $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->EcmProdutoEcmTipoProduto
            ->EcmProduto->find('list', ['limit' => 200,'keyField' => 'id','valueField' => 'sigla'])
            ->leftJoin(['EcmProdutoEcmTipoProduto'=>'ecm_produto_ecm_tipo_produto'],
                'EcmProduto.id = EcmProdutoEcmTipoProduto.ecm_produto_id')
            ->leftJoin(['EcmTipoProduto'=>'ecm_tipo_produto'],
                'EcmProdutoEcmTipoProduto.ecm_tipo_produto_id = EcmTipoProduto.id')
            ->where(['EcmTipoProduto.nome'=>'Destaque']);

        $this->set(compact('ecmProdutoEcmTipoProduto','ecmAlternativeHost','ecmProduto'));
    }
}
