<?php
namespace Produto\Criterio;

use Cake\ORM\TableRegistry;
use Carrinho\Model\Entity\EcmCarrinhoItem;

class Criterio_Vagas implements CriterioInterface
{
    private $criterio = CRITERIO_TIPO_VAGAS;

    public function review($request, $carrinho, $args = array())
    {
        if(isset($request["presencial"])){
            $item = new EcmCarrinhoItem();

            $this->EcmProduto = TableRegistry::get('EcmProduto');
            $ecmProduto = $this->EcmProduto->get($request["produto"]);
            $item->set("ecm_produto", $ecmProduto);

            $this->EcmCursoPresencialTurma = TableRegistry::get('EcmCursoPresencialTurma');
            $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->get($request["presencial"]);
            $item->set("ecm_curso_presencial_turma", $ecmCursoPresencialTurma);

            $quantidade = 1;
            if(isset($request["quantidade"]))
                $quantidade = $request["quantidade"];

            if ($carrinho->existeItem($item)) {
                $item = $carrinho->getItem($item);
                if($item->get("status") == "Adicionado")
                    $quantidade += $item->get("quantidade");
            }

            if ($quantidade + $ecmCursoPresencialTurma->vagas_preenchidas > $ecmCursoPresencialTurma->vagas_total) {
                return ["sucesso" => false,
                    "mensagem" => "A quantidade solicitada ultrapassa o limite do criterio ".$this->criterio
                ];
            }
            return ["sucesso" => true, "mensagem" => "Quantidade aceita"];
        }
        return ["sucesso" => false, "mensagem" => "Para este criterio, favor informe a turma"];
    }

    public function get_args($args = array())
    {
        return true;
    }
}
