<?php

use Phalcon\Mvc\Model\Query;

class UpdatecleanTask extends \Phalcon\CLI\Task {

    public function MainAction() {
        $toDay = date('Y_m_d');

        $tbMOToday = "SELECT * FROM tb_mo_today";
        $resultMOToday = $this->dblog->query($tbMOToday);
        $dataMOToday = $resultMOToday->fetchAll();

        $tableNameMo = "tb_mo_$toDay";
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

            $savetoDate = $this->dblog->query($querySaveMo);
            if ($savetoDate->numRows() > 0) {
                $queryDeleteMo = "DELETE FROM tb_mo_today WHERE id_mo = '" . $dMoT['id_mo'] . "'";
                $deleteTbMoDate = $this->dblog->query($queryDeleteMo);
                if ($deleteTbMoDate->numRows() > 0) {
                    echo date('Y-m-d h:i:s') . " : Move tb_mo_today to $tableNameMo success \n";
                }
            }
        }

        ///////////

        $tbPUSHToday = "SELECT * FROM tb_push_today";
        $resultPUSToday = $this->dblog->query($tbPUSHToday);
        $dataPUSHToday = $resultPUSToday->fetchAll();

        $tableNamePush = "tb_push_$toDay";
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
                    . "(telco,shortcode,msisdn,sms_field,keyword,trx_id,trx_date,session_id,session_date,reg_type) "
                    . "VALUES ('" . $dMoT['telco'] . "','" . $dMoT['shortcode'] . "','" . $dMoT['msisdn'] . "','" . $dMoT['sms_field'] . "','" . $dMoT['keyword'] . "','" . $dMoT['trx_id'] . "','" . $dMoT['trx_date'] . "','" . $dMoT['session_id'] . "','" . $dMoT['session_date'] . "','" . $dMoT['reg_type'] . "')";

            $savetoDatePush = $this->dblog->query($querySaveMo);
            if ($savetoDatePush->numRows() > 0) {
                $queryDeletePush = "DELETE FROM tb_push_today WHERE id_push = '" . $dPushT['id_push'] . "'";
                $deleteTbPushDate = $this->dblog->query($queryDeletePush);
                if ($deleteTbPushDate->numRows() > 0) {
                    echo date('Y-m-d h:i:s') . " : Move tb_push_today to $tableNamePush success \n";
                }
            }
        }


//        $tableMO = "tb_mo_$prevDay";
//        $tablePUSH = "tb_push_$prevDay";
//        $tableDR = "tb_dr_$prevDay";
//
//        $moveMO = "SELECT * FROM $tableMO";
//        $resultMO = $this->dblog->query($moveMO);
//        $dataMO = $resultMO->fetchAll();
    }

}
