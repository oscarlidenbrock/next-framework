<?php

require('../lib/bootstrap.php');

$users = core()->database('main')->repository('user');
$temp = $users->find([1]);
pre($temp);


// $values = core()->database['main']->query("SELECT * FROM `test` WHERE `id` = :id", ['id' => 1]);

// $user = new \NextFramework\Core\Entity\User();
// pre($user);

// $core->logger->log('¡Hola mundo! (solo se muestra en modo debug (TYPE_DEBUG)', TYPE_INFO, OUTPUT_ALL);

pre($core);

