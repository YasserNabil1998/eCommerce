<?php  

ob_start(); 

    session_start();

    if(isset($_SESSION['Username'])) {

        $PageTitle = 'Dashboard';
        include 'int.php';
        /**Start  Dashboared Page */

        $latestUser = 5; //Number of Latest User

        $theLatest = getLatest("*", "user", "UserID", $latestUser);  //Latest User Array

        $numItems = 5; //Number of Latest Items

        $latestItems = getLatest("*", "items", "item_ID", $numItems);  //Latest Items Array

        $numComments = 5; // Number  of Comments


        ?>
<div class="container home-stat text-center">
      <h1>Dashboard</h1>
      <div class="row">
        <div class="col-md-3">
          <div class="stat st-member">
                  <i class="fa fa-users"></i>
                 <div class="info">
                  Total Members
                  <span><a href="members.php"> <?php echo countItems('UserID', 'user')  ?></a> </span>
                </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-pending">
            <div class="info">
            <i class="fa fa-user-plus"></i>
            Pending Members
            <span><a href="members.php?=do=Mange&page=Pending">
              <?php echo checkItem("RogStatus", "user", 0)  ?>
              </a></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-item">
            <div class="info">
            <i class="fa fa-tag"></i>
              Total Items
              <span><a href="items.php"> <?php echo countItems('Item_ID', 'items')  ?></a> </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-comments">
            <div class="info">
            <i class="fa fa-comments"></i>
            Total Comments
            <span>
             <a href="comment.php"> <?php echo countItems('c_id', 'comments')  ?></a>
            </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="latest">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-users"></i> 
                Latest <?php echo $latestUser; ?> Registerd User
                <span class="toggle-info pull-right">
                  <i class="fa fa-minus fa-lg"></i>
                </span>
              </div>
              <div class="panel-body">
              <ul class="list-unstyled latest-users">
                  <?php    
                      foreach ($theLatest as $user) {
                        echo '<li>';  
                                echo $user['Username']; 
                                echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                echo '<span class="btn btn-success pull-right">'; 
                                  echo '<i class=" fa fa-edit "></i> Edit';
                                  if ($user['RogStatus'] == 0) {
                                    echo "<a 
                                    href='members.php?do=Activate&userid=" . $user['UserID'] . "' 
                                    class='btn btn-info pull-right Activate'>
                                    <i class=' fa fa-check'></i> Activate </a>";
                                }
                                echo '</span>';
                                echo '</a>';
                        echo '<li>';
                      }
                  ?>
              </ul>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-tag"></i> Latest <?php echo $numItems?> Items
                <span class="toggle-info pull-right">
                  <i class="fa fa-minus fa-lg"></i>
                </span>
              </div>
              <div class="panel-body">
              <ul class="list-unstyled latest-users">
                  <?php    
                      foreach ($latestItems as $item) {
                        echo '<li>';  
                                echo $item['Name']; 
                                echo '<a href="items.php?do=Edit&itemid=' . $item['item_ID'] . '">';
                                echo '<span class="btn btn-success pull-right">'; 
                                  echo '<i class=" fa fa-edit "></i> Edit';
                                  if ($item['Approve'] == 0) {
                                    echo "<a 
                                    href='items.php?do=Approve&itemid=" . $item['item_ID'] . "' 
                                    class='btn btn-info pull-right Activate'>
                                    <i class=' fa fa-check'></i> Approve </a>";
                                }
                                echo '</span>';
                                echo '</a>';
                        echo '<li>';
                      }
                  ?>
              </ul>
              </div>
            </div>
          </div>
        </div>
        <!---Start Comment-->
        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-comments-o"></i> 
                Latest <?php echo $numComments?>  Comments 
                <span class="toggle-info pull-right">
                  <i class="fa fa-minus fa-lg"></i>
                </span>
              </div>
              <div class="panel-body">

              <?php
                $stmt = $con->prepare("SELECT 
                                          comments.*, user.Username AS Member 
                                       FROM 
                                          comments
                                      INNER JOIN
                                          user
                                       ON
                                          user.UserID = comments.user_id
                                        LIMIT $numComments");
                 $stmt->execute();
                 $comments = $stmt->fetchAll();

                 foreach ($comments as $comment) {
                   echo '<div class="comment-box">';
                      echo '<span class="member-n">' . $comment['Member'] . '</span>'; 
                      echo '<p class="member-c">' . $comment['comment'] . '</p>'; 
                   echo '</div>';
                 }
              ?>
              </div>
            </div>
          </div>
        </div>
        <!--End Comment---->
      </div>
    </div>
<?php
          /**End  Dashbored Page */

        include $tpl  . 'footer.php';

    }else{

        header('location: index.php');

        exit();
    }

    ob_end_flush();

?>    