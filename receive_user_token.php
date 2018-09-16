<?php
require __DIR__ . '/config.php';

// System.IO.Directory.CreateDirectory(AppDomain.CurrentDomain.BaseDirectory + "user_token");
// System.IO.File.WriteAllText(
//    AppDomain.CurrentDomain.BaseDirectory +
//    "user_token" +
//    System.IO.Path.DirectorySeparatorChar +
//    GetMD5String(Request["user_token"]), Request["user_token"]);
// Response.Write("SUCCESS");

function save_user_token($user_token)
{
    is_dir(USER_TOKEN_DIR) or mkdir(USER_TOKEN_DIR, 0775);

    $filename = md5($user_token);
    $file = fopen(USER_TOKEN_DIR . "/$filename", 'w+');
    fwrite($file, $user_token);
}

$user_token = filter_input(INPUT_POST, 'user_token');
$response = 'ERROR';

if ($user_token) {
    save_user_token($user_token);
    $response = 'SUCCESS';
}

echo $response;
