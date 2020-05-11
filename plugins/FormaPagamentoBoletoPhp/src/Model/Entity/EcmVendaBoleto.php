<?php
namespace FormaPagamentoBoletoPhp\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmVendaBoleto Entity.
 *
 * @property int $id * @property int $parcela * @property int $ecm_venda_id * @property \FormaPagamentoBoletoPhp\Model\Entity\EcmVenda $ecm_venda * @property string $status * @property \Cake\I18n\Time $data */
class EcmVendaBoleto extends Entity
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
