<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 16/08/2016
 * Time: 11:32
 */

namespace App\Model\Table;


use App\Model\Entity\EcmLogAcao;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class Table extends \Cake\ORM\Table
{

    public function afterSaveCommit(Event $event, EntityInterface $entity, \ArrayObject $options){
        if ($entity->isNew()) {
            $this->salvarAcao('insert', $entity);
        } else {
            $this->salvarAcao('update', $entity);
        }
    }

    public function afterDelete(Event $event, EntityInterface $entity, \ArrayObject $options){
        $this->salvarAcao('delete', $entity);
    }

    private function salvarAcao($acao, EntityInterface $entity){

        if(isset($_SESSION['Auth']['User']['id'])) {

            $url = '';
            if(isset($_SERVER['HTTP_REFERER'])){
                $url = $_SERVER['HTTP_REFERER'];
            }elseif($_SERVER['REDIRECT_URL']){
                $url = $_SERVER['REDIRECT_URL'];
            }

            $ecmLogAcao = new EcmLogAcao();

            $ecmLogAcao->set('chave', ( $entity->get('pedido') ?: $entity->get('id') ));
            $ecmLogAcao->set('data', new \DateTime());
            $ecmLogAcao->set('tabela', $this->table());
            $ecmLogAcao->set('ip', $_SERVER["REMOTE_ADDR"]);
            $ecmLogAcao->set('mdl_user_id', $_SESSION['Auth']['User']['id']);
            $ecmLogAcao->set('url', $url);
            $ecmLogAcao->set('acao', $acao);

            $ecmLogAcaoTable = TableRegistry::get('EcmLogAcao');
            $ecmLogAcaoTable->save($ecmLogAcao);
        }
    }

}