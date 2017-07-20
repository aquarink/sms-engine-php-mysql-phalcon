<?php

use Phalcon\Mvc\Model\Query;

class DailycleanTask extends \Phalcon\CLI\Task {

    public function MainAction() {
        $prevDay = date('Y_m_d', strtotime(' -1 day'));

        $tableMO = "tb_mo_$prevDay";
        $tablePUSH = "tb_push_$prevDay";
        $tableDR = "tb_dr_$prevDay";

        $moveMO = "SELECT * FROM $tableMO";
        $resultMO = $this->dblog->query($moveMO);
        $dataMO = $resultMO->fetchAll();
        
        
    }

}
