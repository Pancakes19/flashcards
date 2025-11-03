<?php
include 'config.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // 1. Look for the user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(); // fetch() gets the first matching row, or false if not found

    if ($user && password_verify($password, $user["password"])) {
        // 2. If password matches, set their user id in $_SESSION
        $_SESSION["user_id"] = $user["id"];
        header('Location: dashboard.php');
            exit; 
        
    } else {
        echo "Invalid email or password.";
    }
}
?>

<style>
form {
  background: #f6f6f6;
  padding: 2rem;
  max-width: 350px;
  margin: 2rem auto;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
label {
  display: block;
  margin-bottom: 1em;
  color: #333;
  font-weight: 600;
}
input[type="email"], input[type="password"] {
  width: 100%;
  padding: .5em;
  border: 1px solid #bbb;
  border-radius: 4px;
}
button {
  padding: .5em 1.5em;
  border: none;
  background: #0074D9;
  color: #fff;
  border-radius: 4px;
  font-size: 1em;
  cursor: pointer;
}
button:hover {
  background: #005fa3;
}
</style>

<h1 style="text-align:center; margin-top:2rem;">Welcome back!</h1>
<div style="max-width:350px;margin:2rem auto;">
  <h2 style="text-align:center;">Sign in</h2>
  <form method="post">
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
  </form>
  <p style="text-align:center; margin-top:1em;">
    Don't have an account? <a href="register.php">Register</a>
  </p>
</div>