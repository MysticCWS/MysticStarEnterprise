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
//foreach ($carousel_images as $key => $row) {
//    echo $row['carouselurl'];
//}
//    $carousel_query = "select * from carousel";
//    $carousel_result = mysqli_query($con, $carousel_query);
//    
//    $carousel_images = [];
//    if ($carousel_result->num_rows > 0){
//        while($carousel_row = $carousel_result->fetch_assoc()){
//            $carousel_images[] = $carousel_row['img_dir'];
//        }
//    }
//    
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
        <h2>New Promotions</h5>
    </div>

    <!-- Carousel -->
    <div class="c-wrapper">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($carousel_images as $key => $image): ?>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $key; ?>" class="<?php echo $key === 0 ? 'active' : ''; ?>" aria-current="true" aria-label="Slide <?php echo $key + 1; ?>"></button>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner">
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
    <br>

</div>

<?php
include 'includes\footer.php';
?>