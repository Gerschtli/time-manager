<?php

namespace TimeManager\Controller;

use TimeManager\AppAware;

abstract class Controller extends AppAware
{
    protected function _getInfoPresenter()
    {
        return $this->_app->presenterInfo;
    }
}
