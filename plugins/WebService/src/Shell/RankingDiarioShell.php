<?php
namespace WebService\Shell;

use Cake\Console\Shell;

/**
 * RankingDiario shell command.
 */
class RankingDiarioShell extends Shell
{
    public $tasks = ['WebService.RankingDiario'];

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
        $this->out('Ranking Diario em Execucao');
        $this->out('---------------------------------------------------------------');
        $this->out('');
        $this->RankingDiario->main();
        //$this->out('');
        //$this->out('---------------------------------------------------------------');
        $this->out('Fim da Execucao do Ranking Diario');
    }
}
