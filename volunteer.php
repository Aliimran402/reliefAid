<?php
require_once('DBconnect.php');
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nid = $_POST['nid'];
  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $zip = $_POST['zip'];
  $item_id = NULL; // You can assign based on disaster needs

  $stmt = $conn->prepare("INSERT INTO Volunteers (NID, name, contact, item_id, zip) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssis", $nid, $name, $contact, $item_id, $zip);
  if ($stmt->execute()) {
    $message = "✅ Successfully registered as a volunteer!";
  } else {
    $message = "❌ Error: " . $stmt->error;
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Be a Volunteer - ReliefAid</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      text-align: center;
      padding: 50px;
    }
    .box {
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      width: 400px;
      margin: auto;
    }
    input[type="text"],  input[type="submit"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    input[type="submit"] {
      background-color: #007BFF;
      color: white;
      border: none;
    }
    .home-btn {
      display: inline-block;
      background-color: #6c757d;
      color: white;
      padding: 12px 24px;
      text-decoration: none;
      border-radius: 5px;
      margin-top: 15px;
    }
    .message {
      color: #cc0000;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="box">
  <h2>Volunteer Registration</h2>
  <?php if ($message) echo "<p class='message'>$message</p>"; ?>
  <form method="post">
    <input type="text" name="nid" placeholder="National ID" required>
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="text" name="contact" placeholder="Contact Number" required>
    <input type="text" name="zip" placeholder="ZIP Code of Location" required>
    <input type="submit" value="Register as Volunteer">
  </form>
  <a href="home.php" class="home-btn">Back to Home</a>
</div>

</body>
</html>
