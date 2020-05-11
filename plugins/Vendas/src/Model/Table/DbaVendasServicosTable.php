<?php
namespace Vendas\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * DbaVendasServicosTable Model
 *
 * @property \Cake\ORM\Association\HasMany $DbaVendasServicos */
class DbaVendasServicosTable extends Table
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
        $this->table('dba_vendas_servicos');
        $this->primaryKey('registro');

        $this->belongsTo('DbaVendas', [
            'foreignKey' => 'dba_vendas_pedido',
            'joinType' => 'INNER',
            'className' => 'Vendas.DbaVendas'
        ]);

        $this->hasOne('EcmProduto', [
            'foreignKey' => 'idtop',
            'bindingKey' => 'servico_top_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmProduto',
            'conditions' => ["NOT EcmProduto.sigla LIKE 'PC%'"]
        ]);

    }
}
