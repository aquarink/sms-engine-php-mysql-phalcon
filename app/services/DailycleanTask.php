<?php

use Phalcon\Mvc\Model\Query;

class DailycleanTask extends \Phalcon\CLI\Task {

    public function MainAction() {
        //$toDay = date('Y_m_d');
        $toDay = date('Y_m_d', strtotime(' -1 day'));
        $thisMonth = date('Y_m');
        $now = date('Y-m-d');
        $nowFull = date("Y-m-d h:i:s");

        ///////////
        // MO
        ///////////


        $tbMOToday = "SELECT * FROM tb_mo_$toDay";
        $resultMOToday = $this->dblog->query($tbMOToday);
        $dataMOToday = $resultMOToday->fetchAll();

        $tableNameMo = "tb_mo_summary_$thisMonth";
        $checkTableMo = "SHOW TABLES LIKE '$tableNameMo'";

        $ckTabMo = $this->dblog->query($checkTableMo);
        $tableDataMo = $ckTabMo->numRows();

        if ($tableDataMo == 0) {
            $createTableMo = "CREATE TABLE $tableNameMo (
                id_mo INT(11) NOT NULL AUTO_INCREMENT,
                telco VARCHAR(20) DEFAULT NULL,
                shortcode VARCHAR(20) DEFAULT NULL,
                msisdn VARCHAR(20) DEFAULT NULL,
                sms_field VARCHAR(200) DEFAULT NULL,
                keyword VARCHAR(50) DEFAULT NULL,
                trx_id VARCHAR(50) DEFAULT NULL,
                trx_date VARCHAR(50) DEFAULT NULL,
                session_id VARCHAR(50) DEFAULT NULL,
                session_date VARCHAR(50) DEFAULT NULL,
                reg_type VARCHAR(10) DEFAULT NULL,
                PRIMARY KEY (id_mo))";

            $this->dblog->query($createTableMo);
        }

        foreach ($dataMOToday as $dMoT) {
            $querySaveMo = "INSERT INTO $tableNameMo "
                    . "(telco,shortcode,msisdn,sms_field,keyword,trx_id,trx_date,session_id,session_date,reg_type) "
                    . "VALUES ('" . $dMoT['telco'] . "','" . $dMoT['shortcode'] . "','" . $dMoT['msisdn'] . "','" . $dMoT['sms_field'] . "','" . $dMoT['keyword'] . "','" . $dMoT['trx_id'] . "','" . $dMoT['trx_date'] . "','" . $dMoT['session_id'] . "','" . $dMoT['session_date'] . "','" . $dMoT['reg_type'] . "')";

            $saveMotoDate = $this->dblog->query($querySaveMo);
            if ($saveMotoDate->numRows() > 0) {
//                $queryDeleteMo = "DELETE FROM tb_mo_$toDay WHERE id_mo = '" . $dMoT['id_mo'] . "'";
//                $deleteTbMoDate = $this->dblog->query($queryDeleteMo);
//                if ($deleteTbMoDate->numRows() > 0) {
                echo date('Y-m-d h:i:s') . " : Copy data tb_mo_$toDay to $tableNameMo success \n";
//                }
            }
        }


        ///////////
        // PUSH
        ///////////


        $tbPUSHToday = "SELECT * FROM tb_push_$toDay";
        $resultPUSToday = $this->dblog->query($tbPUSHToday);
        $dataPUSHToday = $resultPUSToday->fetchAll();

        $tableNamePush = "tb_push_summary_$thisMonth";
        $checkTablePush = "SHOW TABLES LIKE '$tableNamePush'";

        $ckTabPush = $this->dblog->query($checkTablePush);
        $tableDataPush = $ckTabPush->numRows();

