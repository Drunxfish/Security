<?php

// This is a simple example of how to create an MD5 hash with a salt in PHP.
// The salt is a random string that is added to the password before hashing it.
// This makes it more difficult for attackers to crack the password using precomputed hash tables (rainbow tables).
$salt = "yessydo151031495";
$hash = md5("$salt password1234");


echo "MD5 Hash with salt: $hash \n";