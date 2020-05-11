<?php

namespace App\View\Helper;

use Cake\View\Helper\FormHelper;

class FormOverrideHelper extends FormHelper
{
    public $helpers = ['Dialog','Url','Html'];
    protected function _confirm($message, $okCode, $cancelCode = '', $options = [])
    {
        $script = 'if(result){'.$okCode.'}else{'.$cancelCode.'}';
        $confirm = $this->Dialog->showConfirm($message,$script);
        $escape = isset($options['escape']) && $options['escape'] === false;
        if ($escape) {
            $confirm = h($confirm);
        }
        return $confirm;
    }
}