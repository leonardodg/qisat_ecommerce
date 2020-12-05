<?php
namespace Vendas\Model\Table;

use App\Model\Table\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Vendas\Model\Entity\DbaVendas;

/**
 * DbaVendasTable Model
 *
 * @property \Cake\ORM\Association\HasMany $DbaVendas */
class DbaVendasTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('dba_vendas');
        $this->primaryKey('pedido');

        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'INNER',
            'className' => 'MdlUser'
        ]);

        $this->hasMany('DbaVendasProdutos', [ 
            'foreignKey' => 'dba_vendas_pedido',
            'className' => 'Vendas.DbaVendasProdutos'
        ]);
        
        $this->hasMany('DbaVendasServicos', [ 
                'foreignKey' => 'dba_vendas_pedido', 
                'className' => 'Vendas.DbaVendasServicos'
        ]);
    }

    /**
    * Set Aplicação no Produtos
    * 
    * Return $venda->dba_vendas_produtos[$key]->ecm_produto_ecm_aplicacao 
    * 
    * DbaVendas Deve Conter [ DbaVendasProdutos ]
    * 
    * # EBVs
    * 
    * # 1 - 462 - EBV19PLR
    * # 4 - 472 - EBV19PL

    * # 2 - 463 - EBV19BAR
    * # 6 - 474 - EBV19BA
    
    * # 3 - 464 - EBV19PRR
    * # 5 - 473 - EBV19PR
    
    * # 7 - 475 - EBV19LT
    * # 8 - 601 - EBV19FLE
    * 
    * # EBV19PLR - EBV19BAR - EBV19PRR - EBV19PL - EBV19PR - EBV19BA - EBV19LT - EBV19FLE
    * # produto_id in (462, 463, 464, 472, 473, 474, 475, 601)
    * 
    * 
    * # QIBs
    * # produto_id in (465,466,467,468,469,470,476,600,602,604,605)
    * # 465 - QIBR 2019 
    * # 466 - QIB2019PR 
    * # 467 - QIB2019PRR 
    * # 468 - QIB2019BA 
    * # 469 - QIB2019BAR 
    * # 470 - QIB2019PL 
    * # 471- QIB2019PLR 
    * # 476 - QIB 2019 
    * # 600 - QIBNEXT 
    * # 602 - QIB2019FLE 
    * # 604 - QIBEDTBA 
    * # 605 - QIBEDTPR
    * 01 - STANDARD - EBERICK 2018 - INDET
    * 02 - LIGHT - EBERICK 2019/2018 LTEMP / LANUAL /INDET
    * 03 - ESSENCIAL - EBERICK 2019/2018 LTEMP / LANUAL /INDET
    * 04 - TOP - EBERICK 2019/2018 LTEMP / LANUAL /INDET
    * 
    * 05 - ALVENARIA - EB041
    * 06 - PRÉ-MOLDADO - - EB033
    * 07 - TIPO - modulos
    * 
    * 2018 Vitalicio, 2019 LTEMP e INDET
    * 08 - QH -  QIB + QIBHID LIGHT/ESSENCIAL/TOP
    * 09 - QI -  QIB + QIBINC LIGHT/ESSENCIAL/TOP
    * 10 - QG -  QIB + QiGás LIGHT/ESSENCIAL/TOP
    * 
    * 11 - QE -  QIB + QiElétrico LIGHT/ESSENCIAL/TOP 
    * 12 - QC -  QIB + QiCabeamento LIGHT/ESSENCIAL/TOP
    * 13 - QS -  QIB + QiSPDA LIGHT/ESSENCIAL/TOP
    * 
    * 14 -  QA - QIB + QiAlvenaria LIGHT/ESSENCIAL/TOP
    * 19 - QEA - QiBuilder CAD Editor de armaduras ( SEM LIGHT?)

    * 
    * 15 - CPH - 08+09+10 =27 - QIBHID - QIBINC - QiGás
    * 
    * 16 - CPE - 11-12-13 =36 - QiElétrico - QiCabeamento - QiSPDA
    * 17 - CPEH - 8-11 = 19 - QIBHID - QiElétrico
    * 18 - CPAEA - 14-19 -- NAO TEMP 2019 = 33 - QiAlvenaria + QiBuilder CAD 

    * 
    * --- 2018 LTEMP
    * 
    * 20 - BH - QIB + QIBHID        = 08
    * 21 - BE - QIB + QiElétrico    = 11
    * 
    * 22 - BSP - QIB + QiIncêndio + QiSPDA  = 09+13 = 22
    * 23 - BIP - QIB + QiGás + QiCabeamento = 10+ 12 = 22
    * 24 - BHE - QIB + QIBHID + QiElétrico  = 08 + 11 = 19
    * 25 - BHSP - QIB + QIBHID + QiIncêndio + QiSPDA = 08 + 09 + 13 = 30
    * 26 - BHIP - QIB + QIBHID + QiGás + QiCabeamento = 08 + 10 + 12 = 30
    * 27 - BESP - QIB + QiElétrico + QiIncêndio + QiSPDA = 11 + 09 + 13 = 33
    * 28 - BEIP - QIB + QiElétrico  + QiGás + QiCabeamento = 11  + 10 + 12 = 33
    * 29 - BSPIP - QIB + QiIncêndio + QiSPDA + QiGás + QiCabeamento = 09 + 13 + 10 + 12 = 44
    * 30 - BHESP - QIB + QIBHID + QiElétrico + QiIncêndio + QiSPDA = 08 + 11 + 09 + 13 = 41
    * 31 - BHEIP - QIB +  QIBHID + QiElétrico + QiGás + QiCabeamento = 08 + 11  + 10 + 12 = 41
    * 32 - BHSPIP - QIB + QIBHID + QiIncêndio + QiSPDA + QiGás + QiCabeamento = 08 + 09  + 13 + 10 + 12 = 
    * 33 - BESPIP - QIB + QiElétrico  + QiIncêndio + QiSPDA + QiGás + QiCabeamento = 11 + 09 + 13 + 10 + 12
    * 34 - BHESPIP - QIB +QIBHID + QiElétrico  + QiIncêndio + QiSPDA + QiGás + QiCabeamento = 08 + 11 + 09 + 13  + 10 + 12
    * 
    * -- 2019
    * 35 - CPT - 08-09-10-11-12-13
     */
    public function searchProductsApps(&$venda){

        $order = ['B', 'H', 'E', 'S', 'P', 'I', 'C'];
        $linhas_qib = [ 'H' => [394,551,724], 'E' => [398,554,722], 'S' => [454,557,726], 'P' => [[396,552,725],[399,555,721]], 'I' =>[397,553,723], 'A' => [400,556,720], 'Q' => [393,401,585,586,587,588,606,629,689,727,741,742,796,797,798,799,800,801,827,826,817,816,815,814,813,812,811,808, 877,878,879,880,881,882,883,884,885]]; 

        $condicoesApp = [];
        $protetores = [];

        $this->EcmProduto = TableRegistry::get('Produto.EcmProduto');
        $this->DbaVendasProdutos = TableRegistry::get('Vendas.DbaVendasProdutos');
        $this->EcmProdutoEcmAplicacao = TableRegistry::get('Produto.EcmProdutoEcmAplicacao');
        // Separar por Protetor

        if(isset($venda->dba_vendas_produtos)){
            array_walk( $venda->dba_vendas_produtos, function ($prod, $k) use(&$protetores, $venda){
                                if(!array_key_exists($prod['numero_protetor'], $protetores))
                                    $protetores[$prod['numero_protetor']] = [ 'ebv' => [], 'qib' => [], 'qic' => [], 'qiv' => []];

                                if((array_key_exists($prod->produto_top_id, $this->DbaVendasProdutos->ebericks )) || ((array_key_exists($prod->produto_top_id, $this->DbaVendasProdutos->modulos )) && ($prod->sigla == 'EB033' || $prod->sigla == 'EB046' || $venda->tipo == 'VENDI' || $prod->sigla == 'EB041')))
                                    $protetores[$prod->numero_protetor]['ebv'][] = $k;
                                else if(array_key_exists($prod->produto_top_id, $this->DbaVendasProdutos->qibs ))
                                    $protetores[$prod->numero_protetor]['qib'][] = $k;
                                else if($prod->produto_top_id == 526) // QiCloud
                                    $protetores[$prod->numero_protetor]['qic'][] = $k;
                                else if($prod->produto_top_id == 939) // QIVISUS
                                    $protetores[$prod->numero_protetor]['qiv'][] = $k;
                        });
        }

        foreach ($protetores as $protetor => $itens_app){

            $mod_qib = [ 'tipo1' => 'B', 'tipo2' => 'Q', 'tipo2_all' => 0, 'edicao' => null, 'licenca' => null, 'QIB' =>[], 'app' => null ];
            // Montar Modulos QiBuilder
            if(count($itens_app['qib']) > 0){
                array_map( 
                    function ($k) use(&$mod_qib, &$venda, $linhas_qib){
                        $prod = $venda->dba_vendas_produtos[$k];

                        if(is_null($mod_qib['edicao']))
                            $mod_qib['edicao'] = $prod->edicao;
                        
                        if(is_null($mod_qib['licenca']))
                            $mod_qib['licenca'] = $venda->tipo;

                        if(is_null($mod_qib['app']) && !is_null($prod->aplicacao))
                            $mod_qib['app'] = $prod->aplicacao;

                        if(in_array($prod->produto_top_id, $linhas_qib['H'] )){
                            $venda->dba_vendas_produtos[$k]->linha = '08 - QH';
                            $mod_qib['tipo1'] .= 'H';
                            $mod_qib['tipo2'] .= 'H';
                            $mod_qib['tipo2_all'] += 8;
                        }else if(in_array($prod->produto_top_id, $linhas_qib['E'] )){
                            $venda->dba_vendas_produtos[$k]->linha = '11 - QE';
                            $mod_qib['tipo1'] .= 'E';
                            $mod_qib['tipo2'] .= 'E';
                            $mod_qib['tipo2_all'] += 11;
                        }else if(in_array($prod->produto_top_id, $linhas_qib['S'] )){
                            $venda->dba_vendas_produtos[$k]->linha = '13 - QS';
                            $mod_qib['tipo1'] .= 'S';
                            $mod_qib['tipo2'] .= 'S';
                            $mod_qib['tipo2_all'] += 13;
                        }else if(in_array($prod->produto_top_id, $linhas_qib['P'][0] )){
                            $venda->dba_vendas_produtos[$k]->linha = '09 - QI';
                            $mod_qib['tipo1'] .= 'P';
                            $mod_qib['tipo2'] .= 'I';
                            $mod_qib['tipo2_all'] += 9;
                        }else if(in_array($prod->produto_top_id, $linhas_qib['I'] )){
                            $venda->dba_vendas_produtos[$k]->linha = '10 - QG';
                            $mod_qib['tipo1'] .= 'I';
                            $mod_qib['tipo2'] .= 'G';
                            $mod_qib['tipo2_all'] += 10;
                        }else if(in_array($prod->produto_top_id, $linhas_qib['P'][1] )){
                            $venda->dba_vendas_produtos[$k]->linha = '12 - QC';
                            $mod_qib['tipo1'] .= 'C'; // C representa to P
                            $mod_qib['tipo2'] .= 'C';
                            $mod_qib['tipo2_all'] += 12;
                        }else if(in_array($prod->produto_top_id, $linhas_qib['A'] )){// QiAlvenaria
                            $venda->dba_vendas_produtos[$k]->linha = '14 - QA';
                            $mod_qib['tipo2'] .= 'A';
                            $mod_qib['tipo2_all'] += 14;
                        }else if($prod->produto_top_id == 429 || $prod->produto_top_id == 417){ // Editor de armaduras
                            $venda->dba_vendas_produtos[$k]->linha = '19 - QEA';
                            $mod_qib['tipo2'] .= 'D'; // 
                            $mod_qib['tipo2_all'] += 19;
                        }else if(in_array($prod->produto_top_id, $linhas_qib['Q'] )){// Qibluider
                            $mod_qib['QIB'][] = $k;
                        }
                        
                        if ( ($venda->tipo == 'VENDI' && $mod_qib['edicao'] == 2018) || $mod_qib['edicao'] >= 2019 || $prod->valor > 0 ){
                            $mod_qib['QIB'][] = $k;
                        }

                    }, $itens_app['qib'] );

                // Ordernar Letras Modulos TIPO1 (2018 LTEMP)
                $mod_qib['tipo1'] = str_split($mod_qib['tipo1']);
                usort($mod_qib['tipo1'], function($a, $b) use($order){
                    $pos_a = array_search($a, $order);
                    $pos_b = array_search($b, $order);
                    return $pos_a - $pos_b;
                });
                $mod_qib['tipo1'] = implode("", $mod_qib['tipo1']);
                $mod_qib['tipo1'] = str_replace('C', 'P', $mod_qib['tipo1']);

                // Concat Para Aproveitar logica do where
                if(count($mod_qib['QIB']) > 0)
                    $itens_app['ebv'] = array_merge($itens_app['ebv'], $mod_qib['QIB']);

            }

            if(count($itens_app['qic']) > 0)
                $itens_app['ebv'] = array_merge($itens_app['ebv'], $itens_app['qic']);
            if(count($itens_app['qiv']) > 0)
                $itens_app['ebv'] = array_merge($itens_app['ebv'], $itens_app['qiv']);

            if(count($itens_app['ebv']) > 0){
                // Buscar Aplicaçoes
                foreach ($itens_app['ebv'] as $key){
                    $dba_produto = $venda->dba_vendas_produtos[$key];
                    $modulos = '';
                    $licenca = '';
                    $ativacao = '';
                    $especiais = ''; // CORREÇÃO PARA RENOVAÇÃO E UPGRADE
                    $rede = ($dba_produto->rede) ? 'REDE' : 'MONO';
                    $search = true;
                    $condicao = [];

                    if(isset($dba_produto->ecm_produto))
                        $condicao = ['ecm_produto_id' => $dba_produto->ecm_produto->id ];

                    if($venda->tipo == 'LTEMP' || $venda->tipo == 'LANUAL') 
                        $licenca = $venda->tipo;
                    else if($venda->tipo == 'VENDI' )
                        $licenca = 'INDET';
                
                    if($dba_produto->tipo_protecao == 'USB' )
                        $ativacao = $dba_produto->tipo_protecao;
                    else if( $dba_produto->tipo_protecao == 'Remota' )
                        $ativacao = strtoupper($dba_produto->tipo_protecao);
                    else if( $dba_produto->tipo_protecao == 'Via Software' )
                        $ativacao = 'ONLINE';
                
                    if(array_key_exists($dba_produto->produto_top_id, $this->DbaVendasProdutos->ebericks)) {

                        $or = [];

                        if($dba_produto->rede or $dba_produto->pontos_rede > 0) {
                            $condicao['EcmProdutoAplicacao.tabela'] = 'CORP'; // verificar INDET
                        }else{
                            // $condicao['EcmProdutoAplicacao.tabela'] = 'PRE'; // verificar INDET  

                            array_push($or, ['EcmProdutoAplicacao.tabela' => 'PRE']);
                            array_push($or, ['EcmProdutoAplicacao.tabela' => 'STD']);

                        }

                        if($dba_produto->edicao == 2018){
                            if($dba_produto->sigla  == 'EBV18PL' || $dba_produto->sigla  == 'EBV18PLR'){
                                $condicao['ecm_produto_id'] = 355; 
                            }else if($dba_produto->sigla  == 'EBV18PR' || $dba_produto->sigla  == 'EBV18PRR'){
                                $condicao['ecm_produto_id'] = 354; 
                            }else if($dba_produto->sigla  == 'EBV18BA' || $dba_produto->sigla  == 'EBV18BAR'){
                                $condicao['ecm_produto_id'] = 353; 
                            } if($dba_produto->sigla  == 'EBV18LT'){
                                $condicao['ecm_produto_id'] = 352; 
                            }

                            if( ($licenca == 'INDET') ){
                                // array_push($or, ['EcmProdutoAplicacao.modulos_linha' => '01 - STANDARD']);
                                $condicao['EcmProdutoAplicacao.modulos_linha'] = '01 - STANDARD';
                             }else{
                                switch ($dba_produto->modulos) {
                                    case 'top':
                                        // array_push($or, ['EcmProdutoAplicacao.modulos_linha' => '04 - TOP']);
                                        $condicao['EcmProdutoAplicacao.modulos_linha'] = '04 - TOP';
                                        break;
                                    case 'essencial':
                                        // array_push($or, ['EcmProdutoAplicacao.modulos_linha' => '03 - ESSENCIAL']);
                                        $condicao['EcmProdutoAplicacao.modulos_linha'] = '03 - ESSENCIAL';
                                        break;
                                    case 'light':
                                        // array_push($or, ['EcmProdutoAplicacao.modulos_linha' => '02 - LIGHT']);
                                        $condicao['EcmProdutoAplicacao.modulos_linha'] = '02 - LIGHT';
                                        break;
                                }

                            }
                        }else{

                            if( ($licenca == 'INDET') AND $dba_produto->aplicacao != 'flex' ){
                                // array_push($or, ['EcmProdutoAplicacao.modulos_linha' => '01 - STANDARD']);
                                $condicao['EcmProdutoAplicacao.modulos_linha'] = '01 - STANDARD';
                             }else{
                                switch ($dba_produto->modulos) {
                                    case 'top':
                                        // array_push($or, ['EcmProdutoAplicacao.modulos_linha' => '04 - TOP']);
                                        $condicao['EcmProdutoAplicacao.modulos_linha'] = '04 - TOP';
                                        break;
                                    case 'essencial':
                                        // array_push($or, ['EcmProdutoAplicacao.modulos_linha' => '03 - ESSENCIAL']);
                                        $condicao['EcmProdutoAplicacao.modulos_linha'] = '03 - ESSENCIAL';
                                        break;
                                    case 'light':
                                        // array_push($or, ['EcmProdutoAplicacao.modulos_linha' => '02 - LIGHT']);
                                        $condicao['EcmProdutoAplicacao.modulos_linha'] = '02 - LIGHT';
                                        break;
                                }

                            }

                        }
                    
                        $condicao['OR'] = $or;
                        $especiais = 'ATIVAÇÃO'; // CORRIGIR!!

                    }else if(array_key_exists($dba_produto->produto_top_id, $this->DbaVendasProdutos->qibs)) {

                        
                        if(!is_null($mod_qib['app'])){
                            $condicao['EcmProdutoAplicacao.aplicacao'] = $mod_qib['app'];
                        }

                        if(($mod_qib['edicao'] == 2018) && ($mod_qib['licenca'] == 'LTEMP')){
                            $condicao['EcmProdutoAplicacao.modulos_linha like'] = '%'.$mod_qib['tipo1'];

                            if($dba_produto->ecm_produto->id != 351 ){
                                $condicao['ecm_produto_id'] = 351;
                            }

                        }else{

                                $or = [];

                                if(strlen($mod_qib['tipo2']) == 2 && $dba_produto->ecm_produto->id != 351 ) // <> Qib 2018
                                    array_push($or, ['EcmProdutoAplicacao.modulos_linha like' => '%'.$mod_qib['tipo2']]);

                                 // Qib 2018
                                if($dba_produto->ecm_produto->id == 351 ){
                                    array_push($or, ['EcmProdutoAplicacao.modulos_linha like' => '01 - STANDARD']);
                                }

                                switch ($dba_produto->modulos) {
                                    case 'top':
                                        array_push($or, ['EcmProdutoAplicacao.modulos_linha' => '04 - TOP']);
                                        break;
                                    case 'essencial':
                                        array_push($or, ['EcmProdutoAplicacao.modulos_linha' => '03 - ESSENCIAL']);
                                        break;
                                    case 'light':
                                        array_push($or, ['EcmProdutoAplicacao.modulos_linha'=>'02 - LIGHT']);
                                        break;
                                }

                                switch ( $mod_qib['tipo2_all'] ) {
                                    case 19:
                                        array_push($or, ['EcmProdutoAplicacao.modulos_linha' =>'17 - CPEH']);
                                      break;
                                    case 27:
                                        array_push($or, ['EcmProdutoAplicacao.modulos_linha' =>'15 - CPH']);
                                      break;
                                    case 33:
                                        array_push($or, ['EcmProdutoAplicacao.modulos_linha' =>'18 - CPAEA']);
                                      break;
                                    case 35:
                                        array_push($or, ['EcmProdutoAplicacao.modulos_linha' =>'35 - CPT']);
                                      break;
                                    case 36:
                                        array_push($or, ['EcmProdutoAplicacao.modulos_linha' =>'16 - CPE']);
                                      break;
                                }

                                if($dba_produto->ecm_produto->sigla == 'QIBNEXT'){
                                    array_push($or, ['EcmProdutoAplicacao.modulos_linha' =>'07 - TIPO']);
                                }

                                if( $dba_produto->ecm_produto->id == 465 ||  $dba_produto->ecm_produto->id == 476 ){
                                   if($dba_produto->aplicacao == 'basic' ){

                                        if($dba_produto->rede or $dba_produto->pontos_rede > 0){
                                            $condicao['ecm_produto_id'] = 469; // QIB2019BAR
                                            $condicao['EcmProdutoAplicacao.tabela'] = 'CORP';
                                        }else{  
                                            $condicao['ecm_produto_id'] = 468; // QIB2019BA
                                        }

                                    }else  if($dba_produto->aplicacao == 'plena' ){

                                        if($dba_produto->rede or $dba_produto->pontos_rede > 0){
                                            $condicao['ecm_produto_id'] = 471; // QIB2019PLR
                                            $condicao['EcmProdutoAplicacao.tabela'] = 'CORP';
                                        }else{  
                                            $condicao['ecm_produto_id'] = 470; // QIB2019PL
                                        }

                                    } else if($dba_produto->aplicacao == 'pro' ){

                                        if($dba_produto->rede or $dba_produto->pontos_rede > 0){
                                            $condicao['ecm_produto_id'] = 467; // QIB2019PRr
                                            $condicao['EcmProdutoAplicacao.tabela'] = 'CORP';
                                        }else{  
                                            $condicao['ecm_produto_id'] = 466; // QIB2019PR
                                        }
                                    }
                                }else{
                                    if($dba_produto->rede or $dba_produto->pontos_rede > 0){
                                        $condicao['EcmProdutoAplicacao.tabela'] = 'CORP';
                                    }
                                }

                                if( isset($dba_produto->linha) AND (($mod_qib['edicao'] == 2019) or ( $mod_qib['edicao'] == 2018 AND $mod_qib['licenca'] == 'VENDI'))) {
                                    array_push($or, ['EcmProdutoAplicacao.modulos_linha'=> $dba_produto->linha]);
                                 //   $ativacao = 'REMOTA'; // VERIFICAR  
                                }

                                $condicao['OR'] = $or;
                        }

                        $especiais = 'ATIVAÇÃO'; // CORRIGIR!!

                    }else if(array_key_exists($dba_produto->produto_top_id, $this->DbaVendasProdutos->modulos )) {

                        if($venda->tipo == 'VENDI'){

                            if($dba_produto->sigla != 'EB033' || $dba_produto->sigla != 'EB041'){
                                $condicao = ['ecm_produto_id' => 432 ]; /// EcmProduto Modulo
                            }

                            $condicao['EcmProdutoAplicacao.codigo'] = $dba_produto->sigla;

                            if($dba_produto->tipo_aquisicao == 'Full'){
                                $especiais = 'ATIVAÇÃO';
                                if($dba_produto->sigla != 'EB046' )
                                $dba_produto->edicao = 2018;
                                $condicao['EcmProdutoAplicacao.tabela'] = 'PRE';
                            }

                        }else if($dba_produto->valor == 0) {
                            $search = false;
                        }

                        if($dba_produto->sigla == 'EB033'){

                            if( $licenca == 'INDET' ){
                                $modulos = '07 - TIPO';
                            }else if( $licenca == 'LTEMP' ){
                                $modulos = '06 - PRÉ-MOLDADO';
                                $especiais = 'ATIVAÇÃO'; // CORRIGIR!!
                            }

                            $condicao['EcmProdutoAplicacao.aplicacao'] = $dba_produto->aplicacao;
                        } else if($dba_produto->sigla == 'EB041'){
                            $modulos = '05 - ALVENARIA';
                            $especiais = 'ATIVAÇÃO'; // CORRIGIR!!
                            $condicao['EcmProdutoAplicacao.aplicacao'] = $dba_produto->aplicacao;
                        } else if($dba_produto->sigla == 'MNEXT'){
                            $modulos = '07 - TIPO';
                            $condicao['EcmProdutoAplicacao.aplicacao'] = 'MODULO';
                            $condicao = ['ecm_produto_id' => 515 ]; 
                            $condicao['EcmProdutoAplicacao.codigo'] = 'EB045';
                            $dba_produto->edicao = 2019;
                        } else if($dba_produto->sigla == 'EB046'){
                            $modulos = '07 - TIPO';
                            $condicao['EcmProdutoAplicacao.aplicacao'] = 'MODULO';
                            $condicao['EcmProdutoAplicacao.codigo'] = $dba_produto->sigla;
                        } else if($dba_produto->sigla == 'EB045'){
                            $modulos = '07 - TIPO';
                            $condicao['EcmProdutoAplicacao.aplicacao'] = 'MODULO';
                            $condicao['EcmProdutoAplicacao.codigo'] = $dba_produto->sigla;
                            $condicao = ['ecm_produto_id' => 432 ];
                        }

                        if($venda->tipo != 'LANUAL')
                            $ativacao = 'REMOTA'; // CORRIGIR NO Ecm_produto_aplicacao ( OBS.: TODOS MODULOS ESTAO COMO REMOTA )
                    }else if ($dba_produto->produto_top_id == 526){  // PLANO ALTOQI QICLOUD
                        $condicao['EcmProdutoAplicacao.aplicacao'] = $dba_produto->aplicacao;
                        $especiais = 'ATIVAÇÃO'; // CORRIGIR!!
                        $modulos = $dba_produto->modulos;
                    }else if ($dba_produto->produto_top_id == 939){  // QIVISUS
                        $condicao['EcmProdutoAplicacao.software'] = 'SAS';
                        $condicao['EcmProdutoAplicacao.tabela'] = 'PRE';
                        $especiais = 'ATIVAÇÃO';
                    }

                    if($search){
                        if(!empty($modulos))
                            $condicao['EcmProdutoAplicacao.modulos_linha'] = $modulos;

                        if(!is_null($dba_produto->edicao))
                            $condicao['EcmProdutoEcmAplicacao.edicao'] = $dba_produto->edicao;

                        if(!empty($ativacao))
                            $condicao['EcmProdutoAplicacao.ativacao'] = $ativacao;

                        if(!empty($licenca))
                            $condicao['EcmProdutoAplicacao.licenca'] = $licenca;

                        $condicao['EcmProdutoAplicacao.conexao'] = $rede;

                        if(!empty($especiais))
                            $condicao['EcmProdutoAplicacao.especiais'] = $especiais;

                        $condicoesApp[$key] = $condicao;
                    }
                }
            }
        }


        foreach ($condicoesApp as $key => $where) {
            if($venda->dba_vendas_produtos[$key]->valor > 0){
                $apps = $this->EcmProdutoEcmAplicacao->find()
                                                    ->contain([ 'EcmProdutoAplicacao' => function ($q) {
                                                        return $q->where(['EcmProdutoAplicacao.tecnologia <>' => 'COMBO']);
                                                    }])
                                                    ->where($where);
                                                    
                $apps->formatResults(function (\Cake\Collection\CollectionInterface $results){
                return $results->map(function ($row){
                                            $codigo_tw = $this->EcmProduto->encriptCodigotw($row);
                                            $row->descricao = $codigo_tw['descricao'];
                                            $row->codigo_tw = $codigo_tw['codigo_tw'];
                                        return $row;
                                    });
                });
                    
                $venda->dba_vendas_produtos[$key]->ecm_produto_ecm_aplicacao = $apps->toArray();
            }
        }

    }
}
