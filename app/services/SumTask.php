<?php

use Phalcon\Mvc\Model\Query;

class SumTask extends \Phalcon\CLI\Task {

    public function MainAction() {
        $toDay = date('Y_m_d', strtotime(' -1 day'));
        //$toDay = date('Y_m_d');
        $now = date("Y-m-d h:i:s");

        ///////////
        // PUSH
        ///////////

        $checkEmpty = $this->dblog->query("SELECT id_push FROM tb_push_today");
        if ($checkEmpty->numRows() == 0 || empty($checkEmpty->numRows())) {
            //
            $tbPUSHPrev = "SELECT * FROM tb_push_$toDay";
            $resultPUSPrev = $this->dblog->query($tbPUSHPrev);
            $dataPUSHPrev = $resultPUSPrev->fetchAll();
            //
            foreach ($dataPUSHPrev as $dPush) {

                $querySummary = "SELECT id_app,telco,shortcode,type,cost,subject, IF(send_status = '2', 'Deliver','Rejected') AS dr_stat, COUNT(id_push) AS total,date_create,send_status,response_code
                FROM tb_push_$toDay WHERE
                telco = '" . $dPush['telco'] . "' AND shortcode = '" . $dPush['shortcode'] . "' AND type = '" . $dPush['type'] . "' AND cost = '" . $dPush['cost'] . "' AND send_status = '" . $dPush['send_status'] . "' AND response_code = '" . $dPush['response_code'] . "' AND subject = '" . $dPush['subject'] . "'
                GROUP BY telco,shortcode,id_app,type,cost,send_status,response_code,subject";

                $resultSummary = $this->dblog->query($querySummary);
                $dataSummary = $resultSummary->fetchAll();

                foreach ($dataSummary as $dataInsert) {
//                    [id_app] => 2
//                    [0] => 2
//                    [telco] => xl
//                    [1] => xl
//                    [shortcode] => 912345
//                    [2] => 912345
//                    [TYPE] => push
//                    [3] => push
//                    [cost] => 1000
//                    [4] => 1000
//                    [SUBJECT] => PUSH;IOD;BOLA;RETRY1
//                    [5] => PUSH;IOD;BOLA;RETRY1
//                    [dr_stat] => Deliver
//                    [6] => Deliver
//                    [total] => 1
//                    [7] => 1
//                    [date_create] => 2017-07-25
//                    [8] => 2017-07-25
//                    [send_status] => 1
//                    [9] => 1
//                    [response_code] => 3
//                    [10] => 3

                    $reportPush = new TbReportPush();
                    $reportPush->assign(array(
                        'id_app' => $dataInsert['id_app'],
                        'telco' => $dataInsert['telco'],
                        'shortcode' => $dataInsert['shortcode'],
                        'type' => $dataInsert['type'],
                        'cost' => $dataInsert['cost'],
                        'subject' => $dataInsert['subject'],
                        'dr_status' => $dataInsert['send_status'],
                        'hit_status' => $dataInsert['response_code'],
                        'stat' => $dataInsert['dr_stat'],
                        'total' => $dataInsert['total'],
                        'report_date' => $dataInsert['date_create'],
                        'report_create' => $now,
                            )
                    );
                    if ($reportPush->save()) {
                        echo date('Y-m-d h:i:s') . " : Insert report push Ok \n";
                    }
                }
            }
        } else {
            echo 'ada';
        }
    }

}
