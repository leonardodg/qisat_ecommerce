<?php
namespace Vendas\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * DbaVendasProdutosTable Model
 *
 * @property \Cake\ORM\Association\HasMany $DbaVendasProdutos */
class DbaVendasProdutosTable extends Table
{

    public $pacotes = [

                        491 => [ "edicao" => 2020, "aplicacao" => 'flex', "modulos" => 'light'], // "nome" => 'Pacote Flex BIM 2020 - 36 meses - RMS'], //	PFB12
                        490 => [ "edicao" => 2020, "aplicacao" => 'flex', "modulos" => 'light'], // "nome" => 'Pacote Flex BIM 2020 - 24 meses - RMS'], //	PFB12
                        489 => [ "edicao" => 2020, "aplicacao" => 'flex', "modulos" => 'light'], // "nome" => 'Pacote Flex BIM 2020 - 12 meses - RMS'], //	PFB12
                        323 => [ "edicao" => 2020, "aplicacao" => 'flex', "modulos" => 'light'], // "nome" => 'Pacote Flex BIM - Migração LVit'], //	PFB12
                        318 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Plena EBV19PL + Família Elétrica + ALV - RMS'], //	CEPFELARMS
                        308 => [ "edicao" => 2020, "aplicacao" => 'flex', "modulos" => 'light'], // "nome" => 'Pacote Flex BIM - Migração LVit'], //	PFB12
                        307 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Basic EBV19BA PM + QIBELT + QIBHID - RMS'], //	CEBAEHRMS
                        306 => [ "edicao" => 2019, "aplicacao" => 'flex', "modulos" => 'light'], // "nome" => 'Pacote Flex BIM - LVit'], //	PFB12
                        305 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'], // "nome" => 'Combo TOP Basic EBV19BA + QIB 2019 + Alvenaria- RMS'], //	CBTOPBARMS
                        304 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Basic EBV19BA + ALV +QIBELT + QIBHID - RMS'], //	CEBAEHRARM
                        303 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'], // "nome" => 'Combo TOP Plena EBV19PL + QIB + Pré Mold +ALV - RMS'], //	CBTOPPPARM
                        302 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Pro EBV19PR + QIBELT + QIBHID + ALV- RMS'], //	CBEPREHARM
                        301 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'Combo Light Basic EBV19BA + QIBELÉTRICO - RMS'], //	CEBLERMS
                        300 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'Combo Light Basic EBV19BA + QIBHIDROSSANITÁRIO - RMS'], //	CEBLHRMS
                        297 => [ "edicao" => 2019, "aplicacao" => 'top', "modulos" => 'light'], // "nome" => 'Combo TOP Plena EBV19PL + QIB + ALV 2019 - RMS'], //	CBTOPPLARM
                        296 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Pro Gás - RMS'], //	Q19LPRGRMS
                        295 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Pro Incêndio - RMS'], //	Q19LPRIRMS
                        294 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Pro SPDA - RMS'], //	Q19LPRSRMS
                        293 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Pro Cabeamento - RMS'], //	Q19LPRCARM
                        292 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Plena Gás - RMS'], //	Q19LPLGRM
                        291 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Plena Incêndio - RMS'], //	Q19LPLIRM
                        290 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Plena SPDA - RMS'], //	Q19LPSRMS
                        289 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Plena Cabeamento - RMS'], //	Q19LPCARMS
                        288 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Basic Gás - RMS'], //	Q19BLGRMS
                        287 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Basic Incêndio - RMS'], //	Q19BLIRMS
                        286 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Basic Cabeamento - RMS'], //	Q19LBCARMS
                        282 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Basic SPDA - RMS'], //	Q19BLBSRMS
                        281 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'], // "nome" => 'QiBuilder 2019 TOP Pro - RMS'], //	Q19TOPPRRM
                        280 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'QiBuilder 2019 Essencial Pro QIBELT + QIBHID - RMS'], //	Q19EPREHRM
                        279 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'QiBuilder 2019 Essencial Pro Família Hidráulica - RMS'], //	Q19EPRHRMS
                        278 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'QiBuilder 2019 Essencial Pro Família Elétrica - RMS'], //	Q19EPRELRM
                        277 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'QiBuilder 2019 Essencial Plena QIBELT + QIBHID - RMS'], //	Q19EPLEHRM
                        276 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'QiBuilder 2019 Essencial Plena Família Hidráulica - RMS'], //	Q19EPLHRMS
                        275 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'QiBuilder 2019 Essencial Plena Família Elétrica - RMS'], //	Q19EPLELRM
                        274 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'], // "nome" => 'Eberick 2019 PRO TOP + Pré Moldados e Alvenaria - RMS'], //	E19PRTPARM
                        273 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'], // "nome" => 'Eberick 2019 PRO TOP + Pré Moldados - RMS'], //	E19PRTPMRM
                        272 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'LAB I ESTRUTURAL - DESENVOLVIMENTO DE PROJETOS EM CONCRETO ARMADO'], //	LBEST_F1
                        269 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'], // "nome" => 'Eberick 2019 PRO Light+ Pré Moldados - RMS'], //	E19PRLPMRM
                        268 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'], // "nome" => 'Eberick 2019 PRO Light + Pré Moldados e Alvenaria - RMS'], //	E19PRLPARM
                        267 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'], // "nome" => 'Eberick 2019 PRO Light + Alvenaria - RMS'], //	E19PRLALRM
                        266 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 PRO Essencial - RMS'], //	E19PRERMS
                        265 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'], // "nome" => 'Eberick 2019 PRO TOP - RMS'], //	E19PRTRMS
                        264 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'], // "nome" => 'Eberick 2019 PRO Light - RMS'], //	E19PRLRMS
                        263 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 PRO Essencial + Pré Moldados e Alvenaria - RMS'], //	E19PREPMAR
                        262 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 PRO Essencial + Pré Moldados - RMS'], //	E19PREPMRM
                        261 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 PRO Essencial + Alvenaria - RMS'], //	E19PREALVR
                        260 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 PRO Essencial - RMS'], //	E19PRERMS
                        259 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'], // "nome" => 'Eberick 2019 PLENA TOP + Pré Moldados e Alvenaria - RMS'], //	E19PTPMALR
                        258 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'], // "nome" => 'Eberick 2019 PLENA TOP + Pré Moldados - RMS'], //	E19PTPMRMS
                        257 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'], // "nome" => 'Eberick 2019 PLENA TOP + Alvenaria - RMS'], //	E19PTALVRM
                        256 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 PLENA Essencial + Pré Moldados e Alvenaria - RMS'], //	E19PEPMALR
                        255 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 PLENA Essencial + Pré Moldados - RMS'], //	E19PEPMRMS
                        254 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 PLENA Essencial + Alvenaria - RMS'], //	E19PEALVRM
                        253 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'], // "nome" => 'Eberick 2019 Basic TOP + Pré Moldados e Alvenaria - RMS'], //	E19BTPMALR
                        252 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'], // "nome" => 'Eberick 2019 Basic TOP + Pré Moldados - RMS'], //	E19BTPMRMS
                        251 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'], // "nome" => 'Eberick 2019 Basic TOP + Alvenaria - RMS'], //	E19BTALVRM
                        250 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'], // "nome" => 'Eberick 2019 Basic TOP - RMS'], //	E19BATRMS
                        249 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'Eberick 2019 Basic Light + Pré Moldados e Alvenaria - RMS'], //	E19BLPMALR
                        248 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'Eberick 2019 Basic Light + Pré Moldados - RMS'], //	E19BLPMRMS
                        247 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'Eberick 2019 Basic Light + Alvenaria - RMS'], //	E19BLALVRM
                        246 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 Basic Essencial + Pré Moldados e Alvenaria - RMS'], //	E19BEPMALR
                        245 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 Basic Essencial + Pré Moldados - RMS'], //	E19BEPMRMS
                        244 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 Basic Essencial + Alvenaria - RMS'], //	E19BEALRMS
                        243 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'], // "nome" => 'Combo TOP Pro EBV19PR + QIB 2019 - RMS'], //	CBTOPPRRMS
                        242 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Pro EBV19PR + QIBELT + QIBHID - RMS'], //	CBEPREHRMS
                        241 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Pro EBV19PR + Família Hidráulica - RMS'], //	CBEPRFHRMS
                        240 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Pro EBV19PR + Família Elétrica - RMS'], //	CBEPRERMS
                        239 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Plena EBV19PL + QIBELT + QIBHID - RMS'], //	CBEPLEHRMS
                        238 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Plena EBV19PL + Família Hidráulica - RMS'], //	CBEPLHRMS
                        237 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'], // "nome" => 'QiBuilder 2019 TOP Plena - RMS'], //	Q19TOPPLRM
                        236 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Pro Hidráulica - RMS'], //	Q19LPRHRMS
                        235 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Pro Elétrica - RMS'], //	Q19LPRERMS
                        234 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Plena Elétrica - RMS'], //	Q19PLERMS
                        233 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Basic Elétrica - RMS'], //	Q19BLERMS
                        232 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Basic Hidráulica - RMS'], //	Q19BLHID
                        231 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'QiBuilder 2019 Essencial Basic Família Hidráulica - RMS'], //	Q19EBHRMS
                        230 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'QiBuilder 2019 Essencial Basic Família Elétrica - RMS'], //	Q19EBELTRM
                        229 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'QiBuilder 2019 Essencial Basic QIBELT + QIBHID - RMS'], //	Q19EBEHERM
                        228 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'], // "nome" => 'Combo TOP Basic EBV19BA + QIB 2019 - RMS'], //	CBTOPBARMS
                        227 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 Basic Essencial - RMS'], //	E19BAERMS
                        226 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'], // "nome" => 'Eberick 2019 Basic Light - RMS'], //	E19BALRMS
                        225 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'], // "nome" => 'Eberick 2019 PRO TOP + Alvenaria - RMS'], //	E19PRTALVR
                        224 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'], // "nome" => 'Eberick 2019 PLENA TOP - RMS'], //	E19PLTRMS
                        223 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'light'], // "nome" => 'QiBuilder 2019 Light Plena Hidráulica - RMS'], //	Q19EPLHID
                        222 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 PLENA Essencial - RMS'], //	E19PLERMS
                        221 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'], // "nome" => 'QiBuilder 2019 TOP Basic - RMS'], //	Q19TOPBRMS
                        220 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Plena EBV19PL + Família Elétrica - RMS'], //	CEPLFELRMS
                        219 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Basic EBV19BA + QIBELT + QIBHID - RMS'], //	CEBAEHRMS
                        218 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Basic EBV19BA + Família Hidráulica - RMS'], //	CBEBAFHRMS
                        217 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'], // "nome" => 'Combo Essencial Basic EBV19BA + Família Elétrica - RMS'], //	CBEBAFERMS
                        216 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'], // "nome" => 'Combo TOP Plena EBV19PL + QIB 2019 - RMS'], //	CMBTOPPLRM
                        215 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'], // "nome" => 'Eberick 2019 Plena Top RMS'], //	E19PLT
                        213 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'], // "nome" => 'Eberick 2019 Pro Essencial - RMS'], //	E19PRERMS
                        211 => [ "edicao" => 2019, "aplicacao" => 'flex', "modulos" => 'light', "tempo-up" => 3], // "nome" => 'Pacote Flex BIM - 36 meses'], //	PFB12
                        210 => [ "edicao" => 2019, "aplicacao" => 'flex', "modulos" => 'light', "tempo-up" => 2], // "nome" => 'Pacote Flex BIM - 24 meses'], //	PFB12
                        209 => [ "edicao" => 2019, "aplicacao" => 'flex', "modulos" => 'light', "tempo-up" => 1], // "nome" => 'Pacote Flex BIM - 12 meses'], //	PFB12
                        192 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'],
                        191 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        190 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'],
                        189 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'],
                        188 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        187 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        179 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        178 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        177 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'],
                        176 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'],
                        175 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'],
                        173 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'],
                        172 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'],
                        171 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'],
                        170 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        169 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        168 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        167 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        166 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        165 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        164 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        163 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        162 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        161 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        160 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        159 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        158 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        157 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        156 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        155 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        153 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'],
                        152 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'],
                        151 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'],
                        150 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'],
                        149 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'top'],
                        148 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        147 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        146 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        145 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        144 => [ "edicao" => 2019, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        143 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'],
                        142 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'],
                        141 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'],
                        140 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'top'],
                        139 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        138 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        137 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        136 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'],
                        135 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'],
                        134 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'],
                        133 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'light'],
                        132 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'],
                        131 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'],
                        130 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'],
                        129 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'top'],
                        128 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        127 => [ "edicao" => 2019, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        126 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        125 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        124 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'],
                        123 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'],
                        122 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'],
                        121 => [ "edicao" => 2019, "aplicacao" => 'basic', "modulos" => 'light'],
                        101 => [ "edicao" => 2018, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        100 => [ "edicao" => 2018, "aplicacao" => 'plena', "modulos" => 'top'],
                        99 => [ "edicao" => 2018, "aplicacao" => 'pro', "modulos" => 'top'],
                        98 => [ "edicao" => 2018, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        97 => [ "edicao" => 2018, "aplicacao" => 'pro', "modulos" => 'light'],
                        96 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'top'],
                        95 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        94 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'light'],
                        93 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'top'],
                        92 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'light'],
                        91 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        90 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'top'],
                        88 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        87 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'light'],
                        82 => [ "edicao" => 2018, "aplicacao" => 'plena', "modulos" => 'top'],
                        81 => [ "edicao" => 2018, "aplicacao" => 'plena', "modulos" => 'essencial'],
                        80 => [ "edicao" => 2018, "aplicacao" => 'pro', "modulos" => 'top'],
                        79 => [ "edicao" => 2018, "aplicacao" => 'pro', "modulos" => 'essencial'],
                        78 => [ "edicao" => 2018, "aplicacao" => 'pro', "modulos" => 'light'],
                        77 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'top'],
                        76 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'essencial'],
                        75 => [ "edicao" => 2018, "aplicacao" => 'basic', "modulos" => 'light']
                    ];
                                        
    public $ebericks = [

                        825	=> [ "edicao" => 2020, "aplicacao" => 'pro', "rede" =>  true ], // EBV20PRR	ALTOQI EBERICK 2020 PRO REDE
                        824	=> [ "edicao" => 2020, "aplicacao" => 'basic', "rede" => true ], // EBV20BAR	ALTOQI EBERICK 2020 BASIC REDE
                        823	=> [ "edicao" => 2020, "aplicacao" => 'plena', "rede" => true ], // EBV20PLR	ALTOQI EBERICK 2020 PLENA REDE
                        822	=> [ "edicao" => 2020, "aplicacao" => 'plena', "rede" => false ], // EBV20PL	ALTOQI EBERICK 2020 PLENA
                        821	=> [ "edicao" => 2020, "aplicacao" => 'pro', "rede" => false ], // EBV20PR	ALTOQI EBERICK 2020 PRO
                        820	=> [ "edicao" => 2020, "aplicacao" => 'basic', "rede" => false ], // EBV20BA	ALTOQI EBERICK 2020 BASIC
                        819	=> [ "edicao" => 2020, "aplicacao" => 'lite', "rede" => false ], // EBV20LT	ALTOQI EBERICK 2020 LITE
                        818	=> [ "edicao" => 2020, "aplicacao" => 'flex', "rede" => false ], // EBV20FLE	ALTOQI EBERICK 2020 FLEX
                        809	=> [ "edicao" => 2019, "aplicacao" => 'flex', "rede" => false ], // EBV19FLE	ALTOQI EBERICK 2019 FLEX
                        740 => [ "edicao" => 2019, "aplicacao" => 'pro', "rede" => true ],
                        739 => [ "edicao" => 2019, "aplicacao" => 'basic', "rede" => true ],
                        738 => [ "edicao" => 2019, "aplicacao" => 'plena', "rede" => true ],
                        737 => [ "edicao" => 2019, "aplicacao" => 'plena', "rede" => false ],
                        736 => [ "edicao" => 2019, "aplicacao" => 'pro', "rede" => false ],
                        735 => [ "edicao" => 2019, "aplicacao" => 'basic', "rede" => false ],
                        734 => [ "edicao" => 2019, "aplicacao" => 'lite', "rede" => false ],
                        709 => [ "edicao" => 2018, "aplicacao" => 'basic', "rede" => true ],
                        669 => [ "edicao" => 2018, "aplicacao" => 'plena', "rede" => true ],
                        668 => [ "edicao" => 2018, "aplicacao" => 'plena', "rede" => false ],
                        667 => [ "edicao" => 2018, "aplicacao" => 'pro', "rede" => true ],
                        666 => [ "edicao" => 2018, "aplicacao" => 'pro', "rede" => false ],
                        665 => [ "edicao" => 2018, "aplicacao" => 'basic', "rede" => false ],
                        664 => [ "edicao" => 2018, "aplicacao" => 'lite', "rede" => false],
                    ];
    
    public $qibs = [
                    827	=> [ "edicao" => 2020, "aplicacao" => '', "rede" =>  true, 'familia' => null, 'main' => false ], // QIBR 2020	QiBuilder 2020 Rede
                    826	=> [ "edicao" => 2020, "aplicacao" => '', "rede" =>  false, 'familia' => null, 'main' => false ], // QIB 2020	QiBuilder 2020
                    817	=> [ "edicao" => 2020, "aplicacao" => 'pro', "rede" => true, 'familia' => null, 'main' => true ], // QIB2020PRR	QIBUILDER 2020 PRO REDE
                    816	=> [ "edicao" => 2020, "aplicacao" => 'pro', "rede" => false, 'familia' => null, 'main' => true ], // QIB2020PR	QIBUILDER 2020 PRO
                    815	=> [ "edicao" => 2020, "aplicacao" => 'plena', "rede" => true, 'familia' => null, 'main' => true ], // QIB2020PLR	QIBUILDER 2020 PLENA REDE
                    814	=> [ "edicao" => 2020, "aplicacao" => 'plena', "rede" => false, 'familia' => null, 'main' => true ], // QIB2020PL	QIBUILDER 2020 PLENA
                    813	=> [ "edicao" => 2020, "aplicacao" => 'basic', "rede" => true, 'familia' => null, 'main' => true ], // QIB2020BAR	QIBUILDER 2020 BASIC REDE
                    812	=> [ "edicao" => 2020, "aplicacao" => 'basic', "rede" => false, 'familia' => null, 'main' => true ], // QIB2020BA	QIBUILDER 2020 BASIC
                    811	=> [ "edicao" => 2020, "aplicacao" => 'flex', "rede" => false, 'familia' => null, 'main' => true ], // QIB2020FLE	QIBUILDER 2020 FLEX
                    // 810 => [ "edicao" => , "aplicacao" => '', "rede" => false, 'familia' => null, 'main' => false ], // QIBACAD 2019	QiBuilder 2019 Versão Acadêmica
                    808	=> [ "edicao" => 2019, "aplicacao" => 'flex', "rede" => false, 'familia' => null, 'main' => true ], // QIB2019FLE	QIBUILDER 2019 FLEX
                    
                    801 => [ "edicao" => 2019, "aplicacao" => 'plena', "rede" => true, 'familia' => null, 'main' => true ], //QiBuilder 2019 Plena Rede
                    800 => [ "edicao" => 2019, "aplicacao" => 'plena', "rede" => false, 'familia' => null, 'main' => true ],// QiBuilder 2019 Plena
                    799 => [ "edicao" => 2019, "aplicacao" => 'basic', "rede" => true , 'familia' => null, 'main' => true ], //QiBuilder 2019 Basic Rede
                    798 => [ "edicao" => 2019, "aplicacao" => 'basic', "rede" => false, 'familia' => null, 'main' => true ],// QiBuilder 2019 Basic
                    797 => [ "edicao" => 2019, "aplicacao" => 'pro', "rede" => true, 'familia' => null, 'main' => true ], //QiBuilder 2019 PRO Rede
                    796 => [ "edicao" => 2019, "aplicacao" => 'pro', "rede" => false, 'familia' => null, 'main' => true ],// QiBuilder 2019 PRO
                    742 => [ "edicao" => 2019, "aplicacao" => 'plena', "rede" => true, 'familia' => null, 'main' => true ], //QiBuilder 2019 Rede
                    741 => [ "edicao" => 2019, "aplicacao" => '', "rede" => false, 'familia' => null, 'main' => false ],// QiBuilder 2019
                    727 => [ "edicao" => 2018, "aplicacao" => '', "rede" => false, 'familia' => null, 'main' => false ], //QiBuilder 2018 Versão Acadêmica
                    726 => [ "edicao" => null, "aplicacao" => 'pro', "rede" => false, 'familia' => 'ele', 'main' => false ],// QiSPDA Pro
                    725 => [ "edicao" => null, "aplicacao" => 'pro', "rede" => false, 'familia' => 'hid', 'main' => false ],// QiIncêndio Pro
                    724 => [ "edicao" => null, "aplicacao" => 'pro', "rede" => false, 'familia' => 'hid', 'main' => false ],// QiHidrossanitário Pro
                    723 => [ "edicao" => null, "aplicacao" => 'pro', "rede" => false, 'familia' => 'hid', 'main' => false ],// QiGás Pro
                    722 => [ "edicao" => null, "aplicacao" => 'pro', "rede" => false, 'familia' => 'ele', 'main' => false ],// QiElétrico Pro
                    721 => [ "edicao" => null, "aplicacao" => 'pro', "rede" => false, 'familia' => 'ele', 'main' => false ],// QiCabeamento Pro
                    720 => [ "edicao" => null, "aplicacao" => 'pro', "rede" => false, 'familia' => null, 'main' => false ],// QiAlvenaria Pro
                    689 => [ "edicao" => null, "aplicacao" => '', "rede" => false , 'familia' => null, 'main' => false ],//  QIBNEXT
                    629 => [ "edicao" => 2017, "aplicacao" => '', "rede" => false , 'familia' => null, 'main' => false ],//  QiBuilder 2017 Versão Acadêmica
                    606 => [ "edicao" => null, "aplicacao" => '', "rede" => false , 'familia' => null, 'main' => false ],//  QiBuilder PS1 Versão Acadêmica
                    588 => [ "edicao" => 2018, "aplicacao" => '', "rede" => true  , 'familia' => null, 'main' => false ], // QiBuilder 2018 Rede
                    587 => [ "edicao" => 2017, "aplicacao" => '', "rede" => true  , 'familia' => null, 'main' => false ], // QiBuilder 2017 Rede
                    586 => [ "edicao" => 2018, "aplicacao" => '', "rede" => false , 'familia' => null, 'main' => false ],//  QiBuilder 2018
                    585 => [ "edicao" => 2017, "aplicacao" => '', "rede" => false , 'familia' => null, 'main' => false ],//  QiBuilder 2017
                    557 => [ "edicao" => null, "aplicacao" => 'basic', "rede" => false, 'familia' => 'ele', 'main' => false ], //QiSPDA Basic
                    556 => [ "edicao" => null, "aplicacao" => 'basic', "rede" => false, 'familia' => null , 'main' => false ], //QiAlvenaria Basic
                    555 => [ "edicao" => null, "aplicacao" => 'basic', "rede" => false, 'familia' => 'ele', 'main' => false ], //QiCabeamento Basic
                    554 => [ "edicao" => null, "aplicacao" => 'basic', "rede" => false, 'familia' => 'ele', 'main' => false ], //QiElétrico Basic
                    553 => [ "edicao" => null, "aplicacao" => 'basic', "rede" => false, 'familia' => 'hid', 'main' => false ], //QiGás Basic
                    552 => [ "edicao" => null, "aplicacao" => 'basic', "rede" => false, 'familia' => 'hid', 'main' => false ], //QiIncêndio Basic
                    551 => [ "edicao" => null, "aplicacao" => 'basic', "rede" => false, 'familia' => 'hid', 'main' => false ], //QiHidrossanitário Basic
                    542 => [ "edicao" => null, "aplicacao" => '', "rede" => false, 'familia' => null, 'main' => false ], //EXPORTADOR IFC - QIBUILDER
                    454 => [ "edicao" => null, "aplicacao" => 'plena', "rede" => false, 'familia' => 'ele', 'main' => false ], //QiSPDA
                    429 => [ "edicao" => null, "aplicacao" => '', "rede" => false, 'familia' => null, 'main' => false ], //QiBuilder CAD Editor de armaduras
                    428 => [ "edicao" => null, "aplicacao" => '', "rede" => true , 'familia' => null, 'main' => false ], //QiBuilder CAD Rede
                    427 => [ "edicao" => null, "aplicacao" => '', "rede" => false, 'familia' => null, 'main' => false ], //QiBuilder CAD
                    417 => [ "edicao" => null, "aplicacao" => '', "rede" => true , 'familia' => null, 'main' => false ], // QIBEDT
                    401 => [ "edicao" => null, "aplicacao" => '', "rede" => true , 'familia' => null, 'main' => false ], //QiBuilder PS1 Rede
                    400 => [ "edicao" => null, "aplicacao" => 'plena', "rede" => false, 'familia' => null, 'main' => false ], //QiAlvenaria
                    399 => [ "edicao" => null, "aplicacao" => 'plena', "rede" => false, 'familia' => 'ele', 'main' => false ], //QiCabeamento
                    398 => [ "edicao" => null, "aplicacao" => 'plena', "rede" => false, 'familia' => 'ele', 'main' => false ], //QiElétrico
                    397 => [ "edicao" => null, "aplicacao" => 'plena', "rede" => false, 'familia' => 'hid', 'main' => false ], //QiGás
                    396 => [ "edicao" => null, "aplicacao" => 'plena', "rede" => false, 'familia' => 'hid', 'main' => false ], //QiIncêndio
                    395 => [ "edicao" => null, "aplicacao" => '', "rede" => false, 'familia' => null, 'main' => false], //QiBuilder Hydros
                    394 => [ "edicao" => null, "aplicacao" => 'plena', "rede" => false, 'familia' => 'hid', 'main' => false ], //QiHidrossanitário
                    393 => [ "edicao" => null, "aplicacao" => '', "rede" => false, 'familia' => null, 'main' => false ], //QiBuilder PS1
    ];

    public $modulos = [
                        688	=> [ 'modulo' => "top", 'tipo' => "TIPO IV"], //EB043	EFEITO DINÂMICO DEVIDO AO VENTO
                        687	=> [ 'modulo' => "top", 'tipo' => "TIPO III"], //EB044	EXPORTADOR PARA O SAP 2000
                        589	=> [ 'modulo' => "", 'tipo' => "TIPO I"],//EB042	TEMPERATURA E RETRAÇÃO  
                        584	=> [ 'modulo' => "05 - ALVENARIA", 'tipo' => "TIPO IV"],//EB041	DIMENSIONAMENTO DE ALVENARIA ESTRUTURAL 
                        568	=> [ 'modulo' => "top", 'tipo' => "TIPO IV"], //EB040	INTEGRAÇÃO EBERICK E ADAPT
                        541	=> [ 'modulo' => "", 'tipo' => "TIPO I"],//EB039	EXPORTADOR IFC 
                        540	=> [ 'modulo' => "top", 'tipo' => "TIPO III"], //EB038	CONCRETO DE ALTO DESEMPENHO
                        539	=> [ 'modulo' => "top", 'tipo' => "TIPO II"], //EB037	EDITOR DAS GRELHAS
                        538	=> [ 'modulo' => "light", 'tipo' => "TIPO II"], //EB036	REGIÃO MACIÇA EM LAJES
                        510	=> [ 'modulo' => "06 - PRÉ-MOLDADO", 'tipo' => "TIPO IV"],//EB034	PRÉ-MOLDADOS 30
                        509	=> [ 'modulo' => "06 - PRÉ-MOLDADO", 'tipo' => "TIPO IV"],//EB033	PRÉ-MOLDADOS
                        508	=> [ 'modulo' => "", 'tipo' => "TIPO I"],//EB032	EDITOR SIMPLIFICADO DE ARMADURAS 
                        507	=> [ 'modulo' => "light", 'tipo' => "TIPO III"], //EB031	VERIFICAÇÃO DA ESTRUTURA EM SITUAÇÃO DE INCÊNDIO
                        506	=> [ 'modulo' => "essencial", 'tipo' => "TIPO III"], //EB030	LAJES PLANAS
                        505	=> [ 'modulo' => "essencial", 'tipo' => "TIPO II"], //EB029	RESERVATÓRIOS ENTERRADOS
                        504	=> [ 'modulo' => "essencial", 'tipo' => "TIPO II"], //EB028	RESERVATÓRIOS ELEVADOS
                        503	=> [ 'modulo' => "essencial", 'tipo' => "TIPO II"], //EB027	MUROS DE GRAVIDADE
                        502	=> [ 'modulo' => "essencial", 'tipo' => "TIPO II"], //EB026	MUROS DE CONCRETO
                        501	=> [ 'modulo' => "essencial", 'tipo' => "TIPO II"], //EB025	ESCADAS ESPECIAIS
                        500	=> [ 'modulo' => "essencial", 'tipo' => "TIPO II"], //EB024	RADIER
                        499	=> [ 'modulo' => "light", 'tipo' => "TIPO II"], //EB023	TUBULÕES
                        498	=> [ 'modulo' => "essencial", 'tipo' => "TIPO II"], //EB022	BLOCOS COM MAIS DE 6 ESTACAS
                        497	=> [ 'modulo' => "top", 'tipo' => "TIPO II"], //EB021	SAPATA CORRIDA EM APOIO ELÁSTICO
                        496	=> [ 'modulo' => "essencial", 'tipo' => "TIPO II"], //EB020	PILARES ESBELTOS E PILAR PAREDE
                        495	=> [ 'modulo' => "essencial", 'tipo' => "TIPO II"], //EB019	PLASTIFICAÇÃO DAS LAJES
                        494	=> [ 'modulo' => "light", 'tipo' => "TIPO II"], //EB018	LAJES NERVURADAS
                        493	=> [ 'modulo' => "light", 'tipo' => "TIPO II"], //EB017	LAJES TRELIÇADAS 1D E 2D
                        492	=> [ 'modulo' => "light", 'tipo' => "TIPO II"], //EB016	VIGAS CURVAS
                        491	=> [ 'modulo' => "light", 'tipo' => "TIPO I"], //EB015	VIGA COM VARIAÇÃO DE SEÇÃO NO VÃO
                        490	=> [ 'modulo' => "essencial", 'tipo' => "TIPO I"], //EB014	ESTACAS METÁLICAS
                        489	=> [ 'modulo' => "light", 'tipo' => "TIPO I"], //EB013	LANÇAMENTO DE ESTACAS ISOLADAS
                        488	=> [ 'modulo' => "light", 'tipo' => "TIPO I"], //EB012	ABERTURAS EM VIGAS E LAJES
                        487	=> [ 'modulo' => "light", 'tipo' => "TIPO I"], //EB011	BIBLIOTECA DE DETALHES TÍPICOS
                        486	=> [ 'modulo' => "light", 'tipo' => "TIPO I"], //EB010	MEMORIAL DE CÁLCULO
                        485	=> [ 'modulo' => "essencial", 'tipo' => "TIPO I"], //EB009	PAREDES DE CONTENÇÃO
                        484	=> [ 'modulo' => "essencial", 'tipo' => "TIPO I"], //EB008	PLANTA DE LOCAÇÃO DE ESTACAS
                        483	=> [ 'modulo' => "top", 'tipo' => "TIPO I"], //EB007	FUNDAÇÕES ASSOCIADAS
                        482	=> [ 'modulo' => "essencial", 'tipo' => "TIPO I"], //EB006	LAJES COM VIGOTAS PROTENDIDAS
                        481	=> [ 'modulo' => "light", 'tipo' => "TIPO I"], //EB005	VIGAS E PILARES INCLINADOS
                        480	=> [ 'modulo' => "light", 'tipo' => "TIPO I"], //EB004	RAMPAS
                        479	=> [ 'modulo' => "top", 'tipo' => "TIPO I"], //EB003	VINCULOS ELÁSTICOS PARA FUNDAÇÕES
                        478	=> [ 'modulo' => "light", 'tipo' => "TIPO I"], //EB002	VIGAS COM MESA COLABORANTE
                        477	=> [ 'modulo' => "essencial", 'tipo' => "TIPO I"], //EB001	PILARES COM SEÇÃO COMPOSTA
                        391	=> [ 'modulo' => "07 - TIPO", 'tipo' => "07 - TIPO"], //MNEXT
                    ];

    public $mod_tipo_idtop = [ 794 => 'top', 793 => 'essencial', 792 => 'light'];
                    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->table('dba_vendas_produtos');
        $this->primaryKey('registro');

        $this->belongsTo('DbaVendas', [
            'foreignKey' => 'dba_vendas_pedido',
            'joinType' => 'INNER',
            'className' => 'Vendas.DbaVendas'
        ]);

        $this->hasOne('EcmProduto', [
            'foreignKey' => 'idtop',
            'bindingKey' => 'produto_top_id',
            'joinType' => 'LEFT',
            'className' => 'Produto.EcmProduto'
        ]);
    }

    /**
     *  Preparar lista produtos para salvar na tabela DbaVendasProdutos
     * 
     * @param Type DbaVendas $venda
     * @param Type Array DbaVendasProdutos $produtos
     * 
     */
    
    public function setDadosAltoQi(array &$produtos, $venda){

        $protetores = [];
        array_walk( $produtos, function ($prod, $k) use(&$protetores){
                            if(array_key_exists($prod['numero_protetor'], $protetores))
                                $protetores[$prod['numero_protetor']][] = $k;
                            else
                                $protetores[$prod['numero_protetor']] = [$k];
                    });

        foreach ($protetores as $protetor => $keys){
            $return = [ 'ebv' => [], 'qib' => [], 'modulos' => [], 'modulos_linha' => [], 'qib_mod' => [], 'not-all' => []];
            $apps_qib = [ 'apps' => [], 'fam' => [ 'ele' => 0, 'hid' => 0], 'ele' => [], 'hid' => [], 'not' => [], 'main' => []];         

            $qib_edicao = null;
            $ebv_edicao = null;   
            $mod_ebv = '';
            $app_qib = '';
            $mod_qib = '';
            $app_ebv = '';
            $apps_ebv = [];

            foreach ($keys as $key){
                $produto = $produtos[$key];

                if(isset($produto['pacote_top_id']) && array_key_exists($produto['pacote_top_id'], $this->pacotes )){
                    $remove = false;
                    $pac = $this->pacotes[$produto['pacote_top_id']];

                    if($venda->tipo == 'LTEMP' || $venda->tipo == 'LANUAL' ){
                        if(array_key_exists($produto['produto_top_id'], $this->modulos )){
                            $mod = $this->modulos[$produto['produto_top_id']];

                            if(($mod['modulo'] == 'light' || $mod['modulo'] == 'top' || $mod['modulo'] == 'essencial') and $produto['valor'] == 0){
                                $remove = true;
                            }

                            // // QIBNEXT or QIBEDIT or MNEXT
                        }else if( ($produto['produto_top_id'] == 391 || $produto['produto_top_id'] == 417 || $produto['produto_top_id'] == 689) and $produto['valor'] == 0 ) {
                            $remove = true;
                        }
                    }

                    if(array_key_exists($produto['produto_top_id'],  $this->mod_tipo_idtop )){
                        $remove = true;
                    }

                    if($remove)
                        unset($produtos[$key]);
                    else{
                        $produtos[$key]['edicao'] = $pac['edicao'];
                        $produtos[$key]['modulos'] = $pac['modulos'];
                        $produtos[$key]['aplicacao'] = $pac['aplicacao'];

                        $qib_edicao = $pac['edicao'];
                        $ebv_edicao = $pac['edicao'];
                        $app_ebv = $pac['aplicacao'];

                        if( strpos('qib',  $produto['sigla']) == 0 && (array_key_exists($produto['produto_top_id'], $this->qibs )) ){ // QIB
                            $mod = $this->qibs[$produto['produto_top_id']];
                            $produtos[$key]['rede'] = ($mod['rede'])? 1 : 0;

                            if($produtos[$key]['produto_top_id'] == 741){
                                if($produtos[$key]['aplicacao'] == 'basic')
                                    $produtos[$key]['produto_top_id'] = 798;
                                else if($produtos[$key]['aplicacao'] == 'plena')
                                    $produtos[$key]['produto_top_id'] = 800;
                                else if($produtos[$key]['aplicacao'] == 'pro')
                                    $produtos[$key]['produto_top_id'] = 796;
                            }else if($produtos[$key]['produto_top_id'] == 826){
                                if($produtos[$key]['aplicacao'] == 'basic')
                                    $produtos[$key]['produto_top_id'] = 812;
                                else if($produtos[$key]['aplicacao'] == 'plena')
                                    $produtos[$key]['produto_top_id'] = 814;
                                else if($produtos[$key]['aplicacao'] == 'pro')
                                    $produtos[$key]['produto_top_id'] = 816;
                            }else if($produtos[$key]['produto_top_id'] == 827){
                                if($produtos[$key]['aplicacao'] == 'basic')
                                    $produtos[$key]['produto_top_id'] = 813;
                                else if($produtos[$key]['aplicacao'] == 'plena')
                                    $produtos[$key]['produto_top_id'] = 815;
                                else if($produtos[$key]['aplicacao'] == 'pro')
                                    $produtos[$key]['produto_top_id'] = 817;
                            }

                        }else if(array_key_exists($produto['produto_top_id'],  $this->ebericks )){
                            $mod = $this->ebericks[$produto['produto_top_id']];
                            $produtos[$key]['rede'] = ($mod['rede'])? 1 : 0;
                        } else if(array_key_exists($produto['produto_top_id'], $this->modulos ) ){
                            if($venda->tipo == 'VENDI'){
                                $produtos[$key]['aplicacao'] = 'MODULO';
                            }
                        }
                    }
                }else{

                    if(array_key_exists($produto['produto_top_id'],  $this->mod_tipo_idtop )){
                        array_push($return['modulos_linha'],  $this->mod_tipo_idtop[$produto['produto_top_id']] );
                        unset($produtos[$key]);
                    }else if($produto['grupo'] == 4){ // GRUPO EBERICK
                        if(array_key_exists($produto['produto_top_id'],  $this->ebericks ) && $produto['codigo_classificacao'] == '1'){

                            $ebv = $this->ebericks[$produto['produto_top_id']];
                            $produtos[$key]['rede'] = ($ebv['rede'])? 1 : 0;
                            $produtos[$key]['edicao'] = $ebv['edicao'];
                            $produtos[$key]['aplicacao'] = $ebv['aplicacao'];
                            array_push($return['ebv'], $key);

                            $ebv_edicao = $ebv['edicao'];

                        }else{  // MODULOS
                            if(array_key_exists($produto['produto_top_id'], $this->modulos )){
                                $mod = $this->modulos[$produto['produto_top_id']];

                                if(  ( $venda->tipo == 'LTEMP' || $venda->tipo == 'LANUAL' )&& ($mod['modulo'] == 'light' || $mod['modulo'] == 'top' || $mod['modulo'] == 'essencial') ){
                                    array_push($return['modulos_linha'], $mod['modulo']);
                                    unset($produtos[$key]);
                                }else{
                                    $produtos[$key]['aplicacao'] = 'MODULO';
                                    $produtos[$key]['modulos'] = $mod['tipo'];
                                    $produtos[$key]['edicao'] = null;

                                    if($venda->tipo == 'VENDI'){
                                        array_push($return['modulos_linha'], $mod['modulo']);
                                        array_push($return['modulos'], $key);
                                    }else if ($mod['modulo'] != 'light' && $mod['modulo'] != 'top' && $mod['modulo'] != 'essencial'){
                                        
                                        if( ($produto['produto_top_id'] == 509) or ($produto['produto_top_id'] == 584) ){
                                            $produtos[$key]['modulos'] = $mod['modulo'];
                                            $produtos[$key]['aplicacao'] = NULL;
                                        }

                                        array_push($return['modulos'], $key);
                                    }
                                }
                            }else if($produto['produto_top_id'] == 391){ // MNEXT
                                // unset($produtos[$key]);
                                $produtos[$key]['aplicacao'] = 'MODULO';
                                $produtos[$key]['modulos'] = '07 - TIPO';
                                $produtos[$key]['edicao'] = null;
                                array_push($return['modulos'], $key);
                            }else{
                                // array_push($return['not-mod'], $produtos[$key]);
                                // check para tratar
                            }
                        }
                    }else if( strpos($produto['sigla'], 'QIB') >= 0 && strpos($produto['sigla'], 'QIB') !== FALSE ){ // QIB

                        if(array_key_exists($produto['produto_top_id'], $this->qibs )){

                            $mod = $this->qibs[$produto['produto_top_id']];
                            $produtos[$key]['aplicacao'] = $mod['aplicacao'];
                            $produtos[$key]['rede'] = ($mod['rede'])? 1 : 0;
                            $return['qib_mod'][$produto['produto_top_id']] = $mod;
                            array_push($return['qib'], $key);
                            
                            if(is_null($mod['edicao'])){
                                $produtos[$key]['edicao'] =  null;
                            }else{
                                $produtos[$key]['edicao'] = $mod['edicao'];
                                if(is_null($qib_edicao))
                                    $qib_edicao = $mod['edicao'];
                            }

                        }else if(($produto['produto_top_id'] == 417 || $produto['produto_top_id'] == 689) && $produto['valor'] == 0 ){ // QIBNEXT e QIBEDIT
                            unset($produtos[$key]);
                        }else{
                            // check para tratar
                            // array_push($return['not-qib'], $produtos[$key]); 
                            array_push($return['qib'], $key);
                        }

                    }else if( $produto['produto_top_id'] == 526 ){ // QiCOULD
                        $produtos[$key]['edicao'] = $venda->data_venda->format('Y');
                    }else{ ; 
                        // check para tratar
                        // sem grupo ainda 
                         array_push($return['not-all'], $produtos[$key]);
                    }
                }
            }

            if (in_array('top', $return['modulos_linha'])) {
                $mod_ebv = 'top';
            }else if (in_array('essencial', $return['modulos_linha'])) {
                $mod_ebv = 'essencial';
            }else if (in_array('light', $return['modulos_linha'])) {
                $mod_ebv = 'light';
            }

            // Precorre Produtos EBK
            foreach ($return['ebv'] as $k) {
                    if( $produtos[$key]['edicao'] == 2018 && $venda->tipo == 'VENDI'){ // EBK 2018 INDET
                        $produtos[$k]['modulos'] = '01 - STANDARD';
                        $produtos[$k]['tipo_protecao'] = 'USB';
                    }
                    $produtos[$k]['modulos'] = $mod_ebv;

                    if(!empty($produtos[$k]['aplicacao']))
                        array_push($apps_ebv, $produtos[$k]['aplicacao']);
            }

            $apps_ebv = array_unique($apps_ebv);

            if(count($apps_ebv) == 1)
                $app_ebv = $apps_ebv[0];
            else if(count($apps_ebv) > 0){
                echo 'TRATAR ERRO >> 2 Ebericks para um Protetor: '. $protetor. ' Pedido: '.$venda->pedido;
                die;
            }

            // Precorrer produtos QIB
            array_map(function($key) use (&$apps_qib, $produtos){

                if(!empty($produtos[$key]['aplicacao'])){
                    array_push($apps_qib['apps'], $produtos[$key]['aplicacao']);
                }
                
                if(array_key_exists($produtos[$key]['produto_top_id'], $this->qibs )){
                    $mod = $this->qibs[$produtos[$key]['produto_top_id']];

                    if($mod['main'] && !empty($produtos[$key]['aplicacao']) ){
                        $apps_qib['main'][] = $mod['aplicacao'];
                    }
                    
                    if($produtos[$key]['produto_top_id'] == 394 || $produtos[$key]['produto_top_id'] == 551 || $produtos[$key]['produto_top_id'] == 724 ) //QiHidrossanitário
                        array_push($apps_qib['hid'], $produtos[$key]);
                    else if($produtos[$key]['produto_top_id'] == 398 || $produtos[$key]['produto_top_id'] == 554 || $produtos[$key]['produto_top_id'] == 722 ) //QiElétrico
                        array_push($apps_qib['ele'], $produtos[$key]);
                    else  if(isset($mod['familia'])){
                        if($mod['familia'] == 'ele')
                            $apps_qib['fam']['ele']++;
                        else if($mod['familia'] == 'hid')
                            $apps_qib['fam']['hid']++;
                    }else
                        array_push($apps_qib['not'], $produtos[$key]);
                }
            }, $return['qib']);

            $app_qib = array_unique($apps_qib['apps']);

            if(count($app_qib) == 1)
                $app_qib = $app_qib[0];
            else if(count($app_qib) == 0)
                $app_qib = $app_ebv;
            else if(count($app_qib) > 0){

                $app_qib = array_unique($apps_qib['main']);

                if(count($app_qib) == 1)
                    $app_qib = $app_qib[0];
                else if(count($app_qib) == 0)
                    $app_qib = $app_ebv;
                else if(count($app_qib) > 0){
                    //echo 'TRATAR ERRO >> 2 QIBS para um Protetor: '. $protetor. ' Pedido: '.$venda->pedido;
                    //die;
                }
            }

            if(count($return['qib']) == 1){
                if(!empty($mod_ebv) && $mod_ebv != 'light')
                    $mod_qib = $mod_ebv;
                else
                    $mod_qib = 'light';
            }else if(count($return['qib']) > 1){
                if((count($apps_qib['ele']) >= 1) && (count($apps_qib['hid']) >= 1)){
                    if($apps_qib['fam']['ele'] == 0 && $apps_qib['fam']['hid'] == 0)
                        $mod_qib = 'essencial';
                    else 
                        $mod_qib = 'top';
                }else if((count($apps_qib['ele']) >= 1) && $apps_qib['fam']['ele'] > 0 ){
                    $mod_qib = 'essencial';
                }else if((count($apps_qib['hid']) >= 1) && $apps_qib['fam']['hid'] > 0 ){
                    $mod_qib = 'essencial';
                }else if( count($apps_qib['ele']) == 0 && count($apps_qib['hid']) == 0 && ( $apps_qib['fam']['ele'] == 1 || $apps_qib['fam']['hid'] == 1 )){
                    $mod_qib = 'light';
                }else if( (count($apps_qib['ele']) == 1 || count($apps_qib['hid']) == 1) && ( $apps_qib['fam']['ele'] == 0 && $apps_qib['fam']['hid'] == 0 )){
                    $mod_qib = 'light';
                }else if( (count($apps_qib['ele']) == 0 || count($apps_qib['hid']) == 0) && ( $apps_qib['fam']['ele'] == 0 && $apps_qib['fam']['hid'] == 0 )){
                    $mod_qib = $mod_ebv;
                }
            }

            if(is_null($ebv_edicao))
                $ebv_edicao = $venda->data_venda->format('Y');

            if(is_null($qib_edicao) && !is_null($ebv_edicao))
                $qib_edicao = $ebv_edicao;

            foreach ($return['qib'] as $key) {
                if($produtos[$key]['produto_top_id'] == 741){
                    if(empty($produtos[$key]['aplicacao'])){
                        if($app_qib=='basic')
                            $produtos[$key]['produto_top_id'] = 798;
                        else if($app_qib=='plena')
                            $produtos[$key]['produto_top_id'] = 800;
                        else if($app_qib=='pro')
                            $produtos[$key]['produto_top_id'] = 796;

                        $produtos[$key]['aplicacao'] = $app_qib;
                    }else{
                        if($produtos[$key]['aplicacao'] == 'basic')
                            $produtos[$key]['produto_top_id'] = 798;
                        else if($produtos[$key]['aplicacao'] == 'plena')
                            $produtos[$key]['produto_top_id'] = 800;
                        else if($produtos[$key]['aplicacao'] == 'pro')
                            $produtos[$key]['produto_top_id'] = 796;
                    }
                }else if($produtos[$key]['produto_top_id'] == 826){
                    if(empty($produtos[$key]['aplicacao'])){
                        if($app_qib=='basic')
                            $produtos[$key]['produto_top_id'] = 812;
                        else if($app_qib=='plena')
                            $produtos[$key]['produto_top_id'] = 814;
                        else if($app_qib=='pro')
                            $produtos[$key]['produto_top_id'] = 816;

                        $produtos[$key]['aplicacao'] = $app_qib;
                    }else{
                        if($produtos[$key]['aplicacao'] == 'basic')
                            $produtos[$key]['produto_top_id'] = 812;
                        else if($produtos[$key]['aplicacao'] == 'plena')
                            $produtos[$key]['produto_top_id'] = 814;
                        else if($produtos[$key]['aplicacao'] == 'pro')
                            $produtos[$key]['produto_top_id'] = 816;
                    }
                }else if($produtos[$key]['produto_top_id'] == 827){
                    if(empty($produtos[$key]['aplicacao'])){
                        if($app_qib=='basic')
                            $produtos[$key]['produto_top_id'] = 813;
                        else if($app_qib=='plena')
                            $produtos[$key]['produto_top_id'] = 815;
                        else if($app_qib=='pro')
                            $produtos[$key]['produto_top_id'] = 817;

                        $produtos[$key]['aplicacao'] = $app_qib;
                    }else{
                        if($produtos[$key]['aplicacao'] == 'basic')
                            $produtos[$key]['produto_top_id'] = 813;
                        else if($produtos[$key]['aplicacao'] == 'plena')
                            $produtos[$key]['produto_top_id'] = 815;
                        else if($produtos[$key]['aplicacao'] == 'pro')
                            $produtos[$key]['produto_top_id'] = 817;
                    }
                }

                if(empty($produtos[$key]['aplicacao'])){
                    $produtos[$key]['aplicacao'] = $app_qib;
                }else if($produtos[$key]['aplicacao'] != $app_qib ){ //&& $app_qib != 'flex'
                    $produtos[$key]['aplicacao'] = $app_qib;
                }

                if( !array_key_exists('edicao', $produtos[$key]) || ( array_key_exists('edicao', $produtos[$key]) && is_null($produtos[$key]['edicao']))){
                    $produtos[$key]['edicao'] = $qib_edicao;
                }

                if( $produtos[$key]['edicao'] == 2018 && $venda->tipo == 'VENDI' && $produtos[$key]['produto_top_id'] == 586){ // Qib 2018 INDET
                        $mod_qib = '01 - STANDARD';
                }
                $produtos[$key]['modulos'] = $mod_qib;
        
            }

            foreach ($return['modulos'] as $key) {
                if(isset($produtos[$key]) && is_null($produtos[$key]['edicao'])){
                    $produtos[$key]['edicao'] = $ebv_edicao;
                }

                if(isset($produtos[$key]) && is_null($produtos[$key]['aplicacao'])){
                    $produtos[$key]['aplicacao'] = $app_ebv;
                }
            }
        }
    }
}