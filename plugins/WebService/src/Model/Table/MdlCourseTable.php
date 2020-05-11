<?php
namespace WebService\Model\Table;

use Cake\Datasource\ConnectionManager;
use Cake\Network\Http\Client;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use WebService\Model\Entity\MdlCourse;

/**
 * MdlCourse Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $EcmProduto */
class MdlCourseTable extends Table
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

        $this->table('mdl_course');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsToMany('EcmProduto', [
            'foreignKey' => 'mdl_course_id',
            'targetForeignKey' => 'ecm_produto_id',
            'joinTable' => 'ecm_produto_mdl_course',
            'className' => 'Produto.EcmProduto'
        ]);

        $this->hasMany('MdlEnrol', [
            'foreignKey' => 'courseid',
            'joinType' => 'INNER',
            'className' => 'MdlEnrol'
        ]);

        $this->hasMany('MdlCourseModules', [
            'foreignKey' => 'course',
            'joinType' => 'LEFT',
            'className' => 'MdlCourseModules'
        ]);

        $this->hasMany('EcmProdutoMdlCourse', [
            'className' => 'WebService.EcmProdutoMdlCourse'
        ]);
        $this->EcmProdutoMdlCourse->belongsTo('EcmProduto', [
            'className' => 'Produto.EcmProduto'
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
        $validator
            ->allowEmpty('id', 'create');
        $validator
            ->requirePresence('category', 'create')            ->notEmpty('category');
        $validator
            ->requirePresence('sortorder', 'create')            ->notEmpty('sortorder');
        $validator
            ->requirePresence('fullname', 'create')            ->notEmpty('fullname');
        $validator
            ->requirePresence('shortname', 'create')            ->notEmpty('shortname');
        $validator
            ->requirePresence('idnumber', 'create')            ->notEmpty('idnumber');
        $validator
            ->allowEmpty('summary');
        $validator
            ->integer('summaryformat')            ->requirePresence('summaryformat', 'create')            ->notEmpty('summaryformat');
        $validator
            ->requirePresence('format', 'create')            ->notEmpty('format');
        $validator
            ->integer('showgrades')            ->requirePresence('showgrades', 'create')            ->notEmpty('showgrades');
        $validator
            ->integer('newsitems')            ->requirePresence('newsitems', 'create')            ->notEmpty('newsitems');
        $validator
            ->requirePresence('startdate', 'create')            ->notEmpty('startdate');
        $validator
            ->requirePresence('marker', 'create')            ->notEmpty('marker');
        $validator
            ->requirePresence('maxbytes', 'create')            ->notEmpty('maxbytes');
        $validator
            ->integer('legacyfiles')            ->requirePresence('legacyfiles', 'create')            ->notEmpty('legacyfiles');
        $validator
            ->integer('showreports')            ->requirePresence('showreports', 'create')            ->notEmpty('showreports');
        $validator
            ->boolean('visible')            ->requirePresence('visible', 'create')            ->notEmpty('visible');
        $validator
            ->boolean('visibleold')            ->requirePresence('visibleold', 'create')            ->notEmpty('visibleold');
        $validator
            ->integer('groupmode')            ->requirePresence('groupmode', 'create')            ->notEmpty('groupmode');
        $validator
            ->integer('groupmodeforce')            ->requirePresence('groupmodeforce', 'create')            ->notEmpty('groupmodeforce');
        $validator
            ->requirePresence('defaultgroupingid', 'create')            ->notEmpty('defaultgroupingid');
        $validator
            ->requirePresence('lang', 'create')            ->notEmpty('lang');
        $validator
            ->requirePresence('calendartype', 'create')            ->notEmpty('calendartype');
        $validator
            ->requirePresence('theme', 'create')            ->notEmpty('theme');
        $validator
            ->requirePresence('timecreated', 'create')            ->notEmpty('timecreated');
        $validator
            ->requirePresence('timemodified', 'create')            ->notEmpty('timemodified');
        $validator
            ->boolean('requested')            ->requirePresence('requested', 'create')            ->notEmpty('requested');
        $validator
            ->boolean('enablecompletion')            ->requirePresence('enablecompletion', 'create')            ->notEmpty('enablecompletion');
        $validator
            ->boolean('completionnotify')            ->requirePresence('completionnotify', 'create')            ->notEmpty('completionnotify');
        $validator
            ->requirePresence('cacherev', 'create')            ->notEmpty('cacherev');
        $validator
            ->integer('timeaccesssection')            ->requirePresence('timeaccesssection', 'create')            ->notEmpty('timeaccesssection');
        return $validator;
    }

    /**
     * Consulta para retorno do status do curso de um usuário
     *
     * @param int $idUsuario id do usuário.
     * @param int $idCourse id do curso do usuário.
     * @return \stdClass
     */
    public function buscaStatusCursoUsuario($idUsuario, $idCourse = null){
        $sql = "SELECT ue.id,
                       ue.timestart,
                       ue.timeend,
                       ue.enrolid AS enrol,
                   matricula.roleid,
                   c.fullname,
                   c.shortname,
                   c.id AS idcurso,
                   c.idnumber,
                   g.name AS sigla_turma,
                   sci.timecreated AS maxtime,
                   r.shortname AS role_shortname,
                   ue.status,
                    (
                        SELECT i.src
                        FROM ecm_produto p
                        INNER JOIN ecm_produto_ecm_imagem pri ON pri.ecm_produto_id = p.id
                        INNER JOIN ecm_imagem i ON pri.ecm_imagem_id = i.id
                        INNER JOIN ecm_produto_mdl_course pc ON pc.ecm_produto_id = p.id
                        WHERE i.descricao = 'capa' AND pc.mdl_course_id = c.id AND p.refcurso = 'true'
                        LIMIT 1
                    ) as imagem

                FROM (SELECT ra.roleid,
                          ra.contextid,
                          ra.userid
                  FROM mdl_role_assignments ra
                  WHERE ra.userid = :usuario) as matricula

                INNER JOIN mdl_context co
                ON matricula.contextid = co.id

                INNER JOIN mdl_course c
                ON c.id = co.instanceid ";

        if(!is_null($idCourse)) {
            $sql .= " AND c.id = :curso ";
        }

        $sql .= "LEFT JOIN (SELECT g1.courseid, gm1.userid, g1.name
                       FROM mdl_groups g1
                       INNER JOIN mdl_groups_members gm1
                       ON gm1.userid = :usuario
                           AND gm1.groupid = g1.id ";

        if(!is_null($idCourse)) {
            $sql .= " WHERE g1.courseid = :curso ";
        }

        $sql .= ") g
                ON g.courseid = c.id
                AND g.userid = matricula.userid

                INNER JOIN mdl_enrol e
                ON e.courseid = c.id

                INNER JOIN mdl_user_enrolments ue
                ON ue.userid = matricula.userid
                AND ue.enrolid = e.id

                LEFT JOIN mdl_certificate sc
                ON sc.course = c.id

                LEFT JOIN mdl_certificate_issues sci
                ON sci.certificateid = sc.id
                AND sci.userid = matricula.userid

                INNER JOIN mdl_role r
                ON r.id = matricula.roleid

                GROUP BY matricula.roleid, matricula.contextid, matricula.userid";

        $conn = ConnectionManager::get('default');

        $param = ['usuario'=>$idUsuario];

        if(!is_null($idCourse))
            $param['curso'] = $idCourse;

        $consulta = $conn->execute($sql, $param);

        if(is_null($idCourse)) {
            $consulta = $consulta->fetchAll('assoc');
        }else{
            $consulta = $consulta->fetch('assoc');
        }

        if($consulta) {
            if(!is_null($idCourse))
                return (object)$consulta;

            return $consulta;
        }

        return null;
    }

    /**
     * Consulta para retorno do número de prorrogações feitas em um curso de um usuário
     *
     * @param int $idUsuario id do usuário.
     * @param int $idCourse id do curso do usuário.
     * @return int
     */
    public function buscaNumeroProrrogacoes($idUsuario, $idCourse){
        $conn = ConnectionManager::get('default');

        $consulta = $conn->newQuery();
        $consulta->select('count(data_prorrogacao) as quantidade')
            ->from('mdl_prorrogacoes')
            ->where(['userid' => $idUsuario, 'courseid' => $idCourse]);

        $consulta = $consulta->execute();
        $consulta = $consulta->fetch('assoc');

        return $consulta['quantidade'];
    }

    /**
     * Consulta para retorno das prorrogações do curso de um usuário
     *
     * @param int $idUsuario id do usuário.
     * @param int $idCourse id do curso do usuário.
     * @return \array
     */
    public function buscaProrrogacoesCurso($idUsuario, $idCourse){
        $conn = ConnectionManager::get('default');

        $consulta = $conn->newQuery();
        $consulta->select(['data_prorrogacao', 'nu_dias_prorrogacao'])
            ->from('mdl_prorrogacoes')
            ->where(['userid' => $idUsuario, 'courseid' => $idCourse])
            ->orderDesc('timemodified');

        $consulta = $consulta->execute();
        $consulta = $consulta->fetchAll('assoc');

        return $consulta;
    }

    /**
     * Consulta para verificar se o usuário está bloqueado
     *
     * @param int $idUsuario id do usuário.
     * @return boolean
     */
    public function usuarioBloqueado($idUsuario) {
        $sql = 'SELECT ue.id, ue.status, MIN(bc.date_block) AS date_block, bc.date_unblock
            FROM mdl_user_enrolments ue
            LEFT JOIN mdl_bloqueio_curso bc
                ON bc.user_enrolments = ue.id
                AND (bc.date_unblock IS NULL OR bc.date_unblock = 0)
            WHERE ue.userid = ?
            GROUP BY ue.id';


        $conn = ConnectionManager::get('default');
        $consulta = $conn->execute($sql, [$idUsuario]);
        $consulta = $consulta->fetchAll('assoc');

        foreach ($consulta as $value) {
            if(!$value['status'] || !isset($value['date_block'])){
                return false;
            }
        }

        return true;
    }

    /**
     * Consulta para retorno dos recursos visualizados pelo usuário
     *
     * @param int $idUsuario id do usuário.
     * @param int $idCourse id do curso do usuário.
     * @return \stdClass
     */
    public function buscaStatusRecursos($idUsuario, $idCourse){
        $sql ='SELECT  COUNT(*) AS recursos_total,
                        IF(SUM(cmc.completionstate) IS NULL,0,SUM(cmc.completionstate)) AS recursos_finalizados,
                        IF((100 * SUM(cmc.completionstate)) / COUNT(*) IS NULL,ROUND(0, 2),ROUND((100 * SUM(cmc.completionstate)) / COUNT(*), 2)) AS andamento
                FROM mdl_course_completion_criteria ccc
                LEFT JOIN mdl_course_modules_completion cmc
                ON cmc.coursemoduleid = ccc.moduleinstance
                AND cmc.userid = ?
                AND cmc.completionstate = 1
                WHERE ccc.course = ?
                AND ccc.module IS NOT NULL
                AND ccc.criteriatype = 4
                GROUP BY ccc.course';

        $conn = ConnectionManager::get('default');
        $consulta = $conn->execute($sql, [$idUsuario, $idCourse]);
        $consulta = $consulta->fetch('assoc');

        if($consulta)
            return (object) $consulta;

        return null;
    }

    /**
     * Inscreve o aluno em todos os cursos do produto informados
     *
     * @param int     $idUsuario id do usuário.
     * @param int     $idProduto id do produto do usuário.
     * @param boolean $pago      flag de matricula paga.
     * @param int     $proposta  id da proposta da venda.
     * @return \stdClass
     */
    public function matricular($idUsuario, $idProduto, $pago, $proposta = 0){
        $this->MdlFase = TableRegistry::get('MdlFase');
        if($mdlFase = $this->MdlFase->find('all')->where(['ecm_produto_id' => $idProduto])->first()){
            $mdlCourses = $this->find()
                ->matching('EcmProduto', function($q) use ($idProduto) {
                    return $q->where(['EcmProduto.id' => $idProduto]);
                })->toArray();

            $timeStart = new \DateTime();
            $timeStart->setTime(0, 0, 0);
            $timeStart = $timeStart->format('U');

            $timeEnd = new \DateTime();
            $timeEnd->modify('+'.$mdlFase->enrolperiod.' days');
            $timeEnd->setTime(23, 59, 59);
            $timeEnd = $timeEnd->format('U');

            if(is_numeric($proposta)){
                if(!is_int($proposta))
                    $proposta = intval($proposta);
            } else {
                $proposta = 0;
            }

            $response = [];
            $enviarEmail = true;
            foreach($mdlCourses as $mdlCourse){
                $response[$mdlCourse->id] = $this->inserirInscricao([
                    'courseid' => $mdlCourse->id,
                    'userid' => $idUsuario,
                    'time_start' => $timeStart,
                    'time_end' => $timeEnd,
                    'alternative_host' => 713,
                    'proposta' => $proposta,
                    'produto' => $idProduto,
                    'pago' => $pago,
                    'enviar_email' => $enviarEmail
                ]);
                $enviarEmail = false;
            }
            return $response;
        }
        return false;
    }

    /*
    * Função responsável por inserir uma matrícula para um usuário em um curso,
    * utilizando o serviço de matrícula do moodle
    *
    * @param Array $dados Dados para a requisição
    * @param String $dominioMoodle Domínio para onde será feita a requisição
    * */
    public function inserirInscricao($dados){
        $this->EcmConfig = TableRegistry::get('EcmConfig');

        $dominioMoodle = $this->EcmConfig->find()
            ->where(['nome' => 'dominio_acesso_moodle'])
            ->first()->valor;

        $tokenMoodle = $this->EcmConfig->find()
            ->where(['nome'=>'token_web_service_moodle'])
            ->first()->valor;

        $urlWsMoodle = 'http://'.$dominioMoodle.'/webservice/rest/server.php?wstoken='.$tokenMoodle;
        $urlWsMoodle .= '&wsfunction=web_service_matricula&moodlewsrestformat=json';

        $http = new Client();
        $response = $http->post($urlWsMoodle, $dados);

        return json_decode($response->body());
    }
}
