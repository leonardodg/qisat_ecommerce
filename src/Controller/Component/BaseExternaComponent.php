<?php
namespace App\Controller\Component;

use App\Auth\AESPasswordHasher;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class BaseExternaComponent extends Component
{
    private $s3eng_conn;

    public function __construct(ComponentRegistry $registry, array $config)
    {
        parent::__construct($registry, $config);

        /***
         * produção
         */
        //ConnectionManager::config('s3eng', ['url' => 'sqlserver://34.224.59.220/s3eng1?username=s3eng1&password=azx12jkl56']);

        /***
         * Homologação
         */

        // $db = new \PDO('dblib:host=34.225.230.59;dbname=s3eng1', 's3eng1', '3rL5R=ga0Orlc4a5UWRa');
        // var_dump($db);
        // die;

        // try
        // {
        // $db = new \PDO('odbc:Driver=FreeTDS; Server=192.168.100.160; Port=1433; Database=s3eng1; UID=qisat; PWD=qisat.123;');
        // }
        // catch(\PDOException $exception)
        // {
        // die("Unable to open database.<br />Error message:<br /><br />$exception.");
        // }


        // $query = 'SELECT top 10 * FROM Entidade';
        // $statement = $db->prepare($query);
        // $statement->execute();
        // $result = $statement->fetchAll(\PDO::FETCH_NUM);

        // ConnectionManager::config('s3eng', ['url' => 'Dblib://192.168.100.160/s3eng1?username=qisat&password=qisat.123']);
        ConnectionManager::config('s3eng', ['url' => 'Dblib://34.225.230.59/s3eng1?username=s3eng1&password=3rL5R=ga0Orlc4a5UWRa']);

        $this->s3eng_conn = ConnectionManager::get('s3eng');

        // $result = $this->s3eng_conn->execute($query)->fetch('assoc');
        // var_dump($result);
        // die;

    }

    public function buscarUsuario($chaveAltoQi, $senhaInternet){
        $sql = "SELECT TOP 1 ChaveAltoQi, CNPJCPF, Email FROM Entidade WHERE ChaveAltoQi = {$chaveAltoQi} AND SenhaInternet = '{$senhaInternet}'";
        if(!filter_var($chaveAltoQi, FILTER_VALIDATE_EMAIL)===false){
            $sql = "SELECT TOP 1 ChaveAltoQi, CNPJCPF, Email FROM Entidade WHERE Email = '{$chaveAltoQi}' AND SenhaInternet = '{$senhaInternet}'";
        }
        if($user = $this->s3eng_conn->execute($sql)->fetch('assoc'))
            return $user;

        return false;
    }

    public function verificarUsuario($email, $number)
    {
        $this->MdlUser = TableRegistry::get('MdlUser');
        if($this->MdlUser->exists(['email' => $email]) || $this->MdlUser->MdlUserDados->exists(['numero' => $number]))
            return true;

        if(!is_null($email) || !is_null($number)){
            $sql = "SELECT TOP 1 ChaveAltoQi, SenhaInternet, NomeEntidade, CODIGO FROM Entidade WHERE ";
            if(!is_null($email) && !is_null($number))
                $sql .= "Email = '{$email}' OR CNPJCPF = '{$number}'";
            else if(!is_null($email))
                $sql .= "Email = '{$email}'";
            else if(!is_null($number))
                $sql .= "CNPJCPF = '{$number}'";

            if($user = $this->s3eng_conn->execute($sql)->fetch('assoc'))
                return $user;
        }

        return false;
    }


    public function getUser($chave = null, $cpf = null, $email = null)
    {
        $or = false;
        $sql = "SELECT CODIGO, ChaveAltoQi, DigitoChave, CNPJCPF, Email, NomeEntidade FROM Entidade WHERE ";

        if(!is_null($email)){
            $sql .= "Email = '{$email}'";
            $or = true;
        }

        if(!is_null($chave)){
            if($or)
                $sql .= " OR ChaveAltoQi = '{$chave}'";
            else{
                $sql .= "ChaveAltoQi = '{$chave}'";
                $or = true;
            }
        }
        
        if(!is_null($cpf))
            $sql .= ($or) ? " OR CNPJCPF = '{$number}'" : "CNPJCPF = '{$number}'";

         if($user = $this->s3eng_conn->execute($sql)->fetch('assoc'))
                return $user;

        return false;
    }

    public function checkUser($chave = null, $cpf = null, $email = null)
    {
        $or = false;
        $sql = "SELECT ChaveAltoQi, DigitoChave, CNPJCPF, Email, SenhaInternet, NomeEntidade FROM Entidade WHERE ";

        if(!is_null($email)){
            $sql .= "Email = '{$email}'";
            $or = true;
        }

        if(!is_null($chave)){
            if($or)
                $sql .= " OR ChaveAltoQi = '{$chave}'";
            else{
                $sql .= "ChaveAltoQi = '{$chave}'";
                $or = true;
            }
        }
        
        if(!is_null($cpf))
            $sql .= ($or) ? " OR CNPJCPF = '{$number}'" : "CNPJCPF = '{$number}'";

         if($user = $this->s3eng_conn->execute($sql)->fetch('assoc'))
                return true;

        return false;
    }

    public function getUserList($chave = null, $nome = null, $email = null, $number = null )
    {
        $or = false;
        $sql = "SELECT ChaveAltoQi, DigitoChave, CNPJCPF, Email, SenhaInternet, NomeEntidade FROM Entidade WHERE ";


        if(strlen(trim($email)) > 0){
            $sql .= "Email = '{$email}'";
            $or = true;
        }

        if(strlen(trim($number)) > 0){
            if($or)
                $sql .= " OR CNPJCPF = '{$number}'";
            else{
                $sql .= "CNPJCPF = '{$number}'";
                $or = true;
            }
        }

        if(strlen(trim($nome)) > 0){
            if($or)
                $sql .= " OR upper(NomeEntidade) LIKE '%{$nome}%'";
            else{
                $sql .= "upper(NomeEntidade) LIKE '%{$nome}%'";
                $or = true;
            }
        }
        
        if(strlen(trim($chave)) > 0)
            $sql .= ($or) ? " OR ChaveAltoQi = '{$chave}'" : "ChaveAltoQi = '{$chave}'";


        if($list = $this->s3eng_conn->execute($sql)->fetchAll())
            return $list;

        return false;
    }

    public function importarUsuario($chave, $senha = '')
    {
        $sql = "SELECT TOP 1 * FROM Entidade WHERE ChaveAltoQi = ".$chave;
        if(!empty($senha))
            $sql .= " and SenhaInternet = '$senha'";

        if($usuario = $this->s3eng_conn->execute($sql)->fetch('assoc')){

            $objUser['auth'] = "aesauth";
            $objUser['confirmed'] = 1;
            $objUser['username'] = $usuario['ChaveAltoQi'];

            $forcePassword = false;

            $aes = new AESPasswordHasher();
            if(!$usuario['SenhaInternet']){
                $objUser['password'] = $aes->hash($usuario['ChaveAltoQi']);
                $forcePassword = true;
            }else{
                $objUser['password'] = $aes->hash($usuario['SenhaInternet']);
            }
            $objUser['uppass'] = 1;

            $objUser['idnumber'] = $usuario['ChaveAltoQi'];
            $nomeCompleto = ltrim($usuario['NomeEntidade']);
            if(strpos($nomeCompleto, ' ')){
                $firstname = substr($nomeCompleto, 0,strpos($nomeCompleto, ' '));
                $lastname = ltrim(substr($nomeCompleto, strpos($nomeCompleto, ' ')));
            }else{
                $firstname = $nomeCompleto;
                $lastname = ' ';
            }
            $objUser['firstname'] = addslashes($firstname);
            $objUser['lastname'] = addslashes($lastname);
            $objUser['email'] = addslashes($usuario['Email']?:'');
            $objUser['email_old'] = addslashes($usuario['Email2']?:'');

            $telefone = preg_replace("/[^0-9]/", "", $usuario['Telefone']);
            $fone = '';

            if(strlen($telefone) >= 10){
                $ddd = "(".substr($telefone, 0,2).")";
                $fone = $ddd.substr($telefone, 2,4)."-".substr($telefone, 6);
            }elseif(strlen($telefone) >= 8){
                $fone = "(".$usuario['DDD'].")".substr($telefone, 0,4)."-".substr($telefone, 4);
            }else{
                $fone = $usuario['Telefone']? "(".$usuario['DDD'].")".$usuario['Telefone']:'';
            }

            $objUser['phone1'] = $fone;

            $telefone = preg_replace("/[^0-9]/", "", $usuario['Celular']);
            $fone = '';

            if(strlen($telefone) >= 10){
                $ddd = "(".substr($telefone, 0,2).")";
                $fone = $ddd.substr($telefone, 2,4)."-".substr($telefone, 6);
            }elseif(strlen($telefone) >= 8){
                $fone = "(".$usuario['DDD'].")".substr($telefone, 0,4)."-".substr($telefone, 4);
            }else{
                $fone = $usuario['Celular']? "(".$usuario['DDD'].")".$usuario['Celular']:'';
            }

            $objUser['phone2'] = $fone;

            $objUser['institution'] = $usuario['Empresa']?:'';

            $objUser['address'] = $usuario['Endereco']?:'';

            $objUserEndereco['number'] = $usuario['Numero']?:'';
            $objUserEndereco['complement'] = addslashes($usuario['Complemento']?:'');
            $objUserEndereco['district'] = addslashes($usuario['Bairro']?:'');

            $pais = '';
            if(strlen($usuario['Pais']) >= 2 && (strtolower($usuario['Pais']) == 'brasil' || strtolower($usuario['Pais']) == 'br'))
                $pais = 'BR';

            //$objUser['city'] = addslashes($usuario['Cidade']?utf8_encode($usuario['Cidade']):'');
            $objUser['city'] = addslashes($usuario['Cidade']?:'');
            $objUser['country'] = addslashes($pais);
            $objUserEndereco['state'] = addslashes($usuario['UF']?:'');

            $cep = preg_replace("/[^0-9]/", "", $usuario['CEP']);

            if($cep){
                $cep = substr($cep, 0,5)."-".substr($cep, 5,3);
            }else{
                $cep = '';
            }
            $objUserEndereco['cep'] = $cep;
            $objUserEndereco['updateaddress'] = 0;

            $cnpjcpf = preg_replace("/[^0-9]/", "", $usuario['CNPJCPF']);

            $tipoPessoa = null;
            if(strlen($cnpjcpf)==14){
                $tipoPessoa = 'juridico';
                $numero = substr($cnpjcpf, 0,2).".".substr($cnpjcpf, 2,3).".".substr($cnpjcpf, 6,3).
                    "/".substr($cnpjcpf, 8,4)."-".substr($cnpjcpf, 12,2);
            }elseif(strlen($cnpjcpf)==11){
                $tipoPessoa = 'fisico';
                $numero = substr($cnpjcpf, 0,3).".".substr($cnpjcpf, 3,3).".".substr($cnpjcpf, 6,3).
                    "-".substr($cnpjcpf, 9,2);
            }else{
                $numero =  $usuario['CNPJCPF']?$usuario['CNPJCPF']:'';
            }

            $objUser['skype'] = $usuario['Skype']?:'';
            $objUser['reference'] = 'AltoQi';
            $objUser['mnethostid'] = 1;

            $objUser['lang'] = 'pt_br';
            $objUser['timezone'] = 'America/Sao_Paulo';

            $objUser['timecreated'] = time();

            $this->MdlUser = TableRegistry::get('MdlUser');
            $mdlUser = $this->MdlUser->newEntity($objUser);
            if($this->MdlUser->save($mdlUser)){
                $mdlUserDados = $this->MdlUser->MdlUserDados->newEntity(['mdl_user_id' => $mdlUser->id, 'numero' => $numero]);
                if(!is_null($tipoPessoa))
                    $mdlUserDados->tipousuario = $tipoPessoa;
                $this->MdlUser->MdlUserDados->save($mdlUserDados);

                $objUserEndereco['id'] = $mdlUser->id;
                $mdlUserEndereco = $this->MdlUser->MdlUserEndereco->newEntity($objUserEndereco);
                $this->MdlUser->MdlUserEndereco->save($mdlUserEndereco);

                if($forcePassword){
                    $userPreference['userid'] = $mdlUser->id;
                    $userPreference['name'] = "auth_forcepasswordchange";
                    $userPreference['value'] = 1;

                    $mdlUserPreferences = $this->MdlUser->MdlUserPreferences->newEntity($userPreference);
                    $this->MdlUser->MdlUserPreferences->save($mdlUserPreferences);
                }
                return $mdlUser;
            }
        }
        return false;
    }

    public function exportarUsuario($usuario, $origem, $atualizar = false)
    {
        if (isset($usuario->idnumber) && is_numeric($usuario->idnumber)) {
            $chave = $usuario->idnumber;
        } else {
            $chave = $this->buscaProximaChave();
            $this->atualizaProximaChave($chave);
        }
        $digitoChave = $this->calculaDigitoChaveAltoQi($chave);

        $aes = new AESPasswordHasher();
        $password = $aes->decrypt($usuario->password);
        $ddd = substr($usuario->phone1, 1, 2);
        $telefoneFixo = $this->verificaValor(substr($usuario->phone1, 4, 10));
        $telefoneCelular = $this->verificaValor(substr($usuario->phone2, 4, 10));
        $email2 = substr($this->verificaValor($usuario->email_old), 0, 64);
        $empresa = is_null($this->verificaValor($usuario->institution)) ? NULL : substr($usuario->institution, 0, 50);
        $endereco = is_null($this->verificaValor($usuario->address)) ? NULL : substr($usuario->address, 0, 100);
        $numero = is_null($this->verificaValor($usuario->mdl_user_endereco->number)) ? NULL : (int)substr($usuario->mdl_user_endereco->number, 0, 8);
        $cep = substr($this->verificaValor($usuario->mdl_user_endereco->cep), 0, 9);
        $pais = substr(strtoupper($this->verificaValor($usuario->country)), 0, 30);
        $bairro = is_null($this->verificaValor($usuario->mdl_user_endereco->district)) ? NULL : substr($usuario->mdl_user_endereco->district, 0, 50);
        $cidade = substr($usuario->city, 0, 30);
        $complemento = is_null($this->verificaValor($usuario->mdl_user_endereco->complement)) ? NULL : substr($usuario->mdl_user_endereco->complement, 0, 40);
        $nomeCompleto = $usuario->firstname;
        $nomeCompleto .= ' ' . $usuario->lastname;
        $contato = $usuario->firstname;
        $email = substr($usuario->email, 0, 64);
        $nomeCompleto = substr($nomeCompleto, 0, 120);
        $estado = substr(strtoupper($usuario->mdl_user_endereco->state), 0, 30);

        $codigoOrigemDeEntidade = $origem;
        if ($codigoOrigemDeEntidade == '713')
            $codigoOrigemDeEntidade = '893';

        $sql = '';

        if($atualizar) {
            $sql = "UPDATE Entidade SET ";
            $sql .= " NomeEntidade  = '" . $nomeCompleto . "', ";
            $sql .= " CNPJCPF = '" . $usuario->mdl_user_dados->numero . "', ";
            $sql .= " SenhaInternet  = '" . $password . "', ";
            $sql .= " Email = '" . $email . "', ";
            $sql .= " DDD = '" . $ddd . "', ";
            $sql .= " Telefone = '" . $telefoneFixo . "', ";
            $sql .= " Celular = '" . $telefoneCelular . "', ";
            $sql .= " Cidade = '" . $cidade . "', ";
            $sql .= " UF = '" . $estado . "', ";
            $sql .= " Email2 = '" . $email2 . "', ";
            $sql .= " Empresa = '" . $empresa . "', ";
            $sql .= " Endereco = '" . $endereco . "', ";
            $sql .= " Numero = '" . $numero . "', ";
            $sql .= " Complemento = '" . $complemento . "', ";
            $sql .= " Bairro = '" . $bairro . "', ";
            $sql .= " CEP = '" . $cep . "', ";
            $sql .= " Pais = '" . $pais . "', ";
            $sql .= " Contato = '" . $contato . "', ";
            $sql .= " IP = '" . $this->get_client_ip() . "', ";
            $sql .= " Origem = '" . $origem . "'"; //codigo AltoQi no TopMkt para PLATAFORMA MOODLE
            $sql .= " WHERE ChaveAltoQi = ".$chave;
        }else{
            $sql = "INSERT INTO Entidade (ChaveAltoQi,DigitoChave,NomeEntidade,CNPJCPF,SenhaInternet,Email,DDD, Telefone,Celular,IdCidade,Cidade,UF,Email2,Empresa,Endereco,Numero,Complemento,Bairro,CEP,Pais,Contato,IP, Origem, CodigoOrigemCadastro, CodigoOrigemDeEntidade) VALUES (";
            $sql .= (isset($chave) ? (int)$chave : $chave) . ", ";
            $sql .= $digitoChave . ", ";
            $sql .= "'" . $nomeCompleto . "', ";
            $sql .= "'" . $usuario->mdl_user_dados->numero . "', ";
            $sql .= "'" . $password . "', ";
            $sql .= "'" . $email . "', ";
            $sql .= "'" . $ddd . "', ";
            $sql .= "'" . $telefoneFixo . "', ";
            $sql .= "'" . $telefoneCelular . "', ";
            $sql .= "0, ";
            $sql .= "'" . $cidade . "', ";
            $sql .= "'" . $estado . "', ";
            $sql .= "'" . $email2 . "', ";
            $sql .= "'" . $empresa . "', ";
            $sql .= "'" . $endereco . "', ";
            $sql .= "'" . $numero . "', ";
            $sql .= "'" . $complemento . "', ";
            $sql .= "'" . $bairro . "', ";
            $sql .= "'" . $cep . "', ";
            $sql .= "'" . $pais . "', ";
            $sql .= "'" . $contato . "', ";
            $sql .= "'" . $this->get_client_ip() . "', ";
            $sql .= "'" . $origem . "', "; //codigo AltoQi no TopMkt para PLATAFORMA MOODLE
            $sql .= "2, ";  // 1 = S3Eng 2 = QiSat
            $sql .= $codigoOrigemDeEntidade . ")";
        }

        if(isset($chave) && !empty($chave)){
            $usuario->username = $chave;
            $usuario->idnumber = $chave;
        }

        //$this->enviarTokenConfirmacao($usuario);

        try {
            if(isset($_SESSION['Auth']['User']['id'])){
                $log_ecommerce['mdl_user_id'] = $_SESSION['Auth']['User']['id'];

                $log_ecommerce['tabela'] = 'Entidade SQL_SERVER';
                $log_ecommerce['acao'] = 'insert';
                $log_ecommerce['chave'] = $chave;
                $log_ecommerce['data'] = time();
                $log_ecommerce['ip'] = $this->get_client_ip();

                $url = '';
                if(isset($_SERVER['HTTP_REFERER'])){
                    $url = $_SERVER['HTTP_REFERER'];
                }elseif($_SERVER['REDIRECT_URL']){
                    $url = $_SERVER['REDIRECT_URL'];
                }
                $log_ecommerce['url'] = $url;

                $this->EcmLogAcao = TableRegistry::get('EcmLogAcao');
                $ecmLogAcao = $this->EcmLogAcao->newEntity($log_ecommerce);
                $this->EcmLogAcao->save($ecmLogAcao);
            }

            $this->s3eng_conn->execute($sql);

            return $usuario;
        } catch (\Exception $e) {
            $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
            $noreply = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_noreply']])->first()->valor;
            $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;

            $email = new Email();
            $email->from([$noreply => 'QiSat | O Canal de e-Learning da Engenharia'])
                ->to([$supportemail => 'Administrador QiSat'])
                ->subject('QiSat | Falha na exportação do usuário')
                ->send('Não foi possivel exportar para a AltoQi o usuário:

                    ID: '.(isset($usuario->id) ? $usuario->id : '').'
                    NOME: '.$usuario->firstname.' '.$usuario->lastname.'
                    EMAIL: '.$usuario->email.'
                    USERNAME: '.$chave.'-'.$digitoChave.'
                
                    Erro:'.$e);
        }

        return false;
    }

   public function editUsuario($chave, $cpf = null, $email = null)
    {

        if($chave && ($email || $cpf)){
            if($email) $email = substr($email, 0, 64);

            $sql = "UPDATE Entidade SET ";
            if($cpf && $email) $sql .=  " CNPJCPF = '" . $cpf. "', Email = '" . $email."'";
            else if($email) $sql .=  " Email = '" . $email."'";
            else if($cpf) $sql .=  " CNPJCPF = '" . $cpf."'";
            $sql .= " WHERE ChaveAltoQi = ".$chave;

            try {
                if(isset($_SESSION['Auth']['User']['id'])){
                    $log_ecommerce['mdl_user_id'] = $_SESSION['Auth']['User']['id'];

                    $log_ecommerce['tabela'] = 'Entidade SQL_SERVER';
                    $log_ecommerce['acao'] = 'update';
                    $log_ecommerce['chave'] = $chave;
                    $log_ecommerce['data'] = time();
                    $log_ecommerce['ip'] = $this->get_client_ip();

                    $url = '';
                    if(isset($_SERVER['HTTP_REFERER'])){
                        $url = $_SERVER['HTTP_REFERER'];
                    }elseif($_SERVER['REDIRECT_URL']){
                        $url = $_SERVER['REDIRECT_URL'];
                    }
                    $log_ecommerce['url'] = $url;

                    $this->EcmLogAcao = TableRegistry::get('EcmLogAcao');
                    $ecmLogAcao = $this->EcmLogAcao->newEntity($log_ecommerce);
                    $this->EcmLogAcao->save($ecmLogAcao);
                }

                return $this->s3eng_conn->execute($sql);
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    private function verificaValor($valor)
    {
        return (!isset($valor) || empty($valor)) ? null : $valor;
    }

    private function buscaProximaChave()
    {
        $sql = "SELECT TOP 1 * FROM Configuracoes ORDER BY idConfiguracao desc";
        if ($chave = $this->s3eng_conn->execute($sql)->fetch('assoc')) {
            return $chave['ProximaChave'];
        }
        return false;
    }

    private function atualizaProximaChave($chaveAtual){
        $novaChave = $chaveAtual + 1;
        $sql = "UPDATE CONFIGURACOES SET PROXIMACHAVE = ($novaChave)";
        $this->s3eng_conn->execute($sql);
    }

    private function calculaDigitoChaveAltoQi($chave){
        $chave = str_pad($chave, 6 , "0", STR_PAD_LEFT);
        $d1 = (int)($chave[0]);
        $d2 = (int)($chave[1]);
        $d3 = (int)($chave[2]);
        $d4 = (int)($chave[3]);
        $d5 = (int)($chave[4]);
        $d6 = (int)($chave[5]);
        $multiplica = (($d1 * 5) + ($d2 * 6) + ($d3 * 7) + ($d4 * 3) + ($d5 * 2) + ($d6 * 4));
        $resto = $multiplica % 11;
        if($resto == 10){
            return 1;
        }
        return $resto;
    }

    private function get_client_ip() {
        $ipaddress = 'UNKNOWN';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        return $ipaddress;
    }
}