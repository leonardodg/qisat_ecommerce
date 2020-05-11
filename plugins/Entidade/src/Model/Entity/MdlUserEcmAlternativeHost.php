<?php
namespace Entidade\Model\Entity;

use Cake\ORM\Entity;

/**
 * MdlUserEcmAlternativeHost Entity.
 *
 * @property int $mdl_user_id * @property \Entidade\Model\Entity\MdlUser $mdl_user * @property int $ecm_alternative_host_id * @property \Entidade\Model\Entity\EcmAlternativeHost $ecm_alternative_host * @property string $numero * @property int $adimplente */
class MdlUserEcmAlternativeHost extends Entity
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
        '*' => true
    ];
}
