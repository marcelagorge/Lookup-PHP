<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Inserir postagem
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $content);

    if ($stmt->execute()) {
        echo "Postagem bem-sucedida!";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}

// Curtir postagem
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like_post_id'])) {
    $post_id = $_POST['like_post_id'];

    $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $post_id);

    if ($stmt->execute()) {
        echo "Post curtido!";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}

// Comentar postagem
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_post_id'])) {
    $post_id = $_POST['comment_post_id'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $post_id, $comment);

    if ($stmt->execute()) {
        echo "Comentário adicionado!";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}

// Consultar postagens
$query = "SELECT posts.id, posts.content, posts.created_at, users.username, 
                 (SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS like_count,
                 (SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS comment_count
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          ORDER BY posts.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rede Social</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.php">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?php echo $_SESSION['username']; ?>!</h1>
        <a href="logout.php"><button class="btn btn-custom">Logout</button></a>
        <form method="post" action="index.php">
            <textarea name="content" class="form-control" placeholder="O que você está pensando?" required></textarea>
            <button type="submit" class="btn btn-custom mt-2">Postar</button>
        </form>

        <h2 class="mt-4">Postagens Recentes</h2>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="post">
                <h3><?php echo htmlspecialchars($row['username']); ?></h3>
                <p><?php echo htmlspecialchars($row['content']); ?></p>
                <small><?php echo $row['created_at']; ?></small>
                <form method="post" action="index.php" class="like-form mt-2">
                    <input type="hidden" name="like_post_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-custom">Curtir (<?php echo $row['like_count']; ?>)</button>
                </form>
                <form method="post" action="index.php" class="comment-form mt-2">
                    <input type="hidden" name="comment_post_id" value="<?php echo $row['id']; ?>">
                    <textarea name="comment" class="form-control" placeholder="Escreva um comentário..." required></textarea>
                    <button type="submit" class="btn btn-custom mt-2">Comentar</button>
                </form>
                <p class="mt-2">Comentários (<?php echo $row['comment_count']; ?>)</p>
                <?php
                $comment_query = "SELECT comments.comment, comments.created_at, users.username 
                                  FROM comments 
                                  JOIN users ON comments.user_id = users.id 
                                  WHERE comments.post_id = ? 
                                  ORDER BY comments.created_at DESC";
                $comment_stmt = $conn->prepare($comment_query);
                $comment_stmt->bind_param("i", $row['id']);
                $comment_stmt->execute();
                $comment_result = $comment_stmt->get_result();

                while ($comment_row = $comment_result->fetch_assoc()):
                ?>
                    <div class="comment">
                        <h4><?php echo htmlspecialchars($comment_row['username']); ?></h4>
                        <p><?php echo htmlspecialchars($comment_row['comment']); ?></p>
                        <small><?php echo $comment_row['created_at']; ?></small>
                    </div>
                <?php endwhile; 
                $comment_stmt->close();
                ?>
            </div>
        <?php endwhile; ?>

        <?php $conn->close(); ?>
    </div>
</body>
</html>
