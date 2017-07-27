<?php

use Phalcon\Mvc\Model\Query;

class DrTask extends \Phalcon\CLI\Task {

    // php app/cli.php mo
    public function MainAction() {
        while (true) {
            try {
                $path = getcwd();
                //$path = '/var/www/html/engine';

                $tbDate = date('Y_m_d');

                $projectFolder = $path . '/filesystem/dr';
                if ($handle = opendir($projectFolder)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != '.' && $entry != '..') {

                            // Read File
                            $theFile = fopen($projectFolder . "/" . $entry, "r");
                            if ($theFile) {
                                $dataFile = fread($theFile, filesize($projectFolder . "/" . $entry));
                                $expldData = explode("|", $dataFile);
                                fclose($theFile);

                                $checkTableDr = "SHOW TABLES LIKE 'tb_dr_today'";
                                $ckTabDr = $this->dblog->query($checkTableDr);
                                $tableDataDr = $ckTabDr->numRows();
                                if ($tableDataDr == 0) {
                                    $createTableDr = "CREATE TABLE tb_dr_today (
                                    id_dr INT(11) NOT NULL AUTO_INCREMENT,
                                    telco VARCHAR(20) DEFAULT NULL,
                                    shortcode VARCHAR(20) DEFAULT NULL,
                                    msisdn VARCHAR(20) DEFAULT NULL,
                                    trx_id VARCHAR(50) DEFAULT NULL,
                                    trx_date VARCHAR(50) DEFAULT NULL,
                                    session_id VARCHAR(50) DEFAULT NULL,
                                    session_date VARCHAR(50) DEFAULT NULL,
                                    stat VARCHAR(10) DEFAULT NULL,
                                    PRIMARY KEY (id_dr))";
                                    $this->dblog->query($createTableDr);
                                }

                                $checkQueryToday = "SELECT id_push FROM tb_push_today WHERE telco = '$expldData[0]' AND shortcode = '$expldData[1]' AND msisdn = '$expldData[2]' AND trx_id = '$expldData[3]'";
                                $resultToday = $this->dblog->query($checkQueryToday);
                                if ($resultToday->numRows() == 0 || empty($resultToday->numRows())) {
                                    $checkQueryDate = "SELECT id_push FROM tb_push_$tbDate WHERE telco = '$expldData[0]' AND shortcode = '$expldData[1]' AND msisdn = '$expldData[2]' AND trx_id = '$expldData[3]'";
                                    $resultDate = $this->dblog->query($checkQueryDate);
                                    if ($resultDate->numRows() > 0 || !empty($resultDate->numRows())) {
                                        $drLog = new TbDrToday();
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
                                            // Update tb_push_today
                                            $updateQuery = "UPDATE tb_push_$tbDate SET "
                                                    . "send_status = '$expldData[6]' WHERE "
                                                    . "trx_id = '$expldData[3]'";
                                            $result = $this->dblog->query($updateQuery);
                                            if ($result) {
                                                if (unlink($projectFolder . "/" . $entry)) {
                                                    echo date('Y-m-d h:i:s') . " : Insert DR Log & DR File Unlink On tb_push_$tbDate Success \n";
                                                }
                                            }
                                        }
                                    } else {
                                        echo date('Y-m-d h:i:s') . " : Error Pada DR pencaria content push untuk di update \n";
                                    }
                                } else {
                                    $drLog = new TbDrToday();
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
                                        // Update tb_push_today
                                        $updateQuery = "UPDATE tb_push_today SET "
                                                . "send_status = '$expldData[6]' WHERE "
                                                . "trx_id = '$expldData[3]'";
                                        $result = $this->dblog->query($updateQuery);
                                        if ($result) {
                                            if (unlink($projectFolder . "/" . $entry)) {
                                                echo date('Y-m-d h:i:s') . " : Insert DR Log & DR File Unlink On tb_push_today Success \n";
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
            sleep(1);
        }
    }

}
