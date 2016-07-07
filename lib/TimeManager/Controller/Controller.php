<?php

namespace TimeManager\Controller;

use TimeManager\AppAware;

abstract class Controller extends AppAware
{
    protected function _processInfo($code, $description = null, $returnPlain = false)
    {
        return $this->_app->presenterInfo->process($code, $description, $returnPlain);
    }
}
