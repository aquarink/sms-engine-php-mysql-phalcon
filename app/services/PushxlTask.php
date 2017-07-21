<?php

use Phalcon\Mvc\Model\Query;

class PushxlTask extends \Phalcon\CLI\Task {

    // php app/cli.php push
    public function MainAction() {
        $telco = 'xl';

        while (true) {
            try {
                $path = getcwd();
                //$path = '/var/www/html/engine';

                $pushTelcoFolder = $path . '/filesystem/push/' . $telco;
                $pushFolder = $pushTelcoFolder . '/push';
                $pullFolder = $pushTelcoFolder . '/pull';

                $dateNow = date('Y_m_d');
                $tableName = "tb_push_today";
                $checkTable = "SHOW TABLES LIKE '$tableName'";

                $ckTab = $this->dblog->query($checkTable);
                $tableData = $ckTab->numRows();

                if ($tableData == 0) {
                    $createTable = "CREATE TABLE $tableName (
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
                                PRIMARY KEY (id_push))";

                    //echo $createTable;

                    $createExe = $this->dblog->query($createTable);
                }

                //Telco Config
                $telcoQuery = "SELECT * FROM tb_telco_config WHERE "
                        . "telco_name = '$telco'"
                        . "ORDER BY id_telco DESC LIMIT 1";
                $result = $this->db->query($telcoQuery);
                $telcoConfig = $result->fetchAll()[0];

                $a = scandir($pushTelcoFolder . '/pull');
                $b = array_splice($a, 2);
                $pullLimit = $telcoConfig['pull_limit'];

                if ($b == 0) {
                    $pushLimit = $telcoConfig['push_limit'] + $pullLimit;
                } else {
                    $pushLimit = $telcoConfig['push_limit'];
                }

                if ($handle = opendir($pushFolder)) {
                    // Read File Limit
                    $filesArr = scandir($pushFolder);
                    $listFile = array_splice($filesArr, 2);

                    if (count($listFile) <= $pushLimit) {
                        for ($offset = 0; $offset < count($listFile); $offset++) {

                            // Read File
                            $theFile = fopen($pushFolder . "/" . $listFile[$offset], "r");
                            $dataFile = fread($theFile, filesize($pushFolder . "/" . $listFile[$offset]));
                            $expldData = explode("|", $dataFile);

                            fclose($theFile);
                            if ($theFile) {
                                $options = array(
                                    "username" => $telcoConfig['username'],
                                    "password" => $telcoConfig['password'],
                                    "shortcode" => $expldData[1],
                                    "msisdn" => $expldData[2],
                                    "trxid" => $expldData[5],
                                    "serviceId" => $expldData[14],
                                    "sms" => $expldData[11],
                                    "shortname" => "1234567890");

                                $host = $telcoConfig['address'] . '?';
                                $host .= http_build_query($options, '', '&');

                                $opts = array('http' => array('header' => "User-Agent:Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1\r\n"));
                                $context = stream_context_create($opts);
                                $postResult = file_get_contents($host, false, $context);

                                $xml = simplexml_load_string($postResult);
                                $json = json_encode($xml);
                                $configData = json_decode($json, true);

                                $resCode = $configData['error']['@attributes']['code'];
                                $resTxrid = $configData['trxid']['@attributes']['id'];

                                // Insert
//                            [0] => xl
//                            [1] => 912345
//                            [2] => 6285966655261
//                            [3] => bola
//                            [4] => reg bola
//                            [5] =>
//                            [6] => 2017-07-03
//                            [7] => 535079 // session date
//                            [8] => 2017-07-18 10:41:33
//                            [9] => reg
//                            [10] => 2
//                            [11] => Reply Bola Kedua
//                            [12] => 1
//                            [13] => pull
//                            [14] => 1000
//                            [15] => 1
//                            [16] => subject
//                            [17] => id_app

                                $smsPush = array(
                                    'telco' => "'$expldData[0]'",
                                    'shortcode' => "'$expldData[1]'",
                                    'msisdn' => "'$expldData[2]'",
                                    'sms_field' => "'$expldData[4]'",
                                    'id_app' => "'$expldData[17]'",
                                    'keyword' => "'$expldData[3]'",
                                    'content_number' => "'$expldData[10]'",
                                    'content_field' => "'$expldData[11]'",
                                    'trx_id' => "'$resTxrid'",
                                    'trx_date' => "'$expldData[6]'",
                                    'session_id' => "'$expldData[7]'",
                                    'session_date' => "'$expldData[8]'",
                                    'reg_type' => "'$expldData[9]'",
                                    'type' => "'$expldData[13]'",
                                    'cost' => "'$expldData[14]'",
                                    'send_status' => "'$expldData[15]'",
                                    'response_code' => "'$resCode'",
                                    'subject' => "'$expldData[16]'"
                                );

                                $querySave = "INSERT INTO $tableName ( " . implode(', ', array_keys($smsPush)) . ") VALUES (" . implode(', ', array_values($smsPush)) . ")";
                                $exeSave = $this->dblog->query($querySave);

                                if ($exeSave->numRows() > 0) {
                                    if ($resCode == 0) {
                                        if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                            echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - code 0 max - 1111 Success \n";
                                        }
                                    } else {
                                        // dan response error lainnya
                                        $sessionid = rand(1, 99999999);
                                        $reTryPath = $path . '/filesystem/retry/';
                                        $reFile = $reTryPath . $sessionid . '-retry.txt';

                                        $expldData[16] = $expldData[16] . ';RETRY1';
                                        $reContent = implode('|', array_values($expldData));

                                        if (!file_exists($reTryPath)) {
                                            mkdir($reTryPath, 0777, true);
                                        }

                                        chmod($reTryPath, 0777);

                                        $createReTryFile = fopen($reFile, "w");
                                        if ($createReTryFile) {
                                            $ReTryfw = fwrite($createReTryFile, $reContent);
                                            if ($ReTryfw) {
                                                fclose($createReTryFile);
                                                if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                                    echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - code else 0 max - 2222 Success \n";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        for ($offset = 0; $offset < $pushLimit; $offset++) {
                            // Read File
                            $theFile = fopen($pushFolder . "/" . $listFile[$offset], "r");
                            $dataFile = fread($theFile, filesize($pushFolder . "/" . $listFile[$offset]));
                            $expldData = explode("|", $dataFile);

                            fclose($theFile);
                            if ($theFile) {
                                $options = array(
                                    "username" => $telcoConfig['username'],
                                    "password" => $telcoConfig['password'],
                                    "shortcode" => $expldData[1],
                                    "msisdn" => $expldData[2],
                                    "trxid" => $expldData[5],
                                    "serviceId" => $expldData[14],
                                    "sms" => $expldData[11],
                                    "shortname" => "1234567890");

                                $host = $telcoConfig['address'] . '?';
                                $host .= http_build_query($options, '', '&');

                                $opts = array('http' => array('header' => "User-Agent:Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1\r\n"));
                                $context = stream_context_create($opts);
                                $postResult = file_get_contents($host, false, $context);

                                $xml = simplexml_load_string($postResult);
                                $json = json_encode($xml);
                                $configData = json_decode($json, true);

                                $resCode = $configData['error']['@attributes']['code'];
                                $resTxrid = $configData['trxid']['@attributes']['id'];

                                // Insert
//                            [0] => xl
//                            [1] => 912345
//                            [2] => 6285966655261
//                            [3] => bola
//                            [4] => reg bola
//                            [5] =>
//                            [6] => 2017-07-03
//                            [7] => 535079 // session date
//                            [8] => 2017-07-18 10:41:33
//                            [9] => reg
//                            [10] => 2
//                            [11] => Reply Bola Kedua
//                            [12] => 1
//                            [13] => pull
//                            [14] => 1000
//                            [15] => 1
//                            [16] => subject
//                            [17] => id app

                                $smsPush = array(
                                    'telco' => "'$expldData[0]'",
                                    'shortcode' => "'$expldData[1]'",
                                    'msisdn' => "'$expldData[2]'",
                                    'sms_field' => "'$expldData[4]'",
                                    'id_app' => "'$expldData[17]'",
                                    'keyword' => "'$expldData[3]'",
                                    'content_number' => "'$expldData[10]'",
                                    'content_field' => "'$expldData[11]'",
                                    'trx_id' => "'$resTxrid'",
                                    'trx_date' => "'$expldData[6]'",
                                    'session_id' => "'$expldData[7]'",
                                    'session_date' => "'$expldData[8]'",
                                    'reg_type' => "'$expldData[9]'",
                                    'type' => "'$expldData[13]'",
                                    'cost' => "'$expldData[14]'",
                                    'send_status' => "'$expldData[15]'",
                                    'response_code' => "'$resCode'",
                                    'subject' => "'$expldData[16]'"
                                );

                                $querySave = "INSERT INTO $tableName ( " . implode(', ', array_keys($smsPush)) . ") VALUES (" . implode(', ', array_values($smsPush)) . ")";
                                $exeSave = $this->dblog->query($querySave);

                                if ($exeSave->numRows() > 0) {
                                    if ($resCode == 0) {
                                        if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                            echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - code 0 min - 3333 Success \n";
                                        }
                                    } else {
                                        // dan response error lainnya
                                        $sessionid = rand(1, 99999999);
                                        $reTryPath = $path . '/filesystem/retry/';
                                        $reFile = $reTryPath . $sessionid . '-retry.txt';

                                        $expldData[16] = $expldData[16] . ';RETRY1';
                                        $reContent = implode('|', array_values($expldData));

                                        if (!file_exists($reTryPath)) {
                                            mkdir($reTryPath, 0777, true);
                                        }

                                        chmod($reTryPath, 0777);

                                        $createReTryFile = fopen($reFile, "w");
                                        if ($createReTryFile) {
                                            $ReTryfw = fwrite($createReTryFile, $reContent);
                                            if ($ReTryfw) {
                                                fclose($createReTryFile);
                                                if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                                    echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - code else 0 min - 4444 Success \n";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    closedir($handle);
                }

                //
                // PULL PROSESS
                //
            
            if ($handlePull = opendir($pullFolder)) {
                    // Read File Limit
                    $filesArrPull = scandir($pullFolder);
                    $listFilePull = array_splice($filesArrPull, 2);

                    if (count($listFilePull) <= $pullLimit) {
                        for ($offsetPull = 0; $offsetPull < count($listFilePull); $offsetPull++) {

                            // Read File
                            $theFilePull = fopen($pullFolder . "/" . $listFilePull[$offsetPull], "r");
                            $dataFilePull = fread($theFilePull, filesize($pullFolder . "/" . $listFilePull[$offsetPull]));
                            $expldDataPull = explode("|", $dataFilePull);

                            fclose($theFilePull);
                            if ($theFilePull) {
                                $optionsPull = array(
                                    "username" => $telcoConfig['username'],
                                    "password" => $telcoConfig['password'],
                                    "shortcode" => $expldDataPull[1],
                                    "msisdn" => $expldDataPull[2],
                                    "trxid" => $expldDataPull[5],
                                    "serviceId" => $expldDataPull[14],
                                    "sms" => $expldDataPull[11],
                                    "shortname" => "1234567890");

                                $hostPull = $telcoConfig['address'] . '?';
                                $hostPull .= http_build_query($optionsPull, '', '&');

                                $optsPull = array('http' => array('header' => "User-Agent:Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1\r\n"));
                                $contextPull = stream_context_create($optsPull);
                                $postResultPull = file_get_contents($hostPull, false, $contextPull);

                                $xmlPull = simplexml_load_string($postResultPull);
                                $jsonPull = json_encode($xmlPull);
                                $configDataPull = json_decode($jsonPull, true);

                                $resCodePull = $configDataPull['error']['@attributes']['code'];
                                $resTxridPull = $configDataPull['trxid']['@attributes']['id'];

                                // Insert
//                            [0] => xl
//                            [1] => 912345
//                            [2] => 6285966655261
//                            [3] => bola
//                            [4] => reg bola
//                            [5] =>
//                            [6] => 2017-07-03
//                            [7] => 535079 // session date
//                            [8] => 2017-07-18 10:41:33
//                            [9] => reg
//                            [10] => 2
//                            [11] => Reply Bola Kedua
//                            [12] => 1
//                            [13] => pull
//                            [14] => 1000
//                            [15] => 1
//                            [16] => subject
//                            [17] => id_app

                                $smsPull = array(
                                    'telco' => "'$expldDataPull[0]'",
                                    'shortcode' => "'$expldDataPull[1]'",
                                    'msisdn' => "'$expldDataPull[2]'",
                                    'sms_field' => "'$expldDataPull[4]'",
                                    'id_app' => "'$expldDataPull[17]'",
                                    'keyword' => "'$expldDataPull[3]'",
                                    'content_number' => "'$expldDataPull[10]'",
                                    'content_field' => "'$expldDataPull[11]'",
                                    'trx_id' => "'$resTxridPull'",
                                    'trx_date' => "'$expldDataPull[6]'",
                                    'session_id' => "'$expldDataPull[7]'",
                                    'session_date' => "'$expldDataPull[8]'",
                                    'reg_type' => "'$expldDataPull[9]'",
                                    'type' => "'$expldDataPull[13]'",
                                    'cost' => "'$expldDataPull[14]'",
                                    'send_status' => "'$expldDataPull[15]'",
                                    'response_code' => "'$resCodePull'",
                                    'subject' => "'$expldDataPull[16]'"
                                );

                                $querySavePull = "INSERT INTO $tableName ( " . implode(', ', array_keys($smsPull)) . ") VALUES (" . implode(', ', array_values($smsPull)) . ")";
                                $exeSavePull = $this->dblog->query($querySavePull);

                                if ($exeSavePull->numRows() > 0) {
                                    if ($resCodePull == 0) {
                                        if (unlink($pullFolder . "/" . $listFilePull[$offsetPull])) {
                                            echo date('Y-m-d h:i:s') . " : Push to telco, Insert Pull Data & DR File Unlink - code 0 max - 5555 Success \n";
                                        }
                                    } else {
                                        // dan response error lainnya
                                        $sessionidPull = rand(1, 99999999);
                                        $reTryPathPull = $path . '/filesystem/retry/';
                                        $reFilePull = $reTryPathPull . $sessionidPull . '-retry.txt';

                                        $expldDataPull[16] = $expldDataPull[16] . ';RETRY1';
                                        $reContentPull = implode('|', array_values($expldDataPull));

                                        if (!file_exists($reTryPathPull)) {
                                            mkdir($reTryPathPull, 0777, true);
                                        }

                                        chmod($reTryPathPull, 0777);

                                        $createReTryFilePull = fopen($reFilePull, "w");
                                        if ($createReTryFilePull) {
                                            $ReTryfwPull = fwrite($createReTryFilePull, $reContentPull);
                                            if ($ReTryfwPull) {
                                                fclose($createReTryFilePull);
                                                if (unlink($pullFolder . "/" . $listFilePull[$offsetPull])) {
                                                    echo date('Y-m-d h:i:s') . " : Push to telco, Insert Pull Data & DR File Unlink - code else 0 max - 6666 Success \n";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        for ($offsetPull = 0; $offsetPull < $pullLimit; $offsetPull++) {
                            // Read File
                            $theFilePull = fopen($pullFolder . "/" . $listFilePull[$offsetPull], "r");
                            $dataFilePull = fread($theFilePull, filesize($pullFolder . "/" . $listFilePull[$offsetPull]));
                            $expldDataPull = explode("|", $dataFilePull);

                            fclose($theFilePull);
                            if ($theFilePull) {
                                $optionsPull = array(
                                    "username" => $telcoConfig['username'],
                                    "password" => $telcoConfig['password'],
                                    "shortcode" => $expldDataPull[1],
                                    "msisdn" => $expldDataPull[2],
                                    "trxid" => $expldDataPull[5],
                                    "serviceId" => $expldDataPull[14],
                                    "sms" => $expldDataPull[11],
                                    "shortname" => "1234567890");

                                $hosPullt = $telcoConfigPull['address'] . '?';
                                $hostPull .= http_build_query($optionsPull, '', '&');

                                $optsPull = array('http' => array('header' => "User-Agent:Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1\r\n"));
                                $contextPull = stream_context_create($opts);
                                $postResultPull = file_get_contents($hostPull, false, $contextPull);

                                $xmlPull = simplexml_load_string($postResultPull);
                                $jsonPull = json_encode($xmlPull);
                                $configDataPull = json_decode($jsonPull, true);

                                $resCodePull = $configDataPull['error']['@attributes']['code'];
                                $resTxridPull = $configDataPull['trxid']['@attributes']['id'];

                                // Insert
//                            [0] => xl
//                            [1] => 912345
//                            [2] => 6285966655261
//                            [3] => bola
//                            [4] => reg bola
//                            [5] =>
//                            [6] => 2017-07-03
//                            [7] => 535079 // session date
//                            [8] => 2017-07-18 10:41:33
//                            [9] => reg
//                            [10] => 2
//                            [11] => Reply Bola Kedua
//                            [12] => 1
//                            [13] => pull
//                            [14] => 1000
//                            [15] => 1
//                            [16] => subject
//                            [17] => id app

                                $smsPull = array(
                                    'telco' => "'$expldDataPull[0]'",
                                    'shortcode' => "'$expldDataPull[1]'",
                                    'msisdn' => "'$expldDataPull[2]'",
                                    'sms_field' => "'$expldDataPull[4]'",
                                    'id_app' => "'$expldDataPull[17]'",
                                    'keyword' => "'$expldDataPull[3]'",
                                    'content_number' => "'$expldDataPull[10]'",
                                    'content_field' => "'$expldDataPull[11]'",
                                    'trx_id' => "'$resTxridPull'",
                                    'trx_date' => "'$expldDataPull[6]'",
                                    'session_id' => "'$expldDataPull[7]'",
                                    'session_date' => "'$expldDataPull[8]'",
                                    'reg_type' => "'$expldDataPull[9]'",
                                    'type' => "'$expldDataPull[13]'",
                                    'cost' => "'$expldDataPull[14]'",
                                    'send_status' => "'$expldDataPull[15]'",
                                    'response_code' => "'$resCodePull'",
                                    'subject' => "'$expldDataPull[16]'"
                                );

                                $querySavePull = "INSERT INTO $tableName ( " . implode(', ', array_keys($smsPull)) . ") VALUES (" . implode(', ', array_values($smsPull)) . ")";
                                $exeSavePull = $this->dblog->query($querySavePull);

                                if ($exeSave->numRows() > 0) {
                                    if ($resCode == 0) {
                                        if (unlink($pullFolder . "/" . $listFile[$offset])) {
                                            echo date('Y-m-d h:i:s') . " : Push to telco, Insert Pull Data & DR File Unlink - code 0 min - 7777 Success \n";
                                        }
                                    } else {
                                        // dan response error lainnya
                                        $sessionidPull = rand(1, 99999999);
                                        $reTryPathPull = $path . '/filesystem/retry/';
                                        $reFilePull = $reTryPathPull . $sessionidPull . '-retry.txt';

                                        $expldDataPull[16] = $expldDataPull[16] . ';RETRY1';
                                        $reContentPull = implode('|', array_values($expldDataPull));

                                        if (!file_exists($reTryPathPull)) {
                                            mkdir($reTryPathPull, 0777, true);
                                        }

                                        chmod($reTryPathPull, 0777);

                                        $createReTryFilePull = fopen($reFilePull, "w");
                                        if ($createReTryFilePull) {
                                            $ReTryfwPull = fwrite($createReTryFilePull, $reContentPull);
                                            if ($ReTryfwPull) {
                                                fclose($createReTryFilePull);
                                                if (unlink($pullFolder . "/" . $listFilePull[$offsetPull])) {
                                                    echo date('Y-m-d h:i:s') . " : Push to telco, Insert Pull Data & DR File Unlink - code else 0 min - 8888 Success \n";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    closedir($handlePull);
                }
            } catch (\Exception $e) {
                echo date('Y-m-d h:i:s') . " : Error try catch $e \n";
            }
            sleep(1);
        }
    }

}
