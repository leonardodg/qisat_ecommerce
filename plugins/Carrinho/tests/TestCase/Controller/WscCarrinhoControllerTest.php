<?php
namespace Carrinho\Test\TestCase\Controller;

use Cake\Network\Http\Client;
use Cake\TestSuite\IntegrationTestCase;
use Carrinho\Controller\WscCarrinhoController;
use stdClass;

/**
 * Carrinho\Controller\WscCarrinhoController Test Case
 */
class WscCarrinhoControllerTest extends IntegrationTestCase
{
    /**
     * Entidades na base de teste:
     * ecm_carrinho
     * ecm_carrinho_item
     * ecm_carrinho_item_ecm_produto_aplicacao
     * ecm_carrinho_item_mdl_course
     * ecm_config
     * ecm_grupo_permissao
     * ecm_grupo_permissao_ecm_permissao
     * ecm_grupo_permissao_mdl_user
     * ecm_permissao
     * ecm_produto
     * ecm_produto_aplicacao
     * ecm_produto_ecm_aplicacao
     * ecm_produto_ecm_tipo_produto
     * ecm_tipo_produto
     * ecm_validacao_recaptcha
     * mdl_user
     */

    private $url = 'https://local-ecommerce.qisat.com.br/carrinho/wsc-carrinho/produtos_altoqi';
    private $accessToken ='eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzMiwiY29va2llVGltZSI6MTU3MTkyMTUzNCwiZXhwIjoxNTcyNTI2MzM0fQ.5ReRuai1CjOPIr4htLAvizwGmZahEr25b5DpS181MBw';
    private $http;
    private $dados = array(
        array( #0
            'produto'   => 'ebv',
            'edicao'    => 18,
            'app'       => 2,
            'licenca'   => 'LTEMP',
            'conexao'   => 0,
            'especiais' => 'ativa',
            'ativacao'  => 'usb',
            'modulos_ltemp' => 'Light'
        ),
        array( #1
            'produto'   => 'ebv',
            'edicao'    => 19,
            'app'       => 3,
            'licenca'   => 'VITALÍCIA',
            'conexao'   => 0,
            'especiais' => 'ativa',
            'ativacao'  => 'remota',
            'modulos_ltemp' => 'Essencial',
            'MOD-EB033'     => 'EB033'
        ),
        array( #2
            'produto'   => 'ebv',
            'edicao'    => 20,
            'app'       => 4,
            'licenca'   => 'LANUAL',
            'conexao'   => 0,
            'especiais' => 'ativa',
            'ativacao'  => 'online',
            'modulos_ltemp' => 'Top',
            'MOD-EB041'     => 'EB041'
        ),
        array( #3
            'produto'   => 'qib',
            'edicao'    => 18,
            'app'       => 2,
            'licenca'   => 'LTEMP',
            'conexao'   => 0,
            'especiais' => 'ativa',
            'ativacao'  => 'usb',
            'linhas_json' => '["hidraulica"]', // json_encode(array("hidraulica")),
            'ITEM-QIBALV' => 'QIBALV'
        ),
        array( #4
            'produto'   => 'qib',
            'edicao'    => 19,
            'app'       => 3,
            'licenca'   => 'VITALÍCIA',
            'conexao'   => 0,
            'especiais' => 'ativa',
            'ativacao'  => 'remota',
            'itens_json' => '["QIB","QBNEXT","QIBHID"]', // json_encode(array("QIB","QBNEXT","QIBHID"))
            'modulos_ltemp' => 'Light',
        ),
        array( #5
            'produto'   => 'qib',
            'edicao'    => 20,
            'app'       => 4,
            'licenca'   => 'LANUAL',
            'conexao'   => 0,
            'especiais' => 'ativa',
            'ativacao'  => 'online',
            'modulos_ltemp' => 'Light',
            'itens_json' => '["QIB","QBNEXT","QIBHID"]' // json_encode(array("QIB","QBNEXT","QIBHID"))
        ),
        array( #6
            'produto'   => 'mod',
            'conexao'   => 0,
            'especiais' => 'ativa',
            'ativacao'  => 'remota',
            'modulos_json' => '["EB002","EB010"]' // json_encode(array("EB002","EB010"))
        ),
        array( #7
            'produto'   => 'mod',
            'conexao'   => 0,
            'especiais' => 'ativa',
            'ativacao'  => 'online',
            'modulos_json' => '["EB002","EB010"]' // json_encode(array("EB002","EB010"))
        ),
        array( #8
            'produto'   => 'ebvqib',
            'edicao'    => 19,
            'app'       => 1,
            'licenca'   => 'LTEMP',
            'conexao'   => 0,
            'especiais' => 'ativa',
            'ativacao'  => 'online',
            'modulos_ltemp' => 'Light'
        ),
        array( #9
            'produto'   => 'ebvqib',
            'edicao'    => 19,
            'app'       => 1,
            'licenca'   => 'VITALÍCIA',
            'conexao'   => 0,
            'especiais' => 'ativa',
            'ativacao'  => 'online',
            'modulos_ltemp' => 'Light'
        )
    );
    private $respostas = array(
        array( #0
            'codigo_tw'   => 'EBV18-BASAS-02-STMLTUA',
            'valor_total' => 1740
        ),
        array( #1
            'codigo_tw'   => 'EBV19-PRSW-03-PRMINRA',
            'valor_total' => 10788
        ),
        array( #2
            'codigo_tw'   => 'EBV20-PLSW-04-PRMLAOA',
            'valor_total' => 5700
        ),
        array( #3
            'codigo_tw'   => 'QIB18-BASAS-20-STMLTUA',
            'valor_total' => 1740
        ),
        array( #4
            'codigo_tw'   => 'QIB19-PRSW-02-PRMINRA',
            'valor_total' => 8880
        ),
        array( #5
            'codigo_tw'   => 'QIB20-PLSW-02-PRMLAOA',
            'valor_total' => 2760
        ),
        array( #6
            'codigo_tw'   => 'EB002-I-PRMINRA',
            'valor_total' => 990
        ),
        array( #7
            'codigo_tw'   => 'EB002-I-PRMINOA',
            'valor_total' => 0 // Corrigir Valor na Base de Dados
        ),
        array( #8
            'codigo_tw'   => 'EBV19-FLSAS-02-PRMLTOA',
            'valor_total' => 2430
        ),
        array( #9
            'codigo_tw'   => 'EBV19-FLSAS-02-PRMINOA',
            'valor_total' => 8706
        )
    );

