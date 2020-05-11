<?php
namespace Vendas\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmCursoPresencialEmailConfirmacao Entity.
 *
 * @property int $id * @property int $ecm_venda_presencial_id * @property \Vendas\Model\Entity\EcmVendaPresencial $ecm_venda_presencial * @property int $ecm_venda_id * @property \Vendas\Model\Entity\EcmVenda $ecm_venda * @property bool $enviado * @property \Cake\I18n\Time $data_envio */
class EcmCursoPresencialEmailConfirmacao extends Entity
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
