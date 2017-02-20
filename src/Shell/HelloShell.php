<?php

namespace App\Shell;

use Cake\Console\Shell;

/**
 * Hello shell command.
 */
class HelloShell extends Shell {

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main() {
        $this->out('Hello world.');
    }

    public function heyThere($name = 'Anonymous') {
        $this->out('Hey there ' . $name);
    }

}
