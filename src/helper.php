<?php
declare(strict_types=1);


if (!function_exists('encrypt_data')) {
    /**
     * 使用AES-256-CBC加密数据
     *
     * @param string $data 待加密的数据
     * @param string $key 加密密钥
     * @return string Base64编码后的加密结果
     */
    function encrypt_data(string $data, string $key): string
    {
        // 生成16字节IV
        $iv = openssl_random_pseudo_bytes(16);

        // 加密数据
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        // 组合IV和密文并进行Base64编码
        return base64_encode($iv . $encrypted);
    }
}

// 检查函数是否已存在，避免重复定义
if (!function_exists('decrypt_data')) {
    /**
     * 使用AES-256-CBC解密数据
     *
     * @param string $result Base64编码后的加密结果
     * @param string $key 解密密钥
     * @return string 解密后的原始数据
     */
    function decrypt_data(string $result, string $key): string
    {
        // Base64解码
        $data = base64_decode($result);

        // 提取IV和密文
        $iv = substr($data, 0, 16);
        $ciphertext = substr($data, 16);

        // 解密数据
        return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    }
}