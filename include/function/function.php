<?php

/**
 * Get all Records Function v2.0
 * Function To Get Latest all Form Database
 */

function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = 'DESC') {
    global $con;
    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;
}

/**
 * Get Latest Records Function v1.0
 * Function To Get Latest Category Form Database
 */

function getCat() {
    global $con;
    $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
    $getCat->execute();
    $cats = $getCat->fetchAll();
    return $cats;
}



/**
 * Get Ad Latest items Function v2.0
 * Function To Get  Ad Latest items Form Database
 */

function getItems($where, $value, $approve = NULL) {
    global $con;
    if ($approve == NULL) {
        $sql = 'AND Approve = 1';
    } else {
        $sql = NULL;
    }
    $getItems = $con->prepare("SELECT * 
                                FROM 
                                    items 
                                WHERE 
                                    $where = ?
                                $sql
                                ORDER BY 
                                    Item_ID 
                                DESC");
    $getItems->execute(array($value));
    $items = $getItems->fetchAll();
    return $items;
}

/**
   * check if user  is not activated
   * function to check the regstatus  of
   */
  function checkUserStatus($user) {
    global $con;
    $stmtx = $con->prepare("SELECT 
                            Username, RogStatus 
                          FROM 
                            user 
                          WHERE 
                            Username = ?
                          AND 
                            RogStatus = 0");
    $stmtx->execute(array($user));
    $status = $stmtx->rowCount();
    return $status;
  }

/**
 * Title Function That Echo Page Title In Case The Page 
 * Has The Variable $PageTitle And Echo Default  Title For Other Pages
 */

 function getTitle() {
     global $PageTitle;
     if (isset($PageTitle)) {
         echo $PageTitle;
     } else {
         echo 'Default';
     }
 }

 /**
     * Home Redirect Function v2.0
     * This Function Accept Parameters
     * $theMsg  = Echo The Error Message
     * $seconds = Seconds Before  Redirect
 */

 function redirectHome($theMsg, $url = null, $seconds = 3) {
    if ($url === null) {
        $url = 'index.php';
        $link = 'Homepage';
    } else {
        
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';
        } else {
            $url = 'index.php';
            $link = 'Homepage';
        }
    }
    echo $theMsg;
    echo "<div class='alert alert-info'> Yuo Will Be  Redirect to  $link  After $seconds Secondes. </div>";
    header("refresh:$seconds;url=$url");
    exit();
 }

/**
 * Check Items Function v1.0
 * Function to Check Item In Database [ Function Accept Parameter ]
 * $select = The Item To Select [ Example: User , Item Category ]
 * $from = The Table To Select From [ Example: User , Item Categories ] 
 * $value = The Value of Select [ Example: Yaser, Box Electrtions ] 
 */

function checkItem($select, $from, $value) {
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}


/**
 * Count Number Of Items Function v1.0
 * Function To Count Number Of Items Rows
 * $item = The Items To Count
 * $table = The Table To Count Form
 * Count User
 */

 function countItems($item, $table) {
    global $con;
    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();
 }

/**
 * Get Latest Records Function v1.0
 * Function To Get Latest Item Form Database [ User Items Comments]
 * $select = Field To Select
 * $table = The Table To Choose Form
 * $limit = Number Of Records To Get
 */

function getLatest($select, $table, $order, $limit = 5) {
    global $con;
    $stmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}

?>