        if ($tableDataPush == 0) {
            $createTablePush = "CREATE TABLE $tableNamePush (
                id_push INT(11) NOT NULL AUTO_INCREMENT,
                telco VARCHAR(20) DEFAULT NULL,
                shortcode VARCHAR(20) DEFAULT NULL,
                msisdn VARCHAR(20) DEFAULT NULL,
                sms_field VARCHAR(200) DEFAULT NULL,
                id_app INT(11) DEFAULT NULL,
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
                date_create VARCHAR(20) DEFAULT NULL,
                PRIMARY KEY (id_push))";

            $this->dblog->query($createTablePush);
        }

        foreach ($dataPUSHToday as $dPushT) {
            $querySavePush = "INSERT INTO $tableNamePush "
                    . "(telco,shortcode,msisdn,sms_field,id_app,keyword,content_number,content_field,trx_id,trx_date,session_id,session_date,reg_type,type,cost,send_status,response_code,subject,date_create) "
                    . "VALUES ('" . $dPushT['telco'] . "','" . $dPushT['shortcode'] . "','" . $dPushT['msisdn'] . "','" . $dPushT['sms_field'] . "','" . $dPushT['id_app'] . "','" . $dPushT['keyword'] . "','" . $dPushT['content_number'] . "','" . $dPushT['content_field'] . "','" . $dPushT['trx_id'] . "','" . $dPushT['trx_date'] . "','" . $dPushT['session_id'] . "','" . $dPushT['session_date'] . "','" . $dPushT['reg_type'] . "','" . $dPushT['type'] . "','" . $dPushT['cost'] . "','" . $dPushT['send_status'] . "','" . $dPushT['response_code'] . "','" . $dPushT['subject'] . "','" . $now . "')";

            $savePushtoDatePush = $this->dblog->query($querySavePush);
            if ($savePushtoDatePush->numRows() > 0) {
//                $queryDeletePush = "DELETE FROM tb_push_$toDay WHERE id_push = '" . $dPushT['id_push'] . "'";
//                $deleteTbPushDate = $this->dblog->query($queryDeletePush);
//                if ($deleteTbPushDate->numRows() > 0) {
                echo date('Y-m-d h:i:s') . " : Copy data tb_push_$toDay to $tableNamePush success \n";
//                }
            }
        }


        ///////////
        // DR
        ///////////


        $tbDRToday = "SELECT * FROM tb_dr_$toDay";
        $resultDRToday = $this->dblog->query($tbDRToday);
        $dataDRToday = $resultDRToday->fetchAll();

        $tableNameDr = "tb_dr_summary_$thisMonth";
        $checkTableDr = "SHOW TABLES LIKE '$tableNameDr'";

        $ckTabDr = $this->dblog->query($checkTableDr);
        $tableDataDr = $ckTabDr->numRows();

        if ($tableDataDr == 0) {
            $createTableDr = "CREATE TABLE $tableNameDr (
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

        foreach ($dataDRToday as $dDrT) {
            $querySaveDr = "INSERT INTO $tableNameDr "
                    . "(telco,shortcode,msisdn,trx_id,trx_date,session_id,session_date,stat) "
                    . "VALUES ('" . $dDrT['telco'] . "','" . $dDrT['shortcode'] . "','" . $dDrT['msisdn'] . "','" . $dDrT['trx_id'] . "','" . $dDrT['trx_date'] . "','" . $dDrT['session_id'] . "','" . $dDrT['session_date'] . "','" . $dDrT['stat'] . "')";

            $saveDrtoDate = $this->dblog->query($querySaveDr);
            if ($saveDrtoDate->numRows() > 0) {
//                $queryDeleteDr = "DELETE FROM tb_dr_$toDay WHERE id_dr = '" . $dDrT['id_dr'] . "'";
//                $deleteTbDrDate = $this->dblog->query($queryDeleteDr);
//                if ($deleteTbDrDate->numRows() > 0) {
                echo date('Y-m-d h:i:s') . " : Copy data tb_dr_$toDay to $tableNameDr success \n";
//                }
            }
        }



        /////////////////
        // REPORT SUMMARY
        ////////////////

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
                        'report_create' => $nowFull,
                            )
                    );
                    if ($reportPush->save()) {
                        echo date('Y-m-d h:i:s') . " : Insert report push Ok \n";
                    }
                }
            }
        } else {
            // Pindahin dari tb_push_today ke tb_push_$toDay
            $tbPUSHToday2 = "SELECT * FROM tb_push_today";
            $resultPUSToday2 = $this->dblog->query($tbPUSHToday2);
            $dataPUSHToday2 = $resultPUSToday2->fetchAll();

            $tableNamePush2 = "tb_push_$toDay";
            $checkTablePush2 = "SHOW TABLES LIKE '$tableNamePush2'";

            $ckTabPush2 = $this->dblog->query($checkTablePush2);
            $tableDataPush2 = $ckTabPush2->numRows();

            if ($tableDataPush == 0) {
                $createTablePush = "CREATE TABLE $tableNamePush2 (
                id_push INT(11) NOT NULL AUTO_INCREMENT,
                telco VARCHAR(20) DEFAULT NULL,
                shortcode VARCHAR(20) DEFAULT NULL,
                msisdn VARCHAR(20) DEFAULT NULL,
                sms_field VARCHAR(200) DEFAULT NULL,
                id_app INT(11) DEFAULT NULL,
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
                date_create VARCHAR(20) DEFAULT NULL,
                PRIMARY KEY (id_push))";

                $this->dblog->query($createTablePush2);
            }

            foreach ($dataPUSHToday2 as $dPushT2) {
                $querySavePush2 = "INSERT INTO $tableNamePush2 "
                        . "(telco,shortcode,msisdn,sms_field,id_app,keyword,content_number,content_field,trx_id,trx_date,session_id,session_date,reg_type,type,cost,send_status,response_code,subject,date_create) "
                        . "VALUES ('" . $dPushT2['telco'] . "','" . $dPushT2['shortcode'] . "','" . $dPushT2['msisdn'] . "','" . $dPushT2['sms_field'] . "','" . $dPushT2['id_app'] . "','" . $dPushT2['keyword'] . "','" . $dPushT2['content_number'] . "','" . $dPushT2['content_field'] . "','" . $dPushT2['trx_id'] . "','" . $dPushT2['trx_date'] . "','" . $dPushT2['session_id'] . "','" . $dPushT2['session_date'] . "','" . $dPushT2['reg_type'] . "','" . $dPushT2['type'] . "','" . $dPushT2['cost'] . "','" . $dPushT2['send_status'] . "','" . $dPushT2['response_code'] . "','" . $dPushT2['subject'] . "','" . $now . "')";
                $savePushtoDatePush2 = $this->dblog->query($querySavePush2);
                if ($savePushtoDatePush2->numRows() > 0) {
                    $queryDeletePush2 = "DELETE FROM tb_push_today WHERE id_push = '" . $dPushT2['id_push'] . "'";
                    $deleteTbPushDate2 = $this->dblog->query($queryDeletePush2);
                    if ($deleteTbPushDate2->numRows() > 0) {
                        echo date('Y-m-d h:i:s') . " : Move tb_push_today to $tableNamePush2 on daily daily success \n";
                    }
                }
            }

            //
            $tbPUSHPrev = "SELECT * FROM tb_push_$toDay";
            $resultPUSPrev = $this->dblog->query($tbPUSHPrev);
            $dataPUSHPrev = $resultPUSPrev->fetchAll();
            //
            foreach ($dataPUSHPrev as $dPush) {

                $querySummary = "SELECT id_app,telco,shortcode,type,cost,subject, IF(send_status = '2', 'Delivered','Rejected') AS dr_stat, COUNT(id_push) AS total,date_create,send_status,response_code
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
                        'report_create' => $nowFull,
                            )
                    );
                    if ($reportPush->save()) {
                        echo date('Y-m-d h:i:s') . " : Insert report push else Ok \n";
                    }
                }
            }
        }
    }

}
