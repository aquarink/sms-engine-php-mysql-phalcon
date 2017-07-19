<?php

use Phalcon\Mvc\Model\Query;

class DrTask extends \Phalcon\CLI\Task {

    // php app/cli.php mo
    public function MainAction() {
        while (true) {
            try {
                $path = getcwd();
                //$path = '/var/www/html/engine';

                $projectFolder = $path . '/filesystem/dr';
                if ($handle = opendir($projectFolder)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != '.' && $entry != '..') {

                            // Read File
                            $theFile = fopen($projectFolder . "/" . $entry, "r");
                            if ($theFile) {
                                $dataFile = fread($theFile, filesize($projectFolder . "/" . $entry));
                                fclose($theFile);
                                if (unlink($projectFolder . "/" . $entry)) {
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
                                        // Update tb_sms_push
                                        $updateQuery = "UPDATE tb_sms_push SET "
                                                . "send_status = '$expldData[6]' WHERE "
                                                . "trx_id = '$expldData[3]'";
                                        $result = $this->db->query($updateQuery);
                                        if ($result->numRows() > 0) {
                                            unset($this->db);
                                            echo date('Y-m-d h:i:s') . " : Insert DR Log & DR File Unlink Success \n";
                                        } else {
                                            echo date('Y-m-d h:i:s') . " : Error Update sms push \n";
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
            sleep(1);
        }
    }

}
