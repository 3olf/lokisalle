<?php
/* CONNEXION */
$pdo = new PDO('mysql:host=localhost;dbname=;', '', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

/* SESSION */
session_start();

/* FUNCTIONS */
require_once("functions.inc.php"); 

$msg_info = "";

// CONSTANTES
define("URL", "/lokisalle/"); 	// Chemin racine site
define("SERVER_ROOT", $_SERVER['DOCUMENT_ROOT'] );	// Chemin racine serveur