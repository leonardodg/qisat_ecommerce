<?php
namespace Configuracao\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmRedeSocial Entity.
 *
 * @property int $id * @property string $nome * @property int $ecm_imagem_id * @property \Configuracao\Model\Entity\EcmImagem $ecm_imagem * @property \Configuracao\Model\Entity\EcmInstrutorRedeSocial[] $ecm_instrutor_rede_social */
class EcmRedeSocial extends Entity
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
