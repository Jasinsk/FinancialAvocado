<?php

namespace LoginUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LoginUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
