<?php

namespace TimeManager;

use Slim\Slim;

class AppAware
{
    protected $_app;

    public function __construct(Slim $app)
    {
        $this->_app = $app;
    }
}
