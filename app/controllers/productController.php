<?php

class ProductController extends Controller {
    private $productModel;
    private $reviewModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->reviewModel = new ReviewModel();
    }


    // Display product details (assuming by ID)
    public function getProduct($id) {
        $product = $this->productModel->getProduct($id);
        $reviews = $this->reviewModel->getReviewsByProductId($id);
        var_dump($reviews);
        $this->view('product/index', ['product' => $product, 'reviews' => $reviews]);
    }

    // Display the list of products with search and pagination
    public function list() {
        $offset = 0;
        $limit = 8;
    
        if(array_key_exists("page", $_GET)){
            if(is_numeric($_GET["page"])){
                $page = (int) $_GET["page"];
                $offset = ($page - 1) * $limit;
            }
        }
    
        $criteria = [
            "discontinued" => false,
            "limit" => $limit,
            "offset" => $offset
        ];
    
        if (array_key_exists("search", $_GET)){
            $searchValue = $_GET["search"];
            $criteria["name"] = $searchValue;
            // $criteria["category_name"] = $searchValue;
            // $criteria["tag"] = $searchValue;
        }
    
        $products = $this->productModel->getProducts($criteria, true);
    
        // Get total number of products
        $totalProducts = $this->productModel->getTotalProducts($criteria, true);
        $totalPages = ceil($totalProducts / $limit);
        $currentPage = $page ?? 1;
    
        header('Content-Type: application/json');

        echo json_encode([
            "total_pages" => $totalPages,
            "current_page" => $currentPage,
            "products" => $products
        ]);
    }
    
}
