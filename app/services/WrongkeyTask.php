<?php

use Phalcon\Mvc\Model\Query;

class WrongkeyTask extends \Phalcon\CLI\Task {

    // php app/cli.php mo
    public function MainAction() {
        while (true) {
            try {
                $path = getcwd();
                //$path = '/var/www/html/engine';

                $projectFolder = $path . '/filesystem/app/other';

                if ($handle = opendir($projectFolder)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != '.' && $entry != '..') {
                            // Read File
                            $theFile = fopen($projectFolder . "/" . $entry, "r");
                            if ($theFile) {
                                $dataFile = fread($theFile, filesize($projectFolder . "/" . $entry));
                                $expldData = explode("|", $dataFile);
                                // Close Read File Session
                                fclose($theFile);

//                            [0] => xl
//                            [1] => 912345
//                            [2] => 6281966655241
//                            [3] => regbola
//                            [4] => regbola
//                            [5] => 9922112241
//                            [6] => 2017-07-22
//                            [7] => 91750307
//                            [8] => 2017-07-22 04:36:09
//                            [9] => non reg             reg/unreg

                                $contentPush = $expldData[0] . '|' . $expldData[1] . '|' . $expldData[2] . '|not found|' . $expldData[3] . '|' . $expldData[4] . '||' . $expldData[6] . '|' . $expldData[7] . '|' . $expldData[8] . '|' . $expldData[9] . '|0|Format atau keyword yang anda kirim salah|1|PUll|0|1|PUSH;IOD;WRONGKEYWORD|not-found';

                                $pushFolder = $path . '/filesystem/push';
                                $pushTelco = $pushFolder . '/' . $expldData[0] . '/pull';

                                if (!file_exists($pushTelco)) {
                                    mkdir($pushTelco, 0777, true);
                                }

                                chmod($pushTelco, 0777);
                                $sessionid = rand(1, 99999999);

                                $filePush = $pushTelco . '/wrong-keyword-' . $sessionid . '.txt';

                                $createFilePush = fopen($filePush, "w");
                                if ($createFilePush) {
                                    $fwPush = fwrite($createFilePush, $contentPush);
                                    if ($fwPush) {
                                        if (unlink($projectFolder . "/" . $entry)) {
                                            echo date('Y-m-d h:i:s') . " : Create wrong keyword push file & Update ststus member to unreg Success \n";
                                        }
                                    }
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
