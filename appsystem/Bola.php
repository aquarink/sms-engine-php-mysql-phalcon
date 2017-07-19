<?php

$appName = 'bola';
try {
    $path = getcwd();
    //$path = '/var/www/html/engine';

    $appFolder = $path . "/filesystem/app/" . $appName;
    if ($appDir = opendir($appFolder)) {
        while (false !== ($fileNames = readdir($appDir))) {
            if ($fileNames != '.' && $fileNames != '..') {
                // Read File
                $theFiles = fopen($appFolder . "/" . $fileNames, "r");
                if ($theFiles) {
                    $dataFiles = fread($theFiles, filesize($appFolder . "/" . $fileNames));
                    fclose($theFiles);
                    //if (unlink($appFolder . "/" . $fileNames)) {
                    $expldData = explode("|", $dataFiles);
//                        [0] => xl
//                        [1] => 912345
//                        [2] => 6281866655262
//                        [3] => bola
//                        [4] => reg bola
//                        [5] => 9922112262
//                        [6] => 2017-05-12
//                        [7] => 31340523
//                        [8] => 2017-07-19 09:15:09
//                        [9] => reg

                    $checkQuery = "SELECT * FROM tb_sms_push WHERE "
                            . "telco = '$expldData[0]' AND "
                            . "shortcode = '$expldData[1]' AND "
                            . "msisdn = '$expldData[2]' AND "
                            . "keyword = '$expldData[3]' "
                            . "ORDER BY id_push DESC LIMIT 1";
                    $result = $this->db->query($checkQuery);
                    $smsPushData = $result->fetchAll();

                    $sessionRand = rand(1, 99999999);

                    $pathPush = $path . "/filesystem/push/" . $expldData[0];
                    if (!file_exists($pathPush)) {
                        mkdir($pathPush, 0777, true);
                    }

                    chmod($pathPush, 0777);

                    // Keyword Data
                    $keywordQuery = "SELECT * FROM tb_keyword WHERE "
                            . "keyword = '$appName'";
                    $keywordResult = $this->db->query($keywordQuery);
                    $key = $keywordResult->fetchAll()[0];

                    // App Config
                    $appConfigQuery = "SELECT * FROM tb_app_config WHERE "
                            . "id_app = '$key[id_app]'";
                    $appConfigResult = $this->db->query($appConfigQuery);
                    $appConfig = $appConfigResult->fetchAll()[0];
                                        
                    //
                    if ($result->numRows() > 0) {
                        // Create App Message Only
                        $contentSequence = $smsPushData[0]['content_number'] + 1;
                        $contentQuery = "SELECT * FROM tb_apps_content WHERE "
                                . "keyword = '$appName' AND "
                                . "content_number = '$contentSequence'";
                        $contentResult = $this->db->query($contentQuery);
                        $cntn = $contentResult->fetchAll()[0];

                        // seqNumber Reply regType pull/pushType cost statusSend
                        //$contentApp = $expldData[0] . '|' . $expldData[1] . '|' . $expldData[2] . '|' . $expldData[3] . '|' . $expldData[4] . '||' . $expldData[6] . '|' . $expldData[7] . '|' . $expldData[8] . '|' . $expldData[9] . '|' . $cntn['content_number'] . '|' . $cntn['content_field'] . '|1|pull|' . $appConfig['cost_pull'] . '|1|push;iod;' . $appName . ';dailypush';
                        $contentApp = $dataFiles . '|0|Welcome Message Karena reg 2x|1|pull|0|1|reg;iod;' . $appName;
                        $fileApp = $pathPush . '/' . $sessionRand . 'dua.txt';
                    } else {
                        $contentWelcome = $dataFiles . '|0|Welcome Message Karena reg 1x|1|pull|0|1|reg;iod;' . $appName;
                        $fileWelcome = $pathPush . '/' . $expldData[5] . 'satu.txt';
                        // Create App File
                        $createWelcome = fopen($fileWelcome, "w");
                        if ($createWelcome) {
                            $fws = fwrite($createWelcome, $contentWelcome);
                            fclose($createWelcome);
                            if ($fws) {
                                echo date('Y-m-d h:i:s') . " : Create Welcome Message \n";
                                // Create  App Message
                                $contentQuery = "SELECT * FROM tb_apps_content WHERE "
                                        . "keyword = '$appName' AND "
                                        . "content_number = '1'";
                                $contentResult = $this->db->query($contentQuery);
                                $cntn = $contentResult->fetchAll()[0];

                                // seqNumber Reply regType pull/pushType cost statusSend
                                $contentApp = $expldData[0] . '|' . $expldData[1] . '|' . $expldData[2] . '|' . $expldData[3] . '|' . $expldData[4] . '||' . $expldData[6] . '|' . $expldData[7] . '|' . $expldData[8] . '|' . $expldData[9] . '|' . $cntn['content_number'] . '|' . $cntn['content_field'] . '|1|pull|' . $appConfig['cost_pull'] . '|1|push;iod;' . $appName . ';firstpush';
                                $fileApp = $pathPush . '/' . $sessionRand . 'content.txt';
                            }
                        }
                    }

                    // Execute Create App File
                    $createPush = fopen($fileApp, "w");
                    if ($createPush) {
                        $fwsMain = fwrite($createPush, $contentApp);
                        if ($fwsMain) {
                            if (unlink($appFolder . "/" . $fileNames)) {
                                echo date('Y-m-d h:i:s') . " : Create Push & App File Unlink Success \n";
                            }
                        }
                        //}
                    }
                }
            }
        }
        closedir($appDir);
    }
} catch (\Exception $e) {
    echo date('Y-m-d h:i:s') . " : Error try catch " . $e . "\n";
}