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
            $status = isset($_GET['status']) ? $_GET['status'] : 'Semua';
            $statusDb = $statusMapping[$status] ?? null;

            // Log status yang diterima
            error_log("Selected Status: " . $status);
            error_log("Mapped Status: " . $statusDb);

            $orders = $this->orderModel->getAllOrders($id_user, $statusDb);

            view('order/index', ['orders' => $orders, 'status' => $status]);
        } else {
            view('404/index');
        }
    }

    public function detail($id){
        $id_user = $_SESSION['user']['id_user'] ?? null;

        if($id_user){
            $order = $this->orderModel->getOrderById($id, $id_user);

            if($order){
                view('orderdetail/index', ['order' => $order]);
            }else{
                view('404/index');
            }
        }else{
            view('404/index');
        }
    }


    public function updateStatus(){
        $id_user = $_SESSION['user']['id_user'] ?? null;

        if($id_user && $_SERVER['REQUEST_METHOD'] === 'POST'){
            $id_order = $_POST['id_order'] ?? null;
            $status = $_POST['status'] ?? null;

            if($id_order && $status){
                $arrStatus = ['Pending', 'Shipped', 'Delivered', 'Cancelled'];
                if(in_array($status, $arrStatus)){
                    $UpdateStatus = $this->orderModel->updateOrderStatus($id_order, $status);

                    if($UpdateStatus){
                        echo 'Berhasil';
                    }else{
                        echo 'Gagal';
                    }
                }else{
                    echo 'Status Invalid';
                }
            }else{
                echo 'Tidak Sesuai Param';
            }
        }else{
            echo 'Tidak Login';
        }
    }
}