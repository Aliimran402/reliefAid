<?php
require_once('DBconnect.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'LeoMessi_admin') {
  die("Access denied.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['add_disaster_location'])) {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $zip = $_POST['zip'];
    $name = $_POST['name'];
    $population = $_POST['population'];
    

    $conn->begin_transaction();
    
    try {
      
      $stmt = $conn->prepare("SELECT zip FROM Location WHERE zip = ?");
      $stmt->bind_param("s", $zip);
      $stmt->execute();
      $result = $stmt->get_result();
      
      if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO Location (zip, name, population) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $zip, $name, $population);
        $stmt->execute();
      }
      
      $stmt = $conn->prepare("INSERT INTO Disaster (date, type) VALUES (?, ?)");
      $stmt->bind_param("ss", $date, $type);
      $stmt->execute();
      $disaster_id = $conn->insert_id;
      
      
      $stmt = $conn->prepare("INSERT INTO Occured (disaster_id, zip) VALUES (?, ?)");
      $stmt->bind_param("is", $disaster_id, $zip);
      $stmt->execute();
      
      $conn->commit();
      $message = "✅ Disaster and location added successfully.";
    } catch (Exception $e) {
      $conn->rollback();
      $message = "❌ Error: " . $e->getMessage();
    }
    $stmt->close();
  }

  if (isset($_POST['add_storage'])) {
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $stmt = $conn->prepare("INSERT INTO Storage (quantity, price) VALUES (?, ?)");
    $stmt->bind_param("id", $quantity, $price);
    $stmt->execute();
    $message = "✅ Item added to storage.";
    $stmt->close();
  }

  if (isset($_POST['assign_volunteer'])) {
    $nid = $_POST['nid'];
    $item_id = $_POST['item_id'];
    $zip = $_POST['zip'];
    $stmt = $conn->prepare("UPDATE Volunteers SET item_id = ?, zip = ? WHERE NID = ?");
    $stmt->bind_param("iss", $item_id, $zip, $nid);
    $stmt->execute();
    $message = "✅ Volunteer assigned.";
    $stmt->close();
  }

  if (isset($_POST['delete_disaster'])) {
    $id = $_POST['disaster_id'];
    

    $conn->begin_transaction();
    
    try {
      $stmt = $conn->prepare("DELETE FROM Occured WHERE disaster_id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      
      $stmt = $conn->prepare("DELETE FROM Disaster WHERE disaster_id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      
      $conn->commit();
      $message = "🗑 Disaster and related records deleted successfully.";
    } catch (Exception $e) {
      $conn->rollback();
      $message = "❌ Error: " . $e->getMessage();
    }
    $stmt->close();
  }

  if (isset($_POST['delete_user'])) {
    $u_id = $_POST['user_id'];
    
    try {
      $stmt = $conn->prepare("DELETE FROM user WHERE u_id = ?");
      $stmt->bind_param("i", $u_id);
      $stmt->execute();
      
      if ($stmt->affected_rows > 0) {
        $message = "🗑 User deleted successfully.";
      } else {
        $message = "❌ No user found with the given ID.";
      }
    } catch (Exception $e) {
      $message = "❌ Error: " . $e->getMessage();
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel - ReliefAid</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      padding: 20px;
    }
    h2 {
      color: #007BFF;
    }
    form {
      background: white;
      padding: 20px;
      margin-bottom: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
    }
    input, select {
      padding: 8px;
      margin: 5px;
      width: 95%;
    }
    input[type="submit"] {
      background: #28a745;
      color: white;
      width: auto;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .message {
      color: green;
      font-weight: bold;
      margin-bottom: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      background: white;
    }
    table, th, td {
      border: 1px solid #ccc;
      padding: 10px;
    }
    th {
      background: #f9f9f9;
    }
  </style>
</head>
<body>

<header style="display: flex; justify-content: space-between; align-items: center; background-color: #007BFF; color: white; padding: 15px 30px; margin: -20px -20px 20px -20px;">
  <h1 style="margin: 0;">Admin Panel</h1>
  <a href="index.php" style="background-color: white; color: #007BFF; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold;">Logout</a>
</header>

<?php if ($message) echo "<p class='message'>$message</p>"; ?>

<?php
$result = $conn->query("SELECT SUM(amount) as total FROM transaction");
$row = $result->fetch_assoc();
$total_money = $row['total'] ?? 0;

$result = $conn->query("SELECT SUM(price * quantity) as total FROM Storage");
$row = $result->fetch_assoc();
$money_spent = $row['total'] ?? 0;

$fund_remaining = $total_money - $money_spent;
?>

<div style="background: white; padding: 20px; margin-bottom: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc;">
  <h2>Financial Overview</h2>
  <p><strong>Total Money Raised:</strong> ৳<?php echo number_format($total_money, 2); ?></p>
  <p><strong>Money Spent:</strong> ৳<?php echo number_format($money_spent, 2); ?></p>
  <p><strong>Fund Remaining:</strong> ৳<?php echo number_format($fund_remaining, 2); ?></p>
</div>

<form method="post">
  <h2>Add Disaster and Location</h2>
  <input type="date" name="date" placeholder="Disaster Date" required>
  <input type="text" name="type" placeholder="Disaster type (e.g. flood)" required>
  <input type="text" name="zip" placeholder="ZIP Code" required>
  <input type="text" name="name" placeholder="Location Name" required>
  <input type="number" name="population" placeholder="Population" required>
  <input type="submit" name="add_disaster_location" value="Add Disaster and Location">
</form>


<form method="post">
  <h2>Add Storage Item</h2>
  <input type="number" name="quantity" placeholder="Quantity" required>
  <input type="number" step="0.01" name="price" placeholder="Price" required>
  <input type="submit" name="add_storage" value="Add Storage Item">
</form>


<form method="post">
  <h2>Assign Volunteer to Item and Location</h2>
  <input type="text" name="nid" placeholder="Volunteer NID" required>
  <input type="number" name="item_id" placeholder="Item ID" required>
  <input type="text" name="zip" placeholder="ZIP Code of Location" required>
  <input type="submit" name="assign_volunteer" value="Assign Volunteer">
</form>


<form method="post">
  <h2>Delete Disaster</h2>
  <input type="number" name="disaster_id" placeholder="Disaster ID" required>
  <input type="submit" name="delete_disaster" value="Delete Disaster" style="background: #dc3545;">
</form>


<form method="post">
  <h2>Delete User</h2>
  <input type="number" name="user_id" placeholder="User ID" required>
  <input type="submit" name="delete_user" value="Delete User" style="background: #dc3545;">
</form>


<h2>Current Disasters and Locations</h2>
<table>
  <tr>
    <th>Disaster ID</th>
    <th>Date</th>
    <th>Type</th>
    <th>Location ZIP</th>
    <th>Location Name</th>
    <th>Population</th>
  </tr>
  <?php
  $query = "SELECT d.disaster_id, d.date, d.type, l.zip, l.name, l.population 
            FROM Disaster d 
            INNER JOIN Occured o ON d.disaster_id = o.disaster_id 
            INNER JOIN Location l ON o.zip = l.zip
            ORDER BY d.date DESC";
  $res = $conn->query($query);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      echo "<tr>
        <td>{$row['disaster_id']}</td>
        <td>{$row['date']}</td>
        <td>{$row['type']}</td>
        <td>{$row['zip']}</td>
        <td>{$row['name']}</td>
        <td>{$row['population']}</td>
      </tr>";
    }
  } else {
    echo "<tr><td colspan='6'>Error fetching data: " . $conn->error . "</td></tr>";
  }
  ?>
</table>

<h2>Storage Inventory</h2>
<table>
  <tr><th>Item ID</th><th>Quantity</th><th>Price</th></tr>
  <?php
  $res = $conn->query("SELECT * FROM Storage");
  while ($row = $res->fetch_assoc()) {
    echo "<tr><td>{$row['item_id']}</td><td>{$row['quantity']}</td><td>{$row['price']}</td></tr>";
  }
  ?>
</table>

<h2>Assigned Volunteers</h2>
<table>
  <tr>
    <th>NID</th>
    <th>Name</th>
    <th>Contact</th>
    <th>Item ID</th>
    <th>Location ZIP</th>
  </tr>
  <?php
  $query = "SELECT * FROM Volunteers WHERE item_id IS NOT NULL AND zip IS NOT NULL";
  $res = $conn->query($query);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      echo "<tr>
        <td>{$row['NID']}</td>
        <td>{$row['name']}</td>
        <td>{$row['contact']}</td>
        <td>{$row['item_id']}</td>
        <td>{$row['zip']}</td>
      </tr>";
    }
  } else {
    echo "<tr><td colspan='5'>Error fetching assigned volunteers: " . $conn->error . "</td></tr>";
  }
  ?>
