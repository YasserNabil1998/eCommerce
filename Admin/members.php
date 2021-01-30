<?php

/**
 * ===============================================================
 * ==Manage Member Page
 * == You Can Add | Edit | Delete Members From Here
 * ===============================================================
 */


session_start();
$PageTitle = 'Members';

if(isset($_SESSION['Username'])) {

    include 'int.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage

    if($do == 'Manage') {  // Manage Page 

        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query  = 'AND RogStatus = 0';
        }
    
    //Select All User
        $stmt = $con->prepare("SELECT * FROM user WHERE GroupID != 1 $query");

    //Execute the Statement
        $stmt->execute();
    
    //Assign To Variabole
        $rows = $stmt->fetchAll();
    
    ?>
        
        <h1 class="text-center">Manage Member</h1>
            <div class="container">       
            <div class="table-responsive">
            <table class="main-table manage-member text-center table table-bordered">
                <tr>
                <td>#ID</td>
                <td>Avatar</td>
                <td>Username</td>
                <td>Email</td>
                <td>Full Name</td>
                <td>Registerd Data</td> 
                <td>Control</td>
                </tr>
                <?php
                    foreach($rows as $row) {
                        echo "<tr>";                       
                            echo "<td>" . $row['UserID'] . "</td>";
                            echo "<td>";
                            if (empty($row['avatar'] )) {
                                echo "<img src='img-2.png' alt=''/>";
                            } else {
                                echo "<img src='uploads/avatar/" . $row['avatar'] . "' alt=''/>";
                            }
                            echo "</td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>
                                    <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class=' fa fa-edit'></i> Edit</a>
                                    <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class=' fa fa-close'></i> Delete</a>";
                                    if ($row['RogStatus'] == 0) {
                                        echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-info Activate'><i class=' fa fa-check'></i> Activate </a>";
                                    }
                             echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"
            ><i class="fa fa-plus"></i>  New Member</a
            >
        </div>

  <?php  } elseif ($do == 'Add') { ?>

        <h1 class="text-center">Add New Member</h1>
            <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype ="multipart/form-data">
                <!-- Start Username Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10 col-md-4">
                    <input
                    type="text"
                    name="username"
                    class="form-control"
                    autocomplete="off"
                    required="required"
                    placeholder="Username To Login Into Shop"
                    />
                </div>
                </div>
                <!-- End Username Field -->
                <!-- Start Password Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10 col-md-4">
                    <input
                    type="password"
                    name="password"
                    class="password form-control"
                    autocomplete="password"
                    required="required"
                    placeholder="Password Must Be Hard & Complex"
                    />
                    <i class="show-pass fa fa-eye fa-2x"></i>
                </div>
                </div> 
                <!-- End Password Field -->
                <!-- Start Email Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10 col-md-4">
                    <input
                    type="email"
                    name="email"
                    class="form-control"
                    autocomplete="off"
                    required="required"
                    placeholder="Email Must Be Valid"
                    />
                </div>
                </div>
                <!-- End Email Field -->
                <!-- Start Full Name Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Full Name</label>
                <div class="col-sm-10 col-md-4">
                    <input
                    type="text"
                    name="full"
                    class="form-control"
                    autocomplete="off"
                    required="required"
                    placeholder="Full Name Appear In Your Profile Page"
                    />
                </div>
                </div>
                <!-- End Full Name Field -->
                <!-- Start Full Name Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">User Avatar</label>
                <div class="col-sm-10 col-md-4">
                    <input
                    type="file"
                    name="avatar"
                    class="form-control"
                    autocomplete="off"
                    required="required"
                    />
                </div>
                </div>
                <!-- End Full Name Field -->
                <!-- Start submit Field -->
                <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
                </div>
                </div>
                <!-- End submit Field -->
            </form>
            </div>

    <?php    

    } elseif ($do == 'Insert') {
        //Insert Member Page

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";

            // upload variables
            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp  = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];
            

            //list of Allowed File Type To Upload
            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

            //Get Avatar Extension
            $tmp = explode('.', $avatarName);
            $avatarExtension = strtolower(end($tmp));
            //Get Variable Form The Form 
            $user  = $_POST['username'];
            $pass  = $_POST['password'];
            $email = $_POST['email'];
            $name  = $_POST['full'];
            $hashPass = sha1($_POST['password']);
            //Validate the form
            $formErrors = array();
            if(strlen($user) < 4 ) {
                $formErrors[] =  ' Username  Cant Be Less Than <strong> 4 Characters</strong>';
            }
            if(strlen($user) > 20 ) {
                $formErrors[] =  ' Username  Cant Be More Than <strong> 20 Characters</strong>';
            }
            if (empty($user)) {
                $formErrors[] =  'Username  Cant Be <strong> Empty</strong>';
            }
            if (empty($pass)) {
                $formErrors[] =  'Password  Cant Be <strong> Empty</strong>';
            }
            if (empty($name)) {
                $formErrors[] = 'Full Name  Cant Be <strong> Empty</strong>';
            }
            if (empty($email)) {
                $formErrors[] =  'Email  Cant Be <strong> Empty</strong>';
            }
            if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
                $formErrors[] =  'This Extension is Not <strong> Allowed</strong>';
            }
            if (empty($avatarSize)) {
                $formErrors[] =  'Images is <strong> Required</strong>';
            }
            if ($avatarSize > 4194304) {
                $formErrors[] =  'Images Cant Be Larger Than <strong> 4MB</strong>';
            }
            //loop error
            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            //Check If There's No Error Proceed Update Operation
            if(empty($formErrors)) {
                $avatar = rand(0, 1000000000) . '_' . $avatarName;
                move_uploaded_file($avatarTmp, "uploads\avatar\\" . $avatar);

                //Check if user exist in database
                $check = checkItem("Username", "user", $user);
                if ($check == 1) {
                    $theMsg  = '<div class="alert alert-danger"> Sorry This User Is Exist</div>';
                    redirectHome($theMsg, 'back');
                } else {
                //Insert Userinfo In Database
               $stmt = $con->prepare("INSERT INTO 
                                             user(Username, Password, Email, FullName, RogStatus, Date, avatar)                                                                                                                                           
                                             VALUES(:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar) ");
                $stmt->execute(array(
                    'zuser'     => $user,
                    'zpass'     => $hashPass,
                    'zmail'     => $email,
                    'zname'     => $name,
                    'zavatar'   => $avatar 
                ));
                //echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted </div>';
                    redirectHome($theMsg, 'back');
            }    
            }
        } else {
            echo "<div class='container'>";
          $theMsg =  '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
            redirectHome($theMsg);
            echo "</div>";
        }
        echo "</div>";

    } elseif ($do == 'Edit') { //Edit Page 
       
      $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        //Select All Data Depend on This ID
        $stmt = $con->prepare("SELECT * From user Where UserID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        //if there such ID show form
        if($stmt->rowCount() > 0) { ?> 
                <h1 class="text-center">Edit Member</h1>
            <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                <!-- Start Username Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10 col-md-4">
                    <input
                    type="text"
                    name="username"
                    class="form-control"
                    value='<?php echo $row['Username']?>'
                    autocomplete="off"
                    required="required"
                    />
                </div>
                </div>
                <!-- End Username Field -->
                <!-- Start Password Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10 col-md-4">
                <input
                    type="hidden"
                    name="oldpassword"
                    value='<?php echo $row['Password']?>'
                    />
                    <input
                    type="password"
                    name="newpassword"
                    class="form-control"
                    autocomplete="new-password"
                    placeholder="Leave Lank If You Dont Want To Change"
                    />
                </div>
                </div>
                <!-- End Password Field -->
                <!-- Start Email Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10 col-md-4">
                    <input
                    type="email"
                    name="email"
                    class="form-control"
                    value='<?php echo $row['Email']?>'
                    required="required"
                    />
                </div>
                </div>
                <!-- End Email Field -->
                <!-- Start Full Name Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Full Name</label>
                <div class="col-sm-10 col-md-4">
                    <input
                    type="text"
                    name="full"
                    class="form-control"
                    value='<?php echo $row['FullName']?>'
                    autocomplete="off"
                    required="required"
                    />
                </div>
                </div>
                <!-- End Full Name Field -->
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
        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Get Variable Form The Form 
            $id    = $_POST['userid'];
            $user  = $_POST['username'];
            $email = $_POST['email'];
            $name  = $_POST['full'];
            //Password Trick
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
            //Validate the form
            $formErrors = array();
            if(strlen($user) < 4 ) {
                $formErrors[] =  ' Username  Cant Be Less Than <strong> 4 Characters</strong>';
            }
            if(strlen($user) > 20 ) {
                $formErrors[] =  ' Username  Cant Be More Than <strong> 20 Characters</strong>';
            }
            if (empty($user)) {
                $formErrors[] =  ' Username  Cant Be <strong> Empty</strong>';
            }
            if (empty($name)) {
                $formErrors[] = ' Full Name  Cant Be <strong> Empty</strong>';
            }
            if (empty($email)) {
                $formErrors[] =  ' Email  Cant Be <strong> Empty</strong>';
            }
            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            //Check If There's No Error Proceed Update Operation
            if(empty($formErrors)) {
                //Update The Database With This Info
                
                    $stmt = $con->prepare("UPDATE user SET Username = ?, Email = ?, FullName = ?, password = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $id));
                //echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated </div>';
                    redirectHome($theMsg, 'back');
            }
        } else {
            $theMsg =  '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
            redirectHome($theMsg);
        }
        echo "</div>"; 
    }elseif ($do == 'Delete') {
        //Delete Member Page
        echo "<h1 class='text-center'> Delete Member</h1>";
        echo "<div class='container'>";
             
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            //Select All Data Depend on This ID
            $check = checkItem('userid', 'user', $userid);
            //if there such ID show form
            if($check  > 0) {
                $stmt = $con->prepare('DELETE FROM user WHERE UserID = :zuser');
                $stmt->bindParam(":zuser", $userid);
                $stmt->execute();
                //echo success message
                $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted </div>';
                redirectHome($theMsg);
            }else {
                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';
                redirectHome($theMsg);
            }
      echo "</div>";
    } elseif ($do == 'Activate') {
       
        //Activate Member Page
        echo "<h1 class='text-center'> Activate Member</h1>";
        echo "<div class='container'>";
             
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            //Select All Data Depend on This ID
            $check = checkItem('userid', 'user', $userid);
            //if there such ID show form
            if($check  > 0) {
                $stmt = $con->prepare("UPDATE user SET RogStatus = 1 WHERE UserID = ?");
                $stmt->execute(array($userid));
                //echo success message
                $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated </div>';
                redirectHome($theMsg);
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