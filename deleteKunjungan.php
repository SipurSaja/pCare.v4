<?php  
	header('Content-Type: application/json'); 
	//error_log(0);
 $noKunjungan='111808010124Y000001'; //$_GET["noka"];
	require 'vendor/autoload.php';
    $uri="https://apijkn-dev.bpjs-kesehatan.go.id/pcare-rest-dev/kunjungan/$noKunjungan"; 
    $consID 	  = "26993"; //customer ID anda PUSEKSMAS PARAKAN
    $secretKey 	= "7lG1D36E9B"; //secretKey anda
    $userKey 	  = "43f2d31d333d6b4c89a267929574bafc"; //userKey anda
    
    $pcareUname = "develop-11180801"; //username pcare
    $pcarePWD 	= "Pcare123#"; //password pcare anda 
    $kdAplikasi	= "095"; //kode aplikasi ==>ini yag belum tahu
    
    $stamp		= time();
    $data 		= $consID.'&'.$stamp;
    $signature = hash_hmac('sha256', $data, $secretKey, true);

    $encodedSignature = base64_encode($signature);	
    $encodedAuthorization = base64_encode($pcareUname.':'.$pcarePWD.':'.$kdAplikasi);		
    // echo $stamp;  echo "<br>";
// echo $encodedSignature; die();
// echo $encodedAuthorization;
    $headers = array( 
        "Accept: application/json", 
        "X-cons-id:".$consID, 
        "X-timestamp: ".$stamp, 
        "X-signature: ".$encodedSignature, 
        "user_key: ".$userKey, 
        "X-authorization: Basic " .$encodedAuthorization ,
        'Content-Type: text/plain'
    ); 

    // $noBPJS			='0002044148275';
    // $Kunjungan		='001';
    // $tglKunjungan	='09-01-2024';
    // $KdProvider		='0171B011';
 

 
// echo $fields_string; die();
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $uri);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $season_data = curl_exec($ch);

    if (curl_errno($ch)) {
        print "Error: " . curl_error($ch);
        exit();
    }

    // Show me the result
    curl_close($ch);
    //$json= json_decode($season_data, true);
    $data = json_decode($season_data, true);
    echo $season_data; 
    
    // echo $data["response"]["message"];
    // die();

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
          
          // echo   $data["metaData"]['code'];
            $metadata=$data["metaData"]['code'];
            // echo "addsfdsd".$metadata;
       
          

            if($data["metaData"]['code']=="200"){
              	// $text = $data["response"];
            	// $decrypted = stringDecrypt($text);   
                // $response=decompress($decrypted);
                // $datajSon = json_decode($response, true); 
                $message=$data["response"];
            //    $field	=$datajSon["field"];  
             
          }   else{
            // $text = $data["response"];
            // $decrypted = stringDecrypt($text);   
            // $response=decompress($decrypted);
            // // $response= $data["metaData"]['message'];
            $message =  $data["response"][0]["message"];
            // echo $message;
            
		// echo $message;
          }

        //   echo "Response=".$response;


          echo "PESAN=".$message;
//SUDAH OK
 
     

  
 ?>