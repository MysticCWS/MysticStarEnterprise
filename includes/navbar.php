        <nav class="navbar navstyle">
            <ul class="left-side">
                <li><a href="home.php">Homepage</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="orderhistory.php">Order History</a></li>
                <li><a href="submitcartridge.php">Cartridge Submission</a></li>
            </ul>
            <ul class="right-side">
                <?php if(isset($_SESSION['verified_user_id'])) : ?>
                <li><a href="userprofile.php" title="Edit Profile">
                        <?php
                        $uid = $_SESSION['verified_user_id'];
                        try {
                            $user = $auth->getUser($uid);
                            echo $user->displayName;
                            } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                            echo $e->getMessage();
                        }
                        ?>
                        
                    </a>
                </li>
                <li><a href="cart.php">
                        <span class="bi-cart" title="Cart"></span>
                    </a>
                </li>
                <li>
                    <a href="function_logout.php">
                        <span class="bi-power" title="Logout"></span>
                    </a>
                </li>
                <?php else : ?>
                <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>          
        </nav>
        
    <div class="container">