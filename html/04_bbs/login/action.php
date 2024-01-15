<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    http_response_code(400);
    exit;
}

session_start();

try {
    $dbh = new PDO('mysql:host=localhost;dbname=mysql', 'vagrant', 'vagrant');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $dbh->prepare('select * from users where username = :username');
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        header('Location: /04_bbs/');
    } else {
        echo <<<EOM
        ユーザー名またはパスワードが正しくありません<br />
        <a href="javascript:history.back()">戻る</a>
        EOM;
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
