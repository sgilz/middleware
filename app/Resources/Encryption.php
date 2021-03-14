<?php

namespace App\Resources;

class Encryption
{
    public static function generateCredentials()
    {
        //create encryption key
        $key_size = 32;
        $encryption_key = base64_encode(openssl_random_pseudo_bytes($key_size, $strong));

        //create initialization value
        $iv_size = 16;
        $iv = base64_encode(openssl_random_pseudo_bytes($iv_size, $strong));

        return array($encryption_key,$iv);
    }

    public static function encrypt($encryption_key,$iv,$data)
    {

        return openssl_encrypt(
            $data, // padded data
            'AES-256-CBC',        // cipher and mode
            base64_decode($encryption_key),      // secret key
            0,                    // options (not used)
            base64_decode($iv)                   // initialisation vector
        );
    }

    public static function decrypt($encryption_key,$iv,$encrypted_data)
    {
        return openssl_decrypt(
            $encrypted_data, // padded data
            'AES-256-CBC',        // cipher and mode
            base64_decode($encryption_key),      // secret key
            0,                    // options (not used)
            base64_decode($iv)                   // initialisation vector
        );
    }

}