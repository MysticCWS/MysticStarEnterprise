<?php 
session_start();
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Home';
include 'includes\header2.php';
include 'includes\navbar.php';

//Fetch carousel from database
$ref_table = 'carousel';
$carousel_images = $database->getReference($ref_table)->getValue();

//$carouselurlprefix = "https://firebasestorage.googleapis.com/v0/b/mysticstarenterprise.appspot.com/o/carousel%2F";
//$carouselurlsuffix = "?alt=media";
//$carouselurl = $carouselurlprefix.$carouselnum.$carouselurlsuffix;
?>

<div class="content">
<!--Show Status-->
    <?php
        if(isset($_SESSION['status'])){
            echo "<h5 class='alert alert-success'>".$_SESSION['status']."</h5>";
            unset($_SESSION['status']);
        }
    ?>

    <div class="title">
        <h2>New Promotions</h2>
    </div>
    
    <div class="container mt-5 px-4 py-4 border rounded bg-white" id="homepage">
        <!-- Carousel -->
        <div class="c-wrapper">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach ($carousel_images as $key => $image): ?>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $key; ?>" class="<?php echo $key === 0 ? 'active' : ''; ?>" aria-current="true" aria-label="Slide <?php echo $key + 1; ?>"></button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner" data-bs-interval="4000">
                    <?php foreach ($carousel_images as $key => $image): ?>
                        <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo $image['carouselurl']; ?>" class="center-block" width="35%" alt="Slide <?php echo $key + 1; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <br>
        <div class="title">
            <h2><center>About Us</center></h2>
        </div>
        <div class="">
            <p>
                <justify>
                    Welcome to Mystic Star Enterprise, your trusted destination for premium PC components, accessories, and laptops. 
                    We are passionate about technology and committed to providing our customers with top-quality products that meet 
                    their computing needs. Whether you are a gamer, a content creator, a professional, or a DIY enthusiast, Mystic Star 
                    Enterprise has the tools and devices to take your digital experience to the next level.
                </justify>
            </p>
            <p>
                <justify>
                    At Mystic Star Enterprise, we offer a wide range of products, including:
                    <ul>
                        <li>
                            PC Components: From powerful processors, high-performance motherboards, and robust graphics cards, to efficient 
                            cooling solutions and memory modules, we provide the essential building blocks for your custom PC. Our products 
                            are sourced from renowned brands to ensure reliability and performance.
                        </li><br>
                        <li>
                            Laptop Solutions: Whether you’re looking for a high-end gaming laptop, a portable workhorse, or a sleek ultrabook, 
                            we offer an array of laptops designed to suit every need. Our laptops come with cutting-edge specs and features 
                            to enhance your productivity, entertainment, and everything in between.
                        </li><br>
                        <li>
                            PC Accessories: Complete your setup with our wide selection of accessories, including mechanical keyboards, gaming 
                            mice, monitors, gaming chairs, speakers, headsets, and more. We carry products designed for both comfort and 
                            performance, ensuring that you have everything you need for a seamless experience.
                        </li><br>
                    </ul>
                </justify>
            </p>
            <p>
                <justify>
                    Why Choose Us?
                    <ol>
                        <li>
                            Quality and Trust: We only offer products from well-established, trusted brands that are known for their performance 
                            and durability. Our goal is to ensure that you get the best value for your investment.
                        </li>
                        <li>
                            Customer-Centric Service: At Mystic Star Enterprise, we are dedicated to providing an exceptional customer experience. 
                            Our knowledgeable and friendly staff is here to assist you with product recommendations, technical support, and any 
                            questions you might have. We value your satisfaction and aim to create lasting relationships with our customers.
                        </li>
                        <li>
                            Competitive Pricing: We believe that high-quality technology should be accessible to everyone. That's why we strive 
                            to offer competitive prices on all our products, making it easier for you to build or upgrade your setup without 
                            breaking the bank.
                        </li>
                        <li>
                            Fast and Reliable Shipping: We understand that you want your new components and devices as soon as possible. That’s 
                            why we offer fast and reliable shipping options to ensure that your purchases arrive safely and promptly.
                        </li>
                        <li>
                            Wide Product Selection: Our store offers a diverse range of products catering to all types of tech enthusiasts. 
                            Whether you're building a gaming rig, upgrading your workstation, or just looking for the best accessories, Mystic 
                            Star Enterprise has something for you.
                        </li>
                    </ol>
                </justify>
            </p>
            <p>
                <justify>
                    At Mystic Star Enterprise, we are not just about selling products - we are about building a community of tech lovers who share 
                    a passion for performance, quality, and innovation.
                </justify>
            </p>
            <p>
                <justify>
                    Thank you for choosing Mystic Star Enterprise as your go-to destination for all things technology. Let us help you unlock 
                    the true potential of your computing experience!
                </justify>
            </p>
            <p>
                <justify>
                    Feel free to reach out to us with any questions or concerns. Our team is ready to assist you in finding the perfect solutions 
                    for your needs.
                </justify>
            </p>
        </div>
        
    </div>
    <br>
</div>

<?php
include 'includes\footer.php';
?>