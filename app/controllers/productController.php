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
        $product = $this->productModel->get($id);
        $productVariations = [];
        $VTs = VariationTypeModel::new()->get(["id_product", $id]);
        $productCombinations = VariationCombinationModel::new()->get(["id_product", $id]);

        for($i = 0; $i < count($productCombinations); $i++ ){
            $productCombinations[$i]["combination_details"] = CombinationDetailModel::new()->get(["id_combination", $productCombinations[$i]['id_combination']]);
        }

        for ($a = 0; $a < count($VTs); $a++)
        {
            $VOs = VariationOptionModel::new()->get(["id_variation_type", $VTs[$a]["id_variation_type"]]);
            $productVariations[$a] = $VTs[$a];
            for($b = 0; $b < count($VOs); $b++)
            {
                
                $productVariations[$a]["variation_options"][$b] = $VOs[$b];
            }
            // $productVariationOption = VariationOptionModel::new()->get(["id_variation_type", $productVariationType["id_variation_type"]]);

        }

        $variationOptions = [];
        foreach($productVariations as $variation){
            foreach($variation["variation_options"] as $variationOption){
                $variationOptions[] = $variationOption;
            }
        }

        $reviews = ReviewModel::new()->getReviewsByProductId($id);
        $discount = DiscountProductModel::new()->getDiscountByProductId($id);
        $productImages = ProductImageModel::new()->get(['id_product', $id]);
        $data = [
            'product' => $product,
            'productVariations' => $productVariations,
            'variationOptions' => $variationOptions,
            'productCombinations' => $productCombinations,
            'productImages' => $productImages,
            'reviews' => $reviews,
            'discount' => $discount
        ];

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

        if (array_key_exists('category', $_GET)){
            $criteria["category_name"] = $searchValue;
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
