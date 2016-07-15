<?php
return array_merge(require __DIR__ . '/di/aliases.inc.php',
                   require __DIR__ . '/di/actions.inc.php',
                   require __DIR__ . '/di/factories.inc.php',
                   require __DIR__ . '/di/interfaces.inc.php',
                   require __DIR__ . '/di/objects.inc.php');
