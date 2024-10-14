<?php
require_once(__DIR__ . "/../database/connection.db.php");
require_once(__DIR__ . "/../database/post.class.php");
require_once(__DIR__ . "/../utils/session.php");

function editItem(Post $post) { 
    $session = Session::getInstance();
    $token = $session->getCsrfToken();
    ?>
    <link rel="stylesheet" type="text/css" href="/src/css/editProfile.css">
    <script src="/src/javascript/editPost.js" defer></script>
    <section class="edit-profile-section">
        <div class="edit-profile-container">
            <h2>Edit Profile</h2>
            <form method="post" class="edit-profile-form" id="editItemForm">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <input type="hidden" name="id" value="<?php echo $post->id; ?>">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter the new item name" value="<?php echo $post->name; ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" placeholder="Enter the new item description" value="<?php echo $post->description; ?>">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" placeholder="Enter the new item price" value="<?php echo $post->price; ?>">
                </div>
                <fieldset class="buttons">
                    <button type="button" class="cancel-button" onclick="window.history.back();">Cancel</button>
                    <button type="submit" class="save-button">Save Changes</button>
                </fieldset>
            </form>
        </div>
    </section>
<?php }
?>
