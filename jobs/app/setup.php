<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/container/container.php';

/** @var PDO $pdo */
$pdo = $container['pdo'];

$pdo->exec('CREATE TABLE `jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT \'\',
  `query` varchar(255) NOT NULL DEFAULT \'\',
  `interval` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

$pdo->exec('INSERT INTO `jobs` (`id`, `name`, `query`, `interval`)
VALUES
	(1, \'Job1\', \'SELECT SLEEP(10);\', 30),
	(2, \'Job2\', \'SELECT SLEEP(5);\', 20),
	(3, \'Job3\', \'SELECT SLEEP(15);\', 10);
');
