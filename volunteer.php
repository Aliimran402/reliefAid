<?php
require_once('DBconnect.php');
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nid = $_POST['nid'];
  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $zip = $_POST['zip'];
  $item_id = NULL; // You can assign based on disaster needs

  // Start transaction
  $conn->begin_transaction();

  try {
    // First check if location exists
    $stmt = $conn->prepare("SELECT zip FROM Location WHERE zip = ?");
    $stmt->bind_param("s", $zip);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      // Location doesn't exist, create it with default values
      $stmt = $conn->prepare("INSERT INTO Location (zip, name, population) VALUES (?, ?, ?)");
      $location_name = "Area " . $zip; // Default name
      $default_population = 0; // Default population
      $stmt->bind_param("ssi", $zip, $location_name, $default_population);
      $stmt->execute();
    }

    // Now insert the volunteer
    $stmt = $conn->prepare("INSERT INTO Volunteers (NID, name, contact, item_id, zip) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $nid, $name, $contact, $item_id, $zip);
    $stmt->execute();

    $conn->commit();
    $message = "✅ Successfully registered as a volunteer!";
  } catch (Exception $e) {
    $conn->rollback();
    $message = "❌ Error: " . $e->getMessage();
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
