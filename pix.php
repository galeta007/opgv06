<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON payload"]);
    exit;
}

$url = "https://api.blackcatoficial.com/api";
$secret = "sk_live_73c2843462646ec166e3ad9edd0e94ba6709c727fbf21c989b938648697dcd3b";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Basic " . base64_encode("x:{$secret}")
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode >= 200 && $httpcode < 300) {
    $push_ch = curl_init();
    curl_setopt($push_ch, CURLOPT_URL, $pushcut_url);
    curl_setopt($push_ch, CURLOPT_POST, 1);
    curl_setopt($push_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($push_ch, CURLOPT_TIMEOUT, 3);
    curl_exec($push_ch);
    curl_close($push_ch);
}

http_response_code($httpcode);
echo $response;
?>
