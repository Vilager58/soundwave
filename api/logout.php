<?php
session_start();
$_SESSION = array();

session_destroy();
$response = [
    'status' => 'success',
    'message' => "Успешный выход"
];

echo json_encode($response);
exit;
?>