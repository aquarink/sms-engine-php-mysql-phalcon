<?php

use Phalcon\Mvc\Model\Query;

class DailycleanTask extends \Phalcon\CLI\Task {

    public function MainAction() {
        $toDay = date('Y_m_d', strtotime(' -1 day'));


        ///////////
        // MO
        ///////////


        $tbMOToday = "SELECT * FROM tb_mo_$toDay";
        $resultMOToday = $this->dblog->query($tbMOToday);
        $dataMOToday = $resultMOToday->fetchAll();

        $tableNameMo = "tb_mo_summary";
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

        $tableNamePush = "tb_push_summary";
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
                PRIMARY KEY (id_push))";

            $this->dblog->query($createTablePush);
        }

        foreach ($dataPUSHToday as $dPushT) {
            $querySavePush = "INSERT INTO $tableNamePush "
                    . "(telco,shortcode,msisdn,sms_field,keyword,content_number,content_field,trx_id,trx_date,session_id,session_date,reg_type,type,send_status,response_code,subject) "
                    . "VALUES ('" . $dPushT['telco'] . "','" . $dPushT['shortcode'] . "','" . $dPushT['msisdn'] . "','" . $dPushT['sms_field'] . "','" . $dPushT['keyword'] . "','" . $dPushT['content_number'] . "','" . $dPushT['content_field'] . "','" . $dPushT['trx_id'] . "','" . $dPushT['trx_date'] . "','" . $dPushT['session_id'] . "','" . $dPushT['session_date'] . "','" . $dPushT['reg_type'] . "','" . $dPushT['type'] . "','" . $dPushT['send_status'] . "','" . $dPushT['response_code'] . "','" . $dPushT['subject'] . "')";

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

        $tableNameDr = "tb_dr_summary";
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
    }

}