    /**
     * @return void
     */
    public function setUp()
    {
        $this->http = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken, 
                'Referer' => 'https://local-site.qisat.com.br/',
                'Content-Type' => 'application/json'
            ],
            'ssl_verify_host' => false, 
            'ssl_verify_peer' => false, 
            'ssl_verify_peer_name' => false
        ]);
    }

    /**
     * @return void
     *
    public function testCalcular()
    {
        //$key = 8;
        //$value = $this->dados[$key];
        foreach ($this->dados as $key => $value) {
            $result = $this->http->post($this->url, $value, ['type'=>'json']);
            $body = json_decode($result->body());
    //echo '<pre>';var_dump($result);die;
            if(is_null($body)){
                var_dump($key);
                var_dump($value);
                var_dump(is_null($body) ? $result->body() : $body);
                die;
            } else {
                $this->assertTextEquals($body->retorno->codigo_tw,   $this->respostas[$key]['codigo_tw']);
                $this->assertTextEquals($body->retorno->valor_total, $this->respostas[$key]['valor_total']);
            }
        }
    }

    /**
     * @return void
     *
    public function testAddItem()
    {
        $value = end($this->dados);
        reset($this->dados);

        $result = $this->http->post($this->url, $value, ['type'=>'json']);
        $body = json_decode($result->body());
        $this->assertTextNotEquals($body->retorno->codigo_tw, "-");

        $value['codigo_tw'] = $body->retorno->codigo_tw;
        $value['modulos'] = array();
        foreach ($body->retorno->modulos as $key => $val) {
            $value['modulos'][$key] = array('codigo_tw' => $val->codigo_tw);
        }
        
        $result = $this->http->post($this->url, $value, ['type'=>'json']);
        $body = json_decode($result->body());
    //echo '<pre>';var_dump($this);die;
        if(is_null($body)){
            var_dump($value);
            var_dump(is_null($body) ? $result->body() : $body);
            die;
        }
    }

    
    /**
     * @return void
     */
    public function testCriarPropostaAltoqi()
    {
        $value = new stdClass();
        $value->chave_altoqi = "2";
        $value->empresa = $value->empresa_equipe = "AltoQi";
        $value->entidade = new stdClass();
        $value->entidade->cliente = new Cliente();
        $value->produtos = array();
        $produto = new Produto();
        $produto->produto_top_id = 736;
        $produto->sigla = "EBV19PR";
        $produto->valor = 1740;
        $produto->pacote_top_id = 188;
        array_push($value->produtos, $produto);
        $produto = new Produto();
        $produto->produto_top_id = 798;
        $produto->sigla = "QIB 2019";
        $produto->tipo_protecao = "USB";
        $produto->linhas_json = "[\"QIB\",\"QBNEXT\",\"QIBHID\"]";
        array_push($value->produtos, $produto);
        $produto = new Produto();
        $produto->produto_top_id = 477;
        $produto->sigla = "EB001";
        array_push($value->produtos, $produto);
        $produto = new Produto();
        $produto->produto_top_id = 735;
        $produto->sigla = "ebvqib";
        $produto->valor = 2340;
        $produto->pacote_top_id = 209;
        $produto->tipo_adquisicao = "LTEMP";
        $produto->tipo_protecao = "ONLINE";
        array_push($value->produtos, $produto);

        $url = "https://local-ecommerce.qisat.com.br/carrinho/wsc-carrinho/criar_proposta_altoqi";
        $result = $this->http->post($url, json_encode($value), ['type'=>'json']);
//echo '<pre>';var_dump($result);die;
        $body = json_decode($result->body());
        $this->assertTextContains("https://www.qisat.com.br/proposta/", $body->retorno);
        echo '<pre>';var_dump($body->retorno);
    }
}
class Cliente {
    public $Nome = "AlunoQiSat";
    public $Numero = "999.999.999-99";
    public $Email = "inty.castillo@qisat.com.br";
    public $Endereco = "Endereço";
    public $DDD = "48";
    public $FoneCelular = "33325000";
    public $FoneComercial = "33325000";
    public $FoneResidencial = "33325000";
    public $CEP = "88060310";
    public $Bairro = "Centro";
    public $NumeroEndereco = "123";
    public $Cidade = "Florianópolis";
    public $UF = "SC";
    public $Senha = "aluno";
}
class Produto {
    public $pontos_rede = 0;
    public $valor = 0;
    public $pacote_top_id = 187;
    public $modelo_hardlock_top_id = 21;
    public $tipo_adquisicao = "Full";
    public $tipo_protecao = "Remota";
    public $especiais = "ativa";
}

//cd C:\Desenvolvimento\xampp5.6.40x86\htdocs\ecommerce_cake
//php phpunit.phar C:\Desenvolvimento\xampp5.6.40x86\htdocs\ecommerce_cake\plugins\Carrinho\tests\TestCase\Controller\WscCarrinhoControllerTest.php

