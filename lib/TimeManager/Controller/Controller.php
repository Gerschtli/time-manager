<?php

namespace TimeManager\Controller;

use TimeManager\AppAware;

abstract class Controller extends AppAware
{
    protected function _processInfo($code, $description = '')
    {
        $this->_app->presenterInfo->process($code, $description);
    }
}
