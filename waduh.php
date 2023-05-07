<?php
 
function request($url, $data = null, $headers = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if($data):
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($headers):
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_HEADER, 1);
    endif;
 
    curl_setopt($ch, CURLOPT_ENCODING, "GZIP");
    return curl_exec($ch);
}
function getstr($str, $exp1, $exp2)
{
    $a = explode($exp1, $str)[1];
    return explode($exp2, $a)[0];
}
 
function generateRandomNumber() {
    $numDigits = rand(8, 10);
    $min = pow(10, $numDigits - 1);
    $max = pow(10, $numDigits) - 1;
    return rand($min, $max);
}
 
function generateRandomString() {
    $bytes = random_bytes(16);
    $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    return $uuid;
}
 
echo "Email : ";
$email = trim(fgets(STDIN));
echo "Password : ";
$password = trim(fgets(STDIN));
 
awal:
$uuid = generateRandomString();
$num = generateRandomNumber();
 
$url = "https://www.vidio.com/api/partner/auth";
$headers = array();
$headers[] = "referer: androidtv-app://com.vidio.android.tv";
$headers[] = "x-api-platform: tv-android";
$headers[] = "x-api-auth: laZOmogezono5ogekaso5oz4Mezimew1";
$headers[] = "user-agent: tv-android/1.84.0 (405)";
$headers[] = "x-api-app-info: tv-android/7.1.2/1.84.0-405";
$headers[] = "accept-language: en";
$headers[] = "x-visitor-id: $uuid";
$headers[] = "content-type: application/json; charset=UTF-8";
$headers[] = "accept-encoding: gzip";
$data = '{"serial_number":"'.$num.'","partner_agent":"Polytron 2K Smart TV"}';
$partnerAuth = request($url, $data, $headers);
if(strpos($partnerAuth, 'authentication_token')!==false)
{
    $emailPartner = getstr($partnerAuth, 'email":"','"');
    $authTokenPartner = getstr($partnerAuth, 'authentication_token":"','"');
}
else
{
    goto awal;
}
 
 
echo "Activating Subscription : ";
login:
$url = "https://www.vidio.com/api/login";
$headers = array();
$headers[] = "referer: androidtv-app://com.vidio.android.tv";
$headers[] = "x-api-platform: tv-android";
$headers[] = "x-api-auth: laZOmogezono5ogekaso5oz4Mezimew1";
$headers[] = "user-agent: tv-android/1.84.0 (405)";
$headers[] = "x-api-app-info: tv-android/7.1.2/1.84.0-405";
$headers[] = "accept-language: en";
$headers[] = "x-user-email: $emailPartner";
$headers[] = "x-user-token: $authTokenPartner";
$headers[] = "x-visitor-id: $uuid";
$headers[] = "content-type: application/x-www-form-urlencoded";
$headers[] = "accept-encoding: gzip";
$data = "login=$email&password=$password";
$login = request($url, $data, $headers);
if(strpos($login, 'authentication_token')!==false)
{
    echo "Success\n";
    $authToken = getstr($login, 'authentication_token":"','"');
}
else if(strpos($login, '"error":"')!==false)
{
    $errorMessage = getstr($login, '"error":"','"');
    echo "$errorMessage\n";
}
else
{
    goto login;
}
 
echo "Checking Subscription : ";
cek:
$url = "https://www.vidio.com/api/users/has_active_subscription";
$headers = array();
$headers[] = "referer: androidtv-app://com.vidio.android.tv";
$headers[] = "x-api-platform: tv-android";
$headers[] = "x-api-auth: laZOmogezono5ogekaso5oz4Mezimew1";
$headers[] = "user-agent: tv-android/1.84.0 (405)";
$headers[] = "x-api-app-info: tv-android/7.1.2/1.84.0-405";
$headers[] = "accept-language: en";
$headers[] = "x-user-email: $email";
$headers[] = "x-user-token: $authToken";
$headers[] = "x-visitor-id: $uuid";
$headers[] = "accept-encoding: gzip";
$cek = request($url, null, $headers);
if(strpos($cek, '"has_active_subscription":true')!==false)
{
    echo "Active\n";
}
else if(strpos($cek, '"has_active_subscription":false')!==false)
{
    echo "Not Yet Active, Try Again\n";
}
else
{
    goto cek;
}
