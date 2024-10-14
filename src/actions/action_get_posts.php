<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/post.class.php');
require_once(__DIR__ . '/../templates/itemPreview.php');

$db = connectToDatabase();

$categories = isset($_GET['categories']) ? explode(',', htmlspecialchars($_GET['categories'])) : [];
$conditions = isset($_GET['conditions']) ? explode(',', htmlspecialchars($_GET['conditions'])) : [];
$price = isset($_GET['price']) ? (int)htmlspecialchars($_GET['price']) : 0;
$order = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : 'asc';

$posts = Post::filterPosts($db, $price, $order === 'asc', $categories, $conditions);

foreach ($posts as $post) {
    itemPreview($post, false, false);
}
?>
