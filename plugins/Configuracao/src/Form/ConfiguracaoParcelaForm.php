<?php

namespace Configuracao\Form;

use Cake\Form\Form;

class ConfiguracaoParcelaForm extends Form
{

    protected function _buildValidator(Validator $validator)
    {
        return $validator->add('name', 'length', [
            'rule' => ['minLength', 10],
            'message' => 'A name is required'
        ])->add('email', 'format', [
            'rule' => 'email',
            'message' => 'A valid email address is required',
        ]);
    }


}