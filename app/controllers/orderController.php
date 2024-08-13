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
                'Diproses' => 'Processing',
                'Dikirim' => 'Shipped',
                'Selesai' => 'Delivered',
                'Dibatalkan' => 'Cancelled'
            ];
    
            // Reverse mapping for displaying status in Indonesian
            $reverseStatusMapping = [
                null => 'Semua',
                'Processing' => 'Diproses',
                'Shipped' => 'Sedang Dikirim',
                'Delivered' => 'Selesai',
                'Cancelled' => 'Dibatalkan'
            ];
    
            $status = isset($_GET['status']) ? $_GET['status'] : 'Semua';
            $statusDb = $statusMapping[$status] ?? null;
    
            // Log status yang diterima
            error_log("Selected Status: " . $status);
            error_log("Mapped Status: " . $statusDb);
    
            $orders = $this->orderModel->getAllOrders($id_user, $statusDb);
    
            // Loop through orders to update the status to Indonesian
            foreach ($orders as &$order) {
                $order['status_name'] = $reverseStatusMapping[$order['status_name']] ?? 'Semua';
            }
            unset($order); // Unset reference to avoid unintended side effects

            view('order/index', ['orders' => $orders, 'status' => $status]);
        } else {
            view('404/index');
        }
    }
    
    
    
    public function detail($id){
        $id_user = $_SESSION['user']['id_user'] ?? null;

        if($id_user){
            $order = $this->orderModel->getOrderById($id, $id_user);
            d($order);
            die;
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
                    if ($this->orderModel->updateOrderStatus($id_order, $status)) {
                        // Status berhasil diubah, arahkan ulang ke halaman pesanan
                        header("Location: " . BASEURL . "order");
                        exit;
                    } else {
                        // Gagal memperbarui status di database
                        $_SESSION['error'] = 'Failed to update order status in database.';
                    }
                } else {
                    // Status tidak valid
                    $_SESSION['error'] = 'Invalid status received.';
                }
            } else {
                // Parameter tidak lengkap
                $_SESSION['error'] = 'Missing parameters: id_order or status';
            }
        } else {
            // Permintaan tidak sah atau metode permintaan tidak valid
            $_SESSION['error'] = 'Unauthorized request or invalid request method.';
        }

        // Jika terjadi kesalahan, arahkan ulang ke halaman pesanan dengan pesan kesalahan
        header("Location: " . BASEURL . "order");
        exit;
    }

    public function prepareOrder(){
        $user = AuthHelpers::getLoggedInUserData();

        // Check if the cart item belongs to the current user
        if (empty($user)){
            header('HTTP/1.1 403 Forbidden');
            echo json_encode(['error' => 'Forbidden']);
            exit();
        }

        OrderModel::new()->deleteOrdersByInterval($user['id_user'], '30m');

        
    }
}