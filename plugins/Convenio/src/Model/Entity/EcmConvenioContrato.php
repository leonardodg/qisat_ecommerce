<?php
namespace Convenio\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmConvenioContrato Entity.
 *
 * @property int $id * @property \Cake\I18n\Time $data_inicio_convenio * @property \Cake\I18n\Time $data_fim_convenio * @property string $arquivo * @property string $contrato_ativo * @property string $contrato_assinado * @property \Cake\I18n\Time $data_registro * @property \Convenio\Model\Entity\EcmConvenio[] $ecm_convenio */
class EcmConvenioContrato extends Entity
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
