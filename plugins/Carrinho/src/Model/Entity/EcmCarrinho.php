<?php
namespace Carrinho\Model\Entity;

use Cake\ORM\Entity;
use Cupom\Model\Entity\EcmCupom;
use Produto\Model\Entity\EcmProduto;
use Promocao\Model\Entity\EcmPromocao;

/**
 * EcmCarrinho Entity.
 *
 * @property int $id * @property \Cake\I18n\Time $data * @property int $mdl_user_id * @property \Carrinho\Model\Entity\MdlUser $mdl_user * @property string $status * @property int $ecm_cupom_id * @property \Carrinho\Model\Entity\EcmCupom $ecm_cupom * @property \Cake\I18n\Time $edicao * @property int $ecm_alternative_host_id * @property \Carrinho\Model\Entity\EcmAlternativeHost $ecm_alternative_host * @property int $ecm_user_modified * @property \Carrinho\Model\Entity\EcmCarrinhoItem[] $ecm_carrinho_item * @property \Carrinho\Model\Entity\EcmPromocao[] $ecm_promocao */
class EcmCarrinho extends Entity
{
    const STATUS_EM_ABERTO = 'Em Aberto';
    const STATUS_FINALIZADO = 'Finalizado';
    const STATUS_CANCELADO = 'Cancelado';

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

    protected $_virtual = ['ecm_carrinho_item'];


    public function addItem(EcmCarrinhoItem $item){
        $listaItem = $this->get('ecm_carrinho_item');

        $chaveLista = $item->ecm_produto->id;
        if(!is_null($item->get('ecm_curso_presencial_turma'))){
            $chaveLista .= '-'.$item->ecm_curso_presencial_turma->id;
        }

        if(!is_null($item->get('aplicacao'))){
            $chaveLista .= '-A'.$item->aplicacao->id;
        }

        if(!is_null($listaItem) && array_key_exists($chaveLista, $listaItem)) {
            $this->removeItem($item);
        }

        $listaItem[$chaveLista] = $item;
        $this->set('ecm_carrinho_item', $listaItem);
    }

    public function removeItem(EcmCarrinhoItem $item){
        $listaItem = $this->get('ecm_carrinho_item');

        $chave = $item->ecm_produto->id;

        if(!is_null($item->get('ecm_curso_presencial_turma'))){
            $chave .= '-'.$item->ecm_curso_presencial_turma->id;
        }

        if(!is_null($item->get('aplicacao'))){
            $chave .= '-A'.$item->aplicacao->id;
        }

        if(!is_null($listaItem) && array_key_exists($chave, $listaItem)) {
            unset($listaItem[$chave]);
        }
    }

    public function getItem(EcmCarrinhoItem $item){
        $listaItem = $this->get('ecm_carrinho_item');

        $chave = $item->ecm_produto->id;

        if(!is_null($item->get('ecm_curso_presencial_turma'))){
            $chave .= '-'.$item->ecm_curso_presencial_turma->id;
        }

        if(!is_null($item->get('aplicacao'))){
            $chave .= '-A'.$item->aplicacao->id;
        }

        if(!is_null($listaItem) && array_key_exists($chave, $listaItem)) {
            return $listaItem[$chave];
        }

        return null;
    }

    public function existeItem(EcmCarrinhoItem $item){
        $listaItem = $this->get('ecm_carrinho_item');
        $chave = $item->ecm_produto->id;

        if(!is_null($item->get('ecm_curso_presencial_turma'))){
            $chave .= '-'.$item->ecm_curso_presencial_turma->id;
        }

        if(!is_null($item->get('aplicacao'))){
            $chave .= '-A'.$item->aplicacao->id;
        }

        return isset($listaItem[$chave]);
    }

