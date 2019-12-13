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