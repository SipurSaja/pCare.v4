<?php

	session_start();
	error_reporting(0);
	include "../../config/config.php";
	include "../../config/function.php";
	include "../../config/koneksi.php";
	include "../../config/fungsi.php";
	include "../../config/functions_editor.php"; 
	require '../../config/vendor/autoload.php';
   $noBpjs="noKunjungan";

   
	$consID=$_SESSION['consID'] ;
	$secretKey= $_SESSION['secretKey'] ;	    
	$userKey 	  = $_SESSION['userKey'] ;//"43f2d31d333d6b4c89a267929574bafc"; //userKey anda
 	$pcareUname=$_SESSION['userPcare'];
    $pcarePWD=$_SESSION['passPcare']; 
 

  /* No. Rujukan
FKTP
Kabupaten / Kota
:
:
:
111808011020Y000002
Parakan (11180801)
KAB. TEMANGGUNG(0163)
111808011020Y000002 */

$uri=url."/kunjungan/rujukan/".$noBpjs."";

		 
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
			//die();
			$data= json_decode($season_data, 1);

			$metadata=$data["metaData"]['code'];
            // echo "addsfdsd".$metadata; 
          

            if($metadata=="200"){
              	$text = $data["response"];
            	$decrypted = stringDecrypt($text);   
                $response=decompress($decrypted);
                $datajSon = json_decode($response, true); 
            //    $field	=$datajSon["field"]; 
               $noKunjungan	=$datajSon[0] ["message"]; 

			}

			// echo $response; die();

			$noRujukan	=$datajSon['noRujukan'] ;
			$kdPPK		=$datajSon['ppk']['kdPPK'] ;
			$nmPPK		=$datajSon['ppk']['nmPPK'] ;
			$kdKC		=$datajSon['ppk']['kc']['kdKC'] ;
			$nmKC		=ucwords(strtolower($datajSon['ppk']['kc']['nmKC'] ));
			$nmKr		=$datajSon['ppk']['kc']['kdKR']['nmKR'];
			$kdDati		=$datajSon['ppk']['kc']['dati']['kdDati'] ; 
			$nmDati		=$datajSon['ppk']['kc']['dati']['nmDati'] ;

			$nmPoli		=$datajSon['poli']['nmPoli'];
			$nmPPKRujuk		=$datajSon['ppkRujuk']['nmPPK'];

			$nmPst		=$datajSon['nmPst'];
			$tglLahir	=$datajSon['tglLahir'];
			$nokaPst	=$datajSon['nokaPst'];
			$pisa	=$datajSon['pisa'];//status
			$sex	=$datajSon['sex'];//JK
			$kdDiag	=$datajSon['diag1']['kdDiag'];//kdDiag
			$nmDiag	=$datajSon['diag1']['nmDiag'];//nmDiag
			$catatan	=$datajSon['catatan'];//nmDiag
			$tglEstRujuk	=$datajSon['tglEstRujuk'];//tgl rencana berkunjung
			$tglAkhirRujuk	=$datajSon['tglAkhirRujuk'];//tgl berakhir
			$jadwal	=$datajSon['jadwal'];//tgl jadwal
			
			$tglKunjungan	=$datajSon['tglKunjungan'];//tgl  berkunjung
			$nmDokter	=$datajSon['dokter']['nmDokter']; 



echo '<style type="text/css">
    body{
        font-family: tahoma;
        font-size: 1px;
    }
    h3{
        font-family: tahoma;
        font-size: 20px;
    }
    td{
        font-family: arial;
        font-size: 10px;
    }
    .title{
        font-family: arial;
        font-size: 10px;
		font-weight:bold;
    }
</style>';
?>
<center>
 <table align="center" cellpadding="0" cellspacing="0" border="0" width="95%">
                <tr>
                    <td width="55%"><img src="../images/logo_bpjs.png" width="195px"></td>
                    <td align="left" width="45%">
						<table> 
						<tr valign="top" height="25" ><td width="45%" class="title">
								Kedeputian Wilayah </td><td><?php echo $nmKr;?></td></tr>
								<tr height="25"><td class="title"> 
								Surat Rujukan FKTP </td><td>Kantor Cabang <?php echo $nmKC;?></td></tr>
						</table>								
                    </td>
                </tr>
				<tr class="title" height="25">
                    <td colspan="2" align="center">Surat Rujukan FKTP</td> 
                </tr>
