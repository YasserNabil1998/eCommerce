<?php

ob_start();
session_start();
$PageTitle = 'Items';

if(isset($_SESSION['Username'])) {

    include 'int.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

    //Select All User
        $stmt = $con->prepare("SELECT items .*, categories.Name AS Category_name, user.Username FROM items
                                INNER JOIN categories ON categories.ID = items.Cat_ID
                                INNER JOIN user ON user.UserID = items.Member_ID");
    //Execute the Statement
        $stmt->execute();
    
    //Assign To Variabole
        $items = $stmt->fetchAll();
    
    ?>
        
        <h1 class="text-center">Manage Items</h1>
            <div class="container">       
            <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                <td>#ID</td>
                <td>Name</td>
                <td>Description</td>
                <td>Price</td>
                <td>Adding Date</td>
                <td>Category</td>
                <td>Username</td>
                <td>Control</td>
                </tr>
                <?php
                    foreach($items as $item) {
                        echo "<tr>";                       
                            echo "<td>" . $item['item_ID'] . "</td>";
                            echo "<td>" . $item['Name'] . "</td>";
                            echo "<td>" . $item['Description'] . "</td>";
                            echo "<td>" . $item['Price'] . "</td>";
                            echo "<td>" . $item['Add_Date'] . "</td>";
                            echo "<td>" . $item['Category_name'] . "</td>";
                            echo "<td>" . $item['Username'] . "</td>";
                            echo "<td>
                                    <a href='items.php?do=Edit&itemid=" . $item['item_ID'] . "' class='btn btn-success'><i class=' fa fa-edit'></i> Edit</a>
                                    <a href='items.php?do=Delete&itemid=" . $item['item_ID'] . "' class='btn btn-danger confirm'><i class=' fa fa-close'></i> Delete</a>";
                                    if ($item['Approve'] == 0) {
                                        echo "<a 
                                        href='items.php?do=Approve&itemid=" . $item['item_ID'] . "'
                                         class='btn btn-info Activate'>
                                         <i class=' fa fa-check'></i> Approve </a>";
                                    }
                             echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-sm btn-primary"
            ><i class="fa fa-plus"></i>  New Item</a
            >
        </div>

  <?php 

    } elseif ($do == 'Add') {  ?>

        <h1 class="text-center">Add New Item</h1>
        <div class="container">
        <form class="form-horizontal" action="?do=Insert" method="POST">
            <!-- Start Name Field -->
            <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-4">
                <input
                type="text"
                name="name"
                class="form-control"
                required="required"
                placeholder="Name of The Items"
                />
            </div>
            </div>
            <!-- End Name Field -->      
             <!-- Start Description Field -->
             <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-4">
                <input
                type="text"
                name="description"
                class="form-control"
                required="required"
                placeholder="Description of The Items"
                />
            </div>
            </div>
            <!-- End Description Field -->  
            <!-- Start Price Field -->
            <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Price</label>
            <div class="col-sm-10 col-md-4">
                <input
                type="text"
                name="price"
                class="form-control"
                required="required"
                placeholder="Price of The Items"
                />
            </div>
            </div>
            <!-- End Price Field -->     
            <!-- Start Country Field -->
            <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Country</label>
            <div class="col-sm-10 col-md-4">
                <input
                type="text"
                name="country"
                class="form-control"
                required="required"
                placeholder="Country of Made"
                />
            </div>
            </div>
            <!-- End Country Field --> 
             <!-- Start Status Field -->
             <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-10 col-md-4">
               <select  name="Status">
                <option value="0">...</option>
                <option value="1">New</option>
                <option value="2">Like New</option>
                <option value="3">Used</option>
                <option value="4">Vary Old</option>
               </select>
            </div>
            </div>
            <!-- End Status Field --> 
            <!-- Start MEMBER Field -->
            <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Member</label>
            <div class="col-sm-10 col-md-4">
               <select name="member">
                <option value="0">...</option>
                    <?php
                        $allMember = getAllFrom("*", "user", "", "", "UserID");
                        foreach ($allMember as $user) {
                            echo "<option value='" . $user['UserID'] . "'>"  . $user['Username'] . "</option>";
                        }
                    ?>
               </select>
            </div>
            </div>
            <!-- Start MEMBER Field -->
            <!-- Start categories Field -->
            <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">category</label>
            <div class="col-sm-10 col-md-4">
               <select name="category">
                <option value="0">...</option>
                    <?php
                    $allCat = getAllFrom("*", "categories", "where parent = 0", "", "ID");
                        foreach ($allCat as $cat) {
                            echo "<option value='" . $cat['ID'] . "'>"  . $cat['Name'] . "</option>";
                            $childCat = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
                            foreach ($childCat as $child) {
                                echo "<option value='" . $child['ID'] . "'>---"  . $child['Name'] . "</option>";
                            }
                        }
                    ?>
               </select>
            </div>
            </div>
            <!-- Start categories Field -->
            <!-- Start Tags Field -->
            <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Tags</label>
            <div class="col-sm-10 col-md-4">
                <input
                type="text"
                name="tags"
                class="form-control"
                placeholder="Separate Tags With Comma(,)"
                />
            </div>
            </div>
            <!-- End Tags Field -->  
            <!-- Start submit Field -->
            <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Add Items" class="btn btn-primary btn-lg" />
            </div>
            </div>
            <!-- End submit Field -->
        </form>
        </div>
<?php

    } elseif ($do == 'Insert') {

         if($_SERVER['REQUEST_METHOD'] == 'POST') {

            
            echo "<h1 class='text-center'>Update Items</h1>";
            echo "<div class='container'>";
    
                //Get Variable Form The Form 
    
                $name     = $_POST['name'];
                $desc     = $_POST['description'];
                $price    = $_POST['price'];
                $country  = $_POST['country'];
                $status   = $_POST['Status'];
                $member   = $_POST['member'];
                $cat      = $_POST['category'];
                $tags      = $_POST['tags'];
                $formErrors = array();

                if(empty($name)) {
                    $formErrors[] =  'Name Can\'t be<strong> Empty</strong>';
                }
    
                if(empty($desc)) {
                    $formErrors[] =  'Desc Can\'t be<strong> Empty</strong>';
                }
    
                if (empty($price)) {
                    $formErrors[] =  'Price  Can\'t be<strong> Empty</strong>';
                }
    
                if (empty($country)) {
                    $formErrors[] =  'Country Can\'t be<strong> Empty</strong>';
                }
    
                if ($status === 0) {
                    $formErrors[] = 'you Must Choose the<strong> Status</strong>';
                }

                if ($member === 0) {
                    $formErrors[] = 'you Must Choose the<strong> Member</strong>';
                }

                if ($cat === 0) {
                    $formErrors[] = 'you Must Choose the<strong> Category</strong>';
                }
    
                foreach($formErrors as $error) {
    
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }
    
                //Check If There's No Error Proceed Update Operation
    
                if(empty($formErrors)) {
    
                    //Insert Userinfo In Database
    
                   $stmt = $con->prepare("INSERT INTO 
                                            items(Name,
                                                  Description,
                                                  Price,
                                                  Country_Made,
                                                  Status,
                                                  Add_Date,
                                                  Cat_ID,
                                                  Member_ID,
                                                  tags)                                                                                                                                           
                                        VALUES(:zname,
                                               :zdesc,
                                               :zprice,
                                               :zcountry,
                                               :zstatus,
                                                now(),
                                                :zcat,
                                                :zmember,
                                                :ztags)");
                    $stmt->execute(array(
                        'zname'     => $name ,
                        'zdesc'     => $desc,
                        'zprice'    => $price,
                        'zcountry'  => $country,
                        'zstatus'   => $status,
                        'zmember'   => $member,
                        'zcat'      => $cat,
                        'ztags'      => $tags
                       
                    ));

                    //echo success message
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted </div>';
    
                        redirectHome($theMsg, 'back');
                }    
    
            } else {
    
                echo "<div class='container'>";
    
                $theMsg =  '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
                redirectHome($theMsg);
    
                echo "</div>";
            }
    
            echo "</div>";

    } elseif ($do == 'Edit')  {

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        //Select All Data Depend on This ID

        $stmt = $con->prepare("SELECT * From items Where item_ID = ?");

        $stmt->execute(array($itemid));
        $item = $stmt->fetch();
        $count = $stmt->rowCount();

        //if there such ID show form

        if($stmt->rowCount() > 0) { ?> 

        <h1 class="text-center">Edit Item</h1>
        <div class="container">
        <form class="form-horizontal" action="?do=Update" method="POST">
           <input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
            <!-- Start Name Field -->
            <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-4">
                <input
                type="text"
                name="name"
                class="form-control"
                required="required"
                placeholder="Name of The Items"
                value="<?php echo $item['Name'] ?>"
                />
            </div>
            </div>
            <!-- End Name Field -->      
             <!-- Start Description Field -->
             <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-4">
                <input
                type="text"
                name="description"
                class="form-control"
                required="required"
                placeholder="Description of The Items"
                value="<?php echo $item['Description'] ?>"
                />
            </div>
            </div>
            <!-- End Description Field -->  
            <!-- Start Price Field -->
            <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Price</label>
            <div class="col-sm-10 col-md-4">
                <input
                type="text"
                name="price"
                class="form-control"
                required="required"
                placeholder="Price of The Items"
                value="<?php echo $item['Price'] ?>"
                />
            </div>
            </div>
            <!-- End Price Field -->     
            <!-- Start Country Field -->
            <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Country</label>
            <div class="col-sm-10 col-md-4">
                <input
                type="text"
                name="country"
                class="form-control"
                required="required"
                placeholder="Country of Made"
                value="<?php echo $item['Country_Made'] ?>"
                />
            </div>
            </div>
            <!-- End Country Field --> 

             <!-- Start Status Field -->
             <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-10 col-md-4">
               <select  name="Status">
                <option value="0">...</option>
                <option value="1" <?php if($item['Status'] == 1) {echo 'selected';}  ?>>New</option>
                <option value="2" <?php if($item['Status'] == 2) {echo 'selected';}  ?>>Like New</option>
                <option value="3" <?php if($item['Status'] == 3) {echo 'selected';}  ?>>Used</option>
                <option value="4" <?php if($item['Status'] == 4) {echo 'selected';}  ?>>Vary Old</option>
               </select>
            </div>
            </div>
            <!-- End Status Field --> 
             <!-- Start MEMBER Field -->
             <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Member</label>
            <div class="col-sm-10 col-md-4">
               <select name="member">
                    <?php
                        $stmt = $con->prepare("SELECT * FROM user");
                        $stmt->execute();
                        $users = $stmt->fetchAll();
                        foreach ($users as $user) {
                            echo "<option value='" . $user['UserID'] . "'"; 
                            if($item['Member_ID'] == $user['UserID']) {echo 'selected';}  
                            echo">"  . $user['Username'] . "</option>";
                        }
                    ?>
               </select>
            </div>
            </div>
            <!-- Start MEMBER Field -->
            <!-- Start categories Field -->
            <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">category</label>
            <div class="col-sm-10 col-md-4">
               <select name="category">
                    <?php
                        $stmt2 = $con->prepare("SELECT * FROM categories");
                        $stmt2->execute();
                        $cats = $stmt2->fetchAll();
                        foreach ($cats as $cat) {
                            echo "<option value='" . $cat['ID'] . "'";
                            if($item['Cat_ID'] == $cat['ID']) {echo 'selected';}  
                            echo">"  . $cat['Name'] . "</option>";
                        }
                    ?>
               </select>
            </div>
            </div>
            <!-- Start categories Field -->
                        <!-- Start Tags Field -->
                        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Tags</label>
            <div class="col-sm-10 col-md-4">
                <input
                type="text"
                name="tags"
                class="form-control"
                placeholder="Separate Tags With Comma(,)"
                value="<?php echo $item['tags'] ?>"
                />
            </div>
            </div>
            <!-- End Tags Field --> 
            <!-- Start submit Field -->
            <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Save Items" class="btn btn-primary btn-lg" />
            </div>
            </div>
            <!-- End submit Field -->
        </form>
             <!---form--->
            <?php
        //Select All User
                $stmt = $con->prepare("SELECT 
                                            comments.*, user.Username AS Member 
                                    FROM 
                                            comments
                                    INNER JOIN
                                            user
                                    ON
                                            user.UserID = comments.user_id
                                    WHERE item_id = ?");
            //Execute the Statement
                $stmt->execute(array($itemid));
            
            //Assign To Variabole
                $rows = $stmt->fetchAll();
        if (! empty($rows)) {
    ?>
        <h1 class="text-center">Manage {<?php echo $item['Name'] ?>} comment</h1>      
            <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                <td>comment</td>
                <td>User Name</td>
                <td>Added Data</td>
                <td>Control</td>
                </tr>
                <?php
                    foreach($rows as $row) {
                        echo "<tr>";                       
                            echo "<td>" . $row['comment'] . "</td>";
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
                <?php }?>
  <?php  
        
    //else show error message

 } else {

    echo "<div class='container'>";

    $theMsg =  '<div class="alert alert-danger">Theres No Such ID</div>';

     redirectHome($theMsg);

    echo "</div>";
 }

    } elseif ($do == 'Update') {

        echo "<h1 class='text-center'>Update Item</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Get Variable Form The Form 

            $id          = $_POST['itemid'];
            $name        = $_POST['name'];
            $desc        = $_POST['description'];
            $price       = $_POST['price'];
            $country     = $_POST['country'];
            $Status      = $_POST['Status'];
            $member      = $_POST['member'];
            $cat         = $_POST['category']; 
            $tags        = $_POST['tags']; 
            //Validate the form

            $formErrors = array();

            if(empty($name)) {
                $formErrors[] =  'Name Can\'t be<strong> Empty</strong>';
            }

            if(empty($desc)) {
                $formErrors[] =  'Desc Can\'t be<strong> Empty</strong>';
            }

            if (empty($price)) {
                $formErrors[] =  'Price  Can\'t be<strong> Empty</strong>';
            }

            if (empty($country)) {
                $formErrors[] =  'Country Can\'t be<strong> Empty</strong>';
            }

            if ($Status === 0) {
                $formErrors[] = 'you Must Choose the<strong> Status</strong>';
            }

            if ($member === 0) {
                $formErrors[] = 'you Must Choose the<strong> Member</strong>';
            }

            if ($cat === 0) {
                $formErrors[] = 'you Must Choose the<strong> Category</strong>';
            }

            foreach($formErrors as $error) {

                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            //Check If There's No Error Proceed Update Operation

            if(empty($formErrors)) {

                //Update The Database With This Info
                
                    $stmt = $con->prepare("UPDATE
                                              items 
                                          SET 
                                              Name = ?,
                                              Description = ?,
                                              Price = ?,
                                              Country_Made = ?,
                                              Status = ?,
                                              Cat_ID = ?,
                                              Member_ID = ?,
                                              tags = ?
                                          WHERE
                                              item_ID = ?");

                    $stmt->execute(array($name, $desc, $price, $country, $Status, $cat, $member,$tags, $id));

                //echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated </div>';

                    redirectHome($theMsg, 'back');
            }

        } else {

            $theMsg =  '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

            redirectHome($theMsg);
        }

        echo "</div>"; 

    } elseif ($do == 'Delete') {

        //Delete item Page

        echo "<h1 class='text-center'> Delete Item</h1>";
        echo "<div class='container'>";
             
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            //Select All Data Depend on This ID

            $check = checkItem('item_ID', 'items', $itemid);

            //if there such ID show form

            if($check  > 0) {

                $stmt = $con->prepare('DELETE FROM items WHERE item_ID = :zid');
                $stmt->bindParam(":zid", $itemid);
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

//Approve Member Page

echo "<h1 class='text-center'> Approve Member</h1>";
echo "<div class='container'>";
     
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

    //Select All Data Depend on This ID

    $check = checkItem('item_ID', 'items', $itemid);

    //if there such ID show form

    if($check  > 0) {

        $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
        $stmt->execute(array($itemid));

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

} else{

    header('location: index.php');

    exit();
}

ob_end_flush();

?>
