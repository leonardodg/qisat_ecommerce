<?php
namespace Produto\Criterio;

class Criterio_Recorrente implements CriterioInterface
{
    public function review($request, $carrinho, $args = array())
    {
        return ["sucesso" => true, "mensagem" => "Criterio sem regras de negocio"];
    }

    public function get_args($args = array())
    {
        return true;
    }
}
