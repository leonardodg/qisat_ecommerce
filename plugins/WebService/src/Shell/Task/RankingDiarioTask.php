<?php
namespace WebService\Shell\Task;

use Cake\Console\Shell;

/**
 * ConsultarTransacao shell task.
 */
class RankingDiarioTask extends Shell
{
    public $plugin = 'WebService';
    public $today;
    public $rankingDiario;

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        $this->configuracao();
        $this->ranking();
    }

    private function configuracao(){
        $this->loadModel('MdlUser');
        $this->loadModel('Produto.MdlFase');
        $this->loadModel('WebService.MdlCourse');
        $this->loadModel('WebService.MdlCourseModules');

        $this->today = new \DateTime();
        $this->today->setTime(0, 0, 0);
        $this->today = $this->today->getTimestamp();

        $this->rankingDiario = [];
    }

    private function ranking(){
        $mdlUser = $this->MdlUser->find('list', [
            'valueField' => 'id'
        ])
        ->innerJoinWith('MdlGroupsMembers.MdlGroups', function ($q) {
            return $q->where(['mdl_fase_id IS NULL']);
        })
        ->toArray();

        foreach($mdlUser as $value){
            $this->rankingUser($value);
        }

        foreach($this->rankingDiario as $value){
            usort($value,
                function( $a, $b ) {
                    if( $a['ranking'] == $b['ranking'] ) return 0;
                    return ( ( $a['ranking'] > $b['ranking'] ) ? -1 : 1 );
                }
            );

            if(count($value) > 6)
                $value = array_slice($value, 0, 6);

            $mdlFaseRankings = $this->MdlFase->MdlFaseRanking->newEntities($value);
            foreach($mdlFaseRankings as $mdlFaseRanking)
                $this->MdlFase->MdlFaseRanking->save($mdlFaseRanking);

        }
    }

    private function rankingUser($id){
        $retorno = $this->MdlUser->getFase($id);

        if($retorno['sucesso']){
            $id = $retorno['userid'];
            $mdlFase = $retorno['mdlFase'];

            $mdlCourses = $this->MdlCourse->find('list', [
                'keyField' => 'id',
                'valueField' => 'coursehours'
            ])
            ->innerJoinWith('EcmProduto', function ($q) use ($mdlFase) {
                return $q->where(['EcmProduto.id' => $mdlFase->ecm_produto_id]);
            })
            ->toArray();

            $mdlUserEnrolments = $this->MdlUser->MdlUserEnrolments->find()
                ->innerJoinWith('MdlEnrol', function ($q) use ($mdlCourses) {
                    return $q->where(['courseid IN' => array_keys($mdlCourses)]);
                })
                ->where(['userid' => $id])->first();

            $firstDay = new \DateTime('first day of this month');
            $firstDay->setTime(0, 0, 0);

            if (date('m', $mdlUserEnrolments->timestart) == $firstDay->format('m') &&
                date('Y', $mdlUserEnrolments->timestart) == $firstDay->format('Y')) {
                $firstDay->setTimestamp($mdlUserEnrolments->timestart);
            }

            $cargaHoraria = 0;
            $cargaHorariaTotal = 0;

            foreach($mdlCourses as $key => $value){
                $cargaHorariaTotal += $value;

                $mdlCourseModules = $this->MdlCourseModules->find('list', [
                    'valueField' => 'id'
                ])
                ->innerJoinWith('MdlModules', function ($q) {
                    return $q->where(['name' => 'url']);
                })
                ->where(['course' => $key, 'MdlCourseModules.visible' => 1])->toList();

                if(!empty($mdlCourseModules)){
                    $mdlCourseModulesCompletion = $this->MdlCourseModules->MdlCourseModulesCompletion->find()
                        ->where([
                            'coursemoduleid IN' => $mdlCourseModules,
                            'userid' => $id,
                            'timemodified >=' => $firstDay->getTimestamp()
                        ])->count();

                    $cargaHoraria += ($value / count($mdlCourseModules)) * $mdlCourseModulesCompletion;
                }
            }

            $cargaHorariaMensal = ($cargaHorariaTotal / $mdlFase->enrol_period_finish) * 30;

            $concluidoTrilha = ($cargaHoraria / $cargaHorariaMensal) * 100;

            if(!array_key_exists($mdlFase->id, $this->rankingDiario))
                $this->rankingDiario[$mdlFase->id] = [];

            array_push($this->rankingDiario[$mdlFase->id], [
                'mdl_fase_id' => $mdlFase->id,
                'mdl_user_id' => $id,
                'ranking'     => $concluidoTrilha,
                'timecreated' => $this->today
            ]);
        }
    }
}
