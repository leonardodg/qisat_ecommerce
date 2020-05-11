<?php
namespace Entidade\Model\Entity;

use Cake\ORM\Entity;

/**
 * MdlShopUser Entity.
 *
 * @property int $id * @property string $usuario * @property string $senha * @property string $cpf_cnpj * @property string $cpf_cnpj_bkp * @property string $crea * @property string $crea_tipo * @property string $crea_estado * @property string $nome * @property string $email * @property string $endereco * @property int $end_numero * @property string $end_complemento * @property string $bairro * @property string $cidade * @property string $estado * @property string $cep * @property string $telefone * @property string $celular * @property string $profissao * @property string $entidade_principal * @property int $chave_altoqi * @property string $origem * @property int $adimplente * @property \Cake\I18n\Time $data_registro * @property int $ecm_alternative_host_id * @property \Entidade\Model\Entity\EcmAlternativeHost $ecm_alternative_host * @property int $confirmado * @property int $existia_top * @property \Cake\I18n\Time $data_convenio * @property string $titulos * @property string $titulos_completo * @property string $registro_nacional * @property int $atualizado */
class MdlShopUser extends Entity
{

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
}
