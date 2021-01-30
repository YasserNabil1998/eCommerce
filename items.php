<?php 
ob_start();
session_start();
$PageTitle = 'Show Item';
    include 'int.php';
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
  //Select All Data Depend on This ID
    $stmt = $con->prepare("SELECT items .*, categories.Name AS Category_name, user.Username FROM items
                            INNER JOIN categories ON categories.ID = items.Cat_ID
                            INNER JOIN user ON user.UserID = items.Member_ID
                            WHERE Item_ID = ? AND Approve = 1");;
    $stmt->execute(array($itemid));
    $count = $stmt->rowCount();
    if ($count > 0) {

    $item = $stmt->fetch();
?>
<h1 class="text-center">My Profile</h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
        <img class="img-responsive img-thumbnail  center-block" src="img-3.png" alt="" />
        </div>
        <div class="col-md-9 item-info">
            <h2><?php echo $item['Name']?></h2>
            <p><?php echo $item['Description']?></p>
            <ul class="list-unstyled">
                <li>
                    <i class="fa fa-calendar fa-fw"></i>
                    <span>Add Date</span> : <?php echo $item['Add_Date']?>
                </li>
                <li>
                <i class="fa fa-money fa-fw"></i>
                    <span>Price</span> : <?php echo $item['Price']?>
                </li>
                <li>
                <i class="fa fa-building fa-fw"></i>
                    <span>Made In</span> : <?php echo $item['Country_Made']?>
                </li>
                <li>
                <i class="fa fa-tags fa-fw"></i>
                    <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"> <?php echo $item['Category_name']?></a>
                </li>
                <li>
                <i class="fa fa-user fa-fw"></i>
                    <span>Add By</span> : <a href="#"> <?php echo $item['Username']?></a>
                </li>
                <i class="fa fa-user fa-fw"></i>
                    <span>Tags</span> :
                    <?php
                    $allTags = explode(",", $item['tags']);
                    foreach ($allTags as $tag) {
                        $tag = str_replace(' ', '', $tag);
                        $lowertag = strtolower($tag);
                        if (! empty($tag)) {
                        echo "<a href='tags.php?name={$lowertag}'>" . $tag . "</a> |";
                        } 
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <hr class="custom-hr">
    <?php if (isset($_SESSION['user'])) {?> 
    <div class="row">
        <div class="col-md-offset-3">
        <div class="comment">
            <h3>Add Your Comment</3>
            <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['item_ID'] ?>" method="POST">
                <textarea name="comment" required></textarea>
                <input class="btn btn-primary" type="submit" value="Ass Comment">
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                $userid  = $item['item_ID'];
                $userid  = $_SESSION['uid'];
                if (! empty($comment)) {
                    $stmt = $con->prepare("INSERT INTO
                    comments(comment, status, comment_date, item_id, user_id)
                    VALUE(:zcomment, 0, NOW(), :zitemid, :zuserid)");
                    $stmt->execute(array(
                        'zcomment' => $comment,
                        'zitemid'  => $itemid,
                        'zuserid'  => $userid
                    ));
                    if ($stmt) {
                        echo '<div class="alert alert-success">Comment Add</div>';
                    }
                }
            }
            ?>
            </div>
        </div>
    </div>
    <?php } else {
        echo 'Login or Register To Add Comment';
    } ?>
    <hr class="custom-hr"> 
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
                                WHERE
                                    item_id = ?
                                AND
                                    status = 1
                                ORDER BY
                                    c_id DESC");
    //Execute the Statement
        $stmt->execute(array($item['item_ID']));
    //Assign To Variabole
        $comment = $stmt->fetchAll();
    ?>
        <?php foreach($comment as $comment) {  ?>
            <div class="comment-box">
                <div class="row">
                <div class="col-sm-2 text-center">
                <img class="img-responsive img-thumbnail img-circle center-block" src="img-2.png" alt="" />
                <?php echo $comment['Member']?> 
                </div>
                <div class="col-sm-10"> 
                <p class="lead"><?php echo $comment['comment']?></p>
                </div>
                </div>
            </div>
            <hr class="custom-hr"> 
        <?php } ?>
</div>
<?php
    } else {
        echo '<div class="container">';
        echo '<div class="alert alert-danger">There no Such ID Or The Items Is Waiting Approve</div>';
        echo '</div>';
    }
    include $tpl  . 'footer.php';
    ob_end_flush();
?>
  