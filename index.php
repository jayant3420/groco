<?php
    include './database/db.php';
    session_start();

    $email = $emailError = $password = $passwordError = $name = $loginError = "";
    $userName = $userNameError = $productId = $cartResult = $logout = $totalCartProduct = "";
    $total = 0;

    $url_components = parse_url($_SERVER["REQUEST_URI"]);
    if(!empty($url_components['query'])) {
        parse_str($url_components['query'], $params);
        if(!empty($params["logout"])) {
            $logout = $params["logout"];
        }
    }

    if($logout == true) {
        session_unset();
        session_destroy();
        header('/groco/index.php');
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($_POST["email"])) {
            $emailError = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
        }

        if(empty($_POST["password"])) {
            $emailError = "Password is required";
        } else {
            $password = test_input($_POST["password"]);
        }

        if(empty($_POST["name"])) {
            $userNameError = "Name is required";
        } else {
            $userName = test_input($_POST["name"]);
        }

        if(empty($userName)) {
            $login = "SELECT * FROM users WHERE email='".$email."' AND password=".$password;
            $result = mysqli_query($conn, $login);

            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                    $name = $row["name"];
                    $_SESSION["name"] = $name;
                    $_SESSION["userId"] = $row["id"];
                    header('/groco/index.php');
                }
            } else {
                $loginError = "Login credentials are incorrect";
            }
        } else {
            $createAccount = "INSERT INTO users (email, password, name) VALUES ('".$email."', ".$password.", '".$userName."')";
            mysqli_query($conn, $createAccount);
        }
    }

    $products = "SELECT * FROM products";
    $productResult = mysqli_query($conn, $products);
    $totalProducts = mysqli_num_rows($productResult);
    $url_components = parse_url($_SERVER["REQUEST_URI"]);
    if(!empty($url_components['query'])) {
        parse_str($url_components['query'], $params);
        if(!empty($params["id"])) {
            $productId = $params["id"];
        }
        
        if(!empty($params["logout"])) {
            $logout = $params["logout"];
        }
    }

    if($productId) {
        $checkCart = "SELECT * FROM cart WHERE userId=".$_SESSION["userId"]." AND productId=".$productId."";
        $cartResult = mysqli_query($conn, $checkCart);
        $totalCartProduct = mysqli_num_rows($cartResult);
        if($totalCartProduct == 0) {
            $insertProduct = "INSERT INTO cart (userId, productId) VALUES (".$_SESSION["userId"].", ".$productId.")";
            mysqli_query($conn, $insertProduct);
        }
    }

    if(!empty($_SESSION["userId"])) {
        $getUserProducts = "SELECT * FROM cart INNER JOIN products ON cart.productId = products.productId WHERE userId=".$_SESSION["userId"]."";
        $cartResult = mysqli_query($conn, $getUserProducts);
        $totalCartProduct = mysqli_num_rows($cartResult);
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Responsive Grocery Website Design Tutorial</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style1.css">
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    
<!-- header section starts  -->

<header class="header">

    <a href="#" class="logo"> <i class="fas fa-shopping-basket"></i> groco </a>

    <nav class="navbar">
        <a href="#home">home</a>
        <a href="#features">features</a>
        <a href="#products">products</a>
        <a href="#categories">categories</a>
        <a href="#review">review</a>
        <a href="#blogs">blogs</a>
    </nav>

    <div class="icons d-flex">
        <div class="fas fa-bars" id="menu-btn"></div>
        <div class="fas fa-search" id="search-btn"></div>

        <?php
            if(!empty($_SESSION["userId"])) {
        ?>
            <div class="fas fa-shopping-cart" id="cart-btn"></div>
        <?php
            }
        ?>

        <?php 
            if(!empty($_SESSION["name"])) {
        ?>
            <div class="fs-4 name text-capitalize"><?php echo $_SESSION["name"]; ?></div>
        <?php
            } else {
        ?>
            <div class="fas fa-user" id="login-btn"></div>
        <?php } ?>

        <?php
            if(!empty($_SESSION["userId"])) {
        ?>
            <a href="/groco/index.php?logout=true" class="fs-4 py-3 px-5 text-capitalize text-decoration-none bg-muted mx-1" id="">log out</a>
        <?php
            }
        ?>
    </div>

    <form action="" class="search-form">
        <input type="search" id="search-box" placeholder="search here...">
        <label for="search-box" class="fas fa-search"></label>
    </form>

    <div class="shopping-cart">
        <?php
            if($totalCartProduct > 0) {
                while($row = mysqli_fetch_assoc($cartResult)) {
                    $total = $total + $row["rate"];
        ?>
            <div class="box">
                <!-- <i class="fas fa-trash"></i> -->
                <img src="image/<?php echo $row["image"]; ?>" alt="">
                <div class="content">
                    <h3><?php echo $row["name"]; ?></h3>
                    <span class="price">$<?php echo $row["rate"]; ?>/-</span>
                    <!-- <span class="quantity">qty : 1</span> -->
                </div>
            </div>
        <?php
            } } else {
        ?>
            <div class="text-center">
                -- cart is empty --
            </div>
        <?php
            }
        ?>
        <div class="total"> 
            total : $
                <?php 
                    if($total > 0) {
                        echo $total;
                    } else {
                        echo 0;
                    }
                ?>/-  
        </div>
        <!-- <a href="#" class="btn">checkout</a> -->
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="login-form" method="POST">
        <h3 id="loginText">login now</h3>
        <input type="name" name="name" placeholder="your name" class="box hidden" id="name">
        <div class="text-danger fs-5 text-start <?php echo 'hidden' ?>">Please enter name</div>
        <input type="email" name="email" placeholder="your email" class="box">
        <input type="password" name="password" placeholder="your password" class="box">
        <!-- <p>forget your password <a href="#">click here</a></p> -->
        <p id="createNow">don't have an account <a href="#">create now</a></p>
        <p class="hidden" id="loginNow">Already have an account <a href="#">Login</a></p>
        <input type="submit" value="login now" class="btn" id="loginBtn">
        <input type="submit" value="create account" class="btn hidden" id="createAccount">
    </form>

</header>

<!-- header section ends -->

<!-- home section starts  -->

<section class="home" id="home">

    <div class="content">
        <h3>fresh and <span>organic</span> products for your</h3>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam libero nostrum veniam facere tempore nisi.</p>
        <a href="#" class="btn">shop now</a>
    </div>

</section>

<!-- home section ends -->

<!-- features section starts  -->

<section class="features" id="features">

    <h1 class="heading"> our <span>features</span> </h1>

    <div class="box-container">

        <div class="box">
            <img src="image/feature-img-1.png" alt="">
            <h3>fresh and organic</h3>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Deserunt, earum!</p>
            <a href="#" class="btn">read more</a>
        </div>

        <div class="box">
            <img src="image/feature-img-2.png" alt="">
            <h3>free delivery</h3>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Deserunt, earum!</p>
            <a href="#" class="btn">read more</a>
        </div>

        <div class="box">
            <img src="image/feature-img-3.png" alt="">
            <h3>easy payments</h3>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Deserunt, earum!</p>
            <a href="#" class="btn">read more</a>
        </div>

    </div>

</section>

<!-- features section ends -->

<!-- products section starts  -->

<section class="products" id="products">

    <h1 class="heading"> our <span>products</span> </h1>

    <div class="swiper product-slider">
        <div class="d-flex flex-wrap">
            <?php 
                if ($totalProducts > 0) {
                    while($row = mysqli_fetch_assoc($productResult)) {
            ?>
                <div class="product-box">
                    <img src="image/<?php echo $row["image"]; ?>" width="50%" height="50%" alt="" id="<?php echo $row["productId"]; ?>">
                    <h3><?php echo $row["name"] ?></h3>
                    <div class="price fs-3"> $<?php echo $row["rate"]; ?>/- </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <?php
                        if(!empty($_SESSION["name"])) {
                    ?>
                        <a href="/groco/index.php?id=<?php echo $row["productId"]; ?>" class="btn">add to cart</a>
                    <?php
                        }
                    ?>
                </div>
            <?php
                } }
            ?>
        </div>
    </div>
</section>

<!-- products section ends -->

<!-- categories section starts  -->

<section class="categories" id="categories">

    <h1 class="heading"> product <span>categories</span> </h1>

    <div class="box-container">

        <div class="box">
            <img src="image/cat-1.png" alt="">
            <h3>vegitables</h3>
            <p>upto 45% off</p>
            <a href="#" class="btn">shop now</a>
        </div>

        <div class="box">
            <img src="image/cat-2.png" alt="">
            <h3>fresh fruits</h3>
            <p>upto 45% off</p>
            <a href="#" class="btn">shop now</a>
        </div>

        <div class="box">
            <img src="image/cat-3.png" alt="">
            <h3>dairy products</h3>
            <p>upto 45% off</p>
            <a href="#" class="btn">shop now</a>
        </div>

        <div class="box">
            <img src="image/cat-4.png" alt="">
            <h3>fresh meat</h3>
            <p>upto 45% off</p>
            <a href="#" class="btn">shop now</a>
        </div>

    </div>

</section>

<!-- categories section ends -->

<!-- review section starts  -->

<section class="review" id="review">

    <h1 class="heading"> customer's <span>review</span> </h1>

    <div class="swiper review-slider">

        <div class="swiper-wrapper">

            <div class="swiper-slide box">
                <img src="image/pic-1.png" alt="">
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Unde sunt fugiat dolore ipsum id est maxime ad tempore quasi tenetur.</p>
                <h3>john deo</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <div class="swiper-slide box">
                <img src="image/pic-2.png" alt="">
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Unde sunt fugiat dolore ipsum id est maxime ad tempore quasi tenetur.</p>
                <h3>john deo</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <div class="swiper-slide box">
                <img src="image/pic-3.png" alt="">
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Unde sunt fugiat dolore ipsum id est maxime ad tempore quasi tenetur.</p>
                <h3>john deo</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <div class="swiper-slide box">
                <img src="image/pic-4.png" alt="">
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Unde sunt fugiat dolore ipsum id est maxime ad tempore quasi tenetur.</p>
                <h3>john deo</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

        </div>

    </div>

</section>

<!-- review section ends -->

<!-- blogs section starts  -->

<section class="blogs" id="blogs">

    <h1 class="heading"> our <span>blogs</span> </h1>

    <div class="box-container">

        <div class="box">
            <img src="image/blog-1.jpg" alt="">
            <div class="content">
                <div class="icons">
                    <a href="#"> <i class="fas fa-user"></i> by user </a>
                    <a href="#"> <i class="fas fa-calendar"></i> 1st may, 2021 </a>
                </div>
                <h3>fresh and organic vegitables and fruits</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam, expedita.</p>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

        <div class="box">
            <img src="image/blog-2.jpg" alt="">
            <div class="content">
                <div class="icons">
                    <a href="#"> <i class="fas fa-user"></i> by user </a>
                    <a href="#"> <i class="fas fa-calendar"></i> 1st may, 2021 </a>
                </div>
                <h3>fresh and organic vegitables and fruits</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam, expedita.</p>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

        <div class="box">
            <img src="image/blog-3.jpg" alt="">
            <div class="content">
                <div class="icons">
                    <a href="#"> <i class="fas fa-user"></i> by user </a>
                    <a href="#"> <i class="fas fa-calendar"></i> 1st may, 2021 </a>
                </div>
                <h3>fresh and organic vegitables and fruits</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam, expedita.</p>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

    </div>

</section>

<!-- blogs section ends -->

<!-- footer section starts  -->

<section class="footer">

    <div class="box-container">

        <div class="box">
            <h3> groco <i class="fas fa-shopping-basket"></i> </h3>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aliquam, saepe.</p>
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
            </div>
        </div>

        <div class="box">
            <h3>contact info</h3>
            <a href="#" class="links"> <i class="fas fa-phone"></i> +123-456-7890 </a>
            <a href="#" class="links"> <i class="fas fa-phone"></i> +111-222-3333 </a>
            <a href="#" class="links"> <i class="fas fa-envelope"></i> amitgautamcrown@gmail.com </a>
            <a href="#" class="links"> <i class="fas fa-map-marker-alt"></i> Greater Noida, india - 400104 </a>
        </div>

        <div class="box">
            <h3>quick links</h3>
            <a href="#" class="links"> <i class="fas fa-arrow-right"></i> home </a>
            <a href="#" class="links"> <i class="fas fa-arrow-right"></i> features </a>
            <a href="#" class="links"> <i class="fas fa-arrow-right"></i> products </a>
            <a href="#" class="links"> <i class="fas fa-arrow-right"></i> categories </a>
            <a href="#" class="links"> <i class="fas fa-arrow-right"></i> review </a>
            <a href="#" class="links"> <i class="fas fa-arrow-right"></i> blogs </a>
        </div>

        <div class="box">
            <h3>newsletter</h3>
            <p>subscribe for latest updates</p>
            <input type="email" placeholder="your email" class="email">
            <input type="submit" value="subscribe" class="btn">
            <img src="image/payment.png" class="payment-img" alt="">
        </div>

    </div>

    <div class="credit"> created by <span> Mr. Amit Kumar </span> | all rights reserved </div>

</section>

<!-- footer section ends -->


<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>