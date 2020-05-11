<?php
namespace Convenio\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmConvenioInteresse Entity.
 *
 * @property int $id * @property int $ecm_convenio_id * @property \Convenio\Model\Entity\EcmConvenio $ecm_convenio * @property string $nome * @property string $telefone * @property string $email * @property string $chave_altoqi * @property \Cake\I18n\Time $data_registro */
class EcmConvenioInteresse extends Entity
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
