<?php

class AdminController extends Controller{
    public function index(){
        $this->view('admin/dashboard/index');
    }
}