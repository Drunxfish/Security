<?php

// 32 BIT generated with base64_encode()
Define('KEY', 'sdoFiOQVVw8uTGh5/H1Iu1FSzA6qBJnCYq3O81SMwRs=');

// Encrypts text using AES-128 in GCM mode (provides confidentiality and authentication)
function secured_encrypt($data)
{

    // Properties
    $method = "aes-128-gcm";
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length); // Init Vector
    $tag = '';

    // Encryption
    $encrypted = openssl_encrypt($data, $method, base64_decode(KEY), OPENSSL_RAW_DATA, $iv, $tag);
    $output = base64_encode("$iv$tag$encrypted");

    // Delivery
    print "Encrypted '$data' to: $output" . PHP_EOL;
    return $output;
}

// Decrypts aes-128-gcm encrypted code
function decrypt_aes($data)
{
    // Properties
    $method = "aes-128-gcm";
    $iv_length = openssl_cipher_iv_length($method);

    // Decode the base64-encoded input
    $decoded = base64_decode($data);

    // Extract: IV, Tag, and Ciphertext
    $iv = substr($decoded, 0, $iv_length);
    $tag = substr($decoded, $iv_length, 16); // method = GCM tag is always 16 bytes
    $ciphertext = substr($decoded, $iv_length + 16);

    // Decrypt
    $decrypted = openssl_decrypt($ciphertext, $method, base64_decode(KEY), OPENSSL_RAW_DATA, $iv, $tag);

    // Delivery
    print "Decrypted '$data' to: $decrypted" . PHP_EOL;
    return $decrypted;
}

// Execute
$encr = secured_encrypt('Openssl');
decrypt_aes($encr);
