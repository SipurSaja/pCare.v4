<?php  
	header('Content-Type: application/json'); 
	//error_log(0);
 
	//require 'vendor/autoload.php';
         
			$uri		="https://apijkn-dev.bpjs-kesehatan.go.id/pcare-rest-dev/peserta/0000085959819"; 
			$consID 	= "26993"; //customer ID anda PUSEKSMAS PARAKAN
			$secretKey 	= "7lG1D36E9B"; //secretKey anda
            $userKey 	= "43f2d31d333d6b4c89a267929574bafc"; //secretKey anda
            
            $pcareUname = "11180801"; //username pcare
			$pcarePWD 	= "Parakan321*"; //password pcare anda 
			$kdAplikasi	= "095"; //kode aplikasi ==>ini yag belum tahu
			
			$stamp		= time();
			$data 		= $consID.'&'.$stamp;
			

			$signature = hash_hmac('sha256', $data, $secretKey, true);

			$encodedSignature = base64_encode($signature);	
			$encodedAuthorization = base64_encode($pcareUname.':'.$pcarePWD.':'.$kdAplikasi);	
			
			$headers = array( 
		            "Accept: application/json", 
		            "X-cons-id:".$consID, 
		            "X-timestamp: ".$stamp, 
		            "X-signature: ".$encodedSignature, 
		            "user_key: ".$userKey, 
		            "X-authorization: Basic " .$encodedAuthorization 
		        ); 

			$ch = curl_init(); 

		    curl_setopt($ch, CURLOPT_URL, $uri);
		    curl_setopt ($ch, CURLOPT_POST, false);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
		    // echo $season_data;
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