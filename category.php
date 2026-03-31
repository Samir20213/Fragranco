<?php
include("config/db.php");
include("includes/header.php");

// category id check
if (!isset($_GET['id'])) {
    echo "<h2 class='text-center mt-5'>No Category Selected</h2>";
    include("includes/footer.php");
    exit();
}

$category_id = $_GET['id'];

// category fetch
$query = mysqli_query($conn, "SELECT * FROM categories WHERE id='$category_id'");
$category = mysqli_fetch_assoc($query);
?>

<style>
.category-header {
    padding: 60px 0;
    text-align: center;
    background: #111;
    color: gold;
}

.product-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 18px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.product-card:hover {
    transform: translateY(-8px);
}

.product-card img {
    width: 100%;
    height: 250px;
    object-fit: cover;
}

.product-body {
    padding: 15px;
    text-align: center;
}

.product-title {
    font-size: 20px;
    font-weight: bold;
}

.product-price {
    color: #c99700;
    font-weight: bold;
    margin: 10px 0;
}

.btn-shop {
    background: black;
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    text-decoration: none;
    transition: 0.3s;
}

.btn-shop:hover {
    background: gold;
    color: black;
}
</style>

<!-- CATEGORY HEADER -->
<div class="category-header">
    <h1><?php echo $category['name']; ?> Collection</h1>
    <p>Explore premium perfumes in this category</p>
</div>

<!-- PRODUCTS (STATIC NOW) -->
<div class="container py-5">
    <div class="row g-4">

        <div class="col-md-4">
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=800&q=80">
                <div class="product-body">
                    <div class="product-title">Luxury Oud</div>
                    <div class="product-price">৳ 799</div>
                    <a href="#" class="btn-shop">View</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=800&q=80">
                <div class="product-body">
                    <div class="product-title">Golden Perfume</div>
                    <div class="product-price">৳ 999</div>
                    <a href="#" class="btn-shop">View</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?auto=format&fit=crop&w=800&q=80">
                <div class="product-body">
                    <div class="product-title">Midnight Scent</div>
                    <div class="product-price">৳ 899</div>
                    <a href="#" class="btn-shop">View</a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include("includes/footer.php"); ?>