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

                $now = date('Y-m-d');

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
                                date_create VARCHAR(20) DEFAULT NULL,
                                PRIMARY KEY (id_push))";

                    //echo $createTable;

                    $createExe = $this->dblog->query($createTable);
                } else {
                    echo 'sudah ada ';
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

                if (!file_exists($pushFolder)) {
                    mkdir($pushFolder, 0777, true);
                }

                chmod($pushFolder, 0777);

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
                                    "trxid" => $expldData[6],
                                    "serviceId" => $expldData[15],
                                    "sms" => $expldData[12],
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
                                $smsPush = array(
                                    'telco' => "'$expldData[0]'",
                                    'shortcode' => "'$expldData[1]'",
                                    'msisdn' => "'$expldData[2]'",
                                    'sms_field' => "'$expldData[5]'",
                                    'id_app' => "'$expldData[3]'",
                                    'keyword' => "'$expldData[4]'",
                                    'content_number' => "'$expldData[11]'",
                                    'content_field' => "'$expldData[12]'",
                                    'trx_id' => "'$resTxrid'",
                                    'trx_date' => "'$expldData[7]'",
                                    'session_id' => "'$expldData[8]'",
                                    'session_date' => "'$expldData[9]'",
                                    'reg_type' => "'$expldData[10]'",
                                    'type' => "'$expldData[14]'",
                                    'cost' => "'$expldData[15]'",
                                    'send_status' => "'$expldData[16]'",
                                    'response_code' => "'$resCode'",
                                    'subject' => "'$expldData[17]'",
                                    'date_create' => "'$now'"
                                );

                                $querySave = "INSERT INTO $tableName ( " . implode(', ', array_keys($smsPush)) . ") VALUES (" . implode(', ', array_values($smsPush)) . ")";
                                $exeSave = $this->dblog->query($querySave);

                                if ($exeSave->numRows() > 0) {
                                    if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                        echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - code 0 max - 1111 Success \n";
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
                                    "trxid" => $expldData[6],
                                    "serviceId" => $expldData[15],
                                    "sms" => $expldData[12],
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
                                $smsPush = array(
                                    'telco' => "'$expldData[0]'",
                                    'shortcode' => "'$expldData[1]'",
                                    'msisdn' => "'$expldData[2]'",
                                    'sms_field' => "'$expldData[5]'",
                                    'id_app' => "'$expldData[3]'",
                                    'keyword' => "'$expldData[4]'",
                                    'content_number' => "'$expldData[11]'",
                                    'content_field' => "'$expldData[12]'",
                                    'trx_id' => "'$resTxrid'",
                                    'trx_date' => "'$expldData[7]'",
                                    'session_id' => "'$expldData[8]'",
                                    'session_date' => "'$expldData[9]'",
                                    'reg_type' => "'$expldData[10]'",
                                    'type' => "'$expldData[14]'",
                                    'cost' => "'$expldData[15]'",
                                    'send_status' => "'$expldData[16]'",
                                    'response_code' => "'$resCode'",
                                    'subject' => "'$expldData[17]'",
                                    'date_create' => "'$now'"
                                );

                                $querySave = "INSERT INTO $tableName ( " . implode(', ', array_keys($smsPush)) . ") VALUES (" . implode(', ', array_values($smsPush)) . ")";
                                $exeSave = $this->dblog->query($querySave);

                                if ($exeSave->numRows() > 0) {
                                    if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                        echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - code 0 min - 3333 Success \n";
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
                
                if (!file_exists($pullFolder)) {
                    mkdir($pullFolder, 0777, true);
                }

                chmod($pullFolder, 0777);

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
                                    "trxid" => $expldDataPull[6],
                                    "serviceId" => $expldDataPull[15],
                                    "sms" => $expldDataPull[12],
                                    "shortname" => "1234567890");

                                $hostPull = $telcoConfig['address'] . '?';
                                $hostPull .= http_build_query($optionsPull, '', '&');

                                $optsPull = array('http' => array('hea'
                                        . ''
                                        . 'der' => "User-Agent:Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1\r\n"));
                                $contextPull = stream_context_create($optsPull);
                                $postResultPull = file_get_contents($hostPull, false, $contextPull);

                                $xmlPull = simplexml_load_string($postResultPull);
                                $jsonPull = json_encode($xmlPull);
                                $configDataPull = json_decode($jsonPull, true);

                                $resCodePull = $configDataPull['error']['@attributes']['code'];
                                $resTxridPull = $configDataPull['trxid']['@attributes']['id'];

                                // Insert
                                $smsPull = array(
                                    'telco' => "'$expldDataPull[0]'",
                                    'shortcode' => "'$expldDataPull[1]'",
                                    'msisdn' => "'$expldDataPull[2]'",
                                    'sms_field' => "'$expldDataPull[5]'",
                                    'id_app' => "'$expldDataPull[3]'",
                                    'keyword' => "'$expldDataPull[4]'",
                                    'content_number' => "'$expldDataPull[11]'",
                                    'content_field' => "'$expldDataPull[12]'",
                                    'trx_id' => "'$resTxridPull'",
                                    'trx_date' => "'$expldDataPull[7]'",
                                    'session_id' => "'$expldDataPull[8]'",
                                    'session_date' => "'$expldDataPull[9]'",
                                    'reg_type' => "'$expldDataPull[10]'",
                                    'type' => "'$expldDataPull[14]'",
                                    'cost' => "'$expldDataPull[15]'",
                                    'send_status' => "'$expldDataPull[16]'",
                                    'response_code' => "'$resCodePull'",
                                    'subject' => "'$expldDataPull[17]'",
                                    'date_create' => "'$now'"
                                );

                                $querySavePull = "INSERT INTO $tableName ( " . implode(', ', array_keys($smsPull)) . ") VALUES (" . implode(', ', array_values($smsPull)) . ")";
                                $exeSavePull = $this->dblog->query($querySavePull);

                                if ($exeSavePull->numRows() > 0) {
                                    if (unlink($pullFolder . "/" . $listFilePull[$offsetPull])) {
                                        echo date('Y-m-d h:i:s') . " : Push to telco, Insert Pull Data & DR File Unlink - code 0 max - 5555 Success \n";
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
                                    "trxid" => $expldDataPull[6],
                                    "serviceId" => $expldDataPull[15],
                                    "sms" => $expldDataPull[12],
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
                                $smsPull = array(
                                    'telco' => "'$expldDataPull[0]'",
                                    'shortcode' => "'$expldDataPull[1]'",
                                    'msisdn' => "'$expldDataPull[2]'",
                                    'sms_field' => "'$expldDataPull[5]'",
                                    'id_app' => "'$expldDataPull[3]'",
                                    'keyword' => "'$expldDataPull[4]'",
                                    'content_number' => "'$expldDataPull[11]'",
                                    'content_field' => "'$expldDataPull[12]'",
                                    'trx_id' => "'$resTxridPull'",
                                    'trx_date' => "'$expldDataPull[7]'",
                                    'session_id' => "'$expldDataPull[8]'",
                                    'session_date' => "'$expldDataPull[9]'",
                                    'reg_type' => "'$expldDataPull[10]'",
                                    'type' => "'$expldDataPull[14]'",
                                    'cost' => "'$expldDataPull[15]'",
                                    'send_status' => "'$expldDataPull[16]'",
                                    'response_code' => "'$resCodePull'",
                                    'subject' => "'$expldDataPull[17]'",
                                    'date_create' => "'$now'"
                                );

                                $querySavePull = "INSERT INTO $tableName ( " . implode(', ', array_keys($smsPull)) . ") VALUES (" . implode(', ', array_values($smsPull)) . ")";
                                $exeSavePull = $this->dblog->query($querySavePull);

                                if ($exeSavePull->numRows() > 0) {
                                    if (unlink($pullFolder . "/" . $listFilePull[$offsetPull])) {
                                        echo date('Y-m-d h:i:s') . " : Push to telco, Insert Pull Data & DR File Unlink - code 0 min - 7777 Success \n";
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
