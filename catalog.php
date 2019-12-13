<?php 
include("inc/functions.php");


$pageTitle = "Full Catalog";
$section = null;
//Set items to page to 8
$item_per_page = 8;

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
//Total number of pages divided by the items per page
//Return the next highest integer value rounding up value if necessary
//Using the ceil function
$total_page = ceil($total_items / $items_per_page);

//Determine the offset which is the number of items to skip
//For the current page for example on page 3 with 8 items per page
//The offset would be 16
$offset = ($current_page) - 1 * $items_per_page; 



//Category conditional
if(empty($section)){
    //If no category is specified call the full catalog array
    $catalog = full_catalog_array();
} else {
   //If the category = $section is not empty call category array
   $catalog = category_catalog_array($section); 
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