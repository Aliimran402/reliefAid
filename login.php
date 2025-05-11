<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $conn = new mysqli( "localhost","db_user","db_user_password", "ReliefAid");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare(query: "SELECT password FROM user WHERE username = ?");
  $stmt->bind_param( "s",  $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify(password: $password, hash: $hashed_password)) {
      echo "Login successful!" ;
      header("Location: Home.php");
      // You can start a session here and redirect to a dashboard
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
