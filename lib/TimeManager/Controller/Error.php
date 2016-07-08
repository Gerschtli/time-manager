<?php

namespace TimeManager\Controller;

use TimeManager\Presenter\Base as Presenter;
use TimeManager\Presenter\Info;

class Error
{
    private $_infoPresenter;

    public function __construct(Info $infoPresenter)
    {
        $this->_infoPresenter = $infoPresenter;
    }

    public function errorAction()
    {
        echo $this->_infoPresenter->process(
            Presenter::STATUS_INTERNAL_SERVER_ERROR,
            null,
            true
        );
    }

    public function notFoundAction()
    {
        echo $this->_infoPresenter->process(
            Presenter::STATUS_NOT_FOUND,
            Presenter::DESCRIPTION_NO_ROUTE_MATCHED,
            true
        );
    }
}
