<?php

use Phalcon\Mvc\Model\Query;

class AppTask extends \Phalcon\CLI\Task {

    // php app/cli.php mo
    public function MainAction() {
        while (true) {
            try {
                $paths = getcwd();
                //$paths = '/var/www/html/engine';

                $appSystem = $paths . '/appsystem';
                if ($handle = opendir($appSystem)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != '.' && $entry != '..') {
                            include $appSystem . '/' . $entry;
                        }
                    }
                    closedir($handle);
                } else {
                    echo date('Y-m-d h:i:s') . " : Error opendir \n";
                }
            } catch (\Exception $e) {
                echo date('Y-m-d h:i:s') . " : Error try catch \n";
            }
            sleep(1);
        }
    }

}
