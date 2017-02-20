<?php

namespace App\Controller\Home;

use App\Controller\Home\AppController;

/**
 * Index Controller
 *  
 */
class ChatController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->viewBuilder()->autoLayout(FALSE);
    }

    public function index() {
        $this->response->cookie([
            'name' => 'foo',
            'value' => 'bar',
            'path' => '/',
            'expire' => time() + 24*60*60
        ]);
    }

}
