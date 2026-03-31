<?php include("includes/header.php"); ?>

<style>
/* ===== HERO SLIDER ===== */
.slider-section {
    margin-top: 0;
}

.slider-img {
    height: 90vh;
    object-fit: cover;
    animation: zoomEffect 6s ease-in-out infinite;
}

@keyframes zoomEffect {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.carousel-item {
    position: relative;
}

.slider-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.35);
    z-index: 1;
}

.custom-caption {
    z-index: 2;
    bottom: 28%;
}

.slider-title {
    font-size: 60px;
    font-weight: 800;
    color: #ffd700;
    text-shadow: 0 4px 15px rgba(0,0,0,0.6);
}

.slider-text {
    font-size: 22px;
    color: #fff;
    margin: 10px 0 20px;
}

.shop-btn {
    display: inline-block;
    background: linear-gradient(135deg, #ffd700, #ffb300);
    color: #111;
    padding: 12px 30px;
    border-radius: 35px;
    text-decoration: none;
    font-weight: 700;
    transition: 0.3s;
}

.shop-btn:hover {
    background: #111;
    color: #ffd700;
    transform: translateY(-4px);
}

/* ===== COMMON SECTION ===== */
.section-title {
    font-size: 38px;
    font-weight: 800;
    margin-bottom: 30px;
    color: #111;
}

.section-subtitle {
    color: #666;
    max-width: 700px;
    margin: 0 auto 40px;
}

/* ===== PRODUCT CARD ===== */
.product-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 8px 22px rgba(0,0,0,0.08);
    transition: 0.35s ease;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.14);
}

.product-card img {
    width: 100%;
    height: 260px;
    object-fit: cover;
}

.product-body {
    padding: 20px;
}

.product-title {
    font-size: 22px;
    font-weight: 700;
    color: #111;
}

.product-price {
    color: #c99700;
    font-weight: 700;
    font-size: 20px;
    margin: 10px 0;
}

.product-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #111;
    color: #fff;
    text-decoration: none;
    border-radius: 30px;
    transition: 0.3s;
}

.product-btn:hover {
    background: #ffd700;
    color: #111;
}

/* ===== CATEGORY ===== */
.category-card {
    background: #fff;
    border-radius: 18px;
    padding: 35px 20px;
    font-size: 22px;
    font-weight: 700;
    text-align: center;
    transition: all 0.4s ease;
    box-shadow: 0 8px 18px rgba(0,0,0,0.06);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-8px) scale(1.03);
    background: linear-gradient(135deg, #111, #2c2c2c);
    color: #ffd700;
    box-shadow: 0 12px 30px rgba(255,215,0,0.25);
}

.category-card::before {
    content: "";
    position: absolute;
    width: 0%;
    height: 100%;
    top: 0;
    left: 0;
    background: linear-gradient(120deg, transparent, rgba(255,215,0,0.3), transparent);
    transition: 0.5s;
}

.category-card:hover::before {
    width: 100%;
}

/* ===== WHY CHOOSE US ===== */
.why-card {
    background: #fff;
    border-radius: 18px;
    padding: 30px 20px;
    box-shadow: 0 8px 18px rgba(0,0,0,0.06);
    transition: 0.3s;
    height: 100%;
}

.why-card:hover {
    transform: translateY(-6px);
}

.why-icon {
    font-size: 38px;
    margin-bottom: 15px;
}

/* ===== TESTIMONIAL ===== */
.testimonial-card {
    background: #fff;
    border-radius: 18px;
    padding: 30px 25px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    height: 100%;
}

.testimonial-text {
    color: #555;
    font-style: italic;
}

