<?php 
    function http_curl_request($method,$url,$json_data){
            $header = [
                'Content-Type: application/json',
                'Accept: application/json'
            ];
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data );
            curl_setopt($ch, CURLOPT_POSTREDIR, 3);
            $result = curl_exec($ch);
            curl_close($ch);
            return  $result;
    }
?>