<?php
namespace App\Controllers;

class LeaveController extends BaseController {
    public function index() {
        if (!auth_user()) {
            return $this->redirect('/login');
        }
        return $this->render('leave/index', [
            'activePage' => 'leave'
        ]);
    }
}
