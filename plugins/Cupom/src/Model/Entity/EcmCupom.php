<?php
namespace Cupom\Model\Entity;

use Cake\ORM\Entity;
use Produto\Model\Entity\EcmProduto;

/**
 * EcmCupom Entity.
 *
 * @property int $id * @property int $tipo_aquisicao * @property \Cake\I18n\Time $datainicio * @property \Cake\I18n\Time $datafim * @property string $chave * @property float $descontovalor * @property float $descontoporcentagem * @property string $descricao * @property string $habilitado * @property int $numutilizacoes * @property int $numutilizacoesuser * @property string $nome * @property string $tipo * @property string $referencia * @property string $arredondamento * @property string $descontosobretabela * @property \Cupom\Model\Entity\EcmProduto[] $ecm_produto * @property \Cupom\Model\Entity\EcmTipoProduto[] $ecm_tipo_produto * @property \Cupom\Model\Entity\MdlUser[] $mdl_user * @property \Cupom\Model\Entity\EcmAlternativeHost[] $ecm_alternative_host */
class EcmCupom extends Entity
{

    /**
     * Cupom com id do usuário, sem código (token), ativado ao logar no sistema
     */
    const LOGIN_COM_USUARIO  = 0;

    /**
     * Cupom ativado para todos ao logar no sistema
     */
    const LOGIN_SEM_USUARIO  = 1;

    /**
     * Cupom com email do usuário, com código (token), ativado por campanha
     */
    const CAMPANHA_COM_EMAIL = 2;

    /**
     * Cupom sem email do usuário, com código (token), ativado por campanha
     */
    const CAMPANHA_SEM_EMAIL = 3;

    /**
     * Descrição dos tipos de aquisições dos cupons
     */
    const TIPOS_AQUISICOES = array(
        self::LOGIN_COM_USUARIO  => 'Cupom com id do usuário, sem chave (token), ativado ao logar no sistema',
        self::LOGIN_SEM_USUARIO  => 'Cupom ativado para todos ao logar no sistema',
        self::CAMPANHA_COM_EMAIL => 'Cupom com email do usuário, com chave (token), ativado por campanha',
        self::CAMPANHA_SEM_EMAIL => 'Cupom sem email do usuário, com chave (token), ativado por campanha'
    );

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

    /**
     * Função responsável por verificar se o produto poderá receber o desconto de um cupom.
     * Retorna true se permitir o desconto, caso contrário retorna false
     *
     * @param EcmCupom $cupom
     * @param EcmProduto $produto
     *
     * @return boolean
     *
     */
    public static function cupomPermiteDescontoProduto(EcmCupom $cupom, EcmProduto $produto){

        $listaTipos = $cupom->tipo == 'tipo'? $cupom->ecm_tipo_produto:$cupom->ecm_produto;

        if($cupom->tipo == 'tipo') {
            foreach ($produto->ecm_tipo_produto as $tipoProduto) {
                foreach ($cupom->ecm_tipo_produto as $listaTipoCupom) {
                    if ($tipoProduto['id'] == $listaTipoCupom['id'])
                        return true;
                }
            }
        }else{
            foreach ($cupom->ecm_produto as $produtoCupom) {
                if ($produto->id == $produtoCupom['id'])
                    return true;
            }
        }
        return false;
    }
}
