<?php
include 'db.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: index.php");
    } else {
        echo "Usuário ou senha incorretos.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.php">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="post" action="login.php">
            <input type="text" name="username" class="form-control" placeholder="Usuário" required>
            <input type="password" name="password" class="form-control mt-2" placeholder="Senha" required>
            <button type="submit" class="btn btn-custom mt-2">Login</button>
        </form>
        <p class="mt-2">Não tem uma conta ainda? <a href="register.php">Registre-se agora</a></p>
    </div>
</body>
</html>
