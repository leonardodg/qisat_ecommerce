<?php
namespace CursoPresencial\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmCursoPresencialLocal Entity.
 *
 * @property int $id * @property string $nome * @property int $mdl_cidade_id * @property \CursoPresencial\Model\Entity\MdlCidade $mdl_cidade * @property string $endereco */
class EcmCursoPresencialLocal extends Entity
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
