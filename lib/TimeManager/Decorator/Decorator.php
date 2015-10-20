<?php

namespace TimeManager\Decorator;

interface Decorator
{
    public function process($code, $message = null);
}
