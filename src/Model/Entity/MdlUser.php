<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MdlUser Entity.
 *
 * @property int $id * @property string $auth * @property bool $confirmed * @property bool $policyagreed * @property bool $deleted * @property bool $suspended * @property int $mnethostid * @property string $username * @property string $password * @property string $idnumber * @property string $firstname * @property string $lastname * @property string $email * @property bool $emailstop * @property string $icq * @property string $skype * @property string $yahoo * @property string $aim * @property string $msn * @property string $phone1 * @property string $phone2 * @property string $institution * @property string $department * @property string $address * @property string $city * @property string $country * @property string $lang * @property string $calendartype * @property string $theme * @property string $timezone * @property int $firstaccess * @property int $lastaccess * @property int $lastlogin * @property int $currentlogin * @property string $lastip * @property string $secret * @property int $picture * @property string $url * @property string $description * @property int $descriptionformat * @property bool $mailformat * @property bool $maildigest * @property int $maildisplay * @property bool $autosubscribe * @property bool $trackforums * @property int $timecreated * @property int $timemodified * @property int $trustbitmask * @property string $imagealt * @property string $lastnamephonetic * @property string $firstnamephonetic * @property string $middlename * @property string $alternatename * @property \App\Model\Entity\EcmGrupoPermissao[] $ecm_grupo_permissao */
class MdlUser extends Entity
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
        '*' => true
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    /**
     * Função responsável por verificar se o usuário tem permissão para acessar uma action em um controller
     *
     * @param String $action
     * @param String$controller
     * @param Array $listaPermissoes
     *
     * @return boolean
     */
    public static function verificarPermissao($action, $controller, $plugin, $listaPermissoes){
        if(!isset($plugin)){
            $plugin = "";
        }
        if(array_key_exists('acesso_total', $listaPermissoes) &&
            $listaPermissoes['acesso_total'] == true){
            return true;
        }
        if(array_key_exists($plugin, $listaPermissoes)){
            if(array_key_exists($controller, $listaPermissoes[$plugin])){
                if(array_key_exists($action, $listaPermissoes[$plugin][$controller])){
                    return true;
                }
            }
        }
        return false;
    }

    public static function separarNomeSobrenome($nome){
        $nome = trim($nome);
        $posEspaco = stripos($nome, ' ');
        $posEspaco = !$posEspaco? strlen($nome): $posEspaco;

        $firstname = substr($nome, 0, $posEspaco);
        $lastname = substr($nome, $posEspaco);
        $lastname = !$lastname? '' : trim($lastname);

        return ['firstname' => $firstname, 'lastname' => $lastname];
    }

    public static function validarCPF($cpf){
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
        
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    public static function validarCNPJ($cnpj){
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;

        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj))
            return false;	

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}
