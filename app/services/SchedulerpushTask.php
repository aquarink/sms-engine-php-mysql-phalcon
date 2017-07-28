<?php

use Phalcon\Mvc\Model\Query;

class SchedulerpushTask extends \Phalcon\CLI\Task {

    public function MainAction() {
        $tbDate = date('Y_m_d');
        $monthDate = date('Y_m');
        $nowDate = date('Y-m-d');

        $path = getcwd();
        //$path = '/var/www/html/engine';

        $pushFolder = $path . '/filesystem/push';

        $appConf = "SELECT * FROM tb_app_config";
        $resAppConf = $this->db->query($appConf);
        $dataAppConf = $resAppConf->fetchAll();

        foreach ($dataAppConf as $appConfData) {
            $explPushTime = explode(',', $appConfData['push_day']);
            foreach ($explPushTime as $element) {
                if (is_numeric($element)) {
                    if ($element == number_format(date('d'))) {
                        $member = $this->db->query("SELECT * FROM tb_members WHERE id_app = '" . $appConfData['id_app'] . "'");
                        $memberData = $member->fetchAll();
                        foreach ($memberData as $memData) {
                            if (empty($memData['content_seq'])) {
                                $seqContent = 1;
                            } else {
                                $seqContent = $memData['content_seq'] + 1;
                            }
                            //
                            $checkSeqOnToday = $this->dblog->query("SELECT * FROM tb_push_today WHERE shortcode = '" . $memData['shortcode'] . "' AND msisdn = '" . $memData['msisdn'] . "' AND keyword = '" . $memData['keyword'] . "' AND content_number = '" . $memData['content_seq'] . "'");
                            if ($checkSeqOnToday->numRows() == 0 || empty($checkSeqOnToday->numRows())) {
                                
                                $callContent = $this->db->query("SELECT * FROM tb_apps_content WHERE id_app = '" . $memData['id_app'] . "' AND keyword = '" . $memData['keyword'] . "' AND content_number = '" . $seqContent . "'");
                                if ($callContent->numRows() > 0) {
                                    foreach ($callContent->fetchAll() as $cntent) {
                                        //SessionID
                                        $sessionid = rand(1, 99999999);
                                        //SessionDate
                                        $sessionDate = date("Y-m-d h:i:s");

                                        $contentPush = $memData['telco'] . "|" . $memData['shortcode'] . "|" . $memData['msisdn'] . "|" . $appConfData['id_app'] . "|" . $memData['keyword'] . "|DAILY PUSH " . strtoupper($memData['keyword']) . "||" . $nowDate . "|" . $sessionid . "|" . $sessionDate . "|dailypush|" . $cntent['content_number'] . "|" . $cntent['content_field'] . "|1|push|" . $appConfData['cost_push'] . "|1|PUSH;IOD;" . strtoupper($memData['keyword']) . ";DAILYPUSH";
                                        $pushTelco = $pushFolder . '/' . $memData['telco'] . '/push';

                                        if (!file_exists($pushTelco)) {
                                            mkdir($pushTelco, 0777, true);
                                        }
                                        chmod($pushTelco, 0777);

                                        $filePush = $pushTelco . '/daily-push-' . $sessionid . '.txt';

                                        $createFilePush = fopen($filePush, "w");
                                        if ($createFilePush) {
                                            $fwPush = fwrite($createFilePush, $contentPush);
                                            if ($fwPush) {
                                                echo date('Y-m-d h:i:s') . " : Create content $seqContent daily push file IF Date Numeric success \n";
                                            }
                                        }
                                    }
                                } else {
                                    $checkContent = $this->db->query("SELECT content_number FROM tb_apps_content WHERE 1 ORDER BY RAND() LIMIT 1");
                                    foreach ($callContent->fetchAll() as $valCntn) {
                                        $seqContent = rand($valCntn['content_number']);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($element == strtolower(date('D'))) {
                        $member = $this->db->query("SELECT * FROM tb_members WHERE id_app = '" . $appConfData['id_app'] . "'");
                        $memberData = $member->fetchAll();
                        foreach ($memberData as $memData) {
                            if (empty($memData['content_seq'])) {
                                $seqContent = 1;
                            } else {
                                $seqContent = $memData['content_seq'] + 1;
                            }
                            //
                            $checkSeqOnToday = $this->dblog->query("SELECT * FROM tb_push_today WHERE shortcode = '" . $memData['shortcode'] . "' AND msisdn = '" . $memData['msisdn'] . "' AND keyword = '" . $memData['keyword'] . "' AND content_number = '" . $memData['content_seq'] . "'");
                            if ($checkSeqOnToday->numRows() == 0 || empty($checkSeqOnToday->numRows())) {
                                $callContent = $this->db->query("SELECT * FROM tb_apps_content WHERE id_app = '" . $memData['id_app'] . "' AND keyword = '" . $memData['keyword'] . "' AND content_number = '" . $seqContent . "'");
                                if ($callContent->numRows() > 0) {
                                    foreach ($callContent->fetchAll() as $cntent) {
                                        //SessionID
                                        $sessionid = rand(1, 99999999);
                                        //SessionDate
                                        $sessionDate = date("Y-m-d h:i:s");

                                        $contentPush = $memData['telco'] . "|" . $memData['shortcode'] . "|" . $memData['msisdn'] . "|" . $appConfData['id_app'] . "|" . $memData['keyword'] . "|DAILY PUSH " . strtoupper($memData['keyword']) . "||" . $nowDate . "|" . $sessionid . "|" . $sessionDate . "|dailypush|" . $cntent['content_number'] . "|" . $cntent['content_field'] . "|1|push|" . $appConfData['cost_push'] . "|1|PUSH;IOD;" . strtoupper($memData['keyword']) . ";DAILYPUSH";
                                        $pushTelco = $pushFolder . '/' . $memData['telco'] . '/push';

                                        if (!file_exists($pushTelco)) {
                                            mkdir($pushTelco, 0777, true);
                                        }
                                        chmod($pushTelco, 0777);

                                        $filePush = $pushTelco . '/daily-push-' . $sessionid . '.txt';

                                        $createFilePush = fopen($filePush, "w");
                                        if ($createFilePush) {
                                            $fwPush = fwrite($createFilePush, $contentPush);
                                            if ($fwPush) {
                                                echo date('Y-m-d h:i:s') . " : Create content $seqContent daily push file IF Date Non-Numeric success \n";
                                            }
                                        }
                                    }
                                } else {
                                    $checkContent = $this->db->query("SELECT content_number FROM tb_apps_content WHERE 1 ORDER BY RAND() LIMIT 1");
                                    foreach ($callContent->fetchAll() as $valCntn) {
                                        $seqContent = rand($valCntn['content_number']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}
