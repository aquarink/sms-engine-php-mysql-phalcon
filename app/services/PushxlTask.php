<?php

use Phalcon\Mvc\Model\Query;

class PushxlTask extends \Phalcon\CLI\Task {

    // php app/cli.php push
    public function MainAction() {
        $telco = 'xl';

        //while (true) {
        try {
            $path = getcwd();
            //$path = '/var/www/html/engine';

            $pushFolder = $path . '/filesystem/push/' . $telco;

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

            if ($handle = opendir($pushFolder)) {
                //Telco Config
                $telcoQuery = "SELECT * FROM tb_telco_config WHERE "
                        . "telco_name = '$telco'"
                        . "ORDER BY id_telco DESC LIMIT 1";
                $result = $this->db->query($telcoQuery);
                $telcoConfig = $result->fetchAll()[0];

                // Read File Limit
                $filesArr = scandir($pushFolder);
                $listFile = array_splice($filesArr, 2);

                if (count($listFile) <= $telcoConfig['push_limit']) {
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

                            $smsPush = array(
                                'telco' => "'$expldData[0]'",
                                'shortcode' => "'$expldData[1]'",
                                'msisdn' => "'$expldData[2]'",
                                'sms_field' => "'$expldData[4]'",
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
                                        echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - code 0 max - Success \n";
                                    }
                                } else {
                                    // dan response error lainnya
                                    $sessionid = rand(1, 99999999);
                                    $reTryPath = $path . '/filesystem/retry/' . $telco . '/';
                                    $reFile = $reTryPath . $sessionid . '-retry.txt';

                                    $reContent = implode('|', array_values($expldData)) . ';RETRY1';

                                    if (!file_exists($reTryPath)) {
                                        mkdir($reTryPath, 0777, true);
                                    }

                                    chmod($path, 0777);

                                    $createReTryFile = fopen($reFile, "w");
                                    if ($createReTryFile) {
                                        $ReTryfw = fwrite($createReTryFile, $reContent);
                                        if ($ReTryfw) {
                                            fclose($createReTryFile);
                                            if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                                echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - code else 0 max - Success \n";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    for ($offset = 0; $offset < $telcoConfig['push_limit']; $offset++) {
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

                            $smsPush = array(
                                'telco' => "'$expldData[0]'",
                                'shortcode' => "'$expldData[1]'",
                                'msisdn' => "'$expldData[2]'",
                                'sms_field' => "'$expldData[4]'",
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
                                        echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - code 0 min - Success \n";
                                    }
                                } else {
                                    // dan response error lainnya
                                    $sessionid = rand(1, 99999999);
                                    $reTryPath = $path . '/filesystem/retry/' . $telco . '/';
                                    $reFile = $reTryPath . $sessionid . '-retry.txt';

                                    $reContent = implode('|', array_values($expldData)) . ';RETRY1';

                                    if (!file_exists($reTryPath)) {
                                        mkdir($reTryPath, 0777, true);
                                    }

                                    chmod($path, 0777);

                                    $createReTryFile = fopen($reFile, "w");
                                    if ($createReTryFile) {
                                        $ReTryfw = fwrite($createReTryFile, $reContent);
                                        if ($ReTryfw) {
                                            fclose($createReTryFile);
                                            if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                                echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - code else 0 min - Success \n";
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
        } catch (\Exception $e) {
            echo date('Y-m-d h:i:s') . " : Error try catch $e \n";
        }
        //sleep(1);
        //}
    }

}
