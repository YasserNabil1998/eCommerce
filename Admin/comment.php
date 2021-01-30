<?php

/**
 * ===============================================================
 * ==Manage comment Page
 * == You Can Approve | Edit | Delete Members From Here
 * ===============================================================
 */


session_start();
$PageTitle = 'Comments';

if(isset($_SESSION['Username'])) {

    include 'int.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage

    if($do == 'Manage') {  // Manage Page 
    
    //Select All User
        $stmt = $con->prepare("SELECT 
                                    comments.*, items.Name AS Item_Name, user.Username AS Member 
                               FROM 
                                    comments
                               INNER JOIN
                                    items
                               ON
                                    items.Item_ID = comments.item_id
                               INNER JOIN
                                    user
                               ON
                                    user.UserID = comments.user_id");
    //Execute the Statement
        $stmt->execute();
    
    //Assign To Variabole
        $rows = $stmt->fetchAll();
    
    ?>
        
        <h1 class="text-center">Manage comment</h1>
            <div class="container">       
            <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                <td>ID</td>
                <td>cmment</td>
                <td>Item Name</td>
                <td>User Name</td>
                <td>Added Data</td>
                <td>Control</td>
                </tr>
                <?php
                    foreach($rows as $row) {
                        echo "<tr>";                       
                            echo "<td>" . $row['c_id'] . "</td>";
                            echo "<td>" . $row['comment'] . "</td>";
                            echo "<td>" . $row['Item_Name'] . "</td>";
                            echo "<td>" . $row['Member'] . "</td>";
                            echo "<td>" . $row['comment_date'] . "</td>";
                            echo "<td>
                                    <a href='comment.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class=' fa fa-edit'></i> Edit</a>
                                    <a href='comment.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class=' fa fa-close'></i> Delete</a>";
                                    if ($row['status'] == 0) {
                                        echo "<a href='comment.php?do=Approve&comid=" . $row['c_id'] . "' class='btn btn-info Activate'><i class=' fa fa-check'></i> Approve </a>";
                                    }
                             echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            </div>
        </div>

  <?php  
    } elseif ($do == 'Edit') { //Edit Page 
       
      $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        //Select All Data Depend on This ID

        $stmt = $con->prepare("SELECT * From comments Where c_id = ? ");

        $stmt->execute(array($comid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        //if there such ID show form

        if($stmt->rowCount() > 0) { ?> 

                <h1 class="text-center">Edit comment</h1>
            <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="comid" value="<?php echo $comid ?>" />
                <!-- Start Username Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Comment</label>
                <div class="col-sm-10 col-md-4">
                    <textarea class="form-control" name="comment"><?php echo $row['comment']?></textarea>
                </div>
                </div>
                <!-- End Username Field -->
                <!-- Start submit Field -->
                <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                </div>
                </div>
                <!-- End submit Field -->
            </form>
            </div>

<?php 

    //else show error message

 } else {

    echo "<div class='container'>";

    $theMsg =  '<div class="alert alert-danger">Theres No Such ID</div>';

     redirectHome($theMsg);

    echo "</div>";
 }
 
    } elseif ($do == 'Update') { //Update Page

        echo "<h1 class='text-center'>Update comment</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Get Variable Form The Form 

            $comid    = $_POST['comid'];
            $comment  = $_POST['comment'];

                //Update The Database With This Info
                
                $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
                $stmt->execute(array($comment, $comid));

                //echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated </div>';
                redirectHome($theMsg, 'back');

        } else {

            $theMsg =  '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

            redirectHome($theMsg, 'back');
        }

        echo "</div>"; 

    }elseif ($do == 'Delete') {

        //Delete comment Page

        echo "<h1 class='text-center'> Delete Comment</h1>";
        echo "<div class='container'>";
             
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

            //Select All Data Depend on This ID

            $check = checkItem('c_id', 'comments', $comid);

            //if there such ID show form

            if($check  > 0) {

                $stmt = $con->prepare('DELETE FROM comments WHERE c_id = :zid');
                $stmt->bindParam(":zid", $comid);
                $stmt->execute();

                //echo success message
                $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted </div>';

                redirectHome($theMsg, 'back');

            }else {

                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

                redirectHome($theMsg);
            }

      echo "</div>";

    } elseif ($do == 'Approve') {
       
        //Activate Member Page

        echo "<h1 class='text-center'> Activate Comment</h1>";
        echo "<div class='container'>";
             
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

            //Select All Data Depend on This ID

            $check = checkItem('c_id', 'comments', $comid);

            //if there such ID show form

            if($check  > 0) {

                $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
                $stmt->execute(array($comid));

                //echo success message
                $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated </div>';

                redirectHome($theMsg, 'back');

            }else {

                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

                redirectHome($theMsg);
            }

      echo "</div>";
    }
    include $tpl  . 'footer.php';

}else{

    header('location: index.php');

    exit();
}

?>