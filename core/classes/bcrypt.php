<?php
namespace Nix\Icms\Bcrypt;
class bcrypt
{
    private $rounds;
    public function __construct($rounds = 12)
    {
        if (CRYPT_BLOWFISH != 1) {
            throw new Exception("Bcrypt is not supported, it is required for password hashing. http://php.net/crypt");
        }
        $this->rounds = $rounds;
    }

    /* Gen Salt */
    private function genSalt()
    {
        $string = str_shuffle(mt_rand());
        $salt    = uniqid($string ,true);

        return $salt;
    }

    /* Gen Hash */
    public function genHash($password)
    {
        $hash = crypt($password, '$2y$' . $this->rounds . '$' . $this->genSalt());

        return $hash;
    }

    /* Verify Password */
    public function verify($password, $existingHash)
    {
        /* Hash new password with old hash */
        $hash = crypt($password, $existingHash);

        /* Do the hashes match? */
        if ($hash === $existingHash) {
            return true;
        } else {
            return false;
        }
    }
}
