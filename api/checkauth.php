<?php 
session_start();
if (!isset($_SESSION['uid'])) {
     $response = [
        'status' => 'error',
        'message' => "Ошибка авторизации"
    ];
    echo json_encode($response);
    exit;
}
?>