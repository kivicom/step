<?php
session_start();

unset($_SESSION['user']);
if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])) {
    setcookie("id", "", time() - 3600 * 24 * 30 * 12, "/");
    setcookie("hash", "", time() - 3600 * 24 * 30 * 12, "/");
}
echo header('Location: http://marlinstep.loc/');
exit();