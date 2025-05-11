<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $address = $_POST['address'];
  $contact = $_POST['contact'];

  $sql = new mysqli(hostname: "localhost", username: "root",database: "reliefaid");

  if ($sql->connect_error) {
    $message = "Connection failed: " . $sql->connect_error;
  } else {
    $stmt = $sql->prepare(query: "INSERT INTO user (username, password, address, contact) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $address, $contact); 

    if ($stmt->execute()) {
      $message = "✅ Registration successful! <a href='index.php'>Login here</a>";
    } else {
      $message = "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $sql->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ReliefAid Register</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
      width: 300px;
    }

    .login-box h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .login-box input[type="submit"] {
      width: 100%;
      padding: 10px;
      background: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .login-box input[type="submit"]:hover {
      background: #218838;
    }

    .login-box .link {
      text-align: center;
      margin-top: 10px;
    }

    .login-box .link a {
      color: #007BFF;
      text-decoration: none;
    }

    .message {
      text-align: center;
      margin-bottom: 10px;
      color: #cc0000;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Register</h2>
    <?php if ($message) echo "<p class='message'>$message</p>"; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="text" name="address" placeholder="Address" required>
      <input type="text" name="contact" placeholder="Contact" required>
      <input type="submit" value="Register">
      <div class="link">
        <p>Already have an account? <a href="Index.php">Login here</a></p>
      </div>
    </form>
  </div>
</body>
</html>
