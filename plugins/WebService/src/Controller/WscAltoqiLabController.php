<?php
/**
 * Created by PhpStorm.
 * User: inty.castillo
 * Date: 06/10/2017
 * Time: 10:00
 */

namespace WebService\Controller;

use App\Controller\WscController;
use Cake\Event\Event;

class WscAltoqiLabController extends WscController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadModel('MdlUser');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    /*
    * Função responsável por exibir o tempo decorrido e o tempo total da inscrição do aluno na trilha
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
    * http://{host}/web-service/wsc-altoqi-lab/tempo-conclusao/
    *
    * Retornos:
    * 1- {'sucesso':true, dias_percorridos:dias percorridos inscrito, total_dias:total de dias inscrito}
    * 2- {'sucesso':false, 'mensagem': 'Usuário não encontrado'}
    * 3- {'sucesso':false, 'mensagem': 'Não foi encontrada nenhuma inscrição do aluno em trilhas'}
    *
    * */
    public function tempoConclusao(){
        $retorno = $this->MdlUser->getFase($this->Auth->user()['id'], $this->request->data('id'));

        if($retorno['sucesso']){
            $id = $retorno['userid'];

            $this->loadModel('Produto.EcmProduto');
            $ecmProdutos = [];
            foreach ($retorno['mdlFase'] as $mdlFase) {
                $ecmProdutos[] = $mdlFase->ecm_produto_id;
            }
            $mdlCourse = $this->EcmProduto->EcmProdutoMdlCourse->find('list', [
                'valueField' => 'mdl_course_id'
            ])->where(['ecm_produto_id IN' => $ecmProdutos])->toList();

            $mdlUserEnrolments = $this->MdlUser->MdlUserEnrolments->find()
                ->select(['timestart' => 'MIN(timestart)', 'timeend' => 'MAX(timeend)'])
                ->innerJoinWith('MdlEnrol', function ($q) use ($mdlCourse) {
                    return $q->where(['courseid IN' => $mdlCourse]);
                })
                ->where(['userid' => $id])
                ->group('userid')->first();

            $timestart = new \DateTime();
            $timestart->setTimestamp($mdlUserEnrolments->timestart);
            $atual = new \DateTime();
            $timeend = new \DateTime();
            $timeend->setTimestamp($mdlUserEnrolments->timeend);

            $diff = $atual->diff($timestart);
            $dias_percorridos = intval($diff->format('%a'));

            $diff = $timeend->diff($timestart);
            $total_dias = intval($diff->format('%a'));

            $retorno = ['sucesso' => true, 'dias_percorridos' => $dias_percorridos,
                'total_dias' => $total_dias];
        }

        $this->set(compact('retorno'));
    }

    /*
    * Função responsável por exibir a quantidade percentual de conclusão da trilha
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
    * http://{host}/web-service/wsc-altoqi-lab/concluido-trilha/
    *
    * Retornos:
    * 1- {'sucesso':true, concluidoTrilha:percentual de concluido da trilha}
    * 2- {'sucesso':false, 'mensagem': 'Usuário não encontrado'}
    * 3- {'sucesso':false, 'mensagem': 'Não foi encontrada nenhuma inscrição do aluno em trilhas'}
    *
    * */
    public function concluidoTrilha(){
        $retorno = $this->MdlUser->getFase($this->Auth->user()['id'], $this->request->data('id'));

        if($retorno['sucesso']){
            $id = $retorno['userid'];

            $this->loadModel('WebService.MdlCourse');
            $this->loadModel('WebService.MdlCourseModules');
            $ecmProdutos = [];
            foreach ($retorno['mdlFase'] as $mdlFase) {
                $ecmProdutos[] = $mdlFase->ecm_produto_id;
            }
            $mdlCourses = $this->MdlCourse->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'coursehours'
                ])
                ->innerJoinWith('EcmProduto', function ($q) use ($ecmProdutos) {
                    return $q->where(['EcmProduto.id IN' => $ecmProdutos]);
                })
                ->toArray();


            $cargaHoraria = 0;
            $cargaHorariaTotal = 0;
            foreach ($mdlCourses as $key => $value) {
                $cargaHorariaTotal += $value;

                $mdlCourseModules = $this->MdlCourseModules->find('list', [
                    'valueField' => 'id'
                ])
                    ->innerJoinWith('MdlModules', function ($q) {
                        return $q->where(['name' => 'url']);
                    })
                    ->where(['course' => $key, 'MdlCourseModules.visible' => 1])->toList();

                if (!empty($mdlCourseModules)) {
                    $mdlCourseModulesCompletion = $this->MdlCourseModules->MdlCourseModulesCompletion->find()
                        ->where(['coursemoduleid IN' => $mdlCourseModules, 'userid' => $id])->count();

                    $cargaHoraria += ($value / count($mdlCourseModules)) * $mdlCourseModulesCompletion;
                }
            }
            $concluidoTrilha = round(($cargaHoraria / $cargaHorariaTotal) * 100);

            $retorno = ['sucesso' => true, 'concluidoTrilha' => $concluidoTrilha];
        }
        $this->set(compact('retorno'));
    }

    /*
    * Função responsável por exibir a quantidade de cursos concluidos e o total de cursos da trilha
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
    * http://{host}/web-service/wsc-altoqi-lab/cursos-feitos/
    *
    * Retornos:
    * 1- {'sucesso':true, cursos_concluidos:cursos concluidos da trilha, total_cursos:total de cursos da trilha}
    * 2- {'sucesso':false, 'mensagem': 'Usuário não encontrado'}
    * 3- {'sucesso':false, 'mensagem': 'Não foi encontrada nenhuma inscrição do aluno em trilhas'}
    *
    * */
    public function cursosFeitos(){
        $retorno = $this->MdlUser->getFase($this->Auth->user()['id'], $this->request->data('id'));

        if($retorno['sucesso']){
            $id = $retorno['userid'];

            $this->loadModel('Produto.EcmProduto');
            $ecmProdutos = [];
            foreach ($retorno['mdlFase'] as $mdlFase) {
                $ecmProdutos[] = $mdlFase->ecm_produto_id;
            }

            $mdlCourse = $this->EcmProduto->EcmProdutoMdlCourse->find('list', [
                    'valueField' => 'mdl_course_id'
                ])->where(['ecm_produto_id IN' => $ecmProdutos])->toList();


            $this->loadModel('WebService.MdlCertificate');
            $mdlCertificate = $this->MdlCertificate->find()
                ->innerJoinWith('MdlCertificateIssues', function ($q) use ($id) {
                    return $q->where(['userid' => $id]);
                })
                ->where(['course IN' => $mdlCourse])->toArray();

            $retorno = ['sucesso' => true, 'cursos_concluidos' => count($mdlCertificate),
                'total_cursos' => count($mdlCourse)];
        }

        $this->set(compact('retorno'));
    }

    /*
    * Função responsável por exibir a quantidade percentual de conclusão da trilha
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
    * http://{host}/web-service/wsc-altoqi-lab/concluido-trilha/
    *
    * Retornos:
    * 1- {'sucesso':true, ranking:percentual mensal de conclusão da fase da trilha}
    * 2- {'sucesso':false, 'mensagem': 'Favor, informe o tipo da trilha'}
    * 2- {'sucesso':false, 'mensagem': 'Usuário não encontrado'}
    * 3- {'sucesso':false, 'mensagem': 'Não foi encontrada nenhuma inscrição do aluno em trilhas'}
    *
    * */
    public function ranking()
    {
        $tipo = $this->request->data('id');
        $retorno = ['sucesso' => false, 'mensagem' => __('Favor, informe o tipo da trilha')];
        if(!is_null($tipo)){
            $retorno = $this->MdlUser->getFase($this->Auth->user()['id'], $tipo);
            if ($retorno['sucesso']) {
                $id = $retorno['userid'];

                $this->loadModel('WebService.MdlCourse');
                $ecmProdutos = [];
                foreach ($retorno['mdlFase'] as $mdlFase) {
                    $ecmProdutos[] = $mdlFase->ecm_produto_id;
                }

                $mdlCourses = $this->MdlCourse->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'coursehours'
                ])
                    ->innerJoinWith('EcmProduto', function ($q) use ($ecmProdutos) {
                        return $q->where(['EcmProduto.id IN' => $ecmProdutos]);
                    })
                    ->toArray();

                $this->loadModel('WebService.MdlCourseModules');
                $cargaHoraria = 0;
                $cargaHorariaTotal = 0;

                $firstDay = new \DateTime('first day of this month');
                $firstDay->setTime(0, 0, 0);

                $mdlUserEnrolments = $this->MdlUser->MdlUserEnrolments->find()
                    ->select(['timestart' => 'MIN(timestart)', 'timeend' => 'MAX(timeend)'])
                    ->innerJoinWith('MdlEnrol', function ($q) use ($mdlCourses) {
                        return $q->where(['courseid IN' => array_keys($mdlCourses)]);
                    })
                    ->where(['userid' => $id])
                    ->group('userid')->first();

                if (date('m', $mdlUserEnrolments->timestart) == $firstDay->format('m') &&
                    date('Y', $mdlUserEnrolments->timestart) == $firstDay->format('Y')
                ) {
                    $firstDay->setTimestamp($mdlUserEnrolments->timestart);
                }

                foreach ($mdlCourses as $key => $value) {
                    $cargaHorariaTotal += $value;

                    $mdlCourseModules = $this->MdlCourseModules->find('list', [
                        'valueField' => 'id'
                    ])
                        ->innerJoinWith('MdlModules', function ($q) {
                            return $q->where(['name' => 'url']);
                        })
                        ->where(['course' => $key, 'MdlCourseModules.visible' => 1])->toList();

                    if (!empty($mdlCourseModules)) {
                        $mdlCourseModulesCompletion = $this->MdlCourseModules->MdlCourseModulesCompletion->find()
                            ->where([
                                'coursemoduleid IN' => $mdlCourseModules,
                                'userid' => $id,
                                'timemodified >=' => $firstDay->getTimestamp()
                            ])->count();
                        $cargaHoraria += ($value / count($mdlCourseModules)) * $mdlCourseModulesCompletion;
                    }
                }

                $timestart = new \DateTime();
                $timestart->setTimestamp($mdlUserEnrolments->timestart);
                $timeend = new \DateTime();
                $timeend->setTimestamp($mdlUserEnrolments->timeend);
                $diff = $timeend->diff($timestart);
                $enrol_period_finish = intval($diff->format('%a'));

                $cargaHorariaMensal = ($cargaHorariaTotal / $enrol_period_finish) * 30;

                $concluidoTrilha = ($cargaHoraria / $cargaHorariaMensal) * 100;

                $this->loadModel('Produto.MdlFase');
                $mdlFaseRankings = $this->MdlFase->MdlFaseRanking->find('all')
                    ->select(['ranking'])
                    ->contain(['MdlUser' => function ($q) {
                        return $q->select(['firstname', 'lastname']);
                    }])
                    ->matching('MdlFase.EcmProduto.EcmTipoProduto', function ($q) use ($tipo) {
                        return $q->where([
                            'EcmTipoProduto.id' => $tipo
                        ]);
                    })
                    ->where(['mdl_user_id !=' => $id])
                    ->group('mdl_user_id')
                    ->orderDesc('ranking')
                    ->limit(5)->toArray();

                $mdlUser = $this->MdlUser->get($id, ['fields' => ['firstname', 'lastname']]);
                $mdlFaseRanking = $this->MdlFase->MdlFaseRanking->newEntity([
                    'ranking' => $concluidoTrilha
                ]);
                $mdlFaseRanking->mdl_user = $mdlUser;

                array_push($mdlFaseRankings, $mdlFaseRanking);
                usort($mdlFaseRankings,
                    function ($a, $b) {
                        if ($a->ranking == $b->ranking) return 0;
                        return (($a->ranking > $b->ranking) ? -1 : 1);
                    }
                );

                $retorno = ['sucesso' => true, 'ranking' => $mdlFaseRankings];
            }
        }
        $this->set(compact('retorno'));
    }

    /*
        * Função responsável por exibir a quantidade percentual de conclusão da trilha
        * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
        * http://{host}/web-service/wsc-altoqi-lab/concluido-trilha/
        *
        * Retornos:
        * 1- {'sucesso':true, 'fase':nome da fase, 'andamento':percentual de conclusão dos cursos da fase da trilha}
        * 2- {'sucesso':false, 'mensagem': 'Usuário não encontrado'}
        * 3- {'sucesso':false, 'mensagem': 'Não foi encontrada nenhuma inscrição do aluno em trilhas'}
        *
        * */
    public function andamentoCursos()
    {
        $retorno = $this->MdlUser->getFase($this->Auth->user()['id'], $this->request->data('id'));

        if ($retorno['sucesso']) {
            $id = $retorno['userid'];
            foreach($retorno['mdlFase'] as $mdlFase) {
                $this->loadModel('WebService.MdlCourseModules');
                $mdlCourseModules = $this->MdlCourseModules->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'count'
                ])
                    ->innerJoinWith('MdlModules', function ($q) {
                        return $q->where(['OR' => [['name' => 'url'], ['name' => 'quiz']]]);
                    })
                    ->innerJoinWith('MdlCourse', function ($q) use ($mdlFase) {
                        return $q->innerJoinWith('EcmProduto', function ($q) use ($mdlFase) {
                            return $q->where(['EcmProduto.id' => $mdlFase->ecm_produto_id]);
                        });
                    })
                    ->group('course');
                $mdlCourseModules->select([
                    'id' => 'MdlCourse.id',
                    'count' => $mdlCourseModules->func()->count('course')
                ]);
                $mdlCourseModules = $mdlCourseModules->toArray();

                $this->loadModel('Produto.MdlFase');
                $mdlCourseMdlFases = $this->MdlFase->MdlCourseMdlFase->find()
                    ->select(['mdl_course_id', 'mdl_course_conclusion_id'])
                    ->where(['mdl_fase_id' => $mdlFase->id])->toArray();

                $this->loadModel('WebService.MdlCertificate');
                $mdlCertificate = $this->MdlCertificate->find('list', [
                    'keyField' => 'course',
                    'valueField' => 'timecreated'
                ])
                    ->select(['course', 'timecreated' => 'MdlCertificateIssues.timecreated'])
                    ->innerJoinWith('MdlCertificateIssues', function ($q) use ($id) {
                        return $q->where(['userid' => $id]);
                    })
                    ->innerJoinWith('MdlCourse', function ($q) use ($mdlFase) {
                        return $q->innerJoinWith('EcmProduto', function ($q) use ($mdlFase) {
                            return $q->where(['EcmProduto.id' => $mdlFase->ecm_produto_id]);
                        });
                    })
                    ->toArray();

                $mdlCourseNot = [];
                foreach($mdlCourseMdlFases as $mdlCourseMdlFase){
                    if(!array_key_exists($mdlCourseMdlFase->mdl_course_conclusion_id, $mdlCertificate))
                        $mdlCourseNot[] = $mdlCourseMdlFase->mdl_course_id;
                }

                $mdlCourseModulesCompletion = $this->MdlCourseModules->find('list', [
                    'keyField' => 'course',
                    'valueField' => 'count'
                ])
                    ->innerJoinWith('MdlCourseModulesCompletion', function ($q) use ($id) {
                        return $q->where(['userid' => $id]);
                    })
                    ->innerJoinWith('MdlCourse', function ($q) use ($mdlFase, $mdlCourseNot) {
                        if(!empty($mdlCourseNot))
                            $q->where(['MdlCourse.id NOT IN' => $mdlCourseNot]);
                        return $q->innerJoinWith('EcmProduto', function ($q) use ($mdlFase) {
                            return $q->where(['EcmProduto.id' => $mdlFase->ecm_produto_id]);
                        });
                    })
                    ->group('course');
                $mdlCourseModulesCompletion->select(['course', 'count' => $mdlCourseModulesCompletion->func()->count('course')]);
                $mdlCourseModulesCompletion = $mdlCourseModulesCompletion->toArray();

                $this->loadModel('Produto.EcmProduto');
                $this->loadModel('MdlUser');
                $retorno = ['sucesso' => true, 'trilha' => []];
                if ($this->MdlUser->exists(['MdlUser.id' => $id])) {
                    $mdlUser = $this->MdlUser->get($id, [
                        'fields' => ['id'],
                        'contain' => [
                            'MdlUserEnrolments' => function ($q) use ($mdlCourseModules) {
                                return $q->contain(['MdlEnrol' => function ($q) {
                                    return $q->contain(['MdlCourse' => function ($q) {
                                        return $q->contain(['EcmProduto' => function ($q) {
                                            return $q->contain(['EcmInstrutor' => function ($q) {
                                                return $q->contain(['MdlUser' =>
                                                    ['fields' => ['nome' => 'CONCAT(firstname," ",lastname)']]
                                                ])->select(['userid' => 'mdl_user_id']);
                                            }, 'EcmImagem', 'EcmTipoProduto' => function ($q) {
                                                return $q->select(['EcmTipoProduto.id', 'EcmTipoProduto.nome']);
                                            }
                                            ])->select(['id', 'nome', 'refcurso'])->where(['refcurso' => 'true']);
                                        }])->select(['id', 'curso' => 'fullname', 'category'])

                                            ;
                                    }])->select(['roleid', 'enrolperiod', 'courseid']);
                                }])->select(['timestart', 'timeend', 'userid', 'id', 'status'])
                                    ->where(['MdlEnrol.courseid IN' => array_keys($mdlCourseModules)]);
                            },
                            'MdlUserEcmAlternativeHost' => [
                                'EcmAlternativeHost' => ['fields' => ['id', 'MdlUserEcmAlternativeHost.mdl_user_id']]
                            ]
                        ]
                    ]);

                    $ecmProdutoMdlCourse = $this->EcmProduto->EcmProdutoMdlCourse->find('list', [
                        'keyField' => 'mdl_course_id',
                        'valueField' => 'ordem'
                    ])->where(['ecm_produto_id' => $mdlFase->ecm_produto_id])->toArray();

                    $this->loadModel('Configuracao.EcmConfig');
                    $this->loadModel('WebService.MdlCertificate');
                    $moodle = $this->EcmConfig->find()->where(['nome' => 'dominio_acesso_moodle'])->first()->valor;
                    $matriculas = [];
                    foreach ($mdlUser['mdl_user_enrolments'] as $user_enrolments) {
                        $statusCurso = $this->MdlUser->verificaStatusCurso($user_enrolments, $id);
                        $user_enrolments['roleid'] = $statusCurso['roleid'];
                        $user_enrolments['status'] = empty($user_enrolments['status']) ? $statusCurso['status'] : "Curso Bloqueado";
                        unset($user_enrolments['userid']);
                        $user_enrolments['cursoid'] = $user_enrolments['mdl_enrol']['mdl_course']['id'];
                        $user_enrolments['category'] = $user_enrolments['mdl_enrol']['mdl_course']['category'];
                        $user_enrolments['alternativehostid'] = $mdlUser['ecm_alternative_host'];

                        if (is_array($user_enrolments['mdl_enrol']['mdl_course']['ecm_produto'])) {
                            $ecm_produto = array_shift($user_enrolments['mdl_enrol']['mdl_course']['ecm_produto']);
                            $user_enrolments['produto'] = $ecm_produto;
                            if (!empty($ecm_produto['ecm_instrutor']))
                                $user_enrolments['instrutores'] = $ecm_produto['ecm_instrutor'];
                            if (!empty($ecm_produto['ecm_tipo_produto'])) {
                                $user_enrolments['produto']['categorias'] = $ecm_produto['ecm_tipo_produto'];
                                unset($user_enrolments['produto']['ecm_tipo_produto']);
                            }
                            if (!empty($ecm_produto['ecm_imagem'])) {
                                $user_enrolments['imagem'] = $ecm_produto['ecm_imagem'];
                                foreach ($user_enrolments['imagem'] as $imagem) {
                                    if ($imagem['descricao'] == 'Imagens - Capa') {
                                        $user_enrolments['imagem'] = \Cake\Routing\Router::url('/upload/' . $imagem['src'], true);
                                        break;
                                    }
                                }
                                if (is_array($user_enrolments['imagem']))
                                    $user_enrolments['imagem'] = null;
                            }
                        }
                        /**
                         * view : links.view+"?id="+matricula.courseid+"&instance="+matricula.instance,
                         * forum : links.forum+"?id="+matricula.courseid+"&instance="+matricula.instance,
                         * biblioteca : links.biblioteca+"?id="+matricula.biblioteca+"&instance="+matricula.instance,
                         * contrato : links.contrato+"?id="+_id+"&course="+matricula.courseid+"&instance="+matricula.instance,
                         * tira_duvidas : links.tira_duvidas+"?cid="+matricula.courseid+"&instance="+matricula.instance+"&bid="+matricula.tira_duvidas+"8&rcp=0"
                         */
                        $user_enrolments['view'] = '#';
                        $user_enrolments['andamento'] = 0;
                        if ($user_enrolments['status'] == "Liberado para Acesso") {
                            if (in_array($user_enrolments['cursoid'], $mdlCourseNot)) {
                                $user_enrolments['status'] = "Curso com restrição de conclusão";
                            } else {
                                $user_enrolments['view'] = 'http://' . $moodle . '/course/view.php?id=' . $user_enrolments['cursoid'];
                                if (array_key_exists($user_enrolments['cursoid'], $mdlCourseModulesCompletion)) {
                                    $count = $mdlCourseModulesCompletion[$user_enrolments['cursoid']];
                                    $total = $mdlCourseModules[$user_enrolments['cursoid']];
                                    $user_enrolments['andamento'] = ($count / $total) * 100;
                                }
                            }
                        }

                        $this->loadModel('WebService.MdlCourseModules');
                        $mdlForum = $this->MdlCourseModules->find()
                            ->contain(['MdlModules' => function ($q) {
                                return $q->orWhere(['MdlModules.name' => 'forum']);
                            }])->contain(['MdlForum' => function ($q) use ($user_enrolments) {
                                return $q->where(['MdlForum.course' => $user_enrolments['cursoid']]);
                            }])->first();
                        if (!is_null($mdlForum))
                            $user_enrolments['forum'] = 'http://' . $moodle . '/mod/forum/view.php?id=' . $mdlForum->id;

                        $mdlFolder = $this->MdlCourseModules->find()
                            ->contain(['MdlModules' => function ($q) {
                                return $q->orWhere(['MdlModules.name' => 'folder']);
                            }])->contain(['MdlFolder' => function ($q) use ($user_enrolments) {
                                return $q->where(['MdlFolder.course' => $user_enrolments['cursoid'], 'MdlFolder.name' => 'Biblioteca']);
                            }])->first();
                        if (!is_null($mdlFolder))
                            $user_enrolments['biblioteca'] = 'http://' . $moodle . '/mod/folder/view.php?id=' . $mdlFolder->id;

                        $user_enrolments['tira_duvidas'] = 'http://' . $moodle . '/blocks/tira_duvidas/historico/historico.php?cid=' . $user_enrolments['cursoid'];

                        $user_enrolments['data_conclusao'] = null;
                        if (array_key_exists($user_enrolments['cursoid'], $mdlCertificate))
                            $user_enrolments['data_conclusao'] = $mdlCertificate[$user_enrolments['cursoid']];

                        $user_enrolments['dependencias'] = [];
                        foreach ($mdlCourseMdlFases as $mdlCourseMdlFase) {
                            if ($user_enrolments['cursoid'] == $mdlCourseMdlFase->mdl_course_id) {
                                $dependencia = [];
                                $dependencia['id'] = $mdlCourseMdlFase->mdl_course_conclusion_id;
                                $dependencia['completo'] = !in_array($user_enrolments['cursoid'], $mdlCourseNot);

                                $user_enrolments['dependencias'][] = $dependencia;
                            }
                        }

                        unset($user_enrolments['mdl_enrol']);
                        $matriculas[$ecmProdutoMdlCourse[$user_enrolments['cursoid']]] = $user_enrolments;
                    }

                    ksort($matriculas);
					$matriculas = array_values($matriculas);
                    $retorno['trilha'][] = ['fase' => $mdlFase->descricao, 'matriculas' => $matriculas];
                }
            }
        }
        $this->set(compact('retorno'));
    }

    public function dedicacaoAcumulado()
    {
        $tipo = $this->request->data('id');
        $retorno = ['sucesso' => false, 'mensagem' => __('Favor, informe o tipo da fase do AltoQiLab')];
        if(!is_null($tipo)){
            $retorno = $this->MdlUser->getFase($this->Auth->user()['id'], $tipo);
            if ($retorno['sucesso']) {
                $id = $retorno['userid'];

                $this->loadModel('WebService.MdlCourse');
                $ecmProdutos = [];
                foreach ($retorno['mdlFase'] as $mdlFase) {
                    $ecmProdutos[] = $mdlFase->ecm_produto_id;
                }

                $mdlCourses = $this->MdlCourse->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'coursehours'
                ])
                    ->innerJoinWith('EcmProduto', function ($q) use ($ecmProdutos) {
                        return $q->where(['EcmProduto.id IN' => $ecmProdutos]);
                    })
                    ->toArray();

                $mdlUserEnrolments = $this->MdlUser->MdlUserEnrolments->find()
                    ->select(['timestart' => 'MIN(timestart)', 'timeend' => 'MAX(timeend)'])
                    ->innerJoinWith('MdlEnrol', function ($q) use ($mdlCourses) {
                        return $q->where(['courseid IN' => array_keys($mdlCourses)]);
                    })
                    ->where(['userid' => $id])
                    ->group('userid')->first();

                $this->loadModel('WebService.MdlCourseModules');
                $cargaHorariaTotal = 0;
                $mdlCourseModulesTotal = 0;
                $realizado = 0;

                foreach ($mdlCourses as $key => $value) {
                    $mdlCourseModules = $this->MdlCourseModules->find('list', [
                        'valueField' => 'id'
                    ])
                        ->innerJoinWith('MdlModules', function ($q) {
                            return $q->where(['name' => 'url']);
                        })
                        ->where(['course' => $key, 'MdlCourseModules.visible' => 1])->toList();

                    $cargaHorariaTotal += $value;
                    $mdlCourseModulesTotal += count($mdlCourseModules);

                    if (!empty($mdlCourseModules)) {
                        $mdlCourseModulesCompletion = $this->MdlCourseModules->MdlCourseModulesCompletion->find()
                            ->where([
                                'coursemoduleid IN' => $mdlCourseModules,
                                'userid' => $id,
                                'timemodified >=' => $mdlUserEnrolments->timestart
                            ])->count();

                        $realizado += ($value / count($mdlCourseModules)) * $mdlCourseModulesCompletion;
                    }
                }

                $hoje = new \DateTime();
                $hoje->setTime(0, 0);

                $timestart = new \DateTime();
                $timestart->setTimestamp($mdlUserEnrolments->timestart);

                $diasPercorridos = intval($hoje->diff($timestart)->format('%a'));
                if($diasPercorridos < 1)
                    $diasPercorridos = 1;

                $realizado *= 100;
                $meta = ($cargaHorariaTotal / $mdlCourseModulesTotal) * $diasPercorridos * 100;

                $ida = ($realizado / $meta) * 100;
                if($realizado > $meta){
                    $superado = $realizado - $meta;
                    $realizado = $meta;
                }

                $retorno = [
                    'sucesso' => true,
                    'ida' => $ida,
                    'realizado' => $realizado,
                    'meta' => $meta
                ];

                if(isset($superado))
                    $retorno['superado'] = $superado;
            }
        }
        $this->set(compact('retorno'));
    }
}