<?php
session_start();

function runWs($data)
{
    $url = "http://localhost:3003/ws/live2.php";
    $ch = curl_init();

    if ($data) {
        $data = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Content-Length: ' . strlen($data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);



    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_ENCODING,  '');
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        $salah = curl_error($ch);
        $hasil = [
            "error_code" => 1,
            "error_desc" => $salah,
            "data" => $data
        ];
        $result =  (object) $hasil;
        curl_close($ch);
        return json_encode($result);
        exit();
    }

    curl_close($ch);

    return $result;
}

function token()
{
    $data['act'] = "GetToken";
    $data['username'] = "DIISI_USERNAME";
    $data['password'] = "DIISI_PASSWORD";
    $token = json_decode(runWs($data));
    $_SESSION['feeder_token'] = $token->data->token;
    return $token;
}
