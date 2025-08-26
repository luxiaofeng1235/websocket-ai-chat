<?php

namespace app\common\server;

class AesServer
{
    private $key;
    private $iv;

    public function __construct()
    {
        $this->key = config('aes.aes_key');
        $this->iv = config('aes.aes_iv');
    }

    public function encrypt($data)
    {
        $encrypted = openssl_encrypt($data, 'AES-128-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv);

        return base64_encode($encrypted);
    }

    public function decrypt($data)
    {
        $decrypted = openssl_decrypt(base64_decode($data), 'AES-128-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv);

        return $decrypted;
    }
}