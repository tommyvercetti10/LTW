<?php
require_once(__DIR__ . "/../utils/session.php");
require_once(__DIR__ . "/../database/post.class.php");
require_once(__DIR__ . "/../database/connection.db.php");
require_once(__DIR__ . "/../database/user.class.php");

function itemPreview(Post $post, bool $wishList, bool $cart) { ?>
    <?php 
        $db = connectToDatabase();
        $session = Session::getInstance();
        $token = $session->getCsrfToken();
        $user = $session->get('user');
        $postUser = User::getUserById($db, $post->userId);
        $showDeleteOption = $wishList || $cart || ($user->id ?? "") === $post->userId;
        $action = $wishList ? "deletePostFromWishlist" : ($cart ? "deletePostFromCart" : "deletePost");
        $name = $action === "deletePostFromWishlist" ? "remove_wishlist" : ($action === "deletePostFromCart" ? "remove_cart" : "delete");
    ?>
    
    <link rel="stylesheet" type="text/css" href="/src/css/itemPreview.css">
    <script src="../javascript/deletePost.js" defer></script>
    
    
    <div class="item-preview">
        <section class="item-preview-section" post-id="<?= $post->id?>">
            <a href="../../src/pages/itemPage.php?postId=<?php echo $post->id; ?>" class="item-link">
                <section class="item-container">
                    <img src="<?= $post->getPhotos()[0] ?>" alt="item photo">
                    <div class="user">
                        <img src="<?= $postUser->getPhoto() ?>" class="profile-photo" alt="user photo">
                        <h6><?= $postUser->name ?? ""; ?></h6>
                    </div>
                </section>
            </a>
            <div class="options">
                <form method='POST' action="/src/actions/action_post.php" class="options-form">
                    <input type="hidden" name="postId" value="<?php echo $post->id; ?>">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <?php if ($showDeleteOption) { ?>
                        <button name="<?= $name ?>" onclick="<?= $action ?>(<?= $post->id ?>)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 10V17M10 10V17M6 6V17.8C6 18.9201 6 19.4798 6.21799 19.9076C6.40973 20.2839 6.71547 20.5905 7.0918 20.7822C7.5192 21 8.07899 21 9.19691 21H14.8031C15.921 21 16.48 21 16.9074 20.7822C17.2837 20.5905 17.5905 20.2839 17.7822 19.9076C18 19.4802 18 18.921 18 17.8031V6M6 6H8M6 6H4M8 6H16M8 6C8 5.06812 8 4.60241 8.15224 4.23486C8.35523 3.74481 8.74432 3.35523 9.23438 3.15224C9.60192 3 10.0681 3 11 3H13C13.9319 3 14.3978 3 14.7654 3.15224C15.2554 3.35523 15.6447 3.74481 15.8477 4.23486C15.9999 4.6024 16 5.06812 16 6M16 6H18M18 6H20" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    <?php } else if ($user !== null) { ?>
                        <button type="submit" name="watch" value="<?php echo $user->id; ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.8065 6.20659C4.70663 5.30673 5.92731 4.80122 7.2001 4.80122C8.47288 4.80122 9.69356 5.30673 10.5937 6.20659L12.0001 7.61179L13.4065 6.20659C13.8493 5.74815 14.3789 5.38247 14.9646 5.13091C15.5502 4.87934 16.18 4.74693 16.8174 4.74139C17.4547 4.73585 18.0868 4.8573 18.6767 5.09865C19.2666 5.34 19.8025 5.69641 20.2532 6.1471C20.7039 6.59778 21.0603 7.13371 21.3016 7.72361C21.543 8.31352 21.6644 8.94558 21.6589 9.58292C21.6534 10.2203 21.5209 10.8501 21.2694 11.4357C21.0178 12.0214 20.6521 12.551 20.1937 12.9938L12.0001 21.1886L3.8065 12.9938C2.90664 12.0937 2.40112 10.873 2.40112 9.60019C2.40112 8.32741 2.90664 7.10673 3.8065 6.20659V6.20659Z" stroke="black" stroke-width="2" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    <?php } 
                    if (($user->id ?? "") === $post->userId) { ?>
                        <a href="../pages/editItemPage.php?postId=<?= $post->id ?>" name="postId" value="<?php echo $post->id; ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5499 15.5999L9.7499 19.1999M4.9499 15.5999L16.7813 3.35533C18.0552 2.08143 20.1206 2.08143 21.3945 3.35533C22.6684 4.62923 22.6684 6.69463 21.3945 7.96853L9.1499 19.7999L3.1499 21.5999L4.9499 15.5999Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    <?php } ?>
                </form>
            </div>
            <section class="item-info">
                <h2><?php echo $post->name ?></h2>
                <h3><?php echo $post->price ?>€</h3>
            </section>
        </section>
    </div>
<?php } ?>
