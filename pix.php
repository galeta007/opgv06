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

$url = "https://api.egitopay.com/v1/transactions";
$secret = "sk_19b07ce157b857b22710b20db7deef3d1f30c5e2";

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
    $pushcut_url = "https://api.pushcut.io/U0n2kN2R4cEzMDRSt1v-s/notifications/Venda%20Gui%20";
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
