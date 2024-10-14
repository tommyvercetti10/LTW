<?php 
function editProfile($user) { 
    $session = Session::getInstance();
    $token = $session->getCsrfToken();
    ?>

    <script src="/src/javascript/profile.js" defer></script>
    <section class="edit-profile-section">
        <div class="edit-profile-container">
            <h2>Edit Profile</h2>
            <form action="../actions/action_edit_profile.php" method="post" enctype="multipart/form-data" class="edit-profile-form">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <div class="profile-photo-container">
                    <label for="photo">Photo</label>
                    <div class="photo-container">
                        <img id="preview" src="<?php echo $user->photo != '' ? '../../uploads/' . $user->photo : '../../assets/person.jpeg'; ?>" alt="profile photo">
                        <input type="file" name="profile-photo" id="photo" class="photo-input" onchange="previewImage(event)">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter your name" value="<?php echo $user->name; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" placeholder="Enter your email" value="<?php echo $user->email; ?>">
                </div>
                <div class="form-group">
                    <label for="biography">Biography</label>
                    <input type="text" name="biography" id="biography" placeholder="Enter your biography" value="<?php echo $user->biography; ?>">
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" id="city" placeholder="Enter your city" value="<?php echo $user->city; ?>">
                </div>
                <div class="form-group">
                    <label for="password1">Password</label>
                    <input type="password" name="password1" id="password1" placeholder="Enter your password">
                </div>
                <div class="form-group">
                    <label for="password2">New Password</label>
                    <input type="password" name="password2" id="password2" placeholder="Enter your new password">
                </div>
                <div class="form-group">
                    <label for="password3">Confirm New Password</label>
                    <input type="password" name="password3" id="password3" placeholder="Enter your new password again">
                </div>
                <fieldset class="buttons">
                    <button type="button" class="cancel-button" onclick="toggleEditProfile()">Cancel</button>
                    <button type="submit" class="save-button">Save Changes</button>
                </fieldset>
            </form>
        </div>
    </section>

<?php }
?>
