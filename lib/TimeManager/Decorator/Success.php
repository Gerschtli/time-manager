<?php

namespace TimeManager\Decorator;

class Success extends Base implements Decorator
{
    const STATUS_OK      = 200;
    const STATUS_CREATED = 201;

    public function process($code, $message = '')
    {
        if (is_array($message)) {
            $data    = $message;
            $message = empty($data['message']) ? '' : $data['message'];
            unset($data['message']);
        }
        $description = $this->_generateMessage(
            $code,
            $message
        );

        $output = [
            'success' => [
                'code'        => $code,
                'description' => $description,
            ],
        ];
        if (!empty($data)) {
            $output += [
                'result' => $data,
            ];
        }

        $this->_print($code, $output);
    }
}
