<?php
namespace Convenio\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmConvenio Entity.
 *
 * @property int $id * @property int $ecm_convenio_tipo_instituicao_id * @property \Convenio\Model\Entity\EcmConvenioTipoInstituicao $ecm_convenio_tipo_instituicao * @property int $ecm_convenio_contrato_id * @property \Convenio\Model\Entity\EcmConvenioContrato $ecm_convenio_contrato * @property int $mdl_cidade_id * @property \Convenio\Model\Entity\MdlCidade $mdl_cidade * @property string $nome_responsavel * @property string $nome_coordenador * @property string $nome_instituicao * @property string $curso * @property string $disciplina * @property string $cargo * @property string $email * @property string $telefone * @property string $logo * @property \Cake\I18n\Time $data_registro * @property \Convenio\Model\Entity\EcmConvenioInteresse[] $ecm_convenio_interesse */
class EcmConvenio extends Entity
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
