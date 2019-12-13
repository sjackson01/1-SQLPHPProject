<?php
//Use defualt parameter ($category = null)
//Allows passing a category as a argument to count the array
//Passing no argument to count the full catalog
function get_catalog_count($category = null){
        $category = strlower($category);
        include("connections.php");
        try{
            //Count all the items in the database
            $spl = "SELECT COUNT(media_id) FROM Media";
                if(!empty($category)){
                //Add results to a PDO statement object
                $result = $db->prepare($sq 
                . " WHERE LOWER(category) = ?"
            );
                $result->bindParam(1,$category, PDO::PARAM_STR);
                }else{
                $result = $db->prepare($sql);
            }
             //Execute outside of if statement so that it always runs
             $result->execute();
        }catch(exception $e){
            echo "bad query";
        }
        //Assign a single column to a count method with method Column
        // 0 indicates the first column 
        $count = $result = $result->fetchColumn(0);
        //Return the count
        return $count;
}
function full_catalog_array(){
    include("connections.php");
    try {
        //Specify a title and category query from media
           $results = $db->query("
           SELECT media_id, title, category, img 
           FROM Media
           ORDER BY
           REPLACE(
               REPLACE(
                 REPLACE(title, 'The ', ''),
                    'An ', 
                    ''
                    ),
                 'A ',
                 ''
                 )"
            );
        } catch (Exception $e) {
            echo "Unable to retrieve results";
            exit;
        }
        
        //Store results statement object array in $catalog array
        $catalog = $results->fetchALL();
        return $catalog;
}
//Takes category as argument
function category_catalog_array($category){
    include("connections.php");
    //Insure that category passed and matched is lowercase
    $category = strtolower($category);
    try {
           $results = $db->prepare(
          "SELECT media_id, title, category, img 
           FROM Media 
           WHERE LOWER(category) = ?
           /*Order by title and replace The with empty string */
           ORDER BY
           REPLACE(
               REPLACE(
                 REPLACE(title, 'The ', ''),
                    'An ', 
                    ''
                    ),
                 'A ',
                 ''
                 )"
        );
        //Bind and specify the data type
        $results->bindParam(1,$category,PDO::PARAM_STR);
        $results->execute();
        } catch (Exception $e) {
            echo "Unable to retrieve results";
            exit;
        }
        
        //Store results statement object array in $catalog array
        $item = $results->fetchALL();
        return $item;
}

function random_catalog_array(){
    include("connections.php");
    try {
           $results = $db->query(
               "SELECT media_id, title, category, img 
                FROM Media
                ORDER BY RANDOM()
                LIMIT 4"
               );
        } catch (Exception $e) {
            echo "Unable to retrieve results";
            exit;
        }
        
        //Store results statement object array in $catalog array
        $item = $results->fetchALL();
        return $item;
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
               WHERE Media_People.media_id = ?"
           );
           $results->bindParam(1,$id,PDO::PARAM_INT);
           $results->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "Unable to retrieve results";
            exit;
        }
        //Fetch every row one at a time by using a while loop
        //The first itteration will add the first person and rol
        //To the variable $row
        //If fetch returns an empty value or reaches the end
        //$row will be set to false ending the loop 
        while($row = $results->fetch(PDO::FETCH_ASSOC)) {
            /* Add interal array to the item array variable */ 
            /* People will be assigned to arrays based on their role */ 
            $item[$row["role"]][] = $row["fullname"]; 
        }
        //Change $catalog to item since we are only returning one item
        return $item;
}

function get_item_html($item) {
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