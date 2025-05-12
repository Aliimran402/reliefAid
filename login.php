<?php
require_once('DBconnect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $conn = new mysqli("localhost", "root", "", "reliefaid");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("SELECT password FROM user WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
      $_SESSION['username'] = $username;
      
      // Check if user is admin
      if ($username === "LeoMessi_admin") {
        header("Location: admin.php");
      } else {
        header("Location: home.php");
      }
      exit;
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "User not found.";
  }

  $stmt->close();
  $conn->close();
}
?>
