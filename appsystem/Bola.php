<?php

$appName = 'bola';
try {
    $appFolder = getcwd() . "/filesystem/app/" . $appName;
    if ($appDir = opendir($appFolder)) {
        while (false !== ($fileNames = readdir($appDir))) {
            if ($fileNames != '.' && $fileNames != '..') {
                // Read File
                $theFiles = fopen($appFolder . "/" . $fileNames, "r");
                if ($theFiles) {
                    $dataFiles = fread($theFiles, filesize($appFolder . "/" . $fileNames));
                    $expldData = explode("|", $dataFiles);
                    $checkQuery = "SELECT * FROM tb_sms_push WHERE "
                            . "telco = '$expldData[0]' AND "
                            . "shortcode = '$expldData[1]' AND "
                            . "msisdn = '$expldData[2]' AND "
                            . "keyword = '$expldData[3]' "
                            . "ORDER BY id_push DESC LIMIT 1";
                    $result = $this->db->query($checkQuery);
                    $smsPushData = $result->fetchAll();

                    $sessionRand = rand(1, 99999999);

                    $pathPush = getcwd() . "/filesystem/push";
                    if (!file_exists($pathPush)) {
                        mkdir($pathPush, 0777, true);
                    }

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

                    if ($result->numRows() > 0) {
                        // Create App Message Only
                        $contentSequence = $smsPushData[0]['content_number'] + 1;
                        $contentQuery = "SELECT * FROM tb_apps_content WHERE "
                                . "keyword = '$appName' AND "
                                . "content_number = '$contentSequence'";
                        $contentResult = $this->db->query($contentQuery);
                        $cntn = $contentResult->fetchAll()[0];

                        // seqNumber Reply regType pull/pushType cost statusSend
                        $contentApp = $dataFiles . '|' . $cntn['content_number'] . '|' . $cntn['content_field'] . '|1|pull|' . $appConfig['cost_pull'] . '|1';
                        $fileApp = $pathPush . '/' . $sessionRand . 'a.txt';
                    } else {
                        $contentWelcome = $dataFiles . '|0|Welcome Message Php|1|pull|0|1';
                        $fileWelcome = $pathPush . '/' . $expldData[5] . 'w.txt';
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
                                $contentApp = $dataFiles . '|' . $cntn['content_number'] . '|' . $cntn['content_field'] . '|1|pull|' . $appConfig['cost_pull'] . '|1';
                                $fileApp = $pathPush . '/' . $sessionRand . 'a.txt';
                            }
                        }
                    }

                    // Execute Create App File
                    $createPush = fopen($fileApp, "w");
                    if ($createPush) {
                        $fwsMain = fwrite($createPush, $contentApp);
                        fclose($theFiles);
                        if ($fwsMain) {
                            if (unlink($appFolder . "/" . $fileNames)) {
                                echo date('Y-m-d h:i:s') . " : Create Push & App File Unlink Success \n";
                            }
                        }
                    }
                } else {
                    echo date('Y-m-d h:i:s') . " : Error openfile " . $appName . "\n";
                }
            }
        }
        closedir($appDir);
    } else {
        echo date('Y-m-d h:i:s') . " : Error opendir " . $appName . "\n";
    }
} catch (\Exception $e) {
    echo date('Y-m-d h:i:s') . " : Error try catch " . $e . "\n";
}