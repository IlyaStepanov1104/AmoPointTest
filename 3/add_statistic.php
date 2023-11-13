<?php
include_once 'config.php';
global $pdo;

$ip = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];

$ch = curl_init('http://ip-api.com/json/' . $ip . '?lang=ru');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$res = curl_exec($ch);
curl_close($ch);

$res = mb_convert_encoding($res, 'UTF-8', 'auto');

$res = json_decode($res, true);

$city = $res['city'];

echo '<pre>';
print_r($res);
echo '</pre>';

try {
    $sql = $pdo->prepare('INSERT INTO statistic SET ip="'.$ip.'", city="'.$city.'", user_agent="'.$userAgent.'"');
    $sql->execute();
} catch (PDOException $e){
    echo json_encode([
        'type' => 'error',
        'message' => $e->getMessage()
    ]);
    return;
}


echo json_encode([
    'type' => 'ok',
    'ip' => $ip,
    'city' => $res,
    'client' => $userAgent
]);