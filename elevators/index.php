<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Elevators\Service;
use Elevators\Client;

$loop = \React\EventLoop\Loop::get();

$serviceHaircut = new Service('Haircut', 2);
$serviceLoans = new Service('Loans', 3);

$services = [$serviceHaircut, $serviceLoans];

$elevators = new \Elevators\Elevators([
    new \Elevators\Elevator('#1'),
    new \Elevators\Elevator('#2')
]);

$building = new \Elevators\Building($elevators);

$building->addFloor([$serviceHaircut]);
$building->addFloor([$serviceLoans]);

$handler = new \Elevators\ClientHandler($building, $loop);
$runner = new \Elevators\Runner($handler, $loop);

$runner->scheduleClient(new Client('#1', $serviceLoans), 1);
$runner->scheduleClient(new Client('#2', $serviceHaircut), 0);

$runner->run();
