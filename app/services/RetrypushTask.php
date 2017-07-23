<?php

use Phalcon\Mvc\Model\Query;

class RetrypushTask extends \Phalcon\CLI\Task {

    // php app/cli.php mo
    public function MainAction() {
        while (true) {
            try {
                //$path = getcwd();
                $path = '/var/www/html/engine';

                $projectFolder = $path . '/filesystem/retry';

                if ($handle = opendir($projectFolder)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != '.' && $entry != '..') {
                            // Read File
                            $theFile = fopen($projectFolder . "/" . $entry, "r");
                            if ($theFile) {
                                $dataFile = fread($theFile, filesize($projectFolder . "/" . $entry));
                                $expldData = explode("|", $dataFile);
//                            [0] => xl
//                            [1] => 912345
//                            [2] => 6281966655242
//                            [3] => bola
//                            [4] => DAILY PUSH BOLA
//                            [5] =>
//                            [6] => 2017-07-21
//                            [7] => 26021626
//                            [8] => 2017-07-21 04:55:06
//                            [9] => reg
//                            [10] => 2
//                            [11] => Reply Bola Kedua
//                            [12] => 1
//                            [13] => push
//                            [14] => 10000
//                            [15] => 1
//                            [16] => PUSH; IOD; BOLA; DAILYPUSH; RETRY
//                            [17] => 1;

                                $retryPushPath = $path . '/filesystem/push/' . $expldData[0] . '/push';
                                if (!file_exists($retryPushPath)) {
                                    mkdir($retryPushPath, 0777, true);
                                }
                                chmod($retryPushPath, 0777);

                                if (rename($projectFolder . "/" . $entry, $retryPushPath . '/' . $entry)) {
                                    echo date('Y-m-d h:i:s') . " : Move Retry File to Push \n";
                                }
                            }
                        }
                    }
                    closedir($handle);
                }
            } catch (\Exception $e) {
                echo date('Y-m-d h:i:s') . " : Error try catch $e \n";
            }
            sleep(1);
        }
    }

}
