<?php
namespace Produto\Controller;

use Cake\Filesystem\Folder;
use Produto\Controller\AppController;

/**
 * EcmProdutoInfo Controller
 *
 * @property \Produto\Model\Table\EcmProdutoInfoTable $EcmProdutoInfo */
class EcmProdutoInfoController extends AppController
{

    /**
     * Edit method
     *
     * @param string|null $id Ecm Produto Info id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmProdutoInfo = $this->EcmProdutoInfo->find('all', [
            'contain' => [
                'EcmProdutoInfoArquivos',
                'EcmProdutoInfoConteudo'=> function($q){
                    return $q->orderAsc('ordem');
                },
                'EcmProdutoInfoFaq'
            ],
            'conditions' => ['EcmProdutoInfo.ecm_produto_id' => $id]
        ])->first();
        if(is_null($ecmProdutoInfo))
            $ecmProdutoInfo = $this->EcmProdutoInfo->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {

            $this->loadModel('Imagem.EcmImagem');
            foreach($this->request->data['EcmProdutoInfoArquivos']['path'] as $key => $path) {
                if($path['size'] > 0)
                    $this->request->data['EcmProdutoInfoArquivos']['path'][$key] =
                        $this->EcmImagem->enviarImagem([$key => ['nome' => $path]], 'info')[0]['src'];
                else
                    unset($this->request->data['EcmProdutoInfoArquivos']['path'][$key]);
            }

            $ecmProdutoInfoArquivos = $this->request->data['EcmProdutoInfoArquivos'];
            unset($this->request->data['EcmProdutoInfoArquivos']);
            $this->request->data['ecm_produto_info_arquivos'] = [];
            foreach($ecmProdutoInfoArquivos['tipo'] as $key => $tipo){
                if(!empty($ecmProdutoInfoArquivos['nome'][$key]) || !empty($ecmProdutoInfoArquivos['descricao'][$key])){
                    $arquivos = ['ecm_produto_info_arquivos_tipos_id' => $tipo,
                        'nome' => $ecmProdutoInfoArquivos['nome'][$key],
                        'descricao' => $ecmProdutoInfoArquivos['descricao'][$key]
                    ];
                    if(isset($ecmProdutoInfoArquivos['path'][$key]))
                        $arquivos['path'] = $ecmProdutoInfoArquivos['path'][$key];
                    if(isset($ecmProdutoInfoArquivos['id'][$key]))
                        $arquivos['id'] = $ecmProdutoInfoArquivos['id'][$key];
                    if(isset($ecmProdutoInfoArquivos['link'][$key]))
                        $arquivos['link'] = $ecmProdutoInfoArquivos['link'][$key];

                    $this->request->data['ecm_produto_info_arquivos'][] = $arquivos;
                }
            }


            $ecmProdutoInfoConteudo = $this->request->data['EcmProdutoInfoConteudo'];
            unset($this->request->data['EcmProdutoInfoConteudo']);
            $this->request->data['ecm_produto_info_conteudo'] = [];
            $ordem = 1;
            foreach($ecmProdutoInfoConteudo['titulo'] as $key => $titulo){
                if(!empty($titulo) || !empty($ecmProdutoInfoConteudo['descricao'][$key])){
                    $arquivos = [
                        'titulo' => $titulo,
                        'descricao' => $ecmProdutoInfoConteudo['descricao'][$key],
                        'ordem' => $ordem++
                    ];
                    if(isset($ecmProdutoInfoConteudo['id'][$key]))
                        $arquivos['id'] = $ecmProdutoInfoConteudo['id'][$key];
                    $this->request->data['ecm_produto_info_conteudo'][] = $arquivos;
                }
            }

            $ecmProdutoInfoFaq = $this->request->data['EcmProdutoInfoFaq'];
            unset($this->request->data['EcmProdutoInfoFaq']);
            $this->request->data['ecm_produto_info_faq'] = [];
            foreach($ecmProdutoInfoFaq['titulo'] as $key => $titulo){
                if(!empty($titulo) || !empty($ecmProdutoInfoFaq['descricao'][$key])){
                    $arquivos = ['titulo' => $titulo, 'descricao' => $ecmProdutoInfoFaq['descricao'][$key]];
                    if(isset($ecmProdutoInfoFaq['id'][$key]))
                        $arquivos['id'] = $ecmProdutoInfoFaq['id'][$key];
                    $this->request->data['ecm_produto_info_faq'][] = $arquivos;
                }
            }

            $this->request->data['ecm_produto_id'] = $id;
            $ecmProdutoInfo = $this->EcmProdutoInfo->patchEntity($ecmProdutoInfo, $this->request->data);
            if ($this->EcmProdutoInfo->save($ecmProdutoInfo)) {
                $this->Flash->success(__('The produto info has been saved.'));
                return $this->redirect(['controller' => '', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The produto info could not be saved. Please, try again.'));
            }
        }

        $tiposArquivos = $this->EcmProdutoInfo->EcmProdutoInfoArquivos->EcmProdutoInfoArquivosTipos->find('list', [
            'keyField' => 'id', 'valueField' => 'tipo'
        ]);
        $this->set(compact('ecmProdutoInfo', 'tiposArquivos'));
        $this->set('_serialize', ['ecmProdutoInfo', 'tiposArquivos']);
    }

    /**
     * Delete Arquivos method
     *
     * @param string|null $id Ecm Produto id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteArquivos()
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'delete']);
        if(isset($this->request->data['id'])) {
            $id = $this->request->data['id'];
            $ecmProdutoInfoArquivos = $this->EcmProdutoInfo->EcmProdutoInfoArquivos->get($id);
            if ($this->EcmProdutoInfo->EcmProdutoInfoArquivos->delete($ecmProdutoInfoArquivos)) {
                if(!empty($ecmProdutoInfoArquivos['path']) && file_exists(WWW_ROOT . 'upload/' . $ecmProdutoInfoArquivos['path'])){
                    unlink(WWW_ROOT . 'upload/' . $ecmProdutoInfoArquivos['path']);

                    $folder = new Folder(WWW_ROOT . 'upload/' . substr($ecmProdutoInfoArquivos['path'], 0, strrpos($ecmProdutoInfoArquivos['path'], '/')));
                    if(empty($folder->find()))
                        $folder->delete();
                    //rmdir(WWW_ROOT . 'upload/' . substr($ecmProdutoInfoArquivos['path'], 0, strrpos($ecmProdutoInfoArquivos['path'], '/')));
                }
                $this->Flash->success(__('The arquivos has been deleted.'));
            } else {
                $this->Flash->error(__('The arquivos could not be deleted. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('Id incorreto.'));
        }
    }

    /**
     * Delete Conteudo method
     *
     * @param string|null $id Ecm Produto id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteConteudo()
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'delete']);
        if(isset($this->request->data['id'])) {
            $id = $this->request->data['id'];
            $ecmProdutoInfoConteudo = $this->EcmProdutoInfo->EcmProdutoInfoConteudo->get($id);
            if ($this->EcmProdutoInfo->EcmProdutoInfoConteudo->delete($ecmProdutoInfoConteudo)) {
                $this->Flash->success(__('The conteudo has been deleted.'));
            } else {
                $this->Flash->error(__('The conteudo could not be deleted. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('Id incorreto.'));
        }
    }

    /**
     * Delete Conteudo method
     *
     * @param string|null $id Ecm Produto id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteFaq()
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'delete']);
        if(isset($this->request->data['id'])) {
            $id = $this->request->data['id'];
            $ecmProdutoInfoFaq = $this->EcmProdutoInfo->EcmProdutoInfoFaq->get($id);
            if ($this->EcmProdutoInfo->EcmProdutoInfoFaq->delete($ecmProdutoInfoFaq)) {
                $this->Flash->success(__('The faq has been deleted.'));
            } else {
                $this->Flash->error(__('The faq could not be deleted. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('Id incorreto.'));
        }
    }
}
