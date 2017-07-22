<?php

use Phalcon\Mvc\Model\Query;

class MoTask extends \Phalcon\CLI\Task {

    // php app/cli.php mo
    public function MainAction() {
        //while (true) {
        try {
            $path = getcwd();
            //$path = '/var/www/html/engine';

            $projectFolder = $path . '/filesystem/mo';

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
                            if ($expldData[9] == "unreg") {
//                                [0] => xl
//                                [1] => 912345
//                                [2] => 6281966655212
//                                [3] => bola
//                                [4] => reg bola
//                                [5] => 9922112212
//                                [6] => 2017-05-12
//                                [7] => 67396126
//                                [8] => 2017-07-19 01:01:58
//                                [9] => reg / non-reg

                                $pushFolder = $path . '/filesystem/push';
                                $updateQuery = "UPDATE tb_members SET "
                                        . "reg_types = '$expldData[9]' WHERE "
                                        . "telco = '$expldData[0]' AND "
                                        . "shortcode = '$expldData[1]' AND "
                                        . "msisdn = '$expldData[2]' AND "
                                        . "keyword = '$expldData[3]'";
                                $result = $this->db->query($updateQuery);

                                if ($result) {
                                    $keywordQuery = "SELECT id_app FROM tb_keyword WHERE "
                                            . "telco = '$expldData[0]' AND "
                                            . "shortcode = '$expldData[1]' AND "
                                            . "keyword = '$expldData[3]'";
                                    $keywordResult = $this->db->query($keywordQuery);
                                    $key = $keywordResult->fetchAll()[0];


                                    // App Config
                                    $appConfigQuery = "SELECT * FROM tb_app_config WHERE "
                                            . "id_app = '$key[id_app]'";
                                    $appConfigResult = $this->db->query($appConfigQuery);
                                    $appConfig = $appConfigResult->fetchAll()[0];

                                    // SessionID
                                    $sessionid = rand(1, 99999999);
                                    //SessionDate
                                    $sessionDate = date("Y-m-d h:i:s");

                                    $contentPush = $expldData[0] . '|' . $expldData[1] . '|' . $expldData[2] . '|' . $appConfig['id_app'] . '|' . $expldData[3] . '|' . $expldData[4] . '||' . $expldData[6] . '|' . $expldData[7] . '|' . $expldData[8] . '|' . $expldData[9] . '|0|Anda telah berhenti dari layanan ' . $expldData[3] . '|1|PUSH|' . $appConfig['cost_pull'] . '|1|PUSH;IOD;' . strtoupper($expldData[3]) . ';UNREG|' . $key['id_app'];

                                    $pushTelco = $pushFolder . '/' . $expldData[0] . '/pull';

                                    if (!file_exists($pushTelco)) {
                                        mkdir($pushTelco, 0777, true);
                                    }

                                    chmod($pushTelco, 0777);

                                    $filePush = $pushTelco . '/unreg-' . $sessionid . '.txt';

                                    $createFilePush = fopen($filePush, "w");
                                    if ($createFilePush) {
                                        $fwPush = fwrite($createFilePush, $contentPush);
                                        if ($fwPush) {
                                            if (unlink($projectFolder . "/" . $entry)) {
                                                echo date('Y-m-d h:i:s') . " : Create unreg push file & Update ststus member to unreg Success \n";
                                            }
                                        }
                                    }
                                }
                            } else {

//                                [0] => xl
//                                [1] => 912345
//                                [2] => 6281966655212
//                                [3] => bola
//                                [4] => reg bola
//                                [5] => 9922112212
//                                [6] => 2017-05-12
//                                [7] => 67396126
//                                [8] => 2017-07-19 01:01:58
//                                [9] => reg / non-reg

                                $moLog = new TbMoToday();

                                // Check Keyword Exist
                                $keywordQuery = "SELECT * FROM tb_keyword WHERE "
                                        . "keyword = '$expldData[3]'";
                                $keywordResult = $this->db->query($keywordQuery);
                                $key = $keywordResult->numRows();


                                if ($key > 0) {
                                    foreach ($keywordResult->fetchAll() as $kWord) {
                                        // Ada keyword
                                        // Move File To App
                                        if (!file_exists($path . '/filesystem/app/' . $expldData[3])) {
                                            mkdir($path . '/filesystem/app/' . $expldData[3], 0777, true);
                                        }
                                        chmod($path . '/filesystem/app/' . $expldData[3], 0777);

//                                    
                                        // Insert New Member
                                        $checkQuery = "SELECT * FROM tb_members WHERE "
                                                . "telco = '$expldData[0]' AND "
                                                . "shortcode = '$expldData[1]' AND "
                                                . "msisdn = '$expldData[2]' AND "
                                                . "keyword = '$expldData[3]' AND "
                                                . "reg_types = '$expldData[9]'";
                                        $result = $this->db->query($checkQuery);

                                        if ($result->numRows() == 0) {
                                            $member = new TbMembers();
                                            // Insert New Member
                                            $member->assign(array(
                                                'telco' => $expldData[0],
                                                'shortcode' => $expldData[1],
                                                'msisdn' => $expldData[2],
                                                'id_app' => $kWord['id_app'],
                                                'keyword' => $expldData[3],
                                                'join_date' => $expldData[8],
                                                'reg_types' => $expldData[9],
                                                    )
                                            );
                                            if ($member->save()) {
                                                echo date('Y-m-d h:i:s') . " : Insert Member $expldData[3] Ok \n";
                                            }
                                        }

                                        // Insert Mo Log
                                        $moLog->assign(array(
                                            'telco' => $expldData[0],
                                            'shortcode' => $expldData[1],
                                            'msisdn' => $expldData[2],
                                            'sms_field' => $expldData[4],
                                            'id_app' => $kWord['id_app'],
                                            'keyword' => $expldData[3],
                                            'trx_id' => $expldData[5],
                                            'trx_date' => $expldData[6],
                                            'session_id' => $expldData[7],
                                            'session_date' => $expldData[8],
                                            'reg_type' => $expldData[9],
                                                )
                                        );
                                        if ($moLog->save()) {
                                            if (rename($projectFolder . "/" . $entry, $path . '/filesystem/app/' . $expldData[3] . '/' . $entry)) {
                                                echo date('Y-m-d h:i:s') . " : Insert Mo Log $expldData[3] & Move Mo File else \n";
                                            }
                                        }
                                    }
                                } else {
                                    // Gak ada keyword
                                    // Move File To App                                        
                                    if (!file_exists($path . '/filesystem/app/other')) {
                                        mkdir($path . '/filesystem/app/other', 0777, true);
                                    }
                                    chmod($path . '/filesystem/app/other', 0777);


                                    // Insert Mo Log
                                    $moLog->assign(array(
                                        'telco' => $expldData[0],
                                        'shortcode' => $expldData[1],
                                        'msisdn' => $expldData[2],
                                        'sms_field' => $expldData[4],
                                        'id_app' => '',
                                        'keyword' => $expldData[3],
                                        'trx_id' => $expldData[5],
                                        'trx_date' => $expldData[6],
                                        'session_id' => $expldData[7],
                                        'session_date' => $expldData[8],
                                        'reg_type' => $expldData[9],
                                            )
                                    );
                                    if ($moLog->save()) {
                                        if (rename($projectFolder . "/" . $entry, $path . '/filesystem/app/other/' . $entry)) {
                                            echo date('Y-m-d h:i:s') . " : Wrong Keyword Insert Mo Log & Move Mo File else \n";
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
