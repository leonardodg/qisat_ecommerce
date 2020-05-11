<?php
namespace CursoPresencial\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmCursoPresencialTurma Entity.
 *
 * @property int $id * @property int $ecm_produto_id * @property \CursoPresencial\Model\Entity\EcmProduto $ecm_produto * @property int $carga_horaria * @property int $vagas_total * @property int $vagas_preenchidas * @property float $valor * @property string $valor_produto * @property string $status * @property \CursoPresencial\Model\Entity\EcmCursoPresencialData[] $ecm_curso_presencial_data * @property \CursoPresencial\Model\Entity\EcmInstrutor[] $ecm_instrutor */
class EcmCursoPresencialTurma extends Entity
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
