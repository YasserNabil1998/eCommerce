<?php 

session_start();
$PageTitle = 'profile';
    include 'int.php';
    if (isset($_SESSION['user'])) {
        $getUser = $con->prepare("SELECT * FROM user WHERE Username = ?");
        $getUser->execute(array($sessionUser));
        $info = $getUser->fetch();
        $userid = $info['UserID'];
?>

<h1 class="text-center">My Profile <?php echo $_SESSION['user'];?></h1>

<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Login Name</span> : <?php echo $info['Username'];?> 
                    </li>
                    <li>
                        <i class="fa fa-envelope fa-fw"></i>
                        <span>Email</span>: <?php echo $info['Email'];?> 
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Full Name</span>: <?php echo $info['FullName'];?> 
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Register Date</span>: <?php echo $info['Date'];?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Favourite Category</span>:
                    </li>    
                </ul>
                <a href="#" class="btn btn-default">Edit Information</a>
            </div>
        </div>
    </div>
</div>

<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Items</div>
            <div class="panel-body">
                    <?php
                    $myItems = getAllFrom("*", "items", "where Member_ID = $userid", "", "item_ID");
                        if (! empty($myItems)){
                            echo '<div class="row">';
                            foreach ($myItems as $items) {
                            echo '<div class="col-sm-6 col-md-3">';
                                echo '<div class="thumbnail item-box">';
                                    if ($items['Approve'] == 0) {
                                         echo '<span class="approve-status">Waiting Approve </span>'; 
                                        }
                                    echo '<span class="price-page">' . $items['Price'] . '</span>';
                                    echo '<img class="img-responsive" src="img-3.png" alt="" />';
                                    echo '<div class="caption">';
                                        echo '<h3><a href="items.php?itemid=' . $items['item_ID'] . '">' . $items['Name'] . '</a></h3>';
                                        echo '<p>' . $items['Description'] . '</p>';
                                        echo '<div class="date">' . $items['Add_Date'] . '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                            }
                            echo '</div>';
                        } else {
                             echo 'Sorry There No Ads To Show, Create <a href="newad.php">New Add</a>';
                        }
                    ?>
            </div>
        </div>
    </div>
</div>

<div class="my-Comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
            <?PHP
            //Select All User
            $myComments = getAllFrom("comment", "comments", "where user_id = $userid", "", "c_id");
                if (! empty($myComments)) {
                    foreach ($myComments as $comment) {
                        echo '<p>' . $comment['comment'] . '</p>';
                    }
                } else {
                    echo 'Theres No Comments to Show';
                }
            ?>
            </div>
        </div>
    </div>
</div>

<?php
} else {
    header('Location: login.php');
    exit();
}
    include $tpl  . 'footer.php';
?>
