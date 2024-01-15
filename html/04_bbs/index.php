<?php

session_start();

if (isset($_SESSION['username'])) {
    echo <<<EOM
    {$_SESSION['username']}
    <form action="/04_bbs/logout/" method="post">
      <input type="submit" value="ログアウト" />
    </form>
    EOM;
} else {
    header('Location: /04_bbs/login/');
}
