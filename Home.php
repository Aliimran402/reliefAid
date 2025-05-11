<?php
require_once('DBconnect.php');

// Dummy statistics (replace these with real database queries later)
$people_helped = 3542;
$total_money = 1250000;
$active_volunteers = 138;
$locations_helped = 27;

// Dummy disasters (in real case, fetch from `Disaster` + `Location` + `Occurred`)
$disasters = [
    [
        'image' => 'https://via.placeholder.com/600x200?text=Flood+in+Sylhet',
        'summary' => 'Massive flood displaced thousands in Sylhet region.',
        'needed' => 500000,
        'spent' => 120000,
        'volunteers' => 34
    ],
    [
        'image' => 'https://via.placeholder.com/600x200?text=Fire+in+Chattogram',
        'summary' => 'Warehouse fire affected hundreds of workers.',
        'needed' => 200000,
        'spent' => 50000,
        'volunteers' => 12
    ]
];
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

    .disaster img {
      width: 100%;
      height: auto;
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
  <a href="index.php">Login</a>
</header>

<main>
  <div class="hero-buttons">
    <button onclick="window.location.href='donate.php'">Donate</button>
    <button onclick="window.location.href='volunteer.php'">Be a Volunteer</button>
  </div>

  <div class="stats">
    <div class="stat-card">
      <h3><?php echo $people_helped; ?></h3>
      <p>People Helped</p>
    </div>
    <div class="stat-card">
      <h3>৳<?php echo number_format($total_money); ?></h3>
      <p>Total Money Raised</p>
    </div>
    <div class="stat-card">
      <h3><?php echo $active_volunteers; ?></h3>
      <p>Active Volunteers</p>
    </div>
    <div class="stat-card">
      <h3><?php echo $locations_helped; ?></h3>
      <p>Locations Helped</p>
    </div>
  </div>

  <div class="disaster-section">
    <h2>Active Disasters Needing Support</h2>
    <?php foreach ($disasters as $disaster): ?>
      <div class="disaster">
        <img src="<?php echo $disaster['image']; ?>" alt="Disaster Image">
        <div class="disaster-info">
          <p><strong>Summary:</strong> <?php echo $disaster['summary']; ?></p>
          <p><strong>Money Needed:</strong> ৳<?php echo number_format($disaster['needed']); ?></p>
          <p><strong>Money Spent:</strong> ৳<?php echo number_format($disaster['spent']); ?></p>
          <p><strong>Volunteers Assigned:</strong> <?php echo $disaster['volunteers']; ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<footer>
  &copy; <?php echo date("Y"); ?> ReliefAid. All rights reserved.
</footer>

</body>
</html>

