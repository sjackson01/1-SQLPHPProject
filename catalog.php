<?php 
include("inc/functions.php");


$pageTitle = "Full Catalog";
$section = null;



if (isset($_GET["cat"])) {
    if ($_GET["cat"] == "books") {
        $pageTitle = "Books";
        $section = "books";
    } else if ($_GET["cat"] == "movies") {
        $pageTitle = "Movies";
        $section = "movies";
    } else if ($_GET["cat"] == "music") {
        $pageTitle = "Music";
        $section = "music";
    }
}


//If is set get page
if(isset($_GET["pg"])){
    //Filter and sanitize current page sent through $_GET
    $current_page = filter_input(INPUT_GET,"pg",FILTER_SANITIZE_NUMBER_INT);
}

//If current page is not set, set to one
if(empty($current_page)){
    $current_page = 1;
}

//Count total items in the $section array 
$total_items = get_catalog_count($section);

//Set default items per page to avoid divide by zero error 
$items_per_page = 8;

//Total number of pages divided by the items per page
//Return the next highest integer value rounding up value if necessary
//Using the ceil function
$total_pages = ceil($total_items / $items_per_page);
//Limit results in redirect
//By default we don't want to limit results so we initalize 
//Using and empty string

$limit_results = "";
//Add conditional to see if we are on a category page
if(!empty($section)){
    //If user chooses category we want to redirect them
    //Redirect them to that category page
    //& allows us to pass page number as well
    $limit_results = "cat=" . $section . "&";
}

//Rediret too-large page numbers to the last page
if($current_page > $total_pages){
    header("location:catalog.php?"
    //Category limit results
    . $limit_results 
    ."pg=" . $total_pages);
}

//Redirect too-small page numbers to the first page
if($current_page < $total_pages){
    header("location:catalog.php?" 
        //Category limit results
        . $limit_results 
        . "ph=1" . $total_pages
    );
}

//Determine the offset which is the number of items to skip
//For the current page for example on page 3 with 8 items per page
//The offset would be 16
$offset = ($current_page) - 1 * $items_per_page; 



//Category conditional
//Set limits and offset
if(empty($section)){
    //If no category is specified call the full catalog array
    //Pass $items_per_page = LIMIT and $offset = OFFSET 
    $catalog = full_catalog_array($items_per_page,$offset);
} else {
   //If the category = $section is not empty call category array
   $catalog = category_catalog_array($section,$item_per_page,$offset); 
}

include("inc/header.php"); ?>

<div class="section catalog page">
    
    <div class="wrapper">
        
        <h1><?php 
        if ($section != null) {
            echo "<a href='catalog.php'>Full Catalog</a> &gt; ";
        }
        echo $pageTitle; ?></h1>
        
        <ul class="items">
            <?php
            //For each dynamically generates html for catalog and categories
            foreach ($catalog as $item) {
                echo get_item_html($item);
            }
            ?>
        </ul>
        
    </div>
</div>

<?php include("inc/footer.php"); ?>