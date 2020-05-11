<?php
namespace Carrinho\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmTransacao Entity.
 *
 * @property int $id * @property int $id_integracao * @property int $ecm_transacao_status_id * @property \Carrinho\Model\Entity\EcmTransacaoStatus $ecm_transacao_status * @property string $capturar * @property int $mdl_user_id * @property \Carrinho\Model\Entity\MdlUser $mdl_user * @property float $valor * @property string $descricao * @property int $ecm_tipo_pagamento_id * @property \Carrinho\Model\Entity\EcmTipoPagamento $ecm_tipo_pagamento * @property int $ecm_operadora_pagamento_id * @property \Carrinho\Model\Entity\EcmOperadoraPagamento $ecm_operadora_pagamento * @property int $ecm_venda_id * @property \Carrinho\Model\Entity\EcmVenda $ecm_venda * @property \Cake\I18n\Time $data_envio * @property \Cake\I18n\Time $data_retorno * @property string $tid * @property string $nsu * @property string $pan * @property string $arp * @property string $lr * @property string $url * @property string $erro * @property string $teste * @property string $ip */
class EcmTransacao extends Entity
{

    const STATUS_AGUARDANDO_CAPTURAR = 'aguardando_capturar';
    const STATUS_AGUARDANDO_PAGAMENTO = 'aguardando_pagamento';
    const STATUS_CANCELADA = 'cancelada';
    const STATUS_ERRO = 'erro';
    const STATUS_ESTORNO = 'estorno';
    const STATUS_NEGADA = 'negada';
    const STATUS_PAGA = 'paga';
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];


    public function getMsgErrorOperadora($codigo){
        $mensagens = [
                        '01' => 'Transação não autorizada. Transação referida.',
                        '02' => 'Transação não autorizada. Transação referida.',
                        '03' => 'Transação não permitida. Erro no cadastramento do código do estabelecimento no arquivo de configuração do TEF.',
                        '04' => 'Transação não autorizada. Cartão bloqueado pelo banco emissor.',
                        '05' => 'Transação não autorizada. Cartão inadimplente (Do not honor).',
                        '06' => 'Transação não autorizada. Cartão cancelado.',
                        '07' => 'Transação negada. Reter cartão condição especial',
                        '08' => 'Transação não autorizada. Código de segurança inválido.',
                        '12' => 'Transação inválida, erro no cartão.',
                        '13' => 'Transação não permitida. Valor da transação Inválido.',
                        '14' => 'Transação não autorizada. Cartão Inválido.',
                        '15' => 'Banco emissor indisponível ou inexistente.',
                        '19' => 'Refaça a transação ou tente novamente mais tarde.',
                        '21' => 'Cancelamento não efetuado. Transação não localizada.',
                        '22' => 'Parcelamento inválido. Número de parcelas inválidas.',
                        '23' => 'Transação não autorizada. Valor da prestação inválido.',
                        '24' => 'Quantidade de parcelas inválido.',
                        '25' => 'Pedido de autorização não enviou número do cartão.',
                        '28' => 'Arquivo temporariamente indisponível.',
                        '30' => 'Transação não autorizada. Decline Message',
                        '39' => 'Transação não autorizada. Erro no banco emissor.',
                        '41' => 'Transação não autorizada. Cartão bloqueado por perda.',
                        '43' => 'Transação não autorizada. Cartão bloqueado por roubo.',
                        '51' => 'Transação não autorizada. Limite excedido/sem saldo.',
                        '52' => 'Cartão com dígito de controle inválido.',
                        '53' => 'Transação não permitida. Cartão poupança inválido',
                        '54' => 'Transação não autorizada. Cartão vencido',
                        '55' => 'Transação não autorizada. Senha inválida',
                        '57' => 'Transação não permitida para o cartão',
                        '58' => 'Transação não permitida. Opção de pagamento inválida.',
                        '59' => 'Transação não autorizada. Suspeita de fraude.',
                        '60' => 'Transação não autorizada.',
                        '61' => 'Banco emissor indisponível.',
                        '62' => 'Transação não autorizada. Cartão restrito para uso doméstico',
                        '63' => 'Transação não autorizada. Violação de segurança',
                        '64' => 'Transação não autorizada. Valor abaixo do mínimo exigido pelo banco emissor.',
                        '65' => 'Transação não autorizada. Excedida a quantidade de transações para o cartão.',
                        '67' => 'Transação não autorizada. Cartão bloqueado para compras hoje.',
                        '70' => 'Transação não autorizada. Limite excedido/sem saldo.',
                        '72' => 'Cancelamento não efetuado. Saldo disponível para cancelamento insuficiente.',
                        '74' => 'Transação não autorizada. A senha está vencida.',
                        '75' => 'Senha bloqueada. Excedeu tentativas de cartão.',
                        '76' => 'Cancelamento não efetuado. Banco emissor não localizou a transação original',
                        '77' => 'Cancelamento não efetuado. Não foi localizado a transação original',
                        '78' => 'Transação não autorizada. Cartão bloqueado primeiro uso.',
                        '80' => 'Transação não autorizada. Divergencia na data de transação/pagamento.',
                        '82' => 'Transação não autorizada. Cartão inválido.',
                        '83' => 'Transação não autorizada. Erro no controle de senhas',
                        '85' => 'Transação não permitida. Falha da operação.',
                        '86' => 'Transação não permitida. Falha da operação.',
                        '89' => 'Erro na transação.',
                        '90' => 'Transação não permitida. Falha da operação.',
                        '91' => 'Transação não autorizada. Banco emissor temporariamente indisponível.',
                        '92' => 'Transação não autorizada. Tempo de comunicação excedido.',
                        '93' => 'Transação não autorizada. Violação de regra - Possível erro no cadastro.',
                        '96' => 'Falha no processamento.',
                        '97' => 'Valor não permitido para essa transação.',
                        '98' => 'Sistema/comunicação indisponível.',
                        '99' => 'Sistema/comunicação indisponível.',
                        '999' => 'Sistema/comunicação indisponível.',
                        'AA' => 'Tempo Excedido',
                        'AC' => 'Transação não permitida. Cartão de débito sendo usado com crédito. Use a função débito.',
                        'AE' => 'Tente Mais Tarde',
                        'AF' => 'Transação não permitida. Falha da operação.',
                        'AG' => 'Transação não permitida. Falha da operação.',
                        'AH' => 'Transação não permitida. Cartão de crédito sendo usado com débito. Use a função crédito.',
                        'AI' => 'Transação não autorizada. Autenticação não foi realizada.',
                        'AJ' => 'Transação não permitida. Transação de crédito ou débito em uma operação que permite apenas Private Label. Tente novamente selecionando a opção Private Label.',
                        'AV' => 'Transação não autorizada. Dados Inválidos',
                        'BD' => 'Transação não permitida. Falha da operação.',
                        'BL' => 'Transação não autorizada. Limite diário excedido.',
                        'BM' => 'Transação não autorizada. Cartão Inválido',
                        'BN' => 'Transação não autorizada. Cartão ou conta bloqueado.',
                        'BO' => 'Transação não permitida. Falha da operação.',
                        'BP' => 'Transação não autorizada. Conta corrente inexistente.',
                        'BV' => 'Transação não autorizada. Cartão vencido',
                        'CF' => 'Transação não autorizada.C79:J79 Falha na validação dos dados.',
                        'CG' => 'Transação não autorizada. Falha na validação dos dados.',
                        'DA' => 'Transação não autorizada. Falha na validação dos dados.',
                        'DF' => 'Transação não permitida. Falha no cartão ou cartão inválido.',
                        'DM' => 'Transação não autorizada. Limite excedido/sem saldo.',
                        'DQ' => 'Transação não autorizada. Falha na validação dos dados.',
                        'DS' => 'Transação não permitida para o cartão',
                        'EB' => 'Transação não autorizada. Limite diário excedido.',
                        'EE' => 'Transação não permitida. Valor da parcela inferior ao mínimo permitido.',
                        'EK' => 'Transação não permitida para o cartão',
                        'FA' => 'Transação não autorizada.',
                        'FC' => 'Transação não autorizada. Ligue Emissor',
                        'FD' => 'Transação negada. Reter cartão condição especial',
                        'FE' => 'Transação não autorizada. Divergencia na data de transação/pagamento.',
                        'FF' => 'Cancelamento OK',
                        'FG' => 'Transação não autorizada. Ligue AmEx.',
                        'FG' => 'Ligue 08007285090',
                        'GA' => 'Aguarde Contato',
                        'GD' => 'Transação não permitida.',
                        'HJ' => 'Transação não permitida. Código da operação inválido.',
                        'IA' => 'Transação não permitida. Indicador da operação inválido.',
                        'JB' => 'Transação não permitida. Valor da operação inválido.',
                        'KA' => 'Transação não permitida. Falha na validação dos dados.',
                        'KB' => 'Transação não permitida. Selecionado a opção incorrente.',
                        'KE' => 'Transação não autorizada. Falha na validação dos dados.',
                        'N7' => 'Transação não autorizada. Código de segurança inválido.',
                        'R1' => 'Transação não autorizada. Cartão inadimplente (Do not honor).',
                        'U3' => 'Transação não permitida. Falha na validação dos dados.'
                    ];

        return array_key_exists($codigo, $mensagens) ? $mensagens[$codigo] : '';
    }

  /* 

    NOVA TABELA DE STATUS - VERSAO 3
    link -> https://gateway.dev.yapay.com.br/#/tabela-status

    1	Pago e Capturado	Transação está autorizada e confirmada na instituição financeira
    2	Pago e Não Capturado	Transação está apenas autorizada, aguardando confirmação (captura)
    3	Não Pago	Transação negada pela instituição financeira
    5	Transação em Andamento	Comum para pagamentos cartão redirect ou pagamentos com autenticação
    8	Aguardando Pagamento	Comum para pagamentos com boletos e pedidos em reprocessamento
    9	Falha na Operadora	Houve um problema no processamento com a adquirente
    13	Cancelada	Transação cancelada na adquirente
    14	Estornada	A venda foi estornada na adquirente
    15	Em Análise de Fraude	A transação foi enviada para o sistema de análise de riscos. Status transitório
    17	Recusado pelo AntiFraude	A transação foi negada pelo sistema análise de risco
    18	Falha na Antifraude	Falha. Não foi possível enviar pedido para a análise de Risco, porém será reenviado
    21	Boleto Pago a menor	O boleto foi pago com valor menor do emitido
    22	Boleto Pago a maior	O boleto foi pago com valor maior do emitido
    23	Estorno Parcial	A venda estonada na adquirente parcialmente
    24	Estorno Não Autorizado	O Estorno não foi autorizado pela adquirente
    25	Falha no estorno	Falha ao enviar estorno para a operadora
    27	Cancelamento parcial	Pedido parcialmente cancelado na adquirente
    31	Transação já Paga	Transação já existente e finalizada na adquirente
    40	Aguardando Cancelamento	Processo de cancelamento em andamento

        */ 
    public function getStatusV3($codigo){

            $status = [ 1 => 'paga',
                        2 => 'aguardando_capturar',
                        3 => 'negada',
                        5 => 'aguardando_pagamento',
                        8 => 'aguardando_pagamento',
                        9 => 'negada',
                        13 => 'cancelada',
                        14 => 'estorno',
                        15 => 'aguardando_pagamento',
                        17 => 'negada',
                        18 => 'negada',
                        23 => 'estorno',
                        24 => 'estorno',
                        25 => 'estorno',
                        27 => 'estorno',
                        31 => 'erro',
                        40 => 'cancelada'];
    
            return (array_key_exists($codigo, $status)) ?  $status[$codigo] : false;
    }

    public function getStatusV1($codigo){

            $status = [ 1 => 'aguardando_capturar',
                        2 => 'aguardando_pagamento',
                        3 => 'cancelada',
                        4 => 'erro',
                        5 => 'estorno',
                        6 => 'negada',
                        7 => 'paga'];
    
            return (array_key_exists($codigo, $status)) ?  $status[$codigo] : false;
    }
    
    public function getMensagemV3($codigo){
    
            $status = [ 1 => 'Pago e Capturado',
                        2 => 'Pago e Não Capturado',
                        3 => 'Não Pago',
                        5 => 'Transação em Andamento',
                        8 => 'Aguardando Pagamento',
                        9 => 'Falha na Operadora',
                        13 => 'Cancelada',
                        14 => 'Estornada',
                        15 => 'Em Análise de Fraude',
                        17 => 'Recusado pelo AntiFraude',
                        18 => 'Falha na Antifraude	Falha',
                        21 => 'Boleto Pago a menor',
                        22 => 'Boleto Pago a maior',
                        23 => 'Estorno Parcial',
                        24 => 'Estorno Não Autorizado',
                        25 => 'Falha no estorno',
                        27 => 'Cancelamento parcial',
                        31 => 'Transação já Paga',
                        40 => 'Aguardando Cancelamento'];
    
            return (array_key_exists($codigo, $status)) ?  $status[$codigo] : false;
    }

    public function getStatus($formaController){

        $return = '';
        switch ($formaController) {
            case 'SuperPay':
                switch ($this->ecm_transacao_status_id) {
                    case 1: $return = self::STATUS_AGUARDANDO_CAPTURAR;
                            break;
                    case 2: $return = self::STATUS_AGUARDANDO_PAGAMENTO;
                            break;
                    case 3: $return = self::STATUS_CANCELADA;
                            break;
                    case 4: $return = self::STATUS_ERRO;
                            break;
                    case 5: $return = self::STATUS_ESTORNO;
                            break;
                    case 6: $return = self::STATUS_NEGADA;
                            break;
                    case 7: $return = self::STATUS_PAGA;
                            break;
                }
            break;

            case 'CieloApi2':
                switch ($this->ecm_transacao_status_id) {
                    case 1: $return = self::STATUS_AGUARDANDO_PAGAMENTO;
                            break;
                    case 2: $return = self::STATUS_PAGA;
                            break;
                    case 3: $return = self::STATUS_NEGADA;
                            break;
                    case 4: $return = self::STATUS_CANCELADA;
                            break;
                    case 5: $return = self::STATUS_CANCELADA;
                            break;
                    case 6: $return = self::STATUS_ERRO;
                            break;
                    case 7: $return = self::STATUS_PAGA;
                            break;
                    case 8: $return = self::STATUS_CANCELADA;
                            break;
                }
            break;

            case 'CieloApi3':
                switch ($this->ecm_transacao_status_id) {
                    case 0: $return = self::STATUS_AGUARDANDO_PAGAMENTO;
                            break;
                    case 1: $return = self::STATUS_AGUARDANDO_CAPTURAR;
                            break;
                    case 2: $return = self::STATUS_PAGA;
                            break;
                    case 3: $return = self::STATUS_NEGADA;
                            break;
                    case 10: $return = self::STATUS_CANCELADA;
                            break;
                    case 11: $return = self::STATUS_CANCELADA;
                            break;
                    case 12: $return = self::STATUS_AGUARDANDO_PAGAMENTO;
                            break;
                    case 13: $return = self::STATUS_CANCELADA;
                        break;
                    case 20: $return = self::STATUS_AGUARDANDO_CAPTURAR;
                        break;
                }
            break;

            case 'SuperPayV3':
            case 'SuperPayRecorrencia':
                switch ($this->ecm_transacao_status_id) {
                    case 1 : $return = self::STATUS_PAGA;
                        break;
                    case 2 : $return = self::STATUS_AGUARDANDO_CAPTURAR;
                        break;
                    case 5 : 
                    case 8 : 
                    case 15: $return = self::STATUS_AGUARDANDO_PAGAMENTO;
                        break;
                    case 9 : 
                    case 3 : 
                    case 17: 
                    case 18: $return = self::STATUS_NEGADA;
                        break;
                    case 14: 
                    case 23: 
                    case 24: 
                    case 25: 
                    case 27: $return = self::STATUS_ESTORNO;
                        break;
                    case 31: $return = self::STATUS_ERRO;
                        break;
                    case 13: 
                    case 40: $return = self::STATUS_CANCELADA;
                        break;
                }
            break;
        }

        return $return;
    }
    
}
