<?php
    require_once 'core/connect.php';
    include('includes/head.php');
    include('includes/nav.php');
    include('includes/headerpartial.php');
    include('includes/leftbar.php'); 

    if(isset($_GET['cat'])){
        $cat_id = sanitize($_GET['cat']);
    }else{
        $cat_id = '';
    }

    $sql = "SELECT * FROM product WHERE category='$cat_id'";
    $query = $conn->query($sql);
    $category = get_category($cat_id);
                    
?>
<!--Main Content  -->
<div class="col-md-8" >
    <div class="row">
        <h2 class="text-center"><?php echo $category['parent'].' '.$category['child'] ;?></h2>
        <?php while($product = mysqli_fetch_assoc($query)):?>
        <div class="col-md-3" id="main">
            <h4><?= $product['title'];?></h4>
            <img src="<?= $product['image'];?>" alt="<?= $product['title'];?>" class="img-pro">
            <p class="list-price text-danger">List price <s>#<?= $product['list_price'];?></s></p>
            <p class="price">Our Price: #<?= $product['price'];?> </p>
            <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?=$product['id']; ?>)">Details</button>
        </div>
        <?php endwhile;?>
    </div>
</div>
<?php
    include('includes/rightbar.php');
    include('includes/footer.php');
?>
