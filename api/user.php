<?php
session_start();
require('db.php');
$method = $_SERVER['REQUEST_METHOD'];

if (isset($_SESSION['uid'])) {
    if ($method === 'POST') {
        $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_SPECIAL_CHARS);
        $patronymic = filter_var($_POST['patronymic'], FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_var($_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS);
        $birthdate = filter_var($_POST['birthdate'], FILTER_SANITIZE_SPECIAL_CHARS);
        $instrument = filter_var($_POST['instrument'], FILTER_SANITIZE_SPECIAL_CHARS);
        $city = filter_var($_POST['city'], FILTER_SANITIZE_SPECIAL_CHARS);
        $adress = filter_var($_POST['adress'], FILTER_SANITIZE_SPECIAL_CHARS);
        $q = 'UPDATE users SET 
        firstname = ?, 
        lastname = ?, 
        patronymic = ?, 
        phone = ?, 
        birthdate = ?, 
        instrument = ?, 
        city = ?, 
        adress = ?
        WHERE id = ?';
        $query = $pdo->prepare($q);
        $query->execute([$firstname, $lastname, $patronymic, $phone, $birthdate, $instrument, $city, $adress, $_SESSION['uid']]);

        $response = [
            'status' => 'success',
            'message' => "Данные обновлены!"
        ];
    } elseif ($method === 'GET') {
        $uid = $_SESSION['uid'];
        $q = 'SELECT role_id, firstname, lastname, patronymic, email, phone, birthdate, bonus_balance, instrument, city, adress FROM users WHERE id = ?';
        $query = $pdo->prepare($q);
        $query->execute([$uid]);
        $user_data = $query->fetch(PDO::FETCH_ASSOC);
        $response = [
            'status' => 'success',
            'message' => [
                'role_id' => $user_data['role_id'],
                'firstname' => $user_data['firstname'],
                'lastname' => $user_data['lastname'],
                'patronymic' => $user_data['patronymic'],
                'email' => $user_data['email'],
                'phone' => $user_data['phone'],
                'birthdate' => $user_data['birthdate'],
                'bonus_balance' => $user_data['bonus_balance'],
                'instrument' => $user_data['instrument'],
                'city' => $user_data['city'],
                'adress' => $user_data['adress']
            ]
        ];

    }

} else {
    $response = [
        'status' => 'error',
        'message' => "Ошибка авторизации"
    ];
}


echo json_encode($response);
?>