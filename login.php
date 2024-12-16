<?php

session_start();

// Database connection details
$host="localhost";
$dbname= "";
$username= "root";
$password= "password123";

//establish database connection

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    die("Database connection failed...". $e->getMessage());
}

//Handling Session form

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = $conn->prepare("SELECT username FROM users WHERE username = :username AND password = :password");
    $sql->execute([
        "username"=> $user,
        "password"=> hash('sha256', $pass),
    ]);

    $result = $sql->fetch(PDO::FETCH_ASSOC);

    if($result){
        $_SESSION['username'] = htmlspecialchars($result['username']);
        header("Location: index.php");
        exit();
    }else{
        $error = "Error username and password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTD-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
    </head>

    <body>
        <h1>Login Page!!!</h1>
        <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    </body>

</html>