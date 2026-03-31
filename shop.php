<?php
include("config/db.php");
include("includes/header.php");
?>

<style>
.shop-title {
    text-align: center;
    margin: 40px 0;
    font-size: 40px;
    font-weight: bold;
}

.product-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: 0.3s;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-8px);
}

.product-card img {
    width: 100%;
    height: 260px;
    object-fit: cover;
}

.product-body {
    padding: 20px;
    text-align: center;
}

.product-title {
    font-size: 22px;
    font-weight: 700;
}

.product-price {
    font-size: 20px;
    color: #c99700;
    font-weight: bold;
    margin: 10px 0;
}

.old-price {
    text-decoration: line-through;
    color: #888;
    margin-right: 8px;
}

.product-btn {
    display: inline-block;
    padding: 10px 18px;
    background: #111;
    color: white;
    text-decoration: none;
    border-radius: 25px;
    transition: 0.3s;
}

.product-btn:hover {
    background: gold;
    color: black;
}
</style>

<div class="container py-5">
    <h1 class="shop-title">Our Perfume Collection</h1>

    <div class="row g-4">
        <?php
        $query = mysqli_query($conn, "SELECT * FROM products WHERE status='active' ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($query)) {
        ?>
        <div class="col-md-4">
            <div class="product-card">
                <?php
                $image_path = $row['main_image'];
                if (!filter_var($image_path, FILTER_VALIDATE_URL)) {
                    $image_path = "assets/uploads/" . $image_path;
                }
                ?>
                <img src="<?php echo $image_path; ?>" alt="">
                <div class="product-body">
                    <h3 class="product-title"><?php echo $row['name']; ?></h3>

                    <p class="product-price">
                        <?php if(!empty($row['discount_price']) && $row['discount_price'] > 0) { ?>
                            <span class="old-price">৳<?php echo $row['price']; ?></span>
                            ৳<?php echo $row['discount_price']; ?>
                        <?php } else { ?>
                            ৳<?php echo $row['price']; ?>
                        <?php } ?>
                    </p>

                    <a href="product-details.php?id=<?php echo $row['id']; ?>" class="product-btn">View Details</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php include("includes/footer.php"); ?>