<?php
return [
    'allowed_controllers' => include __DIR__.'/di/allowed_controllers.config.php',
    'instance' => [
        'alias'=> include __DIR__.'/di/instance/alias.config.php',
        'preference' => include __DIR__.'/di/instance/preference.config.php'
    ]
];
