<?php
require('db.php');
header('Content-Type: application/json');
$name = filter_var($_POST['reg-name'], FILTER_SANITIZE_SPECIAL_CHARS);
$lastname = filter_var($_POST['reg-lastname'], FILTER_SANITIZE_SPECIAL_CHARS);
$patronymic = filter_var($_POST['reg-patronymic'], FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_var($_POST['reg-email'], FILTER_SANITIZE_SPECIAL_CHARS);
$phone = filter_var($_POST['reg-phone'], FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_var($_POST['reg-password'], FILTER_SANITIZE_SPECIAL_CHARS);

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$q = 'INSERT INTO users(firstname, lastname, patronymic, email, phone, password_hash) VALUES(?, ?, ?, ?, ?, ?)';
$query = $pdo -> prepare($q);
$query -> execute([$name, $lastname, $patronymic, $email, $phone, $hashedPassword]);

$q = 'SELECT id, role_id, password_hash FROM users WHERE email = ?';
$query = $pdo->prepare($q);
$query->execute([$login]);
$user_data = $query->fetch(PDO::FETCH_ASSOC);

$response = [
    'status' => 'success',
    'message' => "Успешная Регистрация"
];

session_regenerate_id(true);
$_SESSION['uid'] = $user_data['id'];
$_SESSION['role'] = $user_data['role_id'];
$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
$_SESSION['last_activity'] = time();

echo json_encode($response);
exit;



