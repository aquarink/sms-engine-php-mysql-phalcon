<?php

use Phalcon\Mvc\Model\Query;

class PushTask extends \Phalcon\CLI\Task {

    // php app/cli.php mo
    public function MainAction() {
        echo 'Main Push';
    }

    // php app/cli.php mo
    public function XlAction() {

        $telco = 'xl';

        while (true) {
            try {
                $path = getcwd();
                //$path = '/var/www/html/engine';

                $pushFolder = $path . '/filesystem/push/' . $telco;

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
                                parse_str($postResult, $responseArr);
//                                [error] => 0
//                                [trxid] => ID-578807425

                                $smsPush = new TbSmsPush();
                                if ($responseArr['error'] == 0) {
                                    // Push Berhasil
//                                    [0] => xl
//                                    [1] => 912345
//                                    [2] => 6285966655261
//                                    [3] => bola
//                                    [4] => reg bola
//                                    [5] =>
//                                    [6] => 2017-07-03
//                                    [7] => 535079 // session date
//                                    [8] => 2017-07-18 10:41:33
//                                    [9] => reg
//                                    [10] => 2
//                                    [11] => Reply Bola Kedua
//                                    [12] => 1
//                                    [13] => pull
//                                    [14] => 1000
//                                    [15] => 1
//                                    [16] => subject
//                                    
//                                    
                                    //if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                    $smsPush->assign(array(
                                        'telco' => $expldData[0],
                                        'shortcode' => $expldData[1],
                                        'msisdn' => $expldData[2],
                                        'sms_field' => $expldData[4],
                                        'keyword' => $expldData[3],
                                        'content_number' => $expldData[10],
                                        'content_field' => $expldData[11],
                                        'trx_id' => $responseArr['trxid'],
                                        'trx_date' => $expldData[6],
                                        'session_id' => $expldData[7],
                                        'session_date' => $expldData[8],
                                        'reg_type' => $expldData[9],
                                        'type' => $expldData[13],
                                        'cost' => $expldData[14],
                                        'send_status' => $expldData[15],
                                        'response_code' => $responseArr['error'],
                                        'subject' => $expldData[16]
                                            )
                                    );
                                    if ($smsPush->save()) {
                                        if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                            echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - max - Success \n";
                                        }
                                    }
                                    //}
                                } elseif ($responseArr['error'] == 3) {
                                    // Jika response error
                                } else {
                                    // dan response error lainnya
                                }
                            }
                        }
                    } else {
                        for ($offset = 0; $offset < $telcoConfig['push_limit']; $offset++) {
//                            // Read File
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
                                parse_str($postResult, $responseArr);
//                                [error] => 0
//                                [trxid] => ID-578807425

                                $smsPush = new TbSmsPush();
                                if ($responseArr['error'] == 0) {
                                    // Push Berhasil
//                                    [0] => xl
//                                    [1] => 912345
//                                    [2] => 6285966655261
//                                    [3] => bola
//                                    [4] => reg bola
//                                    [5] =>
//                                    [6] => 2017-07-03
//                                    [7] => 535079 // session date
//                                    [8] => 2017-07-18 10:41:33
//                                    [9] => reg
//                                    [10] => 2
//                                    [11] => Reply Bola Kedua
//                                    [12] => 1
//                                    [13] => pull
//                                    [14] => 1000
//                                    [15] => 1
//                                    [16] => subject   
//                                    
//                                                                                                      
                                    //if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                    $smsPush->assign(array(
                                        'telco' => $expldData[0],
                                        'shortcode' => $expldData[1],
                                        'msisdn' => $expldData[2],
                                        'sms_field' => $expldData[4],
                                        'keyword' => $expldData[3],
                                        'content_number' => $expldData[10],
                                        'content_field' => $expldData[11],
                                        'trx_id' => $responseArr['trxid'],
                                        'trx_date' => $expldData[6],
                                        'session_id' => $expldData[7],
                                        'session_date' => $expldData[8],
                                        'reg_type' => $expldData[9],
                                        'type' => $expldData[13],
                                        'cost' => $expldData[14],
                                        'send_status' => $expldData[15],
                                        'response_code' => $responseArr['error'],
                                        'subject' => $expldData[15]
                                            )
                                    );
                                    if ($smsPush->save()) {
                                        if (unlink($pushFolder . "/" . $listFile[$offset])) {
                                            echo date('Y-m-d h:i:s') . " : Push to telco, Insert Push Data & DR File Unlink - min - Success \n";
                                        }
                                    }
                                    //}
                                } elseif ($responseArr['error'] == 3) {
                                    // Jika response error
                                } else {
                                    // dan response error lainnya
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
