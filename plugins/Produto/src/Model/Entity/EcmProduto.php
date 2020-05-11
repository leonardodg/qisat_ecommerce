<?php
namespace Produto\Model\Entity;

use Cake\ORM\Entity;

/**
 * EcmProduto Entity.
 *
 * @property int $id * @property string $nome * @property float $preco * @property string $sigla * @property int $parcela * @property string $refcurso * @property string $moeda * @property string $habilitado * @property string $visivel * @property int $idtop * @property int $ordem * @property string $theme * @property \Produto\Model\Entity\EcmImagem[] $ecm_imagem * @property \Produto\Model\Entity\EcmTipoProduto[] $ecm_tipo_produto * @property \Produto\Model\Entity\MdlCourse[] $mdl_course */
class EcmProduto extends Entity
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

    public static function verificaParcelasTrilhas($produto, $formaPagamento, $maximoNumeroParcela, $valorMinimoParcela){

        $numeroPacelas = 0;

        if(!is_null($formaPagamento))
            $numeroPacelas = $formaPagamento->get('parcelas');

        if($maximoNumeroParcela->get('valor') < $numeroPacelas || $numeroPacelas == 0)
            $numeroPacelas = $maximoNumeroParcela->get('valor');

        $produto->parcela = $produto->parcela == 0? 1 : $produto->parcela;
        if($produto->parcela < $numeroPacelas)
            $numeroPacelas = $produto->parcela;

        for($i = $numeroPacelas; $i > 0;){
            $valor = $produto->valorTotal / $i;

            if($valor > $valorMinimoParcela->get('valor'))
                $i--;
            else {
                $numeroPacelas = $i;
                break;
            }
        }

        return $numeroPacelas;
    }

    /*
     * @return void
     * */
    public static function isTrilha($ecm_tipo_produtos){
        foreach($ecm_tipo_produtos as $ecm_tipo_produto) {
            if (($ecm_tipo_produto->get('id')) == 47) {
                return true;
            }
        }
        return false;
    }
}
