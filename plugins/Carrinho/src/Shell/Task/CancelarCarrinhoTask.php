<?php
namespace Carrinho\Shell\Task;

use Cake\Console\Shell;
use Carrinho\Model\Entity\EcmCarrinho;

/**
 * CancelarCarrinho shell task.
 */
class CancelarCarrinhoTask extends Shell
{
    public $plugin = 'Carrinho';

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        $this->cancelarCarrinhos();
    }

    private function cancelarCarrinhos(){
        $this->loadModel('EcmCarrinho');
        $this->loadModel('Configuracao.EcmConfig');

        $tempo_carrinho_proposta = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'tempo_carrinho_proposta'])->first();

        $dataAtual = new \DateTime();
        $dataAtual->modify('-2hour');
        $this->out('Cancelando carrinhos com status "Em Aberto" anteriores a '.$dataAtual->format('d/m/Y H:i:s'));
        $this->out('Não Modificados');

        $listaCarrinho = $this->EcmCarrinho->find()
            ->where([
                'status' => EcmCarrinho::STATUS_EM_ABERTO,
                'edicao <= ' => $dataAtual->format('Y/m/d H:i:s'),
                'mdl_user_modified_id IS NULL'
            ])
            ->orderASC('id');

        foreach($listaCarrinho as $carrinho){
            $this->out('Carrinho id: '.$carrinho->get('id').'('.$carrinho->get('edicao').')');
            $this->out('');

            $carrinho->set('status', EcmCarrinho::STATUS_CANCELADO);

            $this->EcmCarrinho->save($carrinho);
        }

        $dataAtual = new \DateTime();
        $dataAtual->modify('-'.$tempo_carrinho_proposta->valor);
        $this->out('Cancelando Propostas com status "Em Aberto" anteriores a '.$dataAtual->format('d/m/Y H:i:s'));

        $listaCarrinho = $this->EcmCarrinho->find()
            ->where([
                'status' => EcmCarrinho::STATUS_EM_ABERTO,
                'edicao <= ' => $dataAtual->format('Y/m/d H:i:s'),
                'mdl_user_modified_id IS NOT NULL'
            ])
            ->orderASC('id');

        foreach($listaCarrinho as $carrinho){
            $this->out('Carrinho id: '.$carrinho->get('id').'('.$carrinho->get('edicao').')');
            $this->out('');

            $carrinho->set('status', EcmCarrinho::STATUS_CANCELADO);

            $this->EcmCarrinho->save($carrinho);
        }
    }
}
