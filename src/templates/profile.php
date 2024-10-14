<?php
require_once("../database/user.class.php");
require_once("../database/post.class.php");
require_once("../database/connection.db.php");
require_once("../templates/itemPreview.php");
require_once("../templates/addItem.php");
require_once("../utils/session.php");
require_once("../templates/editProfile.php");
require_once("../templates/adminChanges.php");

function profile(?User $user) { ?>
    <?php
       $db = connectToDatabase();
       $session = Session::getInstance();
       $token = $session->getCsrfToken();
       $currentUser = $session->get("user");
   
       if ($currentUser === null) {
           header("Location: ../pages/login.php");
           exit();
       }
   
       $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
       if ($user_id !== null && $user_id !== $currentUser->id) {
           $user = User::getUserById($db, $user_id);
       } else {
           $user = $currentUser;
       }
   
       if ($user === null) {
           echo "User not found.";
           return;
       }
   
       $isCurrentUser = $currentUser->id === $user->id;
       $isAdmin = $isCurrentUser && $currentUser->isAdmin;
    ?>
    <link rel="stylesheet" type="text/css" href="/src/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="/src/javascript/profile.js" defer></script>

    <section id="edit-profile" style="display: none;">
       <?php 
            editProfile($user);
       ?>
    </section>
    <section id="add-item" style="display: none;">
       <?php 
            addItem();
       ?>
    </section>
    <section id="admin-changes" style="display: none;">
       <?php 
            adminChanges();
       ?>
    </section>
    <section class="profile-section" id="profile-section">
        
        <?php if ($isCurrentUser) { ?>
            <a href="../actions/action_logout.php" class="logout">
                <i class="fa fa-sign-out" style="font-size:24px; color:black"></i>
            </a>
        <?php } ?>
        <img src="<?php echo $user->getPhoto(); ?>" class="profile-photo" alt="Profile Photo">
        <h2><?php echo $user->name; ?></h2>
        <h3><?php echo $user->city != "" ? 'From ' . $user->city : ''?></h3>
        <h3><?php echo $user->biography; ?></h3>
        <?php if ($isCurrentUser) { ?>
            <div class="user-options">
                <button onclick="toggleEditProfile()" class="edit-profile-btn">Edit Profile</button>
                <button onclick="toggleAddItem()">Add Item</button>
                <?php if ($isAdmin) { ?>
                <button onclick="toggleAdminChanges()">Admin Changes</button>
                <?php } ?>
            </div>
        <?php } 
         if (!$user->isAdmin && $currentUser->isAdmin) { ?>
            <div class="admin-options">
                <h4>Admin Options</h4>
                <div class="admin-options-forms">
                    <form action="../actions/action_promote_user.php" method="post">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                        <button type="submit">Promote User</button>
                    </form>
                    <form action="../actions/action_ban_user.php" method="post">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                        <button type="submit">Ban User</button>
                    </form>
                </div>
            </div> 
            <?php } 
            ?>
        <section class="posts" id="posts">
            <?php 
                $posts = Post::getPostsFromUser($db, $user->id);
                foreach ($posts as $post) {
                    itemPreview($post, false, false);
                }
            ?>
        </section>
    </section>
<?php }
?>
