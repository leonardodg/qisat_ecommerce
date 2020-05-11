<?php
namespace Repasse\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmRepasse Entity.
 *
 * @property int $id * @property string $assunto_email * @property string $corpo_email * @property string $chave * @property \Cake\I18n\Time $data_registro * @property int $mdl_user_id * @property int $mdl_usermodified_id * @property \Repasse\Model\Entity\MdlUser $mdl_user * @property string $equipe * @property \Cake\I18n\Time $data_modificacao * @property string $status * @property int $ecm_alternative_host_id * @property \Repasse\Model\Entity\EcmAlternativeHost $ecm_alternative_host * @property string $observacao * @property int $ecm_repasse_categorias_id * @property \Repasse\Model\Entity\EcmRepasseCategoria $ecm_repasse_categoria * @property int $ecm_repasse_origem_id * @property \Repasse\Model\Entity\EcmRepasseOrigem $ecm_repasse_origem */
class EcmRepasse extends Entity
{

    const STATUS_EM_ATENDIMENTO = 'Em Atendimento';
    const STATUS_FINALIZADO = 'Finalizado';
    const STATUS_NAO_ATENDIDO = 'Não Atendido';
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