    /**
     * Função responsável verificar o desconto com maior valor
     * Retorna um array com o seguinte formato
     * [
     * "Cupom" => Objeto ou não retorna o indice,
     * "Promocao"  => Objeto ou não retorna o indice,
     * 'descontoPromocao' => float ou não retorna o indice,
     * 'descontoCupom' => float ou não retorna o indice,
     * "descontoTotal" => float,
     * "valorTotal" => float
     * ]
     *
     * Caso o desconto de uma promoção ou um cupom não for utilizado o array não retornará o objeto em específico
     *
     * @param EcmProduto $produto
     * @param Array $listaPromocoes
     * @param EcmCupom $cupom
     *
     * @return Array ou null
     *
     */
    public static function verificarDesconto(EcmProduto $produto, Array $listaPromocoes = array(), EcmCupom $cupom = NULL, $listaDescontoTrilha = null){

        $promocaoMaiorDesconto = null;
        $maiorDescontoPromocao = 0;

        if(count($listaPromocoes) > 0){
            foreach($listaPromocoes as $promocao){
                if(!is_a($promocao, 'Promocao\Model\Entity\EcmPromocao')){
                    $promocao = new EcmPromocao($promocao->toArray());
                }
                $descontoPromocaoAtual = EcmCarrinho::calcularValorDesconto($produto, null, $promocao);

                if($descontoPromocaoAtual > $maiorDescontoPromocao){
                    $maiorDescontoPromocao = $descontoPromocaoAtual;
                    $promocaoMaiorDesconto = $promocao;
                }
            }
            if(!is_null($promocaoMaiorDesconto))
                if($promocaoMaiorDesconto->arredondamento == 'true' && is_null($promocaoMaiorDesconto->descontovalor)
                        && !is_null($promocaoMaiorDesconto->descontoporcentagem)){
                    $maiorDescontoPromocao = round($maiorDescontoPromocao);
                }
        }

        $cupomMaiorDesconto = null;
        $promocaoAcumulada = false;
        $maiorDescontoCupom = 0;

        if(!is_null($cupom)){
            //Verificar se cupom permitir desconto para o produto
            if(EcmCupom::cupomPermiteDescontoProduto($cupom, $produto)) {
                //Verifica se o cupom permite acumular a promoção
                $acumularPromocao = $cupom->descontosobretabela == 'true' ? null : $promocaoMaiorDesconto;
                $descontoCupomAtual = EcmCarrinho::calcularValorDesconto($produto, $cupom, $acumularPromocao);

                $maiorDescontoCupom = $descontoCupomAtual;
                $cupomMaiorDesconto = $cupom;
                $promocaoAcumulada = !is_null($acumularPromocao);
                if($cupomMaiorDesconto->arredondamento == 'true' && is_null($cupomMaiorDesconto->descontovalor)
                    && !is_null($cupomMaiorDesconto->descontoporcentagem)){
                    $maiorDescontoCupom = round($maiorDescontoCupom);
                }
            }
        }

        $retorno = null;
        $desconto = 0;
        //$arredondamento = false;
        if(!is_null($promocaoMaiorDesconto) || !is_null($cupomMaiorDesconto)) {
            if (!$promocaoAcumulada) {//Verifica se promoção não foi acumulada
                if ($maiorDescontoPromocao > $maiorDescontoCupom) {
                    $retorno['promocao'] = $promocaoMaiorDesconto;
                    $retorno['descontoPromocao'] = $maiorDescontoPromocao;
                    $retorno['descontoTotal'] = $maiorDescontoPromocao;
                    $desconto = $maiorDescontoPromocao;
                    //$arredondamento = ($promocaoMaiorDesconto->arredondamento == 'true');
                } else {
                    $retorno['cupom'] = $cupomMaiorDesconto;
                    $retorno['descontoCupom'] = $maiorDescontoCupom;
                    $retorno['descontoTotal'] = $maiorDescontoCupom;
                    $desconto = $maiorDescontoCupom;
                    //$arredondamento = ($cupomMaiorDesconto->arredondamento == 'true');
                }
            } else {//Se foi acumulada retorna tudo
                $retorno['promocao'] = $promocaoMaiorDesconto;
                $retorno['descontoPromocao'] = $maiorDescontoPromocao;
                $retorno['cupom'] = $cupomMaiorDesconto;
                $retorno['descontoCupom'] = $maiorDescontoCupom-$maiorDescontoPromocao;

                //Valor total do desconto
                $retorno['descontoTotal'] = $maiorDescontoCupom;
                $desconto = $maiorDescontoCupom;

                /*if(($cupomMaiorDesconto->arredondamento == 'true') ||
                    ($promocaoMaiorDesconto->arredondamento == 'true')) {
                    $arredondamento = true;
                }*/
            }
        }

        $descontoFase = null;
        if(!is_null($listaDescontoTrilha)) {
            foreach ($listaDescontoTrilha as $valorDesconto) {
                $descontoFase += $valorDesconto->desconto;
            }

            if($descontoFase > $desconto){
                $desconto = $descontoFase;

                $retorno = [
                    'descontoFase' => $desconto,
                    'descontoCursos' => $listaDescontoTrilha
                ];
            }
        }

        if($desconto > 0){
            $retorno['valorTotal'] = $produto->preco - $desconto;

            /*if($arredondamento)
                $retorno['valorTotal'] = round($retorno['valorTotal']);*/

            if($retorno['valorTotal'] < 0){
                $retorno['valorTotal'] = 0;
            }
        }

        return $retorno;
    }

