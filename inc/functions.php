<?php
function full_catalog_array(){
    include("connections.php");
    try {
        //Specify a title and category query from media
           $results = $db->query("SELECT media_id, title, category, img FROM Media");
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
            //Similar to query only it doesn't run the query right away
            //Creating a prepared statement 
           $results = $db->prepare(
               "SELECT Media.media_id, title, category, img, 
               format, year, genre, publisher, isbn 
               FROM Media
               JOIN Genres ON Media.genre_id = Genres.genre_id
               LEFT OUTER JOIN Books ON Media.media_id = Books.media_id
               /* repalce id argument with un-named place holder ? */ 
               WHERE Media.media_id = ?"
           );
           //Call method on the $result PDO object to run the query
           //1 is the first place holder (?)
           //$id is the variable we would like to replace (?)
           //PDO:: PARAM_INT is the statit method to turn all input into an int 
           $results->bindParam(1,$id,PDO::PARAM_INT);
           //Execute the pepared statement
           $results->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "Unable to retrieve results";
            exit;
        }
        //Call fetch method to retrieve item information for the 
        //One product that matches the ID
        $item = $results->fetch();
        if(empty($item)) return $item;

        try {
           $results = $db->prepare(
               "SELECT fullname, role
               FROM Media_People
               JOIN People ON Media_People.people_id = People.people_id
               WHERE Media_People.people_id = ?"
           );
           $results->bindParam(1,$id,PDO::PARAM_INT);
           $results->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "Unable to retrieve results";
            exit;
        }
        //Change $catalog to item since we are only returning one item
        return $item;
}

function get_item_html($id,$item) {
    $output = "<li><a href='details.php?id="
        /* Instead of $id we use the $item["media_id"]*/
        . $item["media_id"] . "'><img src='" 
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