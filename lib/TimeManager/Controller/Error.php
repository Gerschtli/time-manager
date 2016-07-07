<?php

namespace TimeManager\Controller;

use TimeManager\Presenter\Base as Presenter;

class Error extends Controller
{
    public function errorAction()
    {
        echo $this->_processInfo(
            Presenter::STATUS_INTERNAL_SERVER_ERROR,
            null,
            true
        );
    }

    public function notFoundAction()
    {
        echo $this->_processInfo(
            Presenter::STATUS_NOT_FOUND,
            Presenter::DESCRIPTION_NO_ROUTE_MATCHED,
            true
        );
    }
}
