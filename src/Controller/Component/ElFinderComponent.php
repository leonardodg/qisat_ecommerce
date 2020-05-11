<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Routing\Router;

require_once ROOT . DS . 'Vendor' . DS . 'elfinder' . DS . 'elFinder.class.php';
require_once ROOT . DS . 'Vendor' . DS . 'elfinder' . DS . 'elFinderConnector.class.php';

class ElFinderComponent extends Component
{

    private $opts;
    private $roots_path = WWW_ROOT . 'upload/';
    private $roots_url;

    public function __construct(ComponentRegistry $registry, array $config)
    {
        parent::__construct($registry, $config);
        
        $this->roots_url = Router::url('/upload', true);
        
        $this->opts = $opts = array(
            'roots' => array(
                array(
                    'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
                    'path'          => $this->roots_path, // path to files (REQUIRED)
                    'URL'           => $this->roots_url, // URL to files (REQUIRED)
                    'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
                    'uploadAllow'   => array('image', 'text/plain', 'text/html', 'application/pdf'),// Mimetype `image` and `text/plain` allowed to upload
                    'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
                    'accessControl' => 'access',                     // disable and hide dot starting files (OPTIONAL)
                    'attributes' => array(
                        array( // hide readmes
                            'pattern' => '/.tmb/',
                            'read' => false,
                            'write' => false,
                            'hidden' => true,
                            'locked' => false
                        ),
                        array( // hide readmes
                            'pattern' => '/.quarantine/',
                            'read' => false,
                            'write' => false,
                            'hidden' => true,
                            'locked' => false
                        ),
                    ),
                )
            )
        );
    }

    public function connector($path = null, $inicialize = false)
    {
        if(isset($path)){
            if(!is_null($this->request->data('cmd')) || !is_null($this->request->query('cmd')) && !$inicialize) {
                $this->autoRender = false;
                require ROOT . DS . 'Vendor' . DS . 'elfinder' . DS . 'autoload.php';
                function access($attr, $path, $data, $volume) {
                    return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
                        ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
                        :  null;                                    // else elFinder decide it itself
                }

                if(!is_null($path)){
                    $this->opts['roots'][0]['path'] =  $this->roots_path . $path;
                    $this->opts['roots'][0]['URL'] =  $this->roots_url . '/' . $path;
                }

                $connector = new \elFinderConnector(new \elFinder($this->opts));
                $connector->run();
            } else {
                if(!file_exists($this->roots_path . $path))
                    mkdir($this->roots_path . $path);
            }
        }
    }

    public function setUrl(Array $mimetype){
        $this->roots_path = $mimetype[0];
        $this->roots_url = $mimetype[1];
    }

    public function setUploadAllow(Array $mimetype){
        $this->opts['roots'][0]['uploadAllow'] = $mimetype;
    }

    public function addAttributes(Array $attribute){
        $this->opts['roots'][0]['attributes'][] = $attribute;
    }
}