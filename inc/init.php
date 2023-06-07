<?php


// definir le fuseau horaire
date_default_timezone_set(('Europe/Paris'));

// ouvrir la session (personnaliser nom du cookie)
session_name('BLOGSESSION');
session_start();

// connexion a la BDD
$pdo = new PDO('mysql:host=localhost;charset=utf8;dbname=blog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));

// constante
define('URLSITE','/php-altrh/blog/');

// inclusion des fonctions
require_once('functions.php');