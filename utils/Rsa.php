<?php

require_once 'vendor/autoload.php';

$rsa = new \Crypt_RSA();
$rsa->loadKey('-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDlOJu6TyygqxfWT7eLtGDwajtN
FOb9I5XRb6khyfD1Yt3YiCgQWMNW649887VGJiGr/L5i2osbl8C9+WJTeucF+S76
xFxdU6jE0NQ+Z+zEdhUTooNRaY5nZiu5PgDB0ED/ZKBUSLKL7eibMxZtMlUDHjm4
gwQco1KRMDSmXSMkDwIDAQAB
-----END PUBLIC KEY-----');
// public key
$plaintext = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed gravida felis sit amet nulla accumsan, sed mollis elit tristique. Vivamus fermentum mauris et tellus feugiat luctus. Suspendisse faucibus, orci sed feugiat lobortis, nulla nunc vestibulum nibh, sed vulputate ipsum felis ac nisl. Sed sit amet est a felis posuere mollis eu placerat risus. Mauris eget nisl condimentum, varius sapien vitae, mattis nisl. Nulla porta eu nulla at imperdiet. Integer sollicitudin, ipsum nec tempus rhoncus, ipsum massa elementum sapien, ac malesuada orci augue eu nibh. Quisque posuere porttitor magna id finibus. Nunc porttitor eros et erat semper sagittis. Pellentesque sed luctus sem. Sed vulputate massa mollis lacus tincidunt auctor. Praesent aliquet quis diam sit amet rutrum. Sed mauris sem, placerat sed ex ac, hendrerit lobortis enim. Etiam egestas ex orci. Integer in varius ex, nec scelerisque tortor.';
//$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
$ciphertext = $rsa->encrypt($plaintext);
$rsa->loadKey('-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDlOJu6TyygqxfWT7eLtGDwajtNFOb9I5XRb6khyfD1Yt3YiCgQ
WMNW649887VGJiGr/L5i2osbl8C9+WJTeucF+S76xFxdU6jE0NQ+Z+zEdhUTooNR
aY5nZiu5PgDB0ED/ZKBUSLKL7eibMxZtMlUDHjm4gwQco1KRMDSmXSMkDwIDAQAB
AoGAfY9LpnuWK5Bs50UVep5c93SJdUi82u7yMx4iHFMc/Z2hfenfYEzu+57fI4fv
xTQ//5DbzRR/XKb8ulNv6+CHyPF31xk7YOBfkGI8qjLoq06V+FyBfDSwL8KbLyeH
m7KUZnLNQbk8yGLzB3iYKkRHlmUanQGaNMIJziWOkN+N9dECQQD0ONYRNZeuM8zd
8XJTSdcIX4a3gy3GGCJxOzv16XHxD03GW6UNLmfPwenKu+cdrQeaqEixrCejXdAF
z/7+BSMpAkEA8EaSOeP5Xr3ZrbiKzi6TGMwHMvC7HdJxaBJbVRfApFrE0/mPwmP5
rN7QwjrMY+0+AbXcm8mRQyQ1+IGEembsdwJBAN6az8Rv7QnD/YBvi52POIlRSSIM
V7SwWvSK4WSMnGb1ZBbhgdg57DXaspcwHsFV7hByQ5BvMtIduHcT14ECfcECQATe
aTgjFnqE/lQ22Rk0eGaYO80cc643BXVGafNfd9fcvwBMnk0iGX0XRsOozVt5Azil
psLBYuApa66NcVHJpCECQQDTjI2AQhFc1yRnCU/YgDnSpJVm1nASoRUnU8Jfm3Oz
uku7JUXcVpt08DFSceCEX9unCuMcT72rAQlLpdZir876
-----END RSA PRIVATE KEY-----');
// private key
$decryptedText = $rsa->decrypt($ciphertext);
// $this->assertEquals($decryptedText, $plaintext);
dd('ddd',$decryptedText);

$access_token = $this->getAcessToken()->access_token;

private function getAcessToken()
{
    try {
        $newSecret= $this->encryptSecret($this->clientSecret); 
        // dd($this->clientId,$newSecret);
        $chaveB64 = base64_encode("$this->clientId:$newSecret");
        // dd('auxiliar',$this->encryptSecret('1234'));  

        $this->http_auth = new HttpClient([
            'base_uri' => $this->base_uri,  
            'headers' => [
                'Content-Type'   => 'application/json',
                'Accept'         => 'application/json',
                'Authorization'  => 'Basic '.$chaveB64
            ]
        ]);  
        $response = $this->http_auth->request("POST",'v1/Auth/Client');
    } catch (\Exception $ex) { dd($ex->getMessage());
        throw new \Exception("Erro ao solicitar o token (0007) / {$ex->getMessage()}", $ex->getCode());
    }  
    return ResponseHandler::success((string)$response->getBody());
}


private function encryptSecret($secret)
{
    $secretJson = json_encode([
        "secret"    => $secret,
        "timestamp" => date_format(date_add(date_create(), date_interval_create_from_date_string('3 hours')), "Y-m-d\TH:i:s.u\Z")
    ]);

    $modulus = $this->clientModulus;
    $rsa = new \Crypt_RSA();
    $rsa->loadKey(array(
        'e' => new \Math_BigInteger(65537),
        'n' => new \Math_BigInteger(base64_decode($modulus), 256)
    ));
    $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);

    return base64_encode($rsa->encrypt($secretJson));
}
