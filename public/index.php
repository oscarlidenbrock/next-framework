<?php

require('../lib/bootstrap.php');

$users = core()->database('main')->repository('core.user');
$temp = $users->find([1]);
pre($users);

// core()->hook('init', []);

// $values = core()->database('main')->query("SELECT * FROM `test` WHERE `id` = :id", ['id' => 2]);

// $user = new \NextFramework\Core\Entity\User();
// pre($user);

// $core->logger->log('¡Hola mundo! (solo se muestra en modo debug (TYPE_DEBUG)', TYPE_INFO, OUTPUT_ALL);

pre($core);

