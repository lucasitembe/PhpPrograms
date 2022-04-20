<?php
class Signature
{

    function getPrivateKey($keyPass, $keyAlias, $keyFilePath){
        $privateKey ="";
        if (!$cert_store = file_get_contents($keyFilePath)) {
            echo "Error: Unable to read the cert file\n";
            exit;
        }
        else
        {
            if(!empty($keyAlias))
            {
                if (openssl_pkcs12_read($cert_store, $cert_info, $keyPass))
                {
                    $privateKey = $cert_info['pkey'];
                }
            }
        }

        return $privateKey;

    }

    function createSignature($content,$privateKeyPass,$privateKeyAlias, $privateKeyFilePath){

        $signature = "";
        $privateKey = $this->getPrivateKey($privateKeyPass,$privateKeyAlias, $privateKeyFilePath);
        if(!empty($privateKey) && !empty($content))
        {
            openssl_sign($content, $signature, $privateKey, "sha1WithRSAEncryption");
            $signature = base64_encode($signature);
        }
        return $signature;
    }

    function getPublicKey($keyPass, $keyAlias, $keyFilePath) {

        $publicKey ="";
        if (!$pcert_store = file_get_contents($keyFilePath)) {
            echo "Error: Unable to read the cert file\n";
            exit;
        }
        else
        {
            if(!empty($keyPass))
            {
                if (openssl_pkcs12_read($pcert_store,$pcert_info,$keyPass)) {
                    $publicKey = $pcert_info['extracerts']['0'];
                }
            }
        }
        return $publicKey;

    }

    function verifySignature($signature, $content, $publicKeyPass, $publicKeyAlias,$publicKeyFilePath)
    {
        $t = FALSE;

        $publicKey = $this->getPublicKey($publicKeyPass, $publicKeyAlias, $publicKeyFilePath);

        if(!empty($publicKey) && !empty($content))
        {
            $rawsignature = base64_decode($signature);
            $status = openssl_verify($content, $rawsignature, $publicKey);
            if ($status == 1) {
                $t = TRUE;
            } else if ($status == 0) {
                echo "\n\nSignature Status:"; //echo "BAD";
            }
        }

        return $t;
    }

}



