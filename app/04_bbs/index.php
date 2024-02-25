<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);

session_start();

try {
    $dbh = new PDO('mysql:host=localhost;dbname=development_db', 'vagrant', 'vagrant');

    $stmt = $dbh->prepare('select * from posts order by created_at');
    $stmt->execute();
    $posts = $stmt->fetchAll();

    foreach ($posts as &$post) {
        $stmt = $dbh->prepare('select * from users where id = :id');
        $stmt->bindValue(':id', $post['user_id']);
        $stmt->execute();
        $post['user'] = $stmt->fetch();
    }
    unset($post);

    if (isset($_SESSION['user_id'])) {
        $stmt = $dbh->prepare('select * from users where id = :id');
        $stmt->bindValue(':id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch();
    }

    echo $twig->render('index.html', ['posts' => $posts, 'user' => $user]);
} catch (PDOException $e) {
    echo $e->getMessage();
}
