<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 15/06/2016
 * Time: 10:02
 */

namespace FormaPagamento\Controller;


interface FormaPagamentoAbstractController
{
    /*
     * Função responsável por definir uma requisição para um mecânismo de pagamento
     * */
    public function requisicao();

    /*
     * Função responsável por tratar o retorno do mecânismo de pagamento
     * */
    public function retorno();
}