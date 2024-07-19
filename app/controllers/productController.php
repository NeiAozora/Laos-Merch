<?php

class ProductController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index(){
        var_dump($this->productModel->getProducts());
        // $this->view('productlist/index', $this->list());
    }

    // Display product details (assuming by ID)
    public function getProduct($id) {
        $product = $this->productModel->getProduct($id);
        $this->view('productdetail/index', ['product' => $product]);
    }

    // Display the list of products with search and pagination
    public function list() {
        // Get search query and pagination parameters from URL
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itemsPerPage = 8; // Number of items per page

        // Calculate offset for pagination
        $offset = ($page - 1) * $itemsPerPage;

        // Prepare search criteria
        $criteria = [
            'name' => $search
        ];

        // Get total number of products for pagination
        $totalProducts = count($this->productModel->getProducts($criteria));

        // Get paginated products
        $criteria['limit'] = $itemsPerPage;
        $criteria['offset'] = $offset;
        $products = $this->productModel->getProducts($criteria);

        // Calculate total pages
        $totalPages = ceil($totalProducts / $itemsPerPage);

        // Pass data to the view
        return [
            'products' => $products,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ];
    }
}