    /*
     * Função responsável por calcular o total de desconto para um produto, retornando o total de desconto.
     * Será calculado o valor conforme os parametros informados, se for informado $cupom e $promocao o desconto
     * será acumulado.
     *
     * @param EcmProduto $produto
     * @param EcmCupom $cupom
     * @param EcmPromocao $promocao
     *
     * @return float
     *
     * */
    public static function calcularValorDesconto(EcmProduto $produto, EcmCupom $cupom = null, EcmPromocao $promocao = null)
    {
        $valorProduto = $produto->preco;
        $valorDesconto = 0;

        if(!is_null($promocao))
        {
            if(!is_null($promocao->descontovalor))
            {
                $valorDesconto = $promocao->descontovalor;
            }else
            {
                $valorDesconto = $valorProduto * ($promocao->descontoporcentagem / 100);
            }
        }

        if(!is_null($cupom))
        {
            $valorCalculo = $valorProduto;
            if($cupom->descontosobretabela == 'false')
            {
                $valorCalculo = $valorProduto - $valorDesconto;
                $valorCalculo = $valorCalculo <= 0 ? 0 : $valorCalculo;
            }

            if(!empty($cupom->descontovalor))
            {
                $valorDesconto += $cupom->descontovalor;
            }else
            {
                $valorDesconto += $valorCalculo * ($cupom->descontoporcentagem / 100);
            }
        }

        return $valorDesconto;
    }

    /*
     * Função responsável por calcular os itens do carrinho, se um cupom for informado será feita a verificação
     * de desconto entre promoção e cupom e o cupom será relacionado ao item.
     *
     * @param EcmCupom $cupom
     *
     * @return void
     *
     * */
    public function calcularItens(EcmCupom $cupom = null){
        foreach($this->ecm_carrinho_item as $chave => $item){
            $produto = $item->get('ecm_produto');
            $promocao = $item->get('ecm_promocao');

            if(is_null($promocao) && is_null($cupom))
                continue;

            $promocao = !is_null($promocao)? [$promocao] : array();


            $desconto = $this->verificarDesconto($produto, $promocao, $cupom);

            if (!is_null($desconto)) {
                if(isset($desconto['cupom'])) {
                    $item->set('ecm_cupom', $cupom);
                    $item->set('ecm_cupom_id', $cupom->get('id'));
                }

                if(!isset($desconto['promocao'])){
                    $item->set('ecm_promocao', null);
                    $item->set('ecm_promocao_id', null);
                }

                $valorItem = $desconto['valorTotal'];
                $item->set('valor_produto_desconto', $valorItem);
            }

            $this->removeItem($item);
            $this->addItem($item);
        }
    }
    /*
     * Função responsável por calcular o total do carrinho somando todos os itens
     *
     * @return float
     *
     * */
    public function calcularTotal(){
        $total = 0;
        if(!is_null($this->ecm_carrinho_item))
            foreach($this->ecm_carrinho_item as $chave => $item){

                if($item->status == 'Adicionado') {
                    $valor = $item->get('valor_produto_desconto');
                    $quantidade = $item->get('quantidade');

                    $total += $valor * $quantidade;
                }
            }

        return number_format($total, 2, '.', '');
    }