</table>
<table width="95%" border="0" cellpadding="5" cellspacing="1" bgcolor="#000000">
    <tr height="155" bgcolor="#FFFFFF">
        <td width="100%"> 
            <table align="center" cellpadding="30"   border="0"  cellspacing="1" bgcolor="#000000" width="95%">
                <tr bgcolor="#FFFFFF"> 
                   <td width="100%" class="title"> 
				   <table width="100%">
						<tr bgcolor="#FFFFFF"> 
						<td width="85%" class="title"> 
						   <table width="95%"> 
						   <tr bgcolor="#FFFFFF" valign="top" height="25" ><td width="25%" class="title">
								NO Rujukan </td><td>: <?php echo $noRujukan;?> </td></tr> 
								<tr  bgcolor="#FFFFFF" height="25"><td class="title"> 
								FKTP </td><td>: <?php echo $nmPPK;?> (<?php echo $kdPPK;?>)</td></tr> 
								<tr bgcolor="#FFFFFF" height="25"><td class="title"> 
								Kabupaten/Kota </td><td>: <?php echo $nmDati;?> (<?php echo $kdDati;?>)</td></tr>
							</table>
						</td>
						
						<td align="center"> <img alt='testing' src='barcode.php?codetype=code39&size=30&text=<?php echo $noRujukan;?>&print=true'/> 
						</td>

						</tr>
						</table> 
						</td>
                </tr> 
            </table>


			 <table align="center" width="95%">
                <tr bgcolor="#FFFFFF" height="45"> 
                   <td width="15%"> 

							<table width="95%"> 
							<tr bgcolor="#FFFFFF" height="25"> 
								<td width="45%"> Kepada Yth. TS Dokter
								</td>
								<td>: <?php echo $nmPoli;?>
								</td>
							</tr>
							<tr bgcolor="#FFFFFF" height="25"> 
								<td width="45%"> Di
								</td>
								<td>: <?php echo $nmPPKRujuk;?>
								</td>
							</tr> 
							</table> 
				   
					</td> 
					<td>
					</td>
                </tr>  
			
				<tr bgcolor="#FFFFFF" height="45"> 
                   <td width="25%" colspan="2"> Mohon pemeriksaan dan penanganan lebih lanjut pasien:
					</td>
                </tr>
				<tr bgcolor="#FFFFFF" height="45"> 
                   <td width="25%">  
						<table width="100%"> 
						   <tr bgcolor="#FFFFFF" valign="top" height="25" ><td width="45%" class="title">
								Nama </td><td>: <?php echo $nmPst;?></td></tr> 
								<tr  bgcolor="#FFFFFF" height="25"><td class="title"> 
								No. Kartu BPJS </td><td>:  <?php echo $nokaPst;?></td></tr> 
								<tr bgcolor="#FFFFFF" height="25"><td class="title"> 
								Diagnosa </td><td>:  <?php echo $nmDiag;?> (<?php echo $kdDiag;?>)</td></tr>
						</table>
					</td>
					<td width="25%">  
					<table width="95%"> 
						   <tr bgcolor="#FFFFFF" valign="top" height="25" ><td width="15%" class="title">
								Umur </td><td>:  <?php echo hitung_umur($tglLahir); ?>    (<?php   echo $tglLahir;?>)</td></tr> 
								<tr  bgcolor="#FFFFFF" height="25"><td class="title"> 
								Status </td><td>: <?php echo $pisa;?>  Utama/Tanggungan  (<?php   echo $sex;?>)  </td></tr> 
								<tr bgcolor="#FFFFFF" height="25"><td class="title"> 
								Catatan </td><td>: <?php echo $catatan;?></td></tr>
						</table>
					</td>
                </tr>

				<tr bgcolor="#FFFFFF" height="45"> 
                   <td width="25%" colspan="2"> Telah diberikan:
					</td>
                </tr>

				<tr bgcolor="#FFFFFF" height="45"> 
                   <td width="25%" colspan="2">  
					</td>
                </tr>

				<tr bgcolor="#FFFFFF" height="45"> 
                   <td width="25%" colspan="2">Atas bantuannya diucapkan terimakasih.
					</td>
                </tr>
				<tr bgcolor="#FFFFFF" height="45"> 
                   <td width="25%" colspan="2">
						<table width="95%"> 
						   <tr bgcolor="#FFFFFF" valign="top" height="25" >
						   <td width="80%">
								Tgl. Berkunjung : <?php echo $tglEstRujuk; ?>
								<p>
								Jadwal Praktek : <?php echo $jadwal; ?>
								<p>
								Surat rujukan berlaku 1(satu) kali kunjungan, berlaku sampai dengan : <?php echo $tglAkhirRujuk; ?>
								</td>
								<td align="center">Salam sejawat,
								<p>
								<?php echo $tglKunjungan; ?>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<?php echo $nmDokter;  ?>
								</td></tr>  
						</table>
					</td>
                </tr> 

            </table> 


        </td>
    </tr>


</table>
</center>

  



