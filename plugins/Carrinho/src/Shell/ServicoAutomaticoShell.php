<?php
namespace Carrinho\Shell;

use Cake\Console\Shell;

/**
 * ServicoAutomatico shell command.
 */
class ServicoAutomaticoShell extends Shell
{
    public $tasks = ['Carrinho.CancelarCarrinho'];

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main() 
    {
        //$this->out($this->OptionParser->help());

        $this->out('');
        $this->out('Servico Automatico em Execucao');
        $this->out('---------------------------------------------------------------');
        $this->out('');
        $this->CancelarCarrinho->main();
        $this->out('');
        $this->out('---------------------------------------------------------------');
        $this->out('Fim da Execucao do Servico Automatico');
    }
}