    /**
     * Função resposável por retornar o valor da parcela 
     * @param $parcelas numero de parcelas da venda
     * @param $primeira valor da primeira parcela?
     * 
     * @return float
     * */
    public function calcularParcela($parcelas, $primeira = false){
        $total = $this->calcularTotal();

        $valor_parcela = floor(($total / $parcelas) * 100) * .01;
        $diferenca = $total - ($valor_parcela * $parcelas);
        if($primeira)
            $valor_parcela += $diferenca;

        return number_format($valor_parcela, 2, '.', '');
    }

    /*
     * Função responsável por criar uma nova instancia da classe com seus valores, sem as relações
     *
     * @return EcmCarrinho
     * */
    public function novaEntidadeComValores(){
        $nova = new $this;
        $nova->set('data', $this->data);
        $nova->set('mdl_user_id', $this->mdl_user_id);
        $nova->set('status', $this->status);
        $nova->set('edicao', $this->edicao);
        $nova->set('ecm_alternative_host_id', $this->ecm_alternative_host_id);

        return $nova;
    }

    /*
     * Função responsável por adicionar itens de uma lista com um status definido no parâmetro, podem
     * inserir um status default para esses itens
     *
     * @param $listaItens array com objetos EcmCarrinhoItem
     * @param $addStatusIgual string para comparar os status que serão adicionados
     * @param $setStatusAdd string com status default para os itens adicionados
     *
     * @return void
     * */
    public function addItensPorStatus($listaItens, $addStatusIgual, $setStatusAdd = EcmCarrinhoItem::STATUS_ADICIONADO){
        foreach($listaItens as $key => $item){
            if($item->get('status') == $addStatusIgual) {
                $itemClone = clone $item;
                $itemClone->set('status', $setStatusAdd);
                $itemClone->set('ecm_carrinho_id', $this->id);
                $this->addItem($itemClone);
            }
        }
    }

    /*
     * Função responsável por remover itens com status igual ao informado por parâmetro
     *
     * @param $status string para comparar os status dos itens que serão removidos
     *
     * @return void
     * */
    public function removeItensPorStatus($status){
        foreach($this->get('ecm_carrinho_item') as $key => $item){
            if($item->get('status') == $status)
                unset($this->get('ecm_carrinho_item')[$key]);
        }
    }

    /*
     * Função responsável por verificar se há um item com o status informado por parâmetro
     *
     * @param $status string para comparar os status dos itens
     *
     * @return void
     * */
    public function checkItensStatus($status){
        foreach($this->get('ecm_carrinho_item') as $key => $item){
            if($item->get('status') == $status)
                return true;
        }
        return false;
    }

    /*
     * @return void
     * */
    public static function countItems($ecm_carrinho_items){
        $count = 0;
        foreach($ecm_carrinho_items as $ecm_carrinho_item) {
            if (($ecm_carrinho_item->get('status')) == "Adicionado") {
                $count = count($ecm_carrinho_item->get('ecm_produto')->get('mdl_course'));
            }
        }
        return $count;
    }
}