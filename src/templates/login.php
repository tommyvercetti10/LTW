<?php
require_once(__DIR__ . "/../utils/session.php");
function login() { 
    $session = Session::getInstance();
    $token = $session->getCsrfToken();
    $messages = $session->getMessages();

    ?>

    
<!DOCTYPE html>
    <html lang="en-US">
    <head>
        <title>Login</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="/src/css/login.css">
        <link rel="stylesheet"type="text/css" href="/src/css/alert.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <section class="login-section">
            <div class="login-container">
                <h2>Welcome Back!</h2>
                <?php 
                    foreach ($messages as $message) {
                        if($message['type'] === 'loginerror')
                        echo "<div class='alert'>{$message['message']}</div>";
                    }
                ?>
                
                <form action="../actions/action_signin.php" method="post" class="login-form">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                    <div class="links">
                        <p>Don't have an account?</p>
                        <a href="/src/pages/signupPage.php" class="register-link">Register</a>
                    </div>
                    <button type="submit" class="login-button">Login</button>
                </form>
            </div>
        </section>
    </body>
    </html>
    

    

    <?php
} ?>