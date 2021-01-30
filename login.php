<?php    
    session_start();
    $PageTitle = 'Login';
    if(isset($_SESSION['user'])) {
        header('location: index.php'); 
    }
    include 'int.php';
//Check If User Coming From HTTP Post Request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['login'])) { //login
    $user = $_POST['username'];
    $pass = $_POST['password']; 
    $hashedpass = sha1($pass);
    //Chick If The User Exist In Database 
        $stmt = $con->prepare("SELECT 
                                    UserID, Username, Password 
                                FROM 
                                    user 
                                WHERE 
                                    Username = ?
                                AND 
                                    Password = ?");
    $stmt->execute(array($user, $hashedpass));
    $get = $stmt->fetch();
    $count = $stmt->rowCount();
    //If Count > 0
    if($count > 0) {
        $_SESSION['user'] = $user; //Register SESSION Name
        $_SESSION['uid'] = $get['UserID']; //Register SESSION ID
        header('location: index.php'); //Redirect To Dashboard Page
        exit();
    }
  } else {

    $formError = array();
    
    $username  = $_POST['username'];
    $password1 = $_POST['password'];
    $password2 = $_POST['password-again'];
    $email     = $_POST['email'];

    if(isset($username)) { //Protection User
        $filterUser = filter_var($username, FILTER_SANITIZE_STRING);
        if (strlen($filterUser) < 4) {
            $formError[] = 'Username Must Be Large Than 4 Character';
        }
    }
    if(isset($password1) && isset($password2)) { //Protection Pass
        if (empty($password1)) {
            $formError[] = 'Sorry Password Cant Be Empty';
        }
        if (sha1($password1) !== sha1($password2)) {
            $formError[] = 'Sorry Password Is Not Match';
        }
    }
    if(isset($email )) { //Protection User
        $filterEmail = filter_var($email , FILTER_SANITIZE_EMAIL);
        if (filter_var($filterEmail, FILTER_VALIDATE_EMAIL) !=true) {
            $formError[] = 'This Email Is Not Valid';
        }
    }
    //Check If There's No Error Proceed Update Operation
    if(empty($formErrors)) {

        //Check if user exist in database
        $check = checkItem("Username", "user", $username);
        if ($check == 1) {
            $formError[] = 'Sorry This User Is Exist';
        } else {
        //Insert Userinfo In Database
       $stmt = $con->prepare("INSERT INTO 
                                     user(Username, Password, Email, RogStatus, Date)                                                                                                                                           
                                     VALUES(:zuser, :zpass, :zmail, 0, now())");
        $stmt->execute(array(
            'zuser' => $username,
            'zpass' => sha1($password1),
            'zmail' => $email,
        ));
        //echo success message
       $succesMsg = 'Congrats You Are New Registerd User';
    }   
    }
  }
}
?>

<div class="container login-page">
<h1 class="text-center">
    <span class="active" data-class="login">Login</span> 
    | 
    <span data-class="signup">Signup</span>
</h1>
<!--- star login form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <input 
            class="form-control" 
            type="text" 
            name="username" 
            autocomplete="off" 
            placeholder="Type Your username"
        />
        <input 
            class="form-control" 
            type="password" 
            name="password" 
            autocomplete="new password" 
            placeholder="Type Your password"
        />
        <input 
            class="btn btn-primary btn-block" 
            type="submit" 
            name="login"
            value="login" 
        />
    </form>
    <!--- end login form -->
    <!--- end Signup form -->
    <form class="signup msg"  action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <input 
            class="form-control" 
            type="text" 
            name="username" 
            autocomplete="off" 
            placeholder="Type Your username"
        />
        <input 
            class="form-control" 
            type="password" 
            name="password" 
            autocomplete="new password" 
            placeholder="Type Your password"
        />
        <input 
            class="form-control" 
            type="password" 
            name="password-again" 
            autocomplete="new password" 
            placeholder="Type Your password again"
        />
        <input 
            class="form-control" 
            type="text" 
            name="email" 
            placeholder="Type Your email"
        />
        <input 
            class="btn btn-success btn-block" 
            type="submit" 
            name="signup"
            value="Signup" 
        />
    </form>
    <!--- end Signup form -->
    <div class="the-errors text-center">
        <?php
            if (!empty($formError)) {
                foreach($formError as $error) {
                    echo '<div class="msg error">' . $error . '</div>';
                }
            }
            if (isset($succesMsg)) {
                echo '<div class="msg success">' . $succesMsg . '</div>';
            }
        ?>
    </div>
</div>

<?php
    include $tpl  . 'footer.php';
?>
