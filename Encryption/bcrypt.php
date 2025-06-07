<?php


class Bsafe
{
    private string $pwd;
    private string $secret;

    public function __construct($pwd)
    {
        $this->pwd = password_hash($pwd, PASSWORD_BCRYPT);
        $this->secret = '4f6f702e2e2053656372657420686173206265656e2073746f6c656e206d75616861686168616861';

        // Bcrypt version
        print "# - Here is your hashed pwd: $this->pwd" . PHP_EOL;
    }

    public function unveilSecret()
    {
        $ipwd = readline("# What's the password?" . PHP_EOL);
        if ($ipwd == password_verify($ipwd, $this->pwd)) {
            return print "- Password matched, here's the secret: " . hex2bin($this->secret) . PHP_EOL;
        }
        return print "- Password doesn't match, get lost!" . PHP_EOL;
    }
}

// Init bsafe
$safe = new Bsafe("mypassword");
$safe->unveilSecret();
$safe->unveilSecret();
