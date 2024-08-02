<?php

class ProductController extends Controller {
    private $productModel;
    private $reviewModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->reviewModel = new ReviewModel();
    }


    public function getProduct($id) {
        // Retrieve product details
        $product = $this->productModel->get($id);
    
        // Initialize arrays to hold variations and combinations
        $productVariations = [];
        $productCombinations = [];
    
        // Retrieve product variations and combinations
        $VTs = VariationTypeModel::new()->get(["id_product", $id]);
        $productCombinations = VariationCombinationModel::new()->get(["id_product", $id]);
    
        // Loop through each variation type
        foreach ($VTs as $variationType) {
            // Retrieve variation options for each variation type
            $VOs = VariationOptionModel::new()->get(["id_variation_type", $variationType["id_variation_type"]]);
            
            // Add variation type to the variations array
            $variationType['variation_options'] = []; // Initialize the variation options array
            $productVariations[] = $variationType;
    
            // Loop through each variation option
            foreach ($VOs as $variationOption) {
                // Retrieve combination details for each variation option
                $CDs = CombinationDetailModel::new()->get(['id_option', $variationOption["id_option"]]);
                
                // Add combination details to the variation option
                $variationOption['combination_details'] = $CDs;
                
                // Add variation option to the current variation type
                $productVariations[count($productVariations) - 1]['variation_options'][] = $variationOption;
            }
        }
    
        // Retrieve reviews and discounts for the product
        $reviews = ReviewModel::new()->getReviewsByProductId($id);
        $discounts = DiscountProductModel::new()->getDiscountByProductId($id);
    
        // Prepare data for view
        $data = [
            'product' => $product,
            'productVariations' => $productVariations,
            'productCombinations' => $productCombinations,
            'reviews' => $reviews,
            'discounts' => $discounts
        ];
    
        d($data); // Debugging output
        die; // Terminate script execution
        // Pass data to view
        $this->view('product/index', $data);
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
