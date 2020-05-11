<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmPermissao Entity.
 *
 * @property int $id * @property string $action * @property string $controller * @property string $plugin * @property string $label * @property string $descricao * @property string $restricao * @property \App\Model\Entity\EcmGrupoPermissao[] $ecm_grupo_permissao */
class EcmPermissao extends Entity
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