</table>

<h2>Registered Volunteers</h2>
<table>
  <tr>
    <th>NID</th>
    <th>Name</th>
    <th>Contact</th>
  </tr>
  <?php
  $query = "SELECT NID, name, contact FROM Volunteers WHERE item_id IS NULL OR zip IS NULL";
  $res = $conn->query($query);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      echo "<tr>
        <td>{$row['NID']}</td>
        <td>{$row['name']}</td>
        <td>{$row['contact']}</td>
      </tr>";
    }
  } else {
    echo "<tr><td colspan='3'>Error fetching registered volunteers: " . $conn->error . "</td></tr>";
  }
  ?>
</table>

<h2>User Information</h2>
<table>
  <tr>
    <th>User ID</th>
    <th>Username</th>
    <th>Address</th>
    <th>Contact</th>
  </tr>
  <?php
  $query = "SELECT u_id, username, address, contact FROM user WHERE u_id IS NOT NULL AND username IS NOT NULL AND address IS NOT NULL AND contact IS NOT NULL";
  $res = $conn->query($query);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      echo "<tr>
        <td>{$row['u_id']}</td>
        <td>{$row['username']}</td>
        <td>{$row['address']}</td>
        <td>{$row['contact']}</td>
      </tr>";
    }
  } else {
    echo "<tr><td colspan='4'>Error fetching user data: " . $conn->error . "</td></tr>";
  }
  ?>
</table>

</body>
</html>