<?php 
require_once(__DIR__ ."/../database/connection.db.php");
require_once(__DIR__ ."/../database/post.class.php");
require_once(__DIR__ ."/../utils/session.php");

function addItem() { 
    $db = connectToDatabase();
    $session = Session::getInstance();
    $token = $session->getCsrfToken();
    $categories = Post::getCategories($db);
    $conditions = Post::getConditions($db);
?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../javascript/dropdown.js" defer></script>
    <script src="../javascript/profile.js" defer></script>

    <section class="add-item-section">
        <div class="add-item-container">
            <h2>Add Item</h2>
            <form action="../actions/action_add_item.php" method="post" class="add-item-form" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" placeholder="Enter your description">
                </div>
                <div class="dropdown">
                    <button type="button" onClick="toggleDropdown('categories-dropdown')" class="dropdown-button">
                        <span>Select Categories</span>
                        <span><i class="fa fa-arrow-down"></i></span>
                    </button>
                    <div id="categories-dropdown" class="dropdown-content">
                        <?php foreach ($categories as $category) { ?>
                            <label><input type="checkbox" name="categories[]" value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></label>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="condition-dropdown">Select Condition</label>
                    <select id="condition-dropdown" name="condition">
                        <option value="">Select Condition</option>
                        <?php foreach ($conditions as $condition) { ?>
                            <option value="<?php echo htmlspecialchars($condition); ?>">
                                <?php echo htmlspecialchars($condition); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="photos">Upload Photos</label>
                    <input type="file" name="photos[]" id="photos" multiple>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" required>
                </div>
                <fieldset class="buttons">
                    <button type="button" class="cancel-button" onClick="toggleAddItem()">Cancel</button>
                    <button type="submit" class="save-button">Save Changes</button>
                </fieldset>
            </form>
        </div>
    </section>

<?php }
?>
