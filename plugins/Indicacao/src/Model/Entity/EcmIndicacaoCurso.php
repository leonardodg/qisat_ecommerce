<?php
namespace Indicacao\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmIndicacaoCurso Entity.
 *
 * @property int $id * @property int $mdl_user_id * @property \Indicacao\Model\Entity\MdlUser $mdl_user * @property int $ecm_indicacao_segmento_id * @property \Indicacao\Model\Entity\EcmIndicacaoSegmento $ecm_indicacao_segmento * @property string $tema * @property \Cake\I18n\Time $timemodified * @property int $ecm_alternative_host_id * @property \Indicacao\Model\Entity\EcmAlternativeHost $ecm_alternative_host * @property string $nome_base_antiga */
class EcmIndicacaoCurso extends Entity
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
