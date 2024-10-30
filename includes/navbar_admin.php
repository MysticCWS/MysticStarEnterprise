        <nav class="navbar navstyle">
            <ul class="left-side">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_products.php">Manage Products</a></li>
                <li><a href="admin_orders.php">Manage Orders</a></li>
                <li><a href="admin_cartridges.php">Manage Cartridge Submissions</a></li>
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