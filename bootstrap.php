<?php

\Autoloader::add_core_namespace('SynergiTech\\Messages');

\Autoloader::add_classes(array(
    'SynergiTech\\Messages'                        => __DIR__.'/classes/messages.php',
    'SynergiTech\\Messages\\Instance'                        => __DIR__.'/classes/instance.php',
));
