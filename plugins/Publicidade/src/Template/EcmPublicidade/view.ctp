<?php
    $this->layout = false;
    if(!isset($_GET['convenio'])) $_GET['convenio'] = "";

    if($ecmPublicidade->tipo == 'Catalogo') {
        global $ecmProdutos, $ecmAlternativeHost, $ecmPromocao, $convenioDesconto;
        $ecmProdutos = $produtos;
        $ecmAlternativeHost = $ecmAlternativeHosts;
        $ecmPromocao = $ecmPromocoes;
        $convenioDesconto = $convenio_desconto;
    }
    if($ecmPublicidade->tipo == 'Convite') {
        global $linkInfo;
        $linkInfo = $linkCurso;
    }
    global $hostSite;
    $hostSite = $host;

    function retornarValor($id, $tipo = "valortabela", $entidade = "QiSat"){
        global $ecmProdutos, $ecmAlternativeHost, $ecmPromocao;
        $produto = $ecmProdutos[$id];
        $idEntidade = $ecmAlternativeHost[$entidade];
        if($tipo == 'valordesconto' || $tipo == 'porcentagem'){
            $promocoes = [];
            foreach($ecmPromocao as $promocao){
                if(array_key_exists($produto->id, $promocao->ecm_produto) &&
                        array_key_exists($idEntidade, $promocao->ecm_alternative_host))
                    $promocoes[] = $promocao;
            }
            $descontos = \Carrinho\Model\Entity\EcmCarrinho::verificarDesconto($produto, $promocoes);

            if($tipo == 'valordesconto'){
                if(isset($descontos['valorTotal']))
                    return number_format(round($descontos['valorTotal']), 2, ',', '.');
            } else {
                if($descontos['promocao']['descontoporcentagem'] == 0){
                    return round($descontos['valorTotal'] / $produto->preco * 100);
                } else {
                    return $descontos['promocao']['descontoporcentagem'];
                }
            }
        }
        return number_format(round($produto->preco), 2, ',', '.');
    }

    function retornaValorConvenio($id, $tipo = ""){
        global $ecmProdutos, $convenioDesconto;
        $produto = $ecmProdutos[$id];
        switch($tipo){
            case 'professor':
                return number_format(round($produto->preco-($produto->preco*$convenioDesconto['professor']/100)), 2, ',', '.');
                break;
            case 'estudante':
                return number_format(round($produto->preco-($produto->preco*$convenioDesconto['aluno']/100)), 2, ',', '.');
                break;
            case 'associado':
                return number_format(round($produto->preco-($produto->preco*$convenioDesconto['associado']/100)), 2, ',', '.');
                break;
            default:
                return number_format(round($produto->preco), 2, ',', '.');
                break;
        }
    }

    function retornarInfo($id){
        global $linkInfo;
        if(!is_null($linkInfo))
            return $linkInfo;
        global $ecmProdutos, $hostSite;
        $produto = $ecmProdutos[$id];
        if(isset($produto['ecm_produto_info']['url']))
            return $hostSite.$produto['ecm_produto_info']['url'];
        return $hostSite.'cursos';
    }

    include(WWW_ROOT . 'upload/publicidade/' . $ecmPublicidade->id . "/" . $ecmPublicidade->arquivo);
?>