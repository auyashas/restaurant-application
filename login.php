<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <form action="" method="POST">
        <h2>Login</h2>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label  >
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
    <p>Don't have an account?</p><a href="register.html"> Register here</a>
</body>

</html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $error = array();
    if (empty($email) || empty($password)) {
        $error[] = "Username or password is invalid";
    }
    if (count($error) == 0) {
        require_once "config.php";
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($mysqli, $query);
        $rc= mysqli_num_rows($result);
        if ($rc!=0) {
            session_start();
            $_SESSION['email'] = $email;
            header("Location: index.php");
            exit();
        } else {
            $error[] = "Username or password is invalid";
            echo "<script>alert('Username or password is invalid.');</script>";
            echo "<script>window.location.href = 'login.php';</script>";
            exit();
        }
    }
}
?>