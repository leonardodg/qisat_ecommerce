<?php
namespace Vendas\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmVendaPresencial Entity.
 *
 * @property int $id * @property int $ecm_curso_presencial_turma_id * @property \Vendas\Model\Entity\EcmCursoPresencialTurma $ecm_curso_presencial_turma * @property int $pedido * @property \Cake\I18n\Time $data * @property int $mdl_user_id * @property \Vendas\Model\Entity\MdlUser $mdl_user * @property int $quantidade_reserva * @property string $nome * @property string $status * @property string $email */
class EcmVendaPresencial extends Entity
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
