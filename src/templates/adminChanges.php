<?php 
require_once(__DIR__ . '/../utils/session.php');
function adminChanges() { 
    $session = Session::getInstance();
    $token = $session->getCsrfToken();
    ?>
    <script src="../javascript/profile.js" defer></script>
    <section class="admin-changes-section">
        <div class="admin-changes-container">
            <h2>Admin Changes</h2>
            <form action="../actions/action_admin_changes.php" method="post" class="admin-changes-form">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" name="category" id="category" placeholder="Enter the new category">
                </div>
                <div class="form-group">
                    <label for="condition">Condition</label>
                    <input type="text" name="condition" id="condition" placeholder="Enter the new condition">
                </div>
                <fieldset class="buttons">
                    <button type="button" class="cancel-button" onclick="toggleAdminChanges()">Cancel</button>
                    <button type="submit" class="save-button">Save Changes</button>
                </fieldset>
            </form>
        </div>
    </section>

<?php }
?>
