<?php
require_once(__DIR__ . '/../utils/session.php');
function register() { 
    $session = Session::getInstance();
    $token = $session->getCsrfToken();
    $messages = $session->getMessages();
    ?>
    <!DOCTYPE html>
    <html lang="en-US">
    <head>
        <title>Register</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="/src/css/register.css">
        <link rel="stylesheet"type="text/css" href="/src/css/alert.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <section class="signup-section">
            <div class="signup-container">
                <h2>Welcome!</h2>
                <?php 
                    foreach ($messages as $message) {
                        if($message['type'] === 'signuperror')
                        echo "<div class='alert'>{$message['message']}</div>";
                    }
                ?>
                <form action="../actions/action_signup.php" method="post" class="signup-form">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                    <div class="links">
                        <p>Already have an account?</p>
                        <a href="/src/pages/signinPage.php" class="login-link">Login</a>
                    </div>
                    <button type="submit" class="signup-button">Sign up</button>
                </form>
            </div>
        </section>
    </body>
    </html>
<?php } ?>
