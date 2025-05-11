<?php
require_once('DBconnect.php');
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $amount = $_POST['amount'];
  $u_id = 1; // Use logged-in user's ID in a real system
  $item_id = NULL;

  // Insert into Storage if needed (this example skips it)
  $stmt1 = $conn->prepare("INSERT INTO transaction (amount, item_id) VALUES (?, ?)");
  $stmt1->bind_param("di", $amount, $item_id);
  if ($stmt1->execute()) {
    $trans_id = $stmt1->insert_id;

    $stmt2 = $conn->prepare("INSERT INTO User_donates (u_id, trans_id) VALUES (?, ?)");
    $stmt2->bind_param("ii", $u_id, $trans_id);
    if ($stmt2->execute()) {
      $message = "✅ Thank you for your donation!";
    } else {
      $message = "❌ Error recording donation.";
    }
    $stmt2->close();
  } else {
    $message = "❌ Transaction failed.";
  }
  $stmt1->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Donate - ReliefAid</title>
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
    input[type="number"], input[type="submit"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    input[type="submit"] {
      background-color: #28a745;
      color: white;
      border: none;
    }
    .message {
      color: #cc0000;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="box">
  <h2>Make a Donation</h2>
  <?php if ($message) echo "<p class='message'>$message</p>"; ?>
  <form method="post">
    <input type="number" name="amount" placeholder="Enter amount in Taka" min="20" required>
    <input type="submit" value="Donate">
  </form>
</div>

</body>
</html>