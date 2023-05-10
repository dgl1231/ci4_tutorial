<?php

namespace App\Interfaces\Auth;

interface pbkdf2Interface
{

    public function create_hash($password, $force_compat = false);
    public function validate_password($password, $hash);
    public function needs_upgrade($hash);
    public function slow_equals($a, $b);

    public function pbkdf2_default($algo, $password, $salt, $count, $key_length);
    public function pbkdf2_fallback($password, $salt, $count, $key_length);
}