<?php

class OrderController extends Controller{
    private $orderModel;

    public function __construct(){
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $id_user = $_SESSION['user']['id_user'] ?? null;

        if ($id_user) {
            $statusMapping = [
                'Semua' => null,
                'Diproses' => 'Pending',  
                'Dikirim' => 'Shipped',   
                'Selesai' => 'Delivered', 
                'Dibatalkan' => 'Cancelled'
            ];
            $status = isset($_GEt['status']) ? $_GET['status'] : 'Semua';
            $statusDb = $statusMapping[$status] ?? null;
            
            $orders = $this->orderModel->getAllOrders($id_user, $statusDb);

            view('order/index', ['orders' => $orders, 'status' => $status]);
        } else {
            view('404/index');
        }
    }

    public function detail(){
        $id_order = $_GET['id'] ?? null;

        if($id_order){
            $order = $this->orderModel->getOrderById($id_order);

            if($order){
                view('orderdetail/index', ['order' => $order]);
            }else{
                view('404/index');
            }
        }else{
            view('404/index');
        }
    }
}