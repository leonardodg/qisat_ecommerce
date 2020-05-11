<?php
namespace Produto\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmProdutoInfo Entity.
 *
 * @property int $id * @property int $ecm_produto_id * @property \Produto\Model\Entity\EcmProduto $ecm_produto * @property string $titulo * @property string $chamada * @property string $persona * @property string $descricao * @property int $qtd_aulas * @property int $tempo_acesso * @property int $tempo_aula * @property int $carga_horaria * @property bool $material * @property bool $certificado_digital * @property bool $certificado_impresso * @property bool $forum * @property bool $tira_duvidas * @property bool $mobile * @property bool $software_demo * @property bool $simulador * @property bool $disponibilidade * @property string $metatag_titulo * @property string $metatag_key * @property string $metatag_descricao * @property string $url * @property \Produto\Model\Entity\EcmProdutoInfoArquivo[] $ecm_produto_info_arquivos * @property \Produto\Model\Entity\EcmProdutoInfoConteudo[] $ecm_produto_info_conteudo */
class EcmProdutoInfo extends Entity
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
