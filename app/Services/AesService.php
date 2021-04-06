<?php

namespace App\Services;

class AesService
{
    /**
     * var string $method 加解密方法，可通过openssl_get_cipher_methods()获得
     */
    protected $method;

    /**
     * var string $secret_key 加解密的密钥
     */
    protected $secret_key;

    /**
     * var string $iv 加解密的向量，有些方法需要设置比如CBC
     */
    protected $iv;

    /**
     * var string $options （不知道怎么解释，目前设置为0没什么问题）
     */
    protected $options;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->method = config('api.aes.method');
        $this->secret_key = config('api.aes.key');
        $this->options = config('api.aes.options');
        $this->iv = config('api.aes.iv');

    }

    /**
     * 加密方法，对数据进行加密，返回加密后的数据
     *
     * @param  string  $data  要加密的数据
     *
     * @return string
     */
    public function encrypt(string $data)
    {
        return openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }

    /**
     * 解密方法，对数据进行解密，返回解密后的数据
     *
     * @param  string  $data  要解密的数据
     *
     * @return string
     */
    public function decrypt(string $data)
    {
        return openssl_decrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }
}
