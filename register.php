<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <h2>Register</h2>
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account?</p><a href="login.html"> Login here</a>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $errors = array();
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {
        require_once "config.php";

        $qry = "SELECT * FROM users WHERE email = '$email'";
        $rs = mysqli_query($mysqli, $qry);
        $rn = mysqli_num_rows($rs);
        if ($rn > 0) {
            echo "<script>alert('Email already exists. Please use a different email.');</script>";
            echo "<script>window.location.href = 'register.html';</script>";
        } else {
            $qry = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
            $rs = mysqli_query($mysqli, $qry);
            if ($rs) {
                echo "<script>alert('Registration successful. Please log in.');</script>";
                echo "<script>window.location.href = 'login.html';</script>";
            } else {
                echo "Registration unsuccessful";
            }
        }
    } else {
        foreach ($errors as $error) {
            echo "<p>Error: " . $error . "</p>";
        }
    }
}
