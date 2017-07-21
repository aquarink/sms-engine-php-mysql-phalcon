<?php

class MoController extends \Phalcon\Mvc\Controller {

    public function indexAction() {
        echo 'DR Telco Not Found';
    }

    public function xlAction() {
        if (count($this->request->getQuery()) > 1) {
            $firstArray = $this->request->getQuery('_url');
            $expldUrl = explode('/', $firstArray);

            $telco = $expldUrl[2];
            $msisdn = $this->request->getQuery('msisdn');
            $sms = $this->request->getQuery('sms');
            $trxid = $this->request->getQuery('trxid');
            $trxdate = $this->request->getQuery('trxdate');
            $shortcode = $this->request->getQuery('shortcode');

            $countryCode = substr($msisdn, 0, 2);
            if ($countryCode == '62') {
                $newMsisdn = $msisdn;
            } else {
                $newMsisdn = '62' . substr($msisdn, 1);
            }

            $smsExpld = explode(' ', $sms);
            
            if(strtolower($smsExpld[0]) == 'reg') {
                $sataus = 'reg';
                $keyword = $smsExpld[1];
            } else {
                $sataus = '';
                $keyword = $smsExpld[0];
            }

            $sessionid = rand(1, 99999999);

            $projectFolder = $_SERVER['DOCUMENT_ROOT'] . "/sms-engine-php-mysql-1";
            //$projectFolder = '/var/www/html/engine';
            
            $path = $projectFolder . '/filesystem/mo/';
            $file = $path . $sessionid . '.txt';

            $content = $telco . '|' . $shortcode . '|' . $newMsisdn . '|' . $keyword . '|' . $sms . '|' . $trxid . '|' . $trxdate . '|' . $sessionid . '|' . date('Y-m-d h:i:s') . '|' . $sataus;

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            chmod($path, 0777);

            $createFile = fopen($file, "w");
            if ($createFile) {
                $fw = fwrite($createFile, $content);
                if ($fw) {
                    echo 'ok';
                } else {
                    echo 'Gagal write Mo 2';
                }
            } else {
                echo 'Gagal open Mo 2';
            }
        } else {
            echo 'Kosong';
        }
    }

}
