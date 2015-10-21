<?php

namespace TimeManager\Decorator;

class Success extends Base implements Decorator
{
    const STATUS_OK      = '200';
    const STATUS_CREATED = '201';

    public function process($code, $message = '')
    {
        $description = $this->_generateMessage(
            $code,
            $message
        );
        
        $output = [
            'success' => [
                'code'        => $code,
                'description' => $description,
            ]
        ];
        $this->_print($code, $output);
    }
}
