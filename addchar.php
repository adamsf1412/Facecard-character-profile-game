<?php
// addchar.php

include('config.php');

function bersihkan_input($conn, $data) {
    $data = trim($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    $data = $conn->real_escape_string($data);
    return $data;
}

$uid      = bersihkan_input($conn, $_POST['uid']);
$name     = bersihkan_input($conn, $_POST['name']);
$server   = bersihkan_input($conn, $_POST['server']);
$game     = bersihkan_input($conn, $_POST['game']);
$guild    = bersihkan_input($conn, $_POST['guild']);
$class    = bersihkan_input($conn, $_POST['class']);
$social   = bersihkan_input($conn, $_POST['social']);
$quote    = bersihkan_input($conn, $_POST['quote']);
$avatar   = bersihkan_input($conn, $_POST['avatar']);
$preview  = bersihkan_input($conn, $_POST['preview']);
$pass     = bersihkan_input($conn, $_POST['password']); 

$stmt = $conn->prepare("INSERT INTO characters (uid, name, server, game, guild, class, social, password, quote, avatar, preview) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssss", $uid, $name, $server, $game, $guild, $class, $social, $pass, $quote, $avatar, $preview);

if ($stmt->execute()) {
    http_response_code(200);
} else {
    http_response_code(500);
}

$stmt->close();
$conn->close();
?>