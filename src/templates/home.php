<?php 
require_once(__DIR__ .'/../database/connection.db.php');
require_once(__DIR__ .'/../database/post.class.php');
require_once(__DIR__ .'/../utils/session.php');
require_once(__DIR__ .'/../database/post.class.php');
require_once(__DIR__ .'/../templates/itemPreview.php');
function home() {
    $db = connectToDatabase();
    $session = Session::getInstance();
    $categories = Post::getCategories($db);
    $conditions = Post::getConditions($db);
    $posts = Post::getPosts($db, $session->get('user')->id ?? null);
    ?>
    <link rel="stylesheet" type="text/css" href="/src/css/home.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/src/javascript/home.js" defer></script>
    <button onclick="toggleFilterSection()">Filter</button>
    <section class="filter-section" id="filter-section" style="display: none;">
        <div class="filter-header">
            <h2>Filter</h2>
            <button class="close-btn" aria-label="Close filter section" onclick="toggleFilterSection()">&times;</button>
        </div>
        <div class="filter-item">
            <label>Order By</label>
            <select id="sort">
                <option value="asc" name="order">Ascending Price</option>
                <option value="desc" name="order">Descending Price</option>
            </select>
        </div>
        <div class="filter-item">
            <label>Category</label>
            <div class="category-options">
                <?php foreach ($categories as $category) { ?>
                    <button class="category-btn" name="categories[]" onclick="toggleSelection(this)"><?php echo htmlspecialchars($category); ?></button>
                <?php } ?>
            </div>
        </div>
        <div class="filter-item">
            <label>Condition</label>
            <div class="condition-options">
                <?php foreach ($conditions as $condition) { ?>
                    <button class="condition-btn" name="conditions[]" onclick="toggleSelection(this)"><?php echo htmlspecialchars($condition); ?></button>
                <?php } ?>
            </div>
        </div>
        <div class="filter-item">
            <label>Price</label>
            <input type="range" id="price" min="0" max="1000" step="1" onchange="updatePriceValue(this.value)">
            <span id="price-value">0 - 1000</span>
        </div>
        <div class="option-btn">
            <button id="apply-filters">See Items</button>
            <button id="clear-filters">Reset Filters</button>
        </div>
    </section>

    <section id="results">
        <?php foreach ($posts as $post) {
            itemPreview($post, false, false); 
        } ?>
    </section>
<?php }
?>
