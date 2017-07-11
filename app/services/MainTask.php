<?php

use Phalcon\Mvc\Model\Query;

class MainTask extends \Phalcon\CLI\Task {

    // php app/cli.php
    public function MainAction() {
        while (true) {
            echo 'Main';
            sleep(1);
        }
    }

    // php app/cli.php main mo
    // mo
    public function MoAction() {
        while (true) {
            try {
                $projectFolder = getcwd() . '/filesystem/mo';
                if ($handle = opendir($projectFolder)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != '.' && $entry != '..') {

                            // Read File
                            $theFile = fopen($projectFolder . "/" . $entry, "r");
                            if ($theFile) {
                                $dataFile = fread($theFile, filesize($projectFolder . "/" . $entry));
                                $expldData = explode("|", $dataFile);
                                //
                                $member = new TbMembers();
                                $moLog = new TbMoLog();

                                $checkQuery = "SELECT * FROM tb_members WHERE "
                                        . "telco = '$expldData[0]' AND "
                                        . "shortcode = '$expldData[1]' AND "
                                        . "msisdn = '$expldData[2]' AND "
                                        . "app = '$expldData[3]'";
                                $result = $this->db->query($checkQuery);

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
                                    'reg_types' => $expldData[9],
                                        )
                                );
                                if ($moLog->save()) {
                                    // Close Read File Session
                                    fclose($theFile);

                                    // Move File To App
                                    if (!file_exists(getcwd() . '/filesystem/app/' . $expldData[3])) {
                                        mkdir(getcwd() . '/filesystem/app/' . $expldData[3], 0777, true);
                                        if (rename($projectFolder . "/" . $entry, getcwd() . '/filesystem/app/' . $expldData[3] . '/' . $entry)) {
                                            echo date('Y-m-d h:i:s') . " : Insert Mo Log & Move Mo File if \n";
                                        } else {
                                            echo date('Y-m-d h:i:s') . " : Error move mo file if \n";
                                        }
                                    } else {
                                        if (rename($projectFolder . "/" . $entry, getcwd() . '/filesystem/app/' . $expldData[3] . '/' . $entry)) {
                                            echo date('Y-m-d h:i:s') . " : Insert Mo Log & Move Mo File else \n";
                                        } else {
                                            echo date('Y-m-d h:i:s') . " : Error move mo file else \n";
                                        }
                                    }
                                } else {
                                    echo date('Y-m-d h:i:s') . " : Error save mo log \n";
                                }
                            } else {
                                echo date('Y-m-d h:i:s') . " : Error openfile \n";
                            }
                        }
                    }
                    closedir($handle);
                } else {
                    echo date('Y-m-d h:i:s') . " : Error opendir \n";
                }
            } catch (\Exception $e) {
                echo date('Y-m-d h:i:s') . " : Error try catch \n";
            }
            sleep(1);
        }
    }

    // php app/cli.php main mo
    // mo
    public function DrAction() {
        while (true) {
            try {
                $projectFolder = getcwd() . '/filesystem/dr';
                if ($handle = opendir($projectFolder)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != '.' && $entry != '..') {

                            // Read File
                            $theFile = fopen($projectFolder . "/" . $entry, "r");
                            if ($theFile) {
                                $dataFile = fread($theFile, filesize($projectFolder . "/" . $entry));
                                $expldData = explode("|", $dataFile);
                                //xl|98900|6285966655260|987654320|2017-05-12|69242567|2|2017-07-11 10:19:25

                                $drLog = new TbDrLog();

                                $drLog->assign(array(
                                    'telco' => $expldData[0],
                                    'shortcode' => $expldData[1],
                                    'msisdn' => $expldData[2],
                                    'trx_id' => $expldData[3],
                                    'trx_date' => $expldData[4],
                                    'session_id' => $expldData[5],
                                    'session_date' => $expldData[7],
                                    'stat' => $expldData[6]
                                        )
                                );
                                if ($drLog->save()) {
                                    // Close Read File
                                    fclose($theFile);

                                    // Update tb_sms_push
                                    $updateQuery = "UPDATE tb_sms_push SET "
                                            . "send_status = '$expldData[6]' WHERE "
                                            . "trx_id = '$expldData[3]'";
                                    $result = $this->db->query($updateQuery);
                                    if ($result->numRows() > 0) {
                                        // Unlink File
                                        if (unlink($projectFolder . "/" . $entry)) {
                                            echo date('Y-m-d h:i:s') . " : Insert DR Log & DR File Unlink Success \n";
                                        } else {
                                            echo date('Y-m-d h:i:s') . " : Error Unlink DR File \n";
                                        }
                                    } else {
                                        echo date('Y-m-d h:i:s') . " : Error Update sms push \n";
                                    }
                                }
                            } else {
                                echo date('Y-m-d h:i:s') . " : Error openfile \n";
                            }
                        }
                    }
                    closedir($handle);
                } else {
                    echo date('Y-m-d h:i:s') . " : Error opendir \n";
                }
            } catch (\Exception $e) {
                echo date('Y-m-d h:i:s') . " : Error try catch $e \n";
            }
            sleep(1);
        }
    }

    // php app/cli.php main app
    // app
    public function AppAction() {
        while (true) {
            try {
                $appSystem = getcwd() . '/appsystem';
                if ($handle = opendir($appSystem)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != '.' && $entry != '..') {
                            include $appSystem . '/' . $entry;
                        }
                    }
                    closedir($handle);
                } else {
                    echo date('Y-m-d h:i:s') . " : Error opendir \n";
                }
            } catch (\Exception $e) {
                echo date('Y-m-d h:i:s') . " : Error try catch \n";
            }
            sleep(1);
        }
    }

    // php app/cli.php main test world universe
    // test
    public function testAction(array $params) {
        // world
        echo sprintf('hello %s', $params[0]) . PHP_EOL;
        // universe
        echo sprintf('best regards, %s', $params[1]) . PHP_EOL;
    }

}
