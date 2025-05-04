<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ReliefAid Login</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: url('https://www.reuters.com/resizer/v2/32OZC5IAGFONNOCPJCDNWAODFY.jpg?auth=73b31a3bc879bfbebb408a0656f108a349dcd7e9777e82cfc0609d912b753c6b&width=1920&quality=80') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-box {
      background: rgba(255, 255, 255, 0.9);
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
      background: #007BFF;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .login-box input[type="submit"]:hover {
      background: #0056b3;
    }

    .login-box .link {
      text-align: center;
      margin-top: 10px;
    }

    .login-box .link a {
      color: #007BFF;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>ReliefAid</h2>
    <form action="login.php" method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="submit" value="Login">
      <div class="link">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
      </div>
    </form>
  </div>

</body>
</html>
