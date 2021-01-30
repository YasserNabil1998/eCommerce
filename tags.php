<?php
    session_start();
    include 'int.php'; 
 ?>
<div class="container">
    <div class="row">
        <?php
            if (isset($_GET['name'])) {
                $tag = ($_GET['name']);
                echo " <h1 class='text-center'>" . $tag . "</h1>";
                $tagItem = getAllFrom("*", "items", "where tags like '%$tag%'", "AND Approve = 1", "item_ID");
                foreach ($tagItem as $item) {
                   echo '<div class="col-sm-6 col-md-3">';
                    echo '<div class="thumbnail item-box">';
                        echo '<span class="price-page">' . $item['Price'] . '</span>';
                        echo '<img class="img-responsive" src="img-2.png" alt="" />';
                        echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid=' . $item['item_ID'] . '">' . $item['Name'] . '</a></h3>';
                            echo '<p>' . $item['Description'] . '</p>';
                            echo '<div class="date">' . $item['Add_Date'] . '</div>';
                        echo '</div>';
                    echo '</div>';
                   echo '</div>';
                }
            } else {
                echo '<div class="alert alert-danger">You Must Add Page ID</div>';
            }         
        ?>
    </div>
</div>
<?php include $tpl  . 'footer.php'; ?>
