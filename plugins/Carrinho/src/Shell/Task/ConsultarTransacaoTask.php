<?php
namespace Carrinho\Shell\Task;

use Cake\Console\Shell;
use Cake\Mailer\Email;
use Cake\Network\Http\Client;
use Carrinho\Model\Entity\EcmTransacao;
use FormaPagamentoSuperPay\Lib\SuperPayGateway\LocawebGateway;
use FormaPagamentoSuperPay\Lib\SuperPayGateway\LocawebGatewayConfig;
use FormaPagamentoSuperPayRecorrencia\Controller\FormaPagamentoSuperPayRecorrenciaController;

/**
 * ConsultarTransacao shell task.
 */
class ConsultarTransacaoTask extends Shell
{
    public $plugin = 'Carrinho';
    public $ecmTransacaoStatus;

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        $this->configuracao();
        $this->consultarTransacao();
        $this->consultarRecorrencia();
    }

    private function configuracao(){
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Carrinho.EcmTransacao');

        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();

        $environment = 'sandbox';
        $token = null;
        if($ambienteProducao->valor == 1){
            $environment = 'production';
            $token = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'token_super_pay'])->first();
        }else{
            $token = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_token_super_pay'])->first();
        }

        LocawebGatewayConfig::setEnvironment($environment);
        LocawebGatewayConfig::setToken($token->valor);

        $this->ecmTransacaoStatus = $this->EcmTransacao->EcmTransacaoStatus->find('list', [
            'keyField' => 'status', 'valueField' => 'id'
        ])->toArray();
    }

    private function consultarTransacao(){
        $this->loadModel('Carrinho.EcmTransacao');

        $dataAtual = new \DateTime();
        $dataAtual->modify('-4days');
        $this->out('Alterando status das transacoes "aguardando..." anteriores a '.$dataAtual->format('d/m/Y H:i:s'));
        $this->out('');

        $ecmTransacao = $this->EcmTransacao->find()
            ->where([
                'data_envio >= ' => $dataAtual->format('Y/m/d H:i:s'),
                'OR' => [
                    ['ecm_transacao_status_id' => $this->ecmTransacaoStatus[EcmTransacao::STATUS_AGUARDANDO_CAPTURAR]],
                    ['ecm_transacao_status_id' => $this->ecmTransacaoStatus[EcmTransacao::STATUS_AGUARDANDO_PAGAMENTO]]
                ]
            ])
            ->orderDESC('EcmTransacao.id')->toArray();

        foreach($ecmTransacao as $transacao){
            $requisicao = LocawebGateway::consultar($transacao->id_integracao)->sendRequest();
            $this->out('Verificando transacao ' . $transacao->id);
            $this->out('');
            if($requisicao->transacao->status != EcmTransacao::STATUS_AGUARDANDO_CAPTURAR ||
                    $requisicao->transacao->status != EcmTransacao::STATUS_AGUARDANDO_PAGAMENTO){
                $transacao->ecm_transacao_status_id = $this->ecmTransacaoStatus[$requisicao->transacao->status];
                if(is_int($transacao->ecm_transacao_status_id)){
                    $this->out('Alterando transacao ' . $transacao->id . ' de "' .
                        array_search($transacao->ecm_transacao_status_id, $this->ecmTransacaoStatus)
                        . '" para "' . $requisicao->transacao->status . '"');
                    $this->out('');
                    if($this->EcmTransacao->save($transacao)){
                        $this->alterarMatricula($transacao);
                    }
                }
            }
        }
    }

    private function alterarMatricula($transacao){
        $this->loadModel('WebService.MdlCourse');
        $mdlCourses = $this->MdlCourse->find()
            ->matching('EcmProduto', function($q) use ($transacao) {
                return $q->matching('EcmCarrinhoItem', function ($q) use ($transacao) {
                    return $q->matching('EcmCarrinho', function ($q) use ($transacao) {
                        return $q->matching('EcmVenda', function ($q) use ($transacao) {
                            return $q->matching('EcmTransacao', function ($q) use ($transacao) {
                                return $q->where(['EcmTransacao.id' => $transacao->id]);
                            });
                        });
                    });
                });
            })->toArray();

        $this->loadModel('MdlUser');
        foreach($mdlCourses as $mdlCourse){
            $mdlUserEnrolments = $this->MdlUser->MdlUserEnrolments->find()
                ->matching('MdlEnrol', function($q) use ($mdlCourse) {
                    return $q->where(['courseid' => $mdlCourse->id]);
                })->where(['userid' => $transacao->mdl_user_id])->first();

            $mdlRoleAssignments = $this->MdlUser->MdlRoleAssignments->find()
                ->matching('MdlContext', function($q) use ($mdlCourse) {
                    return $q->where(['instanceid' => $mdlCourse->id]);
                })->where(['userid' => $transacao->mdl_user_id])->first();

            if(isset($mdlUserEnrolments) && isset($mdlUserEnrolments)){
                if(array_search($transacao->ecm_transacao_status_id, $this->ecmTransacaoStatus) == EcmTransacao::STATUS_PAGA){
                    if($mdlRoleAssignments->roleid == 24){
                        $mdlRoleAssignments->roleid = 11;
                        $this->MdlUser->MdlRoleAssignments->save($mdlRoleAssignments);
                    }
                } else {
                    $this->MdlUser->MdlUserEnrolments->delete($mdlUserEnrolments);

                    $this->MdlUser->MdlRoleAssignments->delete($mdlRoleAssignments);
                }
            } else {
                if(!isset($email)){
                    $email = new Email();
                    $email->template('emailHabilitacaoMatriculaRecorrencia')->emailFormat('html');
                    $email->subject('QiSat | Habilitação da Matricula da Recorrência');

                    $this->loadModel('Configuracao.EcmConfig');
                    $noreply = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_noreply']])->first()->valor;
                    $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;
                    $email->from([$noreply => $fromEmailTitle]);

                    $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;
                    $email->to([$supportemail => $fromEmailTitle]);
                }
                if(!isset($mdlUser))
                    $mdlUser = $this->MdlUser->get($transacao->mdl_user_id);

                $email->viewVars(['usuario' => $mdlUser, 'curso' => $mdlCourse, 'pago' =>
                    array_search($transacao->ecm_transacao_status_id, $this->ecmTransacaoStatus) == EcmTransacao::STATUS_PAGA
                ]);
                $email->send();
            }
        }
    }

    private function consultarRecorrencia(){
        $this->loadModel('Carrinho.EcmRecorrencia');
        $this->loadModel('Configuracao.EcmConfig');

        $dataAtual = new \DateTime();
        $this->out('Consultando recorrencias '.$dataAtual->format('d/m/Y H:i:s'));

        $listaRecorrencia = $this->EcmRecorrencia
            ->find('all')
            ->contain(['MdlUser'])
            ->where([
                'EcmRecorrencia.numero_cobranca_restantes > ' => 0,
                'EcmRecorrencia.transacao_status IN' => [1, 2]
            ])
            ->orderDESC('EcmRecorrencia.id')
            ->group('EcmRecorrencia.ecm_venda_id')
            ->toList();


        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();

        $estabelecimento = null;
        $login= null;
        $senha = null;
        $url = FormaPagamentoSuperPayRecorrenciaController::LINK_HOMOLOGACAO;

        if($ambienteProducao->valor == 1){
            $url = FormaPagamentoSuperPayRecorrenciaController::LINK_PRODUCAO;

            $estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'token_super_pay'])->first()->valor;
            $login =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'usuario_super_pay'])->first()->valor;
            $senha =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'senha_super_pay'])->first()->valor;
        }else{
            $estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_token_super_pay'])->first()->valor;
            $login = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_usuario_super_pay'])->first()->valor;
            $senha = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_senha_super_pay'])->first()->valor;
        }

        if(!is_null($listaRecorrencia)) {
            foreach ($listaRecorrencia as $recorrencia) {
                $this->out('# Verificando recorrencia '.$recorrencia->get('id'));

                $link = $url.'/'.$estabelecimento.'/'.$recorrencia->get('id');

                $dadosRecorrencia = $this->requestRecorrencia($link, json_encode([ "login" => $login, "senha" => $senha ]));

                $erro = true;
                if(array_key_exists('erro', $dadosRecorrencia)){
                    $this->out('Erro ao buscar recorrencia: ');
                    $this->out('- Codigo '.$dadosRecorrencia['erro']['codigo']);
                    $this->out('- Mensagem '.$dadosRecorrencia['erro']['mensagem']);

                }else{
                    $statusTransacao = $dadosRecorrencia['recorrencia']['statusTransacao'];

                    if ($statusTransacao == 1 || $statusTransacao == 2) {
                        $recorrencia->set('numero_cobranca_restantes', $dadosRecorrencia['recorrencia']['numeroCobrancaRestantes']);

                        if ($this->EcmRecorrencia->save($recorrencia)) {
                            $erro = false;
                            $this->out('Recorrencia atualizada com sucesso');
                        } else {
                            $this->out('Erro ao atualizar recorrencia');
                        }
                    }
                }

                if($erro) {
                    $email = new Email();
                    $email->template('Carrinho.emailErroAtualizacaoRecorrencia')->emailFormat('html');
                    $email->subject('QiSat | Erro ao Atualizar Recorrência');

                    $this->loadModel('Configuracao.EcmConfig');
                    $noreply = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_sistema']])->first()->valor;
                    $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

                    $email->from([$noreply => $fromEmailTitle]);

                    $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;
                    $email->to([$supportemail => $fromEmailTitle]);

                    $email->viewVars(['recorrencia' => $recorrencia]);
                    $email->send();
                }
            }
        }
    }

    private function requestRecorrencia($url, $dadosAcesso){
        $http = new Client();
        $response = $http->get(
            $url,
            [],
            [
                'type' => 'json',
                'headers' => array( 'usuario' => $dadosAcesso )
            ]
        );

        return $response->json;
    }
}
