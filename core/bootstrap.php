<?php

use Symfony\Component\Yaml\Yaml;

require_once '../core/functions.php';

$config = Yaml::parseFile('../config/app.yml');
$workspace = null;

/* Check workspaces */
foreach ($config['workspaces'] as $workspaceName => $workspaceConfig) {
    /* check hosts */
    if (preg_match('/'.$workspaceConfig['host'].'/', $_SERVER['HTTP_HOST'])) {
        $workspace = $workspaceName;
        $config = $workspaceConfig;
        break;
    }
}

/* TODO: Change error to standar errors */
if (!$workspace) {
    die("No workspace defined in config/app.yml");
}

/* Create global Core object */
$core = new Core\Class\Core();

pre($config, true);