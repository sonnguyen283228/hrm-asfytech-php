<?php
namespace App\Controllers;

class TimekeepingController extends BaseController {
    public function index() {
        if (!auth_user()) {
            return $this->redirect('/login');
        }
        return $this->render('timekeeping/index', [
            'activePage' => 'timekeeping'
        ]);
    }
}
