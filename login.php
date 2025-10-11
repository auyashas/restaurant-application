<?php
session_start();
require_once "config.php";

if (isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit;
}

$name = $email = "";
$login_err = $register_err_general = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- LOGIN LOGIC ---
    if (isset($_POST['form_type']) && $_POST['form_type'] == 'login') {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        if (empty($email) || empty($password)) { $login_err = "Please enter email and password."; }
        if (empty($login_err)) {
            $sql = "SELECT id, name, password FROM users WHERE email = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("s", $email);
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($id, $name, $hashed_password);
                        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
                            $_SESSION["user_id"] = $id; $_SESSION["user_name"] = $name;
                            header("location: index.php"); exit();
                        } else { $login_err = "Invalid email or password."; }
                    } else { $login_err = "Invalid email or password."; }
                }
                $stmt->close();
            }
        }
    }
    // --- REGISTRATION LOGIC ---
    elseif (isset($_POST['form_type']) && $_POST['form_type'] == 'register') {
        if(empty(trim($_POST["name"]))){ $name_err = "Name is required."; } else { $name = trim($_POST["name"]); }
        if(empty(trim($_POST["email"]))){ $email_err = "Email is required."; } else {
            $sql = "SELECT id FROM users WHERE email = ?";
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param("s", $_POST["email"]); $stmt->execute(); $stmt->store_result();
                if($stmt->num_rows == 1) $email_err = "This email is already taken."; else $email = trim($_POST["email"]);
                $stmt->close();
            }
        }
        $password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password)){ $password_err = "Password is required."; } elseif(strlen($password) < 6){ $password_err = "Password must have at least 6 characters."; }
        if(empty($confirm_password)){ $confirm_password_err = "Please confirm password."; } elseif(empty($password_err) && ($password != $confirm_password)){ $confirm_password_err = "Passwords did not match."; }
        
        if(empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            if($stmt = $mysqli->prepare($sql)){
                // THE CRITICAL FIX: Hash the password BEFORE binding it
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt->bind_param("sss", $name, $email, $hashed_password);
                if($stmt->execute()){ header("location: login.php?registered=true"); exit(); } 
                else { $register_err_general = "Something went wrong. Please try again."; }
                $stmt->close();
            }
        }
    }
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account - The Malabar Table</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login-register-style.css">
</head>
<body>
    <div class="container <?php if (!empty($name_err) || !empty($email_err) || !empty($password_err) || !empty($confirm_password_err) || !empty($register_err_general)) echo 'active'; ?>" id="container">
        
        <div class="form-container sign-up">
            <form action="login.php" method="POST">
                <input type="hidden" name="form_type" value="register">
                <h1>Create Account</h1>
                <?php if(!empty($register_err_general)) echo '<p class="error-message">' . $register_err_general . '</p>'; ?>
                
                <div class="input-box">
                    <input type="text" id="reg-name" name="name" value="<?php echo htmlspecialchars($name); ?>" required />
                    <label for="reg-name">Full Name</label>
                    <span class="field-error"><?php echo $name_err; ?></span>
                </div>
                <div class="input-box">
                    <input type="email" id="reg-email" name="email" value="<?php echo htmlspecialchars($email); ?>" required />
                    <label for="reg-email">Email</label>
                    <span class="field-error"><?php echo $email_err; ?></span>
                </div>
                <div class="input-box">
                    <input type="password" id="reg-password" name="password" class="password-input" required />
                    <label for="reg-password">Password</label>
                    <img src="images/icons/eye.svg" class="toggle-password" alt="Show/Hide password">
                    <span class="field-error"><?php echo $password_err; ?></span>
                </div>
                <div class="input-box">
                    <input type="password" id="confirm_password" name="confirm_password" class="password-input" required />
                    <label for="confirm_password">Confirm Password</label>
                    <img src="images/icons/eye.svg" class="toggle-password" alt="Show/Hide password">
                    <span class="field-error"><?php echo $confirm_password_err; ?></span>
                </div>
                <button type="submit">Register</button>

                <p class="mobile-switch">Already have an account? <a href="#" id="mobileLoginBtn">Sign In</a></p>
            </form>
        </div>

        <div class="form-container sign-in">
            <form action="login.php" method="POST">
                <input type="hidden" name="form_type" value="login">
                <h1>Sign In</h1>
                <?php if(!empty($login_err)) echo '<p class="error-message">' . $login_err . '</p>'; ?>
                <?php if(isset($_GET['registered'])) echo '<p class="success-message">Registration successful! Please log in.</p>'; ?>

                <div class="input-box">
                    <input type="email" id="login-email" name="email" required />
                    <label for="login-email">Email</label>
                </div>
                <div class="input-box">
                    <input type="password" id="login-password" name="password" class="password-input" required />
                    <label for="login-password">Password</label>
                    <img src="images/icons/eye.svg" class="toggle-password" alt="Show/Hide password">
                </div>
                <button type="submit">Login</button>

                <p class="mobile-switch">Don't have an account? <a href="#" id="mobileRegisterBtn">Sign Up</a></p>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Hello, Friend!</h1>
                    <p>Already have an account? Sign in to manage your reservations.</p>
                    <button class="hidden" id="loginBtn">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Welcome Back!</h1>
                    <p>Don't have an account? Register to start your culinary journey with us.</p>
                    <button class="hidden" id="registerBtn">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/login-register.js"></script>
</body>
</html>