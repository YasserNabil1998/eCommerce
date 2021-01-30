<?php 

session_start();
$PageTitle = 'Create New Item';
    include 'int.php';
    if (isset($_SESSION['user'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formError  = array();
        $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $Status     = filter_var($_POST['Status'], FILTER_SANITIZE_NUMBER_INT);
        $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

       if (strlen($name) < 4) {
           $formError [] = 'Item Title Must Be At Least 4 Character';
       }
       if (strlen($desc) < 10) {
        $formError [] = 'Item Description Must Be At Least 10 Character';
    }
    if (strlen($country) < 2) {
        $formError [] = 'Item Country Must Be At Least 2 Character';
    }
    if (empty($price)) {
        $formError [] = 'Item price Must Be Not Empty';
    }
    if (empty($Status)) {
        $formError [] = 'Item Status Must Be Not Empty';
    }
    if (empty($category)) {
        $formError [] = 'Item Category Must Be Not Empty';
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
                                                :ztags) ");
                    $stmt->execute(array(
                        'zname'     => $name ,
                        'zdesc'     => $desc,
                        'zprice'    => $price,
                        'zcountry'  => $country,
                        'zstatus'   => $Status,
                        'zcat'      => $category,
                        'zmember'   => $_SESSION['uid'],
                        'ztags'     => $tags
                       
                    ));
                    //echo success message
                    if ($stmt) {
                        $successMsg  = 'Items Has Been Added';
                    }
                }   
}
?>
<h1 class="text-center"><?php echo $PageTitle?></h1>
<div class="create-add block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo $PageTitle?></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                            <!-- Start Name Field -->
                            <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-10 col-md-9">
                                <input
                                type="text"
                                name="name"
                                class="form-control live"
                                required="required"
                                placeholder="Name of The Items"
                                data-class=".live-title"
                                />
                            </div>
                            </div>
                            <!-- End Name Field -->      
                            <!-- Start Description Field -->
                            <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-10 col-md-9">
                                <input
                                type="text"
                                name="description"
                                class="form-control live"
                                required="required"
                                placeholder="Description of The Items"
                                data-class=".live-desc"
                                />
                            </div>
                            </div>
                            <!-- End Description Field -->  
                            <!-- Start Price Field -->
                            <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Price</label>
                            <div class="col-sm-10 col-md-9">
                                <input
                                type="text"
                                name="price"
                                class="form-control live"
                                required="required"
                                placeholder="Price of The Items"
                                data-class=".live-price"
                                />
                            </div>
                            </div>
                            <!-- End Price Field -->     
                            <!-- Start Country Field -->
                            <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Country</label>
                            <div class="col-sm-10 col-md-9">
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
                            <label class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-10 col-md-8">
                            <select  name="Status">
                                <option value="">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Vary Old</option>
                            </select>
                            </div>
                            </div>
                            <!-- End Status Field --> 
                            <!-- Start categories Field -->
                            <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">category</label>
                            <div class="col-sm-10 col-md-8">
                            <select name="category">
                                <option value="">...</option>
                                    <?php
                                        $cats = getAllFrom('*', 'categories', '', '', 'ID');
                                        foreach ($cats as $cat) {
                                            echo "<option value='" . $cat['ID'] . "'>"  . $cat['Name'] . "</option>";
                                        }
                                    ?>
                            </select>
                            </div>
                            </div>
                            <!-- Start categories Field -->
                                <!-- Start Tags Field -->
                                <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-8">
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
                            <div class="col-sm-offset-3 col-sm-9">
                                <input type="submit" value="Add Items" class="btn btn-primary btn-lg" />
                            </div>
                            </div>
                            <!-- End submit Field -->
                    </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-page">$
                            <span class="live-price">0</span>
                            </span>
                            <img class="img-responsive" src="img-3.png" alt="" />
                            <div class="caption">
                                <h3 class="live-title">Title</h3>
                                <p class="live-desc">Description</p>
                        </div>
                    </div>
                    </div>
                </div>
                <!-- Start Looping Through Error-->
                    <?php
                        if (! empty($formError)) {
                            foreach($formError as $error) {
                                echo '<div class="alert alert-danger">' . $error . '</div>'; 
                            }
                        }
                        if (isset($successMsg)) {
                            echo '<div class="alert alert-success">' . $successMsg . '</div>';
                        }
                    ?>

                <!-- End Looping Through Error-->
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
