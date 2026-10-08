<?php
require('db.php');



header('Content-Type: application/json');
$login = filter_var($_POST['login-login'], FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_var($_POST['login-password'], FILTER_SANITIZE_SPECIAL_CHARS);

$q = 'SELECT id, role_id, password_hash FROM users WHERE email = ?';
$query = $pdo->prepare($q);
$query->execute([$login]);
$user_data = $query->fetch(PDO::FETCH_ASSOC);

if ($query->rowCount() == 0 || !password_verify($password, $user_data['password_hash'])){
    http_response_code(403);
    $response = [
        'status' => 'error',
        'message' => 'Неверные данные',
    ];
} else {
    session_regenerate_id(true);
    $_SESSION['uid'] = $user_data['id'];
    $_SESSION['role'] = $user_data['role_id'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['last_activity'] = time();

    $response = [
        'status' => 'success',
        'message' => "Успешная авторизация!"
    ];

}
    

echo json_encode($response);
exit;



