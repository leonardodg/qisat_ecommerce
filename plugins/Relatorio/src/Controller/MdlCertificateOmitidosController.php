<?php
namespace Relatorio\Controller;

use Cake\Datasource\ConnectionManager;
use Relatorio\Controller\AppController;

/**
 * MdlCertificateOmitidos Controller
 *
 * @property \WebService\Model\Table\MdlCertificateOmitidosTable $MdlCertificateOmitidos */
class MdlCertificateOmitidosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $conditions = 'WHERE ';
        if(isset($this->request->query['nome']) && !empty($this->request->query['nome'])){
            $conditions .= "tp.nome LIKE '%".$this->request->query['nome']."%'";
        }
        if(isset($this->request->query['inicio']) && !empty($this->request->query['inicio'])){
            if($conditions != 'WHERE ') $conditions .= " AND ";
            $conditions .= "cti.timecreated >= UNIX_TIMESTAMP('".$this->formatarData($this->request->query['inicio'])." 00:00:00')";
        }
        if(isset($this->request->query['fim']) && !empty($this->request->query['fim'])){
            if($conditions != 'WHERE ') $conditions .= " AND ";
            $conditions .= "cti.timecreated <= UNIX_TIMESTAMP('".$this->formatarData($this->request->query['fim'])." 23:59:59')";
        }
        if(isset($this->request->query['ecm_tipo_produto']) && !empty($this->request->query['ecm_tipo_produto']['_ids'])){
            if($conditions != 'WHERE ') $conditions .= " AND ";
            $conditions .= "tp.id IN(".implode(",",$this->request->query['ecm_tipo_produto']['_ids']).")";
        }

        if($conditions == 'WHERE '){
            $data = new \DateTime('first day of this month');
            $data->modify('-2 months');
            $conditions .= "cti.timecreated >= UNIX_TIMESTAMP('".$data->format('Y-m-d')." 00:00:00')";
            $conditions .= " AND tp.id IN(23,24)";
            $this->request->query['inicio'] = $data->format('d/m/Y');
            $this->request->query['ecm_tipo_produto'] = array();
            $this->request->query['ecm_tipo_produto']['_ids'] = array(23,24);
        }

        $order = "";
        if(!empty($this->request->query['direction'])){
            $order = "ORDER BY certificados.nome " . $this->request->query['direction'];
        }

        $sql = "SELECT certificados.nome, CONCAT(MONTH(FROM_UNIXTIME(certificados.timecreated)),'/', YEAR(FROM_UNIXTIME(certificados.timecreated))) AS `data`, COUNT(certificados.id) AS total
                FROM
                (
                    SELECT tp.nome, tp.id AS id_tipo, cti.timecreated, cti.id AS id_certificado, ue.id
                    FROM ecm_produto_ecm_tipo_produto ptp
                    INNER JOIN ecm_produto p ON p.id = ptp.ecm_produto_id AND p.refcurso = 'true'
                    INNER JOIN ecm_produto_mdl_course pc ON pc.ecm_produto_id = p.id
                    INNER JOIN ecm_tipo_produto tp ON tp.id = ptp.ecm_tipo_produto_id
                    INNER JOIN mdl_course c ON c.id = pc.mdl_course_id
                    INNER JOIN mdl_certificate ct ON ct.course = c.id
                    INNER JOIN mdl_certificate_issues cti ON cti.certificateid = ct.id
                    INNER JOIN mdl_user u ON u.id = cti.userid
                    INNER JOIN mdl_enrol e ON e.courseid = c.id
                    INNER JOIN mdl_user_enrolments ue ON ue.enrolid = e.id AND ue.userid = u.id
                    $conditions
                    GROUP BY ue.id
                ) AS certificados
                GROUP BY certificados.id_tipo, MONTH(FROM_UNIXTIME(certificados.timecreated)) " . $order;

        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute($sql);
        $mdlCertificates = $stmt->fetchAll('assoc');

        $this->loadModel('Produto.EcmTipoProduto');
        $optionsTipoProduto = $this->EcmTipoProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'nome'])
            ->where(['habilitado' => 'true', 'id !=' => '10']);

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('mdlCertificates', 'optionsTipoProduto'));
        $this->set('_serialize', ['mdlCertificates']);
    }

    private function formatarData($data){
        $data = explode("/", $data);
        return $data[2] . "-" . $data[1] . "-" . $data[0];
    }
}
