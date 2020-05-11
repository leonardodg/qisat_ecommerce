<?php
namespace Produto\Criterio;

use Cake\ORM\TableRegistry;
use Carrinho\Model\Entity\EcmCarrinhoItem;

class Criterio_Quantidade implements CriterioInterface
{
    private $criterio = CRITERIO_TIPO_QUANTIDADE;

    public function review($request, $carrinho, $args = array())
    {
        $item = new EcmCarrinhoItem();

        $this->EcmProduto = TableRegistry::get('EcmProduto');
        $ecmProduto = $this->EcmProduto->get($request["produto"]);
        $item->set("ecm_produto", $ecmProduto);

        if(isset($request["presencial"])){
            $this->EcmCursoPresencialTurma = TableRegistry::get('EcmCursoPresencialTurma');
            $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->get($request["presencial"]);
            $item->set("ecm_curso_presencial_turma", $ecmCursoPresencialTurma);
        }

        $quantidade = 1;
        if(isset($request["quantidade"]))
            $quantidade = $request["quantidade"];

        if ($carrinho->existeItem($item)) {
            $item = $carrinho->getItem($item);
            if($item->get("status") == "Adicionado")
                $quantidade += $item->get("quantidade");
        }

        if ($quantidade > $args) {
            return ["sucesso" => false,
                "mensagem" => "A quantidade solicitada ultrapassa o limite do criterio ".$this->criterio
            ];
        }
        return ["sucesso" => true, "mensagem" => "Quantidade aceita"];
    }

    public function get_args($args = array())
    {
        foreach($args as $arg){
            if($arg['criterio'] == $this->criterio && !empty($arg['quantidade'])){
                return intval($arg['quantidade']);
            }
        }
        return false;
    }
}
