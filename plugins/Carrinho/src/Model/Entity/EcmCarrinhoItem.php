<?php
namespace Carrinho\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmCarrinhoItem Entity.
 *
 * @property int $id * @property int $ecm_carrinho_id * @property \Carrinho\Model\Entity\EcmCarrinho $ecm_carrinho * @property int $ecm_produto_id * @property \Carrinho\Model\Entity\EcmProduto $ecm_produto * @property float $valor_produto * @property int $quantidade * @property string $status * @property int $ecm_curso_presencial_turma_id * @property \Carrinho\Model\Entity\EcmCursoPresencialTurma $ecm_curso_presencial_turma * @property float $valor_produto_desconto * @property int $ecm_promocao_id * @property \Carrinho\Model\Entity\EcmPromocao $ecm_promocao */
class EcmCarrinhoItem extends Entity
{

    const STATUS_ADICIONADO = 'Adicionado';
    const STATUS_REMOVIDO = 'Removido';
    const STATUS_AGUARDANDO_PAGAMENTO = 'Aguardando Pagamento';
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
