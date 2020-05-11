<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmLogAcao Entity.
 *
 * @property int $id * @property int $mdl_user_id * @property \App\Model\Entity\MdlUser $mdl_user * @property string $tabela * @property string $acao * @property string $chave * @property \Cake\I18n\Time $data * @property string $ip */
class EcmLogAcao extends Entity
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
