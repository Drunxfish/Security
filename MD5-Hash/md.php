<?php

// This is a simple PHP script to demonstrate the use of md5 and sha1 hashing functions.
// Although these functions are not recommended for secure password hashing, as they are to be easily cracked.
$myMd5 = md5('password1234');
$mySha1 = sha1('password1234');

// Display the results
echo "Original string: password1234 <br>";
echo "Md5 hash: $myMd5 <br>";
echo "Sha1 hash: $mySha1 <br>";