/* ===== NEWSLETTER ===== */
.newsletter-box {
    background: linear-gradient(135deg, #111, #2a2a2a);
    color: white;
    border-radius: 22px;
    padding: 50px 30px;
}

.newsletter-box input {
    border-radius: 30px;
    padding: 12px 18px;
    border: none;
}

.newsletter-box button {
    border-radius: 30px;
    padding: 12px 25px;
    border: none;
    background: #ffd700;
    color: #111;
    font-weight: 700;
    transition: 0.3s;
}

.newsletter-box button:hover {
    background: white;
}

/* ===== MOBILE ===== */
@media (max-width: 768px) {
    .slider-img {
        height: 70vh;
    }

    .slider-title {
        font-size: 34px;
    }

    .slider-text {
        font-size: 16px;
    }

    .section-title {
        font-size: 28px;
    }
}
</style>

<!-- HERO SLIDER -->
<div class="slider-section">
    <div id="heroSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3500">

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="3"></button>
        </div>

        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="popo.jpeg" class="d-block w-100 slider-img" alt="Slide 1">
                <div class="slider-overlay"></div>
                <div class="carousel-caption custom-caption">
                    <h1 class="slider-title">FRAGRANCO</h1>
                    <p class="slider-text">Where luxury meets fragrance</p>
                    <a href="shop.php" class="shop-btn">Explore Collection</a>
                </div>
            </div>

            <div class="carousel-item">
                <img src="toto.jpeg" class="d-block w-100 slider-img" alt="Slide 2">
                <div class="slider-overlay"></div>
                <div class="carousel-caption custom-caption">
                    <h1 class="slider-title">Signature Scents</h1>
                    <p class="slider-text">Make your presence unforgettable</p>
                    <a href="shop.php" class="shop-btn">Shop Now</a>
                </div>
            </div>

            <div class="carousel-item">
                <img src="cucu.jpeg" class="d-block w-100 slider-img" alt="Slide 3">
                <div class="slider-overlay"></div>
                <div class="carousel-caption custom-caption">
                    <h1 class="slider-title">Premium Quality</h1>
                    <p class="slider-text">Long-lasting powerful fragrance</p>
                    <a href="shop.php" class="shop-btn">Buy Now</a>
                </div>
            </div>

            <div class="carousel-item">
                <img src="erer.jpeg" class="d-block w-100 slider-img" alt="Slide 4">
                <div class="slider-overlay"></div>
                <div class="carousel-caption custom-caption">
                    <h1 class="slider-title">Your Identity</h1>
                    <p class="slider-text">Let your scent define you</p>
                    <a href="shop.php" class="shop-btn">Discover</a>
                </div>
            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

<!-- FEATURED PRODUCTS -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center">Featured Products</h2>
        <p class="section-subtitle text-center">Discover our carefully selected premium perfumes made to leave a lasting impression.</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=800&q=80" alt="Featured 1">
                    <div class="product-body text-center">
                        <h3 class="product-title">Royal Oud</h3>
                        <p class="product-price">৳ 799</p>
                        <a href="shop.php" class="product-btn">View Product</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=800&q=80" alt="Featured 2">
                    <div class="product-body text-center">
                        <h3 class="product-title">Golden Mist</h3>
                        <p class="product-price">৳ 999</p>
                        <a href="shop.php" class="product-btn">View Product</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?auto=format&fit=crop&w=800&q=80" alt="Featured 3">
                    <div class="product-body text-center">
                        <h3 class="product-title">Midnight Bloom</h3>
                        <p class="product-price">৳ 899</p>
                        <a href="shop.php" class="product-btn">View Product</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NEW ARRIVALS -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center">New Arrivals</h2>
        <p class="section-subtitle text-center">Fresh arrivals crafted for modern style, confidence, and all-day charm.</p>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=800&q=80" alt="New 1">
                    <div class="product-body text-center">
                        <h3 class="product-title">Velvet Night</h3>
                        <p class="product-price">৳ 650</p>
                        <a href="shop.php" class="product-btn">Shop Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1563170351-be82bc888aa4?auto=format&fit=crop&w=800&q=80" alt="New 2">
                    <div class="product-body text-center">
                        <h3 class="product-title">Fresh Aura</h3>
                        <p class="product-price">৳ 720</p>
                        <a href="shop.php" class="product-btn">Shop Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1588405748880-12d1d2a59db9?auto=format&fit=crop&w=800&q=80" alt="New 3">
                    <div class="product-body text-center">
                        <h3 class="product-title">Ocean Rush</h3>
                        <p class="product-price">৳ 840</p>
                        <a href="shop.php" class="product-btn">Shop Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1595425964071-6f6d4d4b42d9?auto=format&fit=crop&w=800&q=80" alt="New 4">
                    <div class="product-body text-center">
                        <h3 class="product-title">Soft Desire</h3>
                        <p class="product-price">৳ 780</p>
                        <a href="shop.php" class="product-btn">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BEST SELLER -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center">Best Sellers</h2>
        <p class="section-subtitle text-center">Loved by customers for their unique blend, premium feel, and long-lasting fragrance.</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1590736969955-71cc94901144?auto=format&fit=crop&w=800&q=80" alt="Best 1">
                    <div class="product-body text-center">
                        <h3 class="product-title">Black Essence</h3>
                        <p class="product-price">৳ 1099</p>
                        <a href="shop.php" class="product-btn">Buy Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1600180758890-6b94519a8ba4?auto=format&fit=crop&w=800&q=80" alt="Best 2">
                    <div class="product-body text-center">
                        <h3 class="product-title">Amber Gold</h3>
                        <p class="product-price">৳ 1199</p>
                        <a href="shop.php" class="product-btn">Buy Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1615634262417-8d9ed8f94973?auto=format&fit=crop&w=800&q=80" alt="Best 3">
                    <div class="product-body text-center">
                        <h3 class="product-title">Classic Noir</h3>
                        <p class="product-price">৳ 950</p>
                        <a href="shop.php" class="product-btn">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORY -->
<section class="py-5 bg-white">
    <div class="container text-center">
        <h2 class="section-title">Shop by Category</h2>
        <p class="section-subtitle">Explore perfume collections based on style, personality, and fragrance preference.</p>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="category-card" onclick="window.location.href='category.php?id=1'">👨 Male</div>
            </div>
            <div class="col-md-3">
                <div class="category-card" onclick="window.location.href='category.php?id=2'">👩 Female</div>
            </div>
            <div class="col-md-3">
                <div class="category-card" onclick="window.location.href='category.php?id=3'">👥 Both</div>
            </div>
            <div class="col-md-3">
                <div class="category-card" onclick="window.location.href='category.php?id=4'">✨ Unique</div>
            </div>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="section-title">Why Choose Us</h2>
        <p class="section-subtitle">We combine luxury, quality, and style to create a fragrance experience that feels premium and memorable.</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="why-card">
                    <div class="why-icon">🌟</div>
                    <h4>Premium Quality</h4>
                    <p>We focus on rich fragrance quality and elegant presentation for a high-end perfume experience.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="why-card">
                    <div class="why-icon">⏳</div>
                    <h4>Long Lasting</h4>
                    <p>Our perfumes are selected to provide a lasting impression throughout the day and beyond.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="why-card">
                    <div class="why-icon">💎</div>
                    <h4>Luxury Feel</h4>
                    <p>Every scent is designed to express confidence, style, and exclusivity in every drop.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="py-5 bg-white">
    <div class="container text-center">
        <h2 class="section-title">What Our Customers Say</h2>
        <p class="section-subtitle">Real impressions from fragrance lovers who experienced the elegance of FRAGRANCO.</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <p class="testimonial-text">“Absolutely amazing fragrance quality. The scent lasts for hours and feels very premium.”</p>
                    <h5 class="mt-3 mb-0">— Rahim</h5>
                </div>
            </div>

            <div class="col-md-4">
                <div class="testimonial-card">
                    <p class="testimonial-text">“Beautiful packaging and elegant smell. I really loved the overall experience.”</p>
                    <h5 class="mt-3 mb-0">— Sumaiya</h5>
                </div>
            </div>

            <div class="col-md-4">
                <div class="testimonial-card">
                    <p class="testimonial-text">“This is one of the best perfume pages I found. Great quality and attractive presentation.”</p>
                    <h5 class="mt-3 mb-0">— Hasan</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NEWSLETTER -->
<section class="py-5">
    <div class="container">
        <div class="newsletter-box text-center">
            <h2 class="mb-3">Subscribe to Our Newsletter</h2>
            <p class="mb-4">Get updates on new arrivals, best sellers, exclusive offers, and fragrance tips.</p>

            <form class="row justify-content-center g-3">
                <div class="col-md-6">
                    <input type="email" class="form-control" placeholder="Enter your email">
                </div>
                <div class="col-md-auto">
                    <button type="submit">Subscribe Now</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
function goToCategory(cat){
    window.location.href = "shop.php?category=" + cat;
}
</script>

<?php include("includes/footer.php"); ?>