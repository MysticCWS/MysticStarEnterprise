<?php
session_start();
include 'dbcon.php';
include 'includes\header.php';
echo ' | User Profile';
include 'includes\header2.php';
include 'includes\navbar.php';
?>

<div class="content">
    <div class="title">
        <h2>Profile Information</h2>
    </div>
    
<!--    <div class="profile-info">
        
        <div class="profile-header">
            <div class="profile-picture" onclick="document.getElementById('file-input').click();">
                <img src="default-profile.jpg" alt="Profile Picture" id="profile-img">
                <div class="edit-photo">Edit Photo</div>
            </div>
             Hidden file input to upload image 
            <input type="file" id="file-input" style="display: none;" accept="image/*" onchange="previewImage(event)">
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

<script>
        // Function to preview the uploaded profile picture
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profile-img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Placeholder function for saving profile changes
        function saveProfile() {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const bio = document.getElementById('bio').value;

            // Implement save functionality here (e.g., AJAX request to the server)
            alert(`Profile Saved!\nUsername: ${username}\nEmail: ${email}\nBio: ${bio}`);
        }
    </script>-->

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-picture" onclick="document.getElementById('file-input').click();">
                <img src="default-profile.jpg" alt="Profile Picture" id="profile-img">
                <div class="edit-photo">Edit Photo</div>
            </div>
            <!-- Hidden file input to upload image -->
            <input type="file" id="file-input" style="display: none;" accept="image/*" onchange="previewImage(event)">
        </div>

        <form class="profile-info" id="profile-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username">

            <label for="email">Email</label>
            <input type="email" id="email" name="email">

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone">

            <button type="button" class="save-btn" onclick="saveProfile()">Save Changes</button>
        </form>
    </div>
</div>

    <script>
        // Function to preview the uploaded profile picture
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profile-img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Placeholder function for saving profile changes
        function saveProfile() {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;

            // Implement save functionality here (e.g., AJAX request to the server)
            alert(`Profile Saved!\nUsername: ${username}\nEmail: ${email}\nPhone: ${phone}`);
        }
    </script>

<?php
include 'includes\footer.php';
?>
