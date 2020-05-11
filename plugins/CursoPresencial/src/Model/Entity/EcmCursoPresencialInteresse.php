<?php
namespace CursoPresencial\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmCursoPresencialInteresse Entity.
 *
 * @property int $id * @property string $nome * @property string $email * @property string $telefone * @property int $ecm_curso_presencial_turma_id * @property \CursoPresencial\Model\Entity\EcmCursoPresencialTurma $ecm_curso_presencial_turma * @property int $ecm_produto_id * @property \CursoPresencial\Model\Entity\EcmProduto $ecm_produto */
class EcmCursoPresencialInteresse extends Entity
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
