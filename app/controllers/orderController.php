<?php

class OrderController extends Controller{
    private $orderModel;

    public function __construct(){
        $this->orderModel = new OrderModel();
    }

    public function index() {
        $id_user = $_SESSION['user']['uid'] ?? null;

        if (!$id_user) {
            header('Location: ' . BASEURL . 'login');
            exit();
        }

        $orders = $this->orderModel->getAllOrders($id_user);

        $order = null;
        if (isset($_GET['id_order'])) {
            $order = $this->orderModel->getOrderById($_GET['id_order'], $id_user);
        }

        requireView('order/index', ['orders' => $orders, 'order' => $order]);
    }
}