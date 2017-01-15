<?php
return array_merge(require __DIR__ . '/di/aliases.inc.php',
                   require __DIR__ . '/di/framework.inc.php',
                   require __DIR__ . '/di/settings.inc.php',
                   require __DIR__ . '/di/listeners.inc.php',
                   require __DIR__ . '/di/factories.inc.php',
                   require __DIR__ . '/di/interfaces.inc.php',
                   require __DIR__ . '/di/objects.inc.php');
