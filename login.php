<?php
require_once('DBconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = new mysqli(hostname: "localhost", username: "root",password: "db_user_password", database: "ReliefAid");

  if ($sql->connect_error) {
    die("Connection failed: " . $sql->connect_error);
  }

  $stmt = $sql->prepare(query: "SELECT password FROM user WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify(password: $password, hash: $hashed_password)) {
      echo "Login successful!";
      //use dashboard
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "User not found.";
  }

  $stmt->close();
  $sql->close();
}
?>
