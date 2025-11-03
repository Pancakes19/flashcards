<?php
include 'config.php'; 
session_start(); // Make sure session is started!

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    try {
        $stmt->execute([$email, $hashedPassword]);
        // 1. Get the new user's id
        $userId = $pdo->lastInsertId();
        // 2. Log them in immediately
        $_SESSION["user_id"] = $userId;
        // 3. Redirect to dashboard
        header('Location: dashboard.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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

<h1 style="text-align:center; margin-top:2rem;">Welcome to Flashcards</h1>
<div style="max-width:350px;margin:2rem auto;">
  <h2 style="text-align:center;">Register</h2>
  <form method="post">
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Register</button>
  </form>
  <p style="text-align:center; margin-top:1em;">
    Already have an account? <a href="login.php">Sign in</a>
  </p>
</div>


