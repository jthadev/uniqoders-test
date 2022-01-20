<?php

namespace Uniqoders\Game\Controllers;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\ChoiceQuestion;

use Uniqoders\Game\Controllers\HumanController;
use Uniqoders\Game\Controllers\ComputerController;

class GameController
{
    // Initial numbers to rounds and maximum rounds
    public $round = 1;
    public $max_round = 5;

    // Weapons available
    public $weapons = [
        0 => 'Scissors',
        1 => 'Rock',
        2 => 'Paper',
        3 => 'Lizard',
        4 => 'Spock',
    ];

    public $rules = [
        0 => [2,3],
        1 => [0,3],
        2 => [1,4],
        3 => [2,4],
        4 => [0,1]
    ];

    protected $human;
    protected $computer;

    public function __construct()
    {
        $this->human = new HumanController();
        $this->computer = new ComputerController();
    }

    public function setMaxRound (int $maxRound)
    {
        $this->max_round > 1 ? $this->max_round = $maxRound : $this->max_round = 5;
    }

    // $weapon: nueva arma a agregar
    // $rules: array de armas a vencer
    public function setNewRules (string $weapon, array $rules)
    {
        array_push($this->weapons, $weapon);

        $newRules = [];

        foreach($rules as $key => $value) {
            $getKeyRule = array_search($value, $this->weapons);
            array_push($newRules, $getKeyRule);
        }

        array_push($this->rules, $newRules);
    }

    public function setPlayerName (string $name)
    {
        $this->human->setName($name);
    }

    private function players ()
    {
        return [
            'player' => $this->human->getPlayer(),
            'computer' => $this->computer->getComputer()
        ];
    }
    
    public function startGame($ask, $input, $output, string $playerName)
    {
        $this->setPlayerName($playerName);

        do {
            // User selection
            $question = new ChoiceQuestion('Please select your weapon', array_values($this->weapons), 1);
            $question->setErrorMessage('Weapon %s is invalid.');

            $user_weapon = $ask->ask($input, $output, $question);
            $output->writeln('You have just selected: ' . $user_weapon);
            $user_weapon = array_search($user_weapon, $this->weapons);

            // Computer selection
            $computer_weapon = array_rand($this->weapons);
            $output->writeln('Computer has just selected: ' . $this->weapons[$computer_weapon]);

            if ($this->rules[$user_weapon][0] === $computer_weapon || $this->rules[$user_weapon][1] === $computer_weapon) {
                // setStats(draw, victory, defeat);
                $this->human->setStats(0, 1, 0);
                $this->computer->setStats(0, 0, 1);

                $output->writeln($this->human->getName() . ' win!');
            } else if ($this->rules[$computer_weapon][0] === $user_weapon || $this->rules[$computer_weapon][1] === $user_weapon) {
                $this->human->setStats(0, 0, 1);
                $this->computer->setStats(0, 1, 0);

                $output->writeln('Computer win!');
            } else {
                $this->human->setStats(1, 0, 0);
                $this->computer->setStats(1, 0, 0);

                $output->writeln('Draw!');
            }

            $this->round++;
        } while ($this->round <= $this->max_round);

        if($this->round >= $this->max_round) {
            // When the game ends, we must show a summary table per player that shows us how many times they have won, tied, or lost
            $this->displayStats($this->players(), $output);
        }
    }

    public function displayStats($players, $output)
    {
        // Display stats
        $stats = $players;

        $stats = array_map(function ($player) {
            return [$player['name'], $player['stats']['victory'], $player['stats']['draw'], $player['stats']['defeat']];
        }, $stats);

        $table = new Table($output);
        $table
            ->setHeaders(['Player', 'Victory', 'Draw', 'Defeat'])
            ->setRows($stats);

        $table->render();
    }
}
