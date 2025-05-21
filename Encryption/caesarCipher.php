<?php

$str = readline("Enter a string: ");
$shift = readline("Enter a shift value: ");


function caesarCipher($str, $shift)
{
    $cipher = "";
    for ($i = 0; $i < strlen($str); $i++) {
        if (ctype_upper($str[$i])) {
            $cipher .= chr((ord($str[$i]) + $shift - 65) % 26 + 65);
            continue;
        }
        $cipher .= chr((ord($str[$i]) + $shift - 97) % 26 + 97);
    }

    print("------------------------\nText: $str \nShift: $shift \nCipher: $cipher");
    return $cipher;
}

caesarCipher($str, $shift);
