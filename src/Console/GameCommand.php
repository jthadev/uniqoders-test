<?php

namespace Uniqoders\Game\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Uniqoders\Game\Controllers\GameController;

class GameCommand extends Command
{

    protected $game;

    public function __construct()
    {
        parent::__construct();
        $this->game = new GameController();
    }
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('game')
            ->setDescription('New game: you vs computer')
            ->addArgument('name', InputArgument::OPTIONAL, 'what is your name?', 'Player 1');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(PHP_EOL . 'Made with ♥ by Uniqoders.' . PHP_EOL . PHP_EOL);

        $ask = $this->getHelper('question');

        $this->game->setNewRules('Other', ['Spock', 'Lizard']);
        $this->game->startGame($ask, $input, $output, $input->getArgument('name'));

        return 0;
    }
}
