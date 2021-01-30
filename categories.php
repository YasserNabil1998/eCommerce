<?php
    session_start();
    include 'int.php'; 
 ?>

<div class="container">
    <h1 class="text-center">Shoe Category</h1>
    <div class="row">
        <?php
            if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
                $catrgory = intval($_GET['pageid']);
                $allItems = getAllFrom("*", "items", "where Cat_ID = {$catrgory}", "AND Approve = 1", "item_ID");
                foreach ($allItems as $items) {
                   echo '<div class="col-sm-6 col-md-3">';
                    echo '<div class="thumbnail item-box">';
                        echo '<span class="price-page">' . $items['Price'] . '</span>';
                        echo '<img class="img-responsive" src="img-2.png" alt="" />';
                        echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid=' . $items['item_ID'] . '">' . $items['Name'] . '</a></h3>';
                            echo '<p>' . $items['Description'] . '</p>';
                            echo '<div class="date">' . $items['Add_Date'] . '</div>';
                        echo '</div>';
                    echo '</div>';
                   echo '</div>';
                }
            } else {
                echo '<div class="container">';
                echo '<div class="alert alert-danger">You Must Add Page ID</div>';
                echo '</div>';
            }         
        ?>
    </div>
</div>
<?php include $tpl  . 'footer.php'; ?>
