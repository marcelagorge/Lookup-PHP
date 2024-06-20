<?php
include 'db.php';

$registration_successful = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $registration_successful = true;
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.php">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap">
</head>
<body>
    <div class="container">
        <h1>Registrar</h1>
        <form method="post" action="register.php">
            <input type="text" name="username" class="form-control" placeholder="Usuário" required>
            <input type="password" name="password" class="form-control mt-2" placeholder="Senha" required>
            <button type="submit" class="btn btn-custom mt-2">Registrar</button>
        </form>

        <?php if ($registration_successful): ?>
            <p class="message mt-2">Registro bem-sucedido! <a href="login.php"><button class="btn btn-custom">Fazer Login</button></a></p>
        <?php endif; ?>
    </div>
</body>
</html>
