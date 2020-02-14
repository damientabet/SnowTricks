<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Router;

class Forget
{
    /**
     * @param array $data
     * @return bool
     */
    public function checkIsSame(array $data)
    {
        if ($data['password'] !== $data['password_confirm']) {
            return false;
        }
        return true;
    }
}
