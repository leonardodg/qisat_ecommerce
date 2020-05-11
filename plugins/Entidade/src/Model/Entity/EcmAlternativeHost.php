<?php
namespace Entidade\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmAlternativeHost Entity.
 *
 * @property int $id * @property string $host * @property string $shortname * @property string $fullname * @property string $path * @property string $email * @property string $googleanalytics * @property int $codigoorigemaltoqi * @property \Entidade\Model\Entity\EcmCarrinho[] $ecm_carrinho * @property \Entidade\Model\Entity\EcmCupom[] $ecm_cupom * @property \Entidade\Model\Entity\EcmProdutoEcmTipoProduto[] $ecm_produto_ecm_tipo_produto * @property \Entidade\Model\Entity\EcmPromocao[] $ecm_promocao * @property \Entidade\Model\Entity\MdlUser[] $mdl_user */
class EcmAlternativeHost extends Entity
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
