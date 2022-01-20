<?php

namespace Uniqoders\Game\Controllers;

class HumanController
{

    protected $human = [
        'name' => '',
        'stats' => [
            'draw' => 0,
            'victory' => 0,
            'defeat' => 0,
        ]
    ];

    public function getPlayer ()
    {
        return $this->human;
    }

    public function getName ()
    {
        return $this->human['name'];
    }

    public function setName (string $name)
    {
       $this->human['name'] = $name;
    }

    public function getStats ()
    {
        return $this->human['stats'];
    }

    public function setStats (int $draw, int $victory, int $defeat)
    {
        $this->human['stats']['draw'] += $draw;
        $this->human['stats']['victory'] += $victory;
        $this->human['stats']['defeat'] += $defeat;
    }
}
