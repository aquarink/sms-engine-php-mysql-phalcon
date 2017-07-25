<?php

use Phalcon\Mvc\Model\Query;

class RetrypushTask extends \Phalcon\CLI\Task {

    // php app/cli.php mo
    public function MainAction() {
        $tbDate = date('Y_m_d');
        //while (true) {
        try {
            $path = getcwd();
            //$path = '/var/www/html/engine';

            $pushTelcoFolder = $path . '/filesystem/push/';

            $checkOnToday = $this->dblog->query("SELECT * FROM tb_push_today WHERE send_status != 2");
            if ($checkOnToday->numRows() > 0 || !empty($checkOnToday->numRows())) {

                foreach ($checkOnToday->fetchAll() as $pushRetry) {
                    // SessionID
                    $sessionid = rand(1, 99999999);
                    //SessionDate
                    $sessionDate = date("Y-m-d h:i:s");

                    if ($pushRetry[13] == 'retry1') {
                        $regType = 'retry2';
                        $fields = explode(';', $pushRetry[18]);
                        $index = count($fields) - 1;
                        if (count($fields) > 3) {
                            $fields[$index] = 'RETRY2';
                            $subject = join(';', $fields);
                        } else {
                            $subject = $pushRetry[18] . ';RETRY2';
                        }
                    } else {
                        $regType = 'retry1';
                        $fields = explode(';', $pushRetry[18]);
                        $index = count($fields) - 1;
                        if (count($fields) > 3) {
                            $fields[$index] = 'RETRY1';
                            $subject = join(';', $fields);
                        } else {
                            $subject = $pushRetry[18] . ';RETRY1';
                        }
                    }

                    $contentPush = $pushRetry[1] . "|" . $pushRetry[2] . "|" . $pushRetry[3] . "|" . $pushRetry[5] . "|" . $pushRetry[6] . "|" . $pushRetry[4] . "||" . $pushRetry[10] . "|" . $sessionid . "|" . $sessionDate . "|" . $regType . "|" . $pushRetry[7] . "|" . $pushRetry[8] . "|1|push|" . $pushRetry[15] . "|1|" . $subject;

                    $pushTelco = $pushTelcoFolder . '/' . $pushRetry[1] . '/push';

                    if (!file_exists($pushTelco)) {
                        mkdir($pushTelco, 0777, true);
                    }
                    chmod($pushTelco, 0777);

                    $filePush = $pushTelco . '/retry-push-' . $sessionid . '.txt';

                    $createFilePush = fopen($filePush, "w");
                    if ($createFilePush) {
                        $fwPush = fwrite($createFilePush, $contentPush);
                        if ($fwPush) {
                            echo date('Y-m-d h:i:s') . " : Create retry push from tb_push_today success \n";
                        }
                    }
                }
            } else {
                $checkOnDate = $this->dblog->query("SELECT * FROM tb_push_$tbDate WHERE send_status != 2");
                if ($checkOnDate->numRows() > 0 || !empty($checkOnDate->numRows())) {

                    foreach ($checkOnDate->fetchAll() as $pushRetry) {
                        /// SessionID
                        $sessionid = rand(1, 99999999);
                        //SessionDate
                        $sessionDate = date("Y-m-d h:i:s");

                        if ($pushRetry[13] == 'retry1') {
                            $regType = 'retry2';
                            $fields = explode(';', $pushRetry[18]);
                            $index = count($fields) - 1;
                            if (count($fields) > 3) {
                                $fields[$index] = 'RETRY2';
                                $subject = join(';', $fields);
                            } else {
                                $subject = $pushRetry[18] . ';RETRY2';
                            }
                        } else {
                            $regType = 'retry1';
                            $fields = explode(';', $pushRetry[18]);
                            $index = count($fields) - 1;
                            if (count($fields) > 3) {
                                $fields[$index] = 'RETRY1';
                                $subject = join(';', $fields);
                            } else {
                                $subject = $pushRetry[18] . ';RETRY1';
                            }
                        }

                        $contentPush = $pushRetry[1] . "|" . $pushRetry[2] . "|" . $pushRetry[3] . "|" . $pushRetry[5] . "|" . $pushRetry[6] . "|" . $pushRetry[4] . "||" . $pushRetry[10] . "|" . $sessionid . "|" . $sessionDate . "|" . $regType . "|" . $pushRetry[7] . "|" . $pushRetry[8] . "|1|push|" . $pushRetry[15] . "|1|" . $subject;


                        $pushTelco = $pushTelcoFolder . '/' . $pushRetry[1] . '/push';

                        if (!file_exists($pushTelco)) {
                            mkdir($pushTelco, 0777, true);
                        }
                        chmod($pushTelco, 0777);

                        $filePush = $pushTelco . '/retry-push-' . $sessionid . '.txt';

                        $createFilePush = fopen($filePush, "w");
                        if ($createFilePush) {
                            $fwPush = fwrite($createFilePush, $contentPush);
                            if ($fwPush) {
                                echo date('Y-m-d h:i:s') . " : Create retry push from tb_push_$tbDate success \n";
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            echo date('Y-m-d h:i:s') . " : Error try catch $e \n";
        }
        //sleep(1);
        //}
    }

}
