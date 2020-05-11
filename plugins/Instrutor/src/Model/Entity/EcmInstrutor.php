<?php
namespace Instrutor\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmInstrutor Entity.
 *
 * @property int $id * @property int $mdl_user_id * @property \Instrutor\Model\Entity\MdlUser $mdl_user * @property string $descricao * @property int $ecm_imagem_id * @property \Instrutor\Model\Entity\EcmImagem $ecm_imagem * @property \Instrutor\Model\Entity\EcmInstrutorArtigo[] $ecm_instrutor_artigo * @property \Instrutor\Model\Entity\EcmInstrutorRedeSocial[] $ecm_instrutor_rede_social * @property \Instrutor\Model\Entity\EcmProduto[] $ecm_produto */
class EcmInstrutor extends Entity
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
