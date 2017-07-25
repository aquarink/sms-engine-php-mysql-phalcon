<?php

$appName = 'cantik';
try {
    $path = getcwd();
    //$path = '/var/www/html/engine';

    $thisDay = date('Y_m_d');

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

//                    [0] => xl
//                    [1] => 912345
//                    [2] => 6281966655263
//                    [3] => bola
//                    [4] => reg bola
//                    [5] => 9922112263
//                    [6] => 2017-07-22
//                    [7] => 72987145
//                    [8] => 2017-07-22 06:37:57
//                    [9] => reg

                    $ckTabMo = $this->dblog->query("SHOW TABLES LIKE 'tb_push_$thisDay'");
                    if ($ckTabMo->numRows() == 0) {
                        $createTable = "CREATE TABLE tb_push_$thisDay (
                                id_push INT(11) NOT NULL AUTO_INCREMENT,
                                telco VARCHAR(20) DEFAULT NULL,
                                shortcode VARCHAR(20) DEFAULT NULL,
                                msisdn VARCHAR(20) DEFAULT NULL,
                                sms_field VARCHAR(200) DEFAULT NULL,
                                id_app INT(11) DEFAULT NULL,
                                keyword VARCHAR(100) DEFAULT NULL,
                                content_number INT(11) DEFAULT NULL,
                                content_field VARCHAR(200) DEFAULT NULL,
                                trx_id VARCHAR(250) DEFAULT NULL,
                                trx_date VARCHAR(20) DEFAULT NULL,
                                session_id VARCHAR(100) DEFAULT NULL,
                                session_date VARCHAR(20) DEFAULT NULL,
                                reg_type VARCHAR(10) DEFAULT NULL,
                                type VARCHAR(10) DEFAULT NULL,
                                cost VARCHAR(10) DEFAULT NULL,
                                send_status VARCHAR(10) DEFAULT NULL,
                                response_code VARCHAR(10) DEFAULT NULL,
                                subject VARCHAR(100) DEFAULT NULL,
                                date_create VARCHAR(20) DEFAULT NULL,
                                PRIMARY KEY (id_push))";

                        $createExe = $this->dblog->query($createTable);
                    }
                    
                    $checkQuery = "SELECT content_number FROM tb_push_$thisDay WHERE "
                            . "telco = '$expldData[0]' AND "
                            . "shortcode = '$expldData[1]' AND "
                            . "msisdn = '$expldData[2]' AND "
                            . "keyword = '$expldData[3]' "
                            . "ORDER BY id_push DESC LIMIT 1";
                    $result = $this->dblog->query($checkQuery);

                    $sessionRand = rand(1, 99999999);

                    $pathPush = $path . "/filesystem/push/" . $expldData[0] . "/pull";
                    if (!file_exists($pathPush)) {
                        mkdir($pathPush, 0777, true);
                    }

                    chmod($pathPush, 0777);

                    // Keyword Data
                    $keywordQuery = "SELECT id_app FROM tb_keyword WHERE "
                            . "telco = '$expldData[0]' AND "
                            . "shortcode = '$expldData[1]' AND "
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
                        $contentApp = $expldData[0] . '|' . $expldData[1] . '|' . $expldData[2] . '|' . $appConfig['id_app'] . '|' . $expldData[3] . '|' . $expldData[4] . '|' . $expldData[5] . '|' . $expldData[6] . '|' . $expldData[7] . '|' . $expldData[8] . '|' . $expldData[9] . '|0|Welcome Message Karena reg 2x|1|pull|0|1|REG;IOD;' . strtoupper($appName);
                        $fileApp = $pathPush . '/' . $sessionRand . '-dua.txt';
                    } else {
                        $contentSequence = 1;
                        $contentWelcome = $expldData[0] . '|' . $expldData[1] . '|' . $expldData[2] . '|' . $appConfig['id_app'] . '|' . $expldData[3] . '|' . $expldData[4] . '|' . $expldData[5] . '|' . $expldData[6] . '|' . $expldData[7] . '|' . $expldData[8] . '|' . $expldData[9] . '|0|Welcome Message reg 1x|1|pull|0|1|REG;IOD;' . strtoupper($appName);

                        $fileWelcome = $pathPush . '/' . $expldData[5] . '-satu.txt';
                        // Create App File
                        $createWelcome = fopen($fileWelcome, "w");
                        if ($createWelcome) {
                            $fws = fwrite($createWelcome, $contentWelcome);
                            fclose($createWelcome);
                            if ($fws) {
                                echo date('Y-m-d h:i:s') . " : Create Welcome Message \n";
                                // Create  App Message
                                $contentQuery = "SELECT * FROM tb_apps_content WHERE "
                                        . "id_app = '$key[id_app]' AND "
                                        . "keyword = '$appName' AND "
                                        . "content_number = '$contentSequence'";
                                //echo $contentQuery;
                                $contentResult = $this->db->query($contentQuery);
                                $cntn = $contentResult->fetchAll()[0];

                                // seqNumber Reply regType pull/pushType cost statusSend
                                $contentApp = $expldData[0] . '|' . $expldData[1] . '|' . $expldData[2] . '|' . $appConfig['id_app'] . '|' . $expldData[3] . '|' . $expldData[4] . '||' . $expldData[6] . '|' . $sessionRand . '|' . $expldData[8] . '|firstpush|' . $cntn['content_number'] . '|' . $cntn['content_field'] . '|1|push|' . $appConfig['cost_pull'] . '|1|PUSH;IOD;' . strtoupper($appName) . ';FIRSTPUSH';
                                $fileApp = $pathPush . '/' . $sessionRand . '-content.txt';
                            }
                        }
                    }

                    // Execute Create App File
                    $updateQuery = "UPDATE tb_members SET "
                            . "content_seq = '$contentSequence' WHERE "
                            . "shortcode = '$expldData[1]' AND "
                            . "msisdn = '$expldData[2]' AND "
                            . "keyword = '$expldData[3]'";
                    $resultUpdate = $this->db->query($updateQuery);
                    //
                    if ($resultUpdate->numRows() > 0) {
                        $createPush = fopen($fileApp, "w");
                        if ($createPush) {
                            $fwsMain = fwrite($createPush, $contentApp);
                            if ($fwsMain) {
                                if (unlink($appFolder . "/" . $fileNames)) {
                                    echo date('Y-m-d h:i:s') . " : Create Push & App File Unlink Success \n";
                                }
                            }
                        }
                    }
                }
            }
        }
        closedir($appDir);
    }
} catch (\Exception $e) {
    echo date('Y-m-d h:i:s') . " : Error try catch " . $e . "\n";
}