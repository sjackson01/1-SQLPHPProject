<?php
function full_catalog_array(){
    include("connections.php");
    try {
        //Specify a title and category query from media
           $results = $db->query("SELECT title, category, img FROM Media");
        } catch (Exception $e) {
            echo "Unable to retrieve results";
            exit;
        }
        
        //Store results statement object array in $catalog array
        $catalog = $results->fetchALL();
        return $catalog;
}

//Pass $id argument to select media id and attributes
function single_item_array($id){
    include("connections.php");
    try {
           $results = $db->query(
               "SELECT Media.media_id, title, category, img, 
               format, year, genre, publisher, isbn 
               FROM Media
               JOIN Genres ON Media.genre_id = Genres.genre_id
               LEFT OUTER JOIN Books ON Media.media_id = Books.media_id
               WHERE Media.media_id = $id"
           );
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "Unable to retrieve results";
            exit;
        }
        
        
        //Use fetch since we are only returing a single item array
        $catalog = $results->fetch();
        return $catalog;
}
//Test sing item array function to fetch item id one an all the selected attributes
var_dump(single_item_array(1));

function get_item_html($id,$item) {
    $output = "<li><a href='details.php?id="
        . $id . "'><img src='" 
        . $item["img"] . "' alt='" 
        . $item["title"] . "' />" 
        . "<p>View Details</p>"
        . "</a></li>";
    return $output;
}

function array_category($catalog,$category) {
    $output = array();
    
    foreach ($catalog as $id => $item) {
        if ($category == null OR strtolower($category) == strtolower($item["category"])) {
            $sort = $item["title"];
            $sort = ltrim($sort,"The ");
            $sort = ltrim($sort,"A ");
            $sort = ltrim($sort,"An ");
            $output[$id] = $sort;            
        }
    }
    
    asort($output);
    return array_keys($output);
}