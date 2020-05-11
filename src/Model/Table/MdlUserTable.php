<?php
namespace App\Model\Table;

use App\Auth\AESPasswordHasher;
use Cake\Log\Log;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * MdlUser Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $EcmGrupoPermissao */
class MdlUserTable extends \Cake\ORM\Table
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

        $this->table('mdl_user');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsToMany('EcmGrupoPermissao', [
            'foreignKey' => 'mdl_user_id',
            'targetForeignKey' => 'ecm_grupo_permissao_id',
            'joinTable' => 'ecm_grupo_permissao_mdl_user'
        ]);

        $this->hasMany('MdlUserEcmAlternativeHost', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'LEFT',
            'className' => 'Entidade.MdlUserEcmAlternativeHost'
        ]);

        $this->hasOne('EcmInstrutor', [
            'joinType' => 'INNER',
            'className' => 'Instrutor.EcmInstrutor'
        ]);

        $this->hasMany('MdlUserEnrolments', [
            'foreignKey' => 'userid',
            'className' => 'MdlUserEnrolments'
        ]);

        $this->MdlUserEnrolments->belongsTo('MdlEnrol', [
            'foreignKey' => 'enrolid',
            'className' => 'MdlEnrol'
        ]);

        $this->MdlUserEnrolments->MdlEnrol->hasOne('MdlCourse', [
            'foreignKey' => 'id',
            'bindingKey' => 'courseid',
            'className' => 'MdlCourse'
        ]);

        $this->MdlUserEnrolments->MdlEnrol->MdlCourse->belongsToMany('EcmProduto', [
            'foreignKey' => 'mdl_course_id',
            'targetForeignKey' => 'ecm_produto_id',
            'joinTable' => 'ecm_produto_mdl_course',
            'className' => 'Produto.EcmProduto'
        ]);

        $this->hasMany('MdlGroupsMembers', [
            'foreignKey' => 'userid',
            'className' => 'MdlGroupsMembers'
        ]);

        $this->MdlGroupsMembers->hasOne('MdlGroups', [
            'foreignKey' => 'id',
            'bindingKey' => 'groupid',
            'className' => 'MdlGroups'
        ]);

        $this->hasOne('MdlUserDados', [
            'joinType' => 'LEFT',
            'className' => 'MdlUserDados'
        ]);

        $this->hasOne('MdlUserEndereco', [
            'joinType' => 'LEFT',
            'foreignKey' => 'id',
            'className' => 'MdlUserEndereco'
        ]);

        $this->hasMany('MdlUserPreferences', [
            'foreignKey' => 'userid',
            'className' => 'MdlUserPreferences'
        ]);

        $this->hasMany('MdlRoleAssignments', [
            'foreignKey' => 'userid',
            'className' => 'MdlRoleAssignments'
        ]);

        $this->MdlRoleAssignments->belongsTo('MdlContext', [
            'foreignKey' => 'contextid',
            'className' => 'MdlContext'
        ]);

        $this->hasMany('MdlGroupsMembers', [
            'foreignKey' => 'userid',
            'className' => 'MdlGroupsMembers'
        ]);
        $this->MdlGroupsMembers->hasMany('MdlGroups', [
            'foreignKey' => 'id',
            'bindingKey' => 'groupid',
            'className' => 'MdlGroups'
        ]);
        $this->MdlGroupsMembers->MdlGroups->hasMany('MdlFase', [
            'foreignKey' => 'id',
            'bindingKey' => 'mdl_fase_id',
            'className' => 'Produto.MdlFase'
        ]);

        $this->MdlUserEnrolments->MdlEnrol->hasMany('MdlGroups', [
            'foreignKey' => 'courseid',
            'bindingKey' => 'courseid',
            'className' => 'MdlGroups'
        ]);
        $this->MdlUserEnrolments->MdlEnrol->MdlGroups->hasMany('MdlGroupsMembers', [
            'foreignKey' => 'groupid',
            'bindingKey' => 'id',
            'className' => 'MdlGroupsMembers'
            ,'joinType' => 'LEFT'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator->allowEmpty('id', 'update');
        $validator->requirePresence('auth', 'create')->notEmpty('auth');
        //$validator->requirePresence('username', 'update')->notEmpty('username');
        $validator->requirePresence('password', 'create')->notEmpty('password');
        //$validator->requirePresence('idnumber', 'update')->notEmpty('idnumber');
        $validator->requirePresence('firstname', 'create')->notEmpty('firstname');
        $validator->requirePresence('lastname', 'create')->notEmpty('lastname');
        $validator->email('email')->requirePresence('email', 'create')->notEmpty('email');
        $validator->requirePresence('lang', 'create')->notEmpty('lang');
        $validator->requirePresence('timezone', 'create')->notEmpty('timezone');
        $validator->allowEmpty('description');
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        //$rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }

    /**
     * @return array
     */
    public function getFase($id = null, $tipo = null){
        $retorno = ['sucesso' => false, 'mensagem' => __('Usuário não encontrado')];
        if(is_numeric($id)) {
            $mdlGroupsMembers = $this->MdlGroupsMembers->find()->contain(['MdlGroups'])
                ->notMatching('MdlGroups', function ($q) {
                    return $q->where(['mdl_fase_id IS NULL']);
                })
                ->where(['userid' => $id])->toArray();

            $retorno['mensagem'] = __('Não foi encontrada nenhuma inscrição do aluno em trilhas');
            if (!empty($mdlGroupsMembers)) {
                $where = [];
                foreach($mdlGroupsMembers as $mdlGroupsMember){
                    foreach($mdlGroupsMember->mdl_groups as $mdlGroup){
                        $where[] = $mdlGroup->mdl_fase_id;
                    }
                }
                $this->MdlFase = TableRegistry::get('Produto.MdlFase');
                $mdlFase = $this->MdlFase->find()->where(['MdlFase.id IN' => $where])
                    /*->matching('EcmProduto', function ($q){
                        return $q->where([
                            'EcmProduto.habilitado' => 'true'
                        ]);
                    })*/
                ;
                if(!is_null($tipo)){
                    $mdlFase = $mdlFase->matching('EcmProduto.EcmTipoProduto', function ($q) use ($tipo){
                        return $q->where([
                            'EcmTipoProduto.id' => $tipo
                        ]);
                    });
                }

                $retorno = ['sucesso' => true, 'mdlFase' => $mdlFase->toArray(), 'userid' => $id];
            }
        }
        return $retorno;
    }

    /**
     * EnviarImagem method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function enviarImagem($picture, $mdlUser, $moodledata)
    {
        $id = $mdlUser->id;
        $dir = $moodledata . '\user\0\\' . $id;
        if($id>=1000)
            $dir = $moodledata . '\user\\' . substr($id, (strlen($id)-3), strlen($id)) . '000\\' . $id;
        if(file_exists($dir)){
            $files = scandir($dir);
            foreach($files as $file) {
                if(!is_dir($file)) {
                    unlink($dir.'\\'.$file);
                }
            }
        }else{
            mkdir($dir);
        }
        $this->process_profile_image($picture['tmp_name'], $dir);
        if($mdlUser->picture != 1){
            $mdlUser->picture = 1;
            $this->save($mdlUser);
        }
    }
    private function process_profile_image($originalfile, $destination) {
        $imageinfo = getimagesize($originalfile);
        if (empty($imageinfo)) {
            if (file_exists($originalfile)) {
                unlink($originalfile);
            }
            return false;
        }
        $image = new \stdClass();
        $image->width  = $imageinfo[0];
        $image->height = $imageinfo[1];
        $image->type   = $imageinfo[2];
        switch ($image->type) {
            case IMAGETYPE_GIF:
                if (function_exists('imagecreatefromgif')) {
                    $im = imagecreatefromgif($originalfile);
                } else {
                    unlink($originalfile);
                    return false;
                }
                break;
            case IMAGETYPE_JPEG:
                if (function_exists('imagecreatefromjpeg')) {
                    $im = imagecreatefromjpeg($originalfile);
                } else {
                    unlink($originalfile);
                    return false;
                }
                break;
            case IMAGETYPE_PNG:
                if (function_exists('imagecreatefrompng')) {
                    $im = imagecreatefrompng($originalfile);
                } else {
                    unlink($originalfile);
                    return false;
                }
                break;
            default:
                unlink($originalfile);
                return false;
        }
        unlink($originalfile);
        if (function_exists('ImageCreateTrueColor')) {
            $im1 = imagecreatetruecolor(100,100);
            $im2 = imagecreatetruecolor(35,35);
        } else {
            $im1 = imagecreate(100,100);
            $im2 = imagecreate(35,35);
        }
        $cx = $image->width / 2;
        $cy = $image->height / 2;
        if ($image->width < $image->height) {
            $half = floor($image->width / 2.0);
        } else {
            $half = floor($image->height / 2.0);
        }
        $this->ImageCopyBicubic($im1, $im, 0, 0, $cx-$half, $cy-$half, 100, 100, $half*2, $half*2);
        $this->ImageCopyBicubic($im2, $im, 0, 0, $cx-$half, $cy-$half, 35, 35, $half*2, $half*2);
        if (function_exists('ImageJpeg')) {
            @touch($destination .'/f1.jpg');  // Helps in Safe mode
            @touch($destination .'/f2.jpg');  // Helps in Safe mode
            if (imagejpeg($im1, $destination .'/f1.jpg', 90) and
                imagejpeg($im2, $destination .'/f2.jpg', 95) ) {
                @chmod($destination .'/f1.jpg', 0666);
                @chmod($destination .'/f2.jpg', 0666);
                return 1;
            }
        }
        return 0;
    }
    private function ImageCopyBicubic($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) {
        if (function_exists('imagecopyresampled')) {
            return imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y,
                $dst_w, $dst_h, $src_w, $src_h);
        }
        $totalcolors = imagecolorstotal($src_img);
        for ($i=0; $i<$totalcolors; $i++) {
            if ($colors = imagecolorsforindex($src_img, $i)) {
                imagecolorallocate($dst_img, $colors['red'], $colors['green'], $colors['blue']);
            }
        }
        $scaleX = ($src_w - 1) / $dst_w;
        $scaleY = ($src_h - 1) / $dst_h;
        $scaleX2 = $scaleX / 2.0;
        $scaleY2 = $scaleY / 2.0;
        for ($j = 0; $j < $dst_h; $j++) {
            $sY = $j * $scaleY;
            for ($i = 0; $i < $dst_w; $i++) {
                $sX = $i * $scaleX;
                $c1 = imagecolorsforindex($src_img,imagecolorat($src_img,(int)$sX,(int)$sY+$scaleY2));
                $c2 = imagecolorsforindex($src_img,imagecolorat($src_img,(int)$sX,(int)$sY));
                $c3 = imagecolorsforindex($src_img,imagecolorat($src_img,(int)$sX+$scaleX2,(int)$sY+$scaleY2));
                $c4 = imagecolorsforindex($src_img,imagecolorat($src_img,(int)$sX+$scaleX2,(int)$sY));
                $red = (int) (($c1['red'] + $c2['red'] + $c3['red'] + $c4['red']) / 4);
                $green = (int) (($c1['green'] + $c2['green'] + $c3['green'] + $c4['green']) / 4);
                $blue = (int) (($c1['blue'] + $c2['blue'] + $c3['blue'] + $c4['blue']) / 4);
                $color = imagecolorclosest($dst_img, $red, $green, $blue);
                imagesetpixel($dst_img, $i + $dst_x, $j + $dst_y, $color);
            }
        }
    }

    public function verificaStatusCurso($user_enrolments, $userid){
        $mdlRoleAssignments = $this->MdlRoleAssignments->find('all', ['contain' => ['MdlContext']])
            ->where(['MdlRoleAssignments.userid' => $userid,
                'MdlContext.instanceid' => $user_enrolments['mdl_enrol']['mdl_course']['id'],
                'MdlContext.contextlevel' => 50])
            ->first();

        if(!is_null($mdlRoleAssignments) && $mdlRoleAssignments->roleid == 9)
            return ['roleid' => $mdlRoleAssignments->roleid, 'status' => 'Curso Bloqueado'];

        if(!is_null($mdlRoleAssignments) && $mdlRoleAssignments->roleid == 25)
            return ['roleid' => $mdlRoleAssignments->roleid, 'status' => 'Aguardando Pagamento'];

        $semaceite = false;
        if(!is_null($mdlRoleAssignments) && $mdlRoleAssignments->roleid == 11)
            $semaceite = true;

        $finalizado = false;
        $this->MdlCertificate = TableRegistry::get('WebService.MdlCertificate');
        $mdlCertificate = $this->MdlCertificate->find('all', ['contain' => ['MdlCertificateIssues']])
            ->where(['course' => $user_enrolments['mdl_enrol']['mdl_course']['id'], 'userid' => $userid])->first();

        if(isset($mdlCertificate))
            $finalizado = true;

        $validade = floor($user_enrolments['mdl_enrol']['enrolperiod'] / 86400);
        $dt_atual = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $dt_inicio = $user_enrolments->timestart;
        $dt_fim = $user_enrolments->timeend;

        $roleid = is_null($mdlRoleAssignments) ? 5 : $mdlRoleAssignments->roleid;

        if($dt_inicio > ($dt_atual + 86399))
            return ['roleid' => $roleid, 'status' => 'Curso Agendado'];

        if($dt_fim == 0)
            return ['roleid' => $roleid, 'status' => 'Liberado para Acesso'];

        if(($dt_atual > $dt_fim) && ($validade)){
            if($finalizado)
                return ['roleid' => $roleid, 'status' => 'Curso Finalizado'];

            return ['roleid' => $roleid, 'status' => 'Prazo Encerrado'];
        }
        if($semaceite)
            return ['roleid' => $roleid, 'status' => 'Curso Sem Contrato'];

        return ['roleid' => $roleid, 'status' => 'Liberado para Acesso'];
    }

    public function validarUserAltoQi($data){
        $where = [ 'OR' => []];
        if(!empty($data['chave_altoqi'])){
            $where['OR']['MdlUser.idnumber'] = $data['chave_altoqi'];
            $where['OR']['MdlUser.username'] = $data['chave_altoqi'];
        }

        if(isset($data['entidade'])){
            if(!empty($data['entidade']['Numero']))
                $where['OR']['MdlUserDados.numero'] = $data['entidade']['Numero'];

            if(!empty($data['entidade']['Email']))
                $where['OR']['MdlUser.email'] = $data['entidade']['Email'];
        }

        try{
            $getUsers = $this->find('all')
                            ->contain(['MdlUserDados'])
                            ->where($where)->toArray();
        }catch(RecordNotFoundException $e){
            $user = false;
        }

        $foundUser = false;
        if($getUsers && count($getUsers) == 1){
            $user = $getUsers[0];
            if(($user->idnumber == $data['chave_altoqi']) || ($user->mdl_user_dado->numero == $data['entidade']['Numero'])){
                $foundUser = true; 
            }else{
                $user = false;
                $foundUser = false;
            }
        } else if($getUsers && count($getUsers) > 1){
            $user = array_map( function($el) use ($data){
                return ($el->idnumber == $data['chave_altoqi']) || ($el->username == $data['chave_altoqi']); 
            }, $getUsers);

            $k = array_search(true, $user);
            if($k >=0){
                $user = $getUsers[$k];
                $foundUser = true; 
            }else{
                $user = false;
                $foundUser = false; 
            }

        }else{
            $user = false;
        }

        if(!$user && !$foundUser){

            $user = [ 'auth' => 'aesauth', 'confirmed' => '1', 'mnethostid' => '1', 'country' => 'BR', 'lang' => 'pt_br', 'timezone' => 'America/Sao_Paulo', 'timecreated' => time() ];

            $aes = new AESPasswordHasher();
            $user['password'] = (empty($data['entidade']['Senha'])) ? $aes->hash($data['entidade']['Chave']) : $aes->hash($data['entidade']['Senha']);
            $user['idnumber'] = $data['entidade']['Chave'];
            $user['username'] = $data['entidade']['Chave'];

            if(strpos($data['entidade']['Nome'], ' ')){
                $user['firstname'] = substr($data['entidade']['Nome'], 0,strpos($data['entidade']['Nome'], ' '));
                $user['lastname'] = ltrim(substr($data['entidade']['Nome'], strpos($data['entidade']['Nome'], ' ')));
            }else{
                $user['firstname'] = $data['entidade']['Nome'];
                $user['lastname'] = '';
            }

            if(!empty($data['entidade']['Email']))
                $user['email'] = $data['entidade']['Email'];

            $ddd = "(".$data['entidade']['DDD'].")";
            $telefone2 = "";
            $telefone1 = "";

            if(!empty($data['entidade']['FoneCelular'])){
                $telefone1 = preg_replace("/[^0-9]/", "", $data['entidade']['FoneCelular']);
                if(!empty($data['entidade']['FoneComercial'])){
                    $telefone2 = preg_replace("/[^0-9]/", "", $data['entidade']['FoneComercial']);
                }else if(!empty($data['entidade']['FoneResidencial'])){
                    $telefone2 = preg_replace("/[^0-9]/", "", $data['entidade']['FoneResidencial']);
                }
            }else if(!empty($data['entidade']['FoneComercial'])){
                $telefone1 = preg_replace("/[^0-9]/", "", $data['entidade']['FoneComercial']);
                if(!empty($data['entidade']['FoneResidencial']))
                    $telefone2 = preg_replace("/[^0-9]/", "", $data['entidade']['FoneResidencial']);
            }else if(!empty($data['entidade']['FoneResidencial'])){
                $telefone1 = preg_replace("/[^0-9]/", "", $data['entidade']['FoneResidencial']);
                $telefone2 = "";
            }

            if(!empty($telefone1)){
                if(strlen($telefone1) < 8)
                    $telefone1 = $ddd.$telefone1;
                else if (strlen($telefone1) == 8)
                    $telefone1 = $ddd.substr($telefone1, 0,4)."-".substr($telefone1, 4);
                else if (strlen($telefone1) == 9)
                    $telefone1 = $ddd.substr($telefone1, 0,5)."-".substr($telefone1, 5);
                else if (strlen($telefone1) >= 10)
                    $telefone1 = "(".substr($telefone1, 0,2).")".substr($telefone1, 2,5)."-".substr($telefone1, 5);
                
                $user['phone1']  = $telefone1;
            }

            if(!empty($telefone2)){
                if(strlen($telefone2) < 8)
                    $telefone2 = $ddd.$telefone2;
                else if (strlen($telefone2) == 8)
                    $telefone2 = $ddd.substr($telefone2, 0,4)."-".substr($telefone2, 4);
                else if (strlen($telefone2) == 9)
                    $telefone2 = $ddd.substr($telefone2, 0,5)."-".substr($telefone2, 5);
                else if (strlen($telefone2) >= 10)
                    $telefone2 = "(".substr($telefone2, 0,2).")".substr($telefone2, 2,5)."-".substr($telefone2, 5);
                
                $user['phone2']  = $telefone2;
            }
            
            $user['address'] = $data['entidade']['Endereco']?:'';
            $user['city'] = $data['entidade']['Cidade']?:'';

            $endereco = [];
            $endereco['number'] = $data['entidade']['NumeroEndereco']?:'';
            $endereco['complement'] = $data['entidade']['Complemento']?:'';
            $endereco['district'] = $data['entidade']['Bairro']?:'';
            $endereco['state'] = $data['entidade']['UF']?:'';
            $endereco['cep'] = $data['entidade']['CEP']?:'';

            $userDados = [];
            $tipoPessoa = null;
            $userDados['numero'] = $data['entidade']['Numero'];
            if(strlen(preg_replace("/[^0-9]/", "", $data['entidade']['Numero']))==14)
                $tipoPessoa = 'juridico';
            elseif(strlen(preg_replace("/[^0-9]/", "", $data['entidade']['Numero']))==11)
                $tipoPessoa = 'fisico';
            $userDados['tipousuario'] = $tipoPessoa;

            if(!empty($data['entidade']['Crea']))
                $userDados['numero_crea'] = $data['entidade']['Crea'];
            
            $user = $this->newEntity($user);

            if($this->save($user)){
                $endereco['id'] = $user->id;
                $userDados['mdl_user_id'] = $user->id;
                $userDados = $this->MdlUserDados->newEntity($userDados);
                $this->MdlUserDados->save($userDados);
                $endereco = $this->MdlUserEndereco->newEntity($endereco);
                $this->MdlUserEndereco->save($endereco);
            }else{
                //array_push($return['errors'], $user->errors());
                //array_push($return['errors'],  '( Pedido: '.$venda->pedido.' ) Email: '.$data['entidade']['Email'].' Chave:'.$data['chave_altoqi']. ' CPF:'. $data['entidade']['Numero']);
                Log::error('Erro na criação/edição de usuário: "./src/Model/MdlUserTable::validarUserAltoQi"');
                Log::error(array(
                    "chave_altoqi" => $data['chave_altoqi'],
                    "entidade"     => $data['entidade']
                ));
                Log::error($user->errors());
                $user = false;
            }
        }
        return $user;
    }
}
