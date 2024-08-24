<?php

class AdminController extends Controller{
    public function index(){
        $middleware = invokeClass(AdminMiddleware::class, 'checkLoginSession');
        $middleware();
        
        $this->view('admin/dashboard/index');
    }
}