<?php
include 'includes\header.php';
echo ' | User Profile';
include 'includes\header2.php';
include 'includes\navbar.php';
?>

<div>
    <div class="title">
        <h2>Profile Information</h2>
    </div>
    
    <div class="profile-info">
        <div>
            <img src="/api/placeholder/150/150" alt="Profile Picture" class="profile-img mb-3">
        </div>
         <table>
            
            <tr>
                <td>Username:</td>
                <td></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td></td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td></td>
            </tr>
        </table>
    </div>
</div>


<?php
include 'includes\footer.php';
?>