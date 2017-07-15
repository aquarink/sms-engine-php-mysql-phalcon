<?php

use Phalcon\Mvc\Model\Query;

class MainTask extends \Phalcon\CLI\Task {

    // php app/cli.php
    public function MainAction() {
        while (true) {
            echo 'Main';
            sleep(1);
        }
    }

    // php app/cli.php main test world universe
    // test
    public function testAction(array $params) {
        // world
        echo sprintf('hello %s', $params[0]) . PHP_EOL;
        // universe
        echo sprintf('best regards, %s', $params[1]) . PHP_EOL;
    }

}
