<?php
require __DIR__ . '/config.php';

function push_fcm($title, $content, $token)
{
    $message = [
        'title' => $title,
        'body' => $content,
        // 'icon' => '',
        // 'sound' => ''
    ];

    $fields = array(
        // 'to' => $registrationIds,
        'to' => $token,
        'notification' => $message
    );

    $headers = array(
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function get_subscribed_users()
{
    $users = [];
    foreach (scandir(USER_TOKEN_DIR) as $filename) {
        $users[$filename] = file_get_contents(USER_TOKEN_DIR . "/$filename");
    }

    return array_filter($users);
}

$users = get_subscribed_users();
$result = [];

$title = filter_input(INPUT_POST, 'title') ?: '末班車';
$content = filter_input(INPUT_POST, 'content') ?: '別回頭，末班車要開惹';

foreach ($users as $user_token) {
    $result[] = push_fcm($title, $content . '; timestamp: ' .  time(), $user_token);
}

header('Content-Type:application/json');
echo json_encode($result);
