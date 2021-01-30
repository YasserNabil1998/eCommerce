<?php 
    session_start();
    $noNavbar = '';
    $PageTitle = 'Login';

    if(isset($_SESSION['Username'])) {
        header('location: dashboard.php'); //Redirect To Dashboard Page
    }
    include 'int.php';

    //Check If User Coming From HTTP Post Request

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedpass = sha1($password);

        //Chick If The User Exist In Database

        $stmt = $con->prepare("SELECT 
                                    UserID, Username, Password 
                                From 
                                    user 
                                Where 
                                    Username = ?
                                AND 
                                    Password = ?
                                 AND 
                                    GroupID = 1
                                LIMIT 1");

        $stmt->execute(array($username, $hashedpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        //If Count > 0
        if($count > 0) {
            $_SESSION['Username'] = $username; //Register SESSION Name
            $_SESSION['ID'] = $row['UserID'];   //Register SESSION ID
            header('location: dashboard.php'); //Redirect To Dashboard Page
            exit();
        }
    }
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">      
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
    <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password" />
    <input class="btn btn-primary btn-block" type="submit" value="Login" />
</form>
<?php include $tpl  . 'footer.php';?>
