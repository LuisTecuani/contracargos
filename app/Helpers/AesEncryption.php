<?php

namespace App\Helpers;

use Illuminate\Encryption\Encrypter;

class AesEncryption
{
    public function __construct($request)
    {
        $this->request = $request;
        $this->key = env('Merchant_Request_Signature_Key');
        $this->iv = env('Merchant_Request_Signature_IV');
        $this->mode = 'AES-256-CBC';
        $this->encoding = 'HEX';
    }

//    /**
//     * Crypt data
//     *
//     * @param  string $data Data to encrypt
//     * @return string       Encrypted data
//     */
//    public function encript($data){
//        $requestIv	= pack('H*', $this->iv);
//        $requestKey	= pack('H*', $this->key);
//        $enc		= 'MCRYPT_RIJNDAEL_128';
//        $mode		= 'MCRYPT_MODE_CBC';
//        $block		= 16;
//        $pad		= $block - (strlen($data) % $block);
//        $data		.= str_repeat(chr($pad), $pad);
//        $cData	= bin2hex(mcrypt_encrypt($enc, $requestKey, $data, $mode, $requestIv));
//
//                $cData = openssl_encrypt($data, $this->mode, $requestKey, 0, $requestIv);
//
//
//        return $cData;
//    }

    public function encript($data)
    {
        $sha256 = hash('sha256', $data);



//        'aes-256-cbc-hmac-sha1'

//        OPENSSL_ZERO_PADDING
//        OPENSSL_RAW_DATA
        $bytes = unpack("H*",$sha256);

        $iv =  hex2bin($this->iv);
        $key = base64_decode($this->key);
        $hash = openssl_encrypt($bytes[1], $this->mode, $key, 1, $iv);
        $hexHash = bin2hex($hash);

//        $cripter = new Encrypter($this->key, $this->mode);


        $campare = 'fd72fcc16b66d04cf0f4dd2265a59eb675103482bae806b405bb85595056f
5770b3202b42d42a87b767892591a333eb6b5c9ad3ef34f4d415f8d3bbc3d
0f389e2e5b6f7cd915520c7b2c19225680728b';


//        $hash = unpack('C*', $data);

////        $encrypted = openssl_encrypt($hash, $this->mode, $this->key, 0, md5($this->iv, true));
        return $hash.'---'.$campare;
    }

    function strtohex($x)
    {
        $s='';
        foreach (str_split($x) as $c) $s.=sprintf("%02X",ord($c));
        return($s);
    }
}
