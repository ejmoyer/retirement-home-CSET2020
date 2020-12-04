<?php
session_start();
if ($_SESSION['user']) {
  unset($_SESSION['user']);
  unset($_SESSION['access']);

  header('Location: ../home.html');
  exit;
}
?>
