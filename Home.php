<?php
require_once('DBconnect.php');

// Get total people helped (sum of population from locations with current disasters)
$result = $conn->query("
    SELECT SUM(l.population) as total 
    FROM Location l 
    INNER JOIN Occured o ON l.zip = o.zip 
    INNER JOIN Disaster d ON o.disaster_id = d.disaster_id
");
$row = $result->fetch_assoc();
$people_helped = $row['total'] ?? 0;

// Get total money (sum of price * quantity from storage)
$result = $conn->query("SELECT SUM(amount) as total FROM transaction");
$row = $result->fetch_assoc();
$total_money = $row['total'] ?? 0;

// Get active volunteers count
$result = $conn->query("SELECT COUNT(*) as total FROM Volunteers");
$row = $result->fetch_assoc();
$active_volunteers = $row['total'] ?? 0;

// Get locations helped count
$result = $conn->query("SELECT COUNT(*) as total FROM Location");
$row = $result->fetch_assoc();
$locations_helped = $row['total'] ?? 0;

// Get active disasters with their details
$disasters = [];
$result = $conn->query("
    SELECT d.*, 
           l.name as location_name
    FROM Disaster d
    LEFT JOIN Occured o ON d.disaster_id = o.disaster_id
    LEFT JOIN Location l ON o.zip = l.zip
    GROUP BY d.disaster_id
    ORDER BY d.date DESC
");

while ($row = $result->fetch_assoc()) {
    if (!empty($row['location_name'])) {
        $disasters[] = [
            'summary' => $row['type'] . ' disaster on ' . $row['date'] . ' in ' . $row['location_name']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ReliefAid - Home</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f8f8f8;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #007BFF;
      color: white;
      padding: 15px 30px;
    }

    header h1 {
      margin: 0;
      font-size: 24px;
    }

    header a {
      background-color: white;
      color: #007BFF;
      padding: 8px 15px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
    }

    main {
      text-align: center;
      padding: 50px 20px;
    }

    .hero-buttons {
      margin: 30px 0;
    }

    .hero-buttons button {
      padding: 15px 30px;
      margin: 10px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      background-color: #28a745;
      color: white;
      cursor: pointer;
    }

    .stats {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 40px;
    }

    .stat-card {
      background: white;
      margin: 15px;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 200px;
    }

    .disaster-section {
      margin: 60px auto;
      max-width: 800px;
    }

    .disaster {
      background: white;
      border-radius: 10px;
      margin: 20px 0;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .disaster-info {
      padding: 20px;
      text-align: left;
    }

    .disaster-info p {
      margin: 5px 0;
    }

    footer {
      text-align: center;
      padding: 20px;
      background: #f0f0f0;
      color: #777;
    }
  </style>
</head>
<body>

<header>
  <h1>ReliefAid</h1>
  <a href="index.php">Logout</a>
</header>

<main>
  <div class="hero-buttons">
    <button onclick="window.location.href='donate.php'">Donate</button>
    <button onclick="window.location.href='volunteer.php'">Be a Volunteer</button>
  </div>

  <div class="stats">
    <div class="stat-card">
      <h3><?php echo number_format($people_helped); ?></h3>
      <p>People in Need</p>
    </div>
    <div class="stat-card">
      <h3>৳<?php echo number_format($total_money, 2); ?></h3>
      <p>Total Money Raised</p>
    </div>
    <div class="stat-card">
      <h3><?php echo number_format($active_volunteers); ?></h3>
      <p>Active Volunteers</p>
    </div>
    <div class="stat-card">
      <h3><?php echo number_format($locations_helped); ?></h3>
      <p>Affected Areas</p>
    </div>
  </div>

  <div class="disaster-section">
    <h2>Active Disasters Needing Support</h2>
    <?php if (empty($disasters)): ?>
      <p>No active disasters at the moment.</p>
    <?php else: ?>
      <?php foreach ($disasters as $disaster): ?>
        <div class="disaster">
          <div class="disaster-info">
            <p><strong>Summary:</strong> <?php echo $disaster['summary']; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<footer>
  &copy; <?php echo date("Y"); ?> ReliefAid. All rights reserved.
</footer>

</body>
</html>

