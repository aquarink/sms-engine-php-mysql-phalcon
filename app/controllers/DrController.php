<?php

class DrController extends \Phalcon\Mvc\Controller {

    public function indexAction() {
        echo 'DR Telco Not Found';
    }

    public function xlAction() {
        if (count($this->request->getQuery()) > 1) {
            $firstArray = $this->request->getQuery('_url');
            $expldUrl = explode('/', $firstArray);

            $telco = $expldUrl[2];
            $msisdn = $this->request->getQuery('msisdn');
            $trxid = $this->request->getQuery('trxid');
            $trxdate = $this->request->getQuery('trxdate');
            $shortcode = $this->request->getQuery('shortcode');
            $status = $this->request->getQuery('stat');

            $countryCode = substr($msisdn, 0, 2);
            if ($countryCode == '62') {
                $newMsisdn = $msisdn;
            } else {
                $newMsisdn = '62' . substr($msisdn, 1);
            }

            $sessionid = rand(1, 99999999);

            $projectFolder = 'sms-engine-php-mysql-1';
            $path = $_SERVER["DOCUMENT_ROOT"] . '/' . $projectFolder . '/filesystem/dr/';
            $file = $path . $sessionid . '.txt';

            $content = $telco . '|' . $shortcode . '|' . $newMsisdn . '|' . $trxid . '|' . $trxdate . '|' . $sessionid . '|' . $status . '|' . date('Y-m-d h:i:s');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                //
                $createFile = fopen($file, "w");
                if ($createFile) {
                    $fw = fwrite($createFile, $content);
                    if ($fw) {
                        echo 'Berhasil write Dr 1';
                    } else {
                        echo 'Gagal write Dr 1';
                    }
                } else {
                    echo 'Gagal open Dr 1';
                }
            } else {
                $createFile = fopen($file, "w");
                if ($createFile) {
                    $fw = fwrite($createFile, $content);
                    if ($fw) {
                        echo 'Berhasil write Dr 2';
                    } else {
                        echo 'Gagal write Dr 2';
                    }
                } else {
                    echo 'Gagal open Dr 2';
                }
            }
        } else {
            echo 'Kosong';
        }
    }

}
