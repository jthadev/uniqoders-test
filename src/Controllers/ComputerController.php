<?php

namespace Uniqoders\Game\Controllers;

class ComputerController
{

    protected $computer = [
        'name' => 'Computer',
        'stats' => [
            'draw' => 0,
            'victory' => 0,
            'defeat' => 0,
        ]
    ];

    public function getComputer ()
    {
        return $this->computer;
    }

    public function getStats ()
    {
        return $this->computer['stats'];
    }

    public function setStats (int $draw, int $victory, int $defeat)
    {
        $this->computer['stats']['draw'] += $draw;
        $this->computer['stats']['victory'] += $victory;
        $this->computer['stats']['defeat'] += $defeat;

        return $this->computer['stats'];
    }
}
