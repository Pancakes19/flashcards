<?php
include 'config.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $gender = $_POST["gender"] ?? null;
    $dob = $_POST["dob"] ?? null;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        "INSERT INTO users (email, firstname, lastname, gender, dob, password) VALUES (?, ?, ?, ?, ?, ?)"
    );
    try {
        $stmt->execute([$email, $firstname, $lastname, $gender, $dob, $hashedPassword]);
        $userId = $pdo->lastInsertId();
        $_SESSION["user_id"] = $userId;
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
    <label for="firstname" style="display:block;margin-bottom:0.3em;">First Name:</label>
    <input type="text" name="firstname" id="firstname" required style="width:100%;margin-bottom:1em;">

    <label for="lastname" style="display:block;margin-bottom:0.3em;">Last Name:</label>
    <input type="text" name="lastname" id="lastname" required style="width:100%;margin-bottom:1em;">

    <label for="email" style="display:block;margin-bottom:0.3em;">Email:</label>
    <input type="email" name="email" id="email" required style="width:100%;margin-bottom:1em;">

    <label for="password" style="display:block;margin-bottom:0.3em;">Password:</label>
    <input type="password" name="password" id="password" required style="width:100%;margin-bottom:1em;">

    <label for="gender" style="display:block;margin-bottom:0.3em;">Gender:</label>
    <select name="gender" id="gender" style="width:100%;margin-bottom:1em;">
      <option value="male">Male</option>
      <option value="female">Female</option>
      <option value="other">Other</option>
    </select>

    <label for="dob" style="display:block;margin-bottom:0.3em;">Date of Birth:</label>
    <input type="date" name="dob" id="dob" style="width:100%;margin-bottom:1em;">

    <button type="submit">Register</button>
  </form>
  <p style="text-align:center; margin-top:1em;">
    Already have an account? <a href="login.php">Sign in</a>
  </p>
</div>


