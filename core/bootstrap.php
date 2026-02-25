<?php

use Symfony\Component\Yaml\Yaml;

require_once '../core/errors.php';
require_once '../core/constants.php';
require_once '../core/functions.php';

$config = Yaml::parseFile('../config/app.yml');
$workspace = null;
$workspaceConfig = null;

/* Check workspaces, config and other call will be checked in core() object */
foreach ($config['workspaces'] as $workspaceName => $workspaceConfig) {
    /* check hosts */
    if (preg_match('/'.$workspaceConfig['host'].'/', $_SERVER['HTTP_HOST'])) {
        $workspace = $workspaceName;
        $workspaceConfig = $workspaceConfig;
        break;
    }
}

if (!$workspace) {
    /* TODO: make standar errors without core() object */
    die("ERROR: No workspace defined in config/app.yml");
}

/* Create global Core object */
if ($workspaceConfig) {
    $core = new \Core\Class\Core($workspaceConfig);

    /* Initialize core() object */
    $core->init();
} else {
    /* TODO: make standar errors without core() object */
    die("ERROR: No config workspace defined in config/app.yml");
}