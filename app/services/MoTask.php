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

                            if ($expldData[9] == "reg") {

//                                [0] => xl
//                                [1] => 912345
//                                [2] => 6281966655212
//                                [3] => bola
//                                [4] => reg bola
//                                [5] => 9922112212
//                                [6] => 2017-05-12
//                                [7] => 67396126
//                                [8] => 2017-07-19 01:01:58
//                                [9] => reg

                                $member = new TbMembers();
                                $moLog = new TbMoToday();

                                $checkQuery = "SELECT id_member FROM tb_members WHERE "
                                        . "telco = '$expldData[0]' AND "
                                        . "shortcode = '$expldData[1]' AND "
                                        . "msisdn = '$expldData[2]' AND "
                                        . "app = '$expldData[3]' AND "
                                        . "reg_types = '$expldData[9]'";
                                $result = $this->db->query($checkQuery);

                                // Close Read File Session
                                fclose($theFile);

                                // Check Keyword Exist
                                $keywordQuery = "SELECT * FROM tb_keyword WHERE "
                                        . "keyword = '$expldData[3]'";
                                $keywordResult = $this->db->query($keywordQuery);
                                $key = $keywordResult->numRows();

                                if ($key > 0) {
                                    // Ada keyword
                                    // Move File To App
                                    if (!file_exists($path . '/filesystem/app/' . $expldData[3])) {
                                        mkdir($path . '/filesystem/app/' . $expldData[3], 0777, true);
                                    }
                                    chmod($path . '/filesystem/app/' . $expldData[3], 0777);

                                    if (rename($projectFolder . "/" . $entry, $path . '/filesystem/app/' . $expldData[3] . '/' . $entry)) {

                                        // Insert New Member
                                        if ($result->numRows() == 0) {

                                            // Insert New Member
                                            $member->assign(array(
                                                'telco' => $expldData[0],
                                                'shortcode' => $expldData[1],
                                                'msisdn' => $expldData[2],
                                                'app' => $expldData[3],
                                                'join_date' => $expldData[8],
                                                'reg_types' => $expldData[9],
                                                    )
                                            );
                                            if ($member->save()) {
                                                echo date('Y-m-d h:i:s') . " : Insert Member Ok \n";
                                            }
                                        }

                                        // Insert Mo Log
                                        $moLog->assign(array(
                                            'telco' => $expldData[0],
                                            'shortcode' => $expldData[1],
                                            'msisdn' => $expldData[2],
                                            'sms_field' => $expldData[4],
                                            'keyword' => $expldData[3],
                                            'trx_id' => $expldData[5],
                                            'trx_date' => $expldData[6],
                                            'session_id' => $expldData[7],
                                            'session_date' => $expldData[8],
                                            'reg_type' => $expldData[9],
                                                )
                                        );
                                        if ($moLog->save()) {
                                            echo date('Y-m-d h:i:s') . " : Insert Mo Log & Move Mo File else \n";
                                        }
                                    }
                                } else {
                                    // Gak ada keyword
                                    // Move File To App                                        
                                    if (!file_exists($path . '/filesystem/app/other')) {
                                        mkdir($path . '/filesystem/app/other', 0777, true);
                                    }
                                    chmod($path . '/filesystem/app/other', 0777);

                                    if (rename($projectFolder . "/" . $entry, $path . '/filesystem/app/other/' . $entry)) {
                                        // Insert Mo Log
                                        $moLog->assign(array(
                                            'telco' => $expldData[0],
                                            'shortcode' => $expldData[1],
                                            'msisdn' => $expldData[2],
                                            'sms_field' => $expldData[4],
                                            'keyword' => $expldData[3],
                                            'trx_id' => $expldData[5],
                                            'trx_date' => $expldData[6],
                                            'session_id' => $expldData[7],
                                            'session_date' => $expldData[8],
                                            'reg_type' => $expldData[9],
                                                )
                                        );
                                        if ($moLog->save()) {
                                            echo date('Y-m-d h:i:s') . " : Wrong Keyword Insert Mo Log & Move Mo File else \n";
                                        }
                                    }
                                }
                            } else {
                                fclose($theFile);
                                $updateQuery = "UPDATE tb_members SET "
                                        . "reg_types = '$expldData[9]' WHERE "
                                        . "telco = '$expldData[0]' AND "
                                        . "shortcode = '$expldData[1]' AND "
                                        . "msisdn = '$expldData[2]' AND "
                                        . "app = '$expldData[3]";
                                $result = $this->db->query($updateQuery);
                                if ($result->numRows() > 0) {
                                    if (unlink($projectFolder . "/" . $entry)) {
                                        echo date('Y-m-d h:i:s') . " : Update ststus member to unreg Success \n";
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
