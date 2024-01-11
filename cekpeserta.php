<?php 
	require 'vendor/autoload.php';

  $jenis=$_GET['jenis'];
  $nomor=$_GET['nomor'];
  $tanggal=$_GET['tanggal'];
  

  $uri="https://dvlp.bpjs-kesehatan.go.id/vclaim-rest-1.1/Peserta/".$jenis."/".$nomor."/tglSEP/".$tanggal;

  $consID   = "xxxxx";
  $secretKey  = "xxxxxxxx";
  $stamp    = time();
  $data     = $consID.'&'.$stamp;
  $signature = hash_hmac('sha256', $data, $secretKey, true);
  $encodedSignature = base64_encode($signature);  
  
  $headers = array( 
        "Accept: application/json", 
        "X-cons-id:".$consID, 
        "X-timestamp: ".$stamp, 
        "X-signature: ".$encodedSignature
    ); 

  $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $season_data = curl_exec($ch);
    if (curl_errno($ch)) {
        print "Error: " . curl_error($ch);
        exit();
    }
    curl_close($ch);
  
   $data = json_decode($season_data, true);

  function stringDecrypt($string){
    $output = false;
    $encrypt_method = 'AES-256-CBC';
    global $consID, $secretKey, $stamp;
    $signature = $consID . $secretKey . $stamp;
    // hash
    $key = hex2bin(hash('sha256', $signature));
      // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hex2bin(hash('sha256', $signature)), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, OPENSSL_RAW_DATA, $iv);
      return $output;
  }

// function lzstring decompress https://github.com/nullpunkt/lz-string-php
function decompress($string){
   return \LZCompressor\LZString::decompressFromEncodedURIComponent($string);
}

  $data["metaData"]['code'];
  if($data["metaData"]['code']=="200"){
    $text = $data["response"];
  $decrypted = stringDecrypt($text);
  echo decompress($decrypted);
  }else{
    echo $season_data;
  }
?>