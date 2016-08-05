<?php

namespace TimeManager\Presenter;

use Slim\Http\Response;
use TimeManager\Model\Task;
use TimeManager\Transformer\Task as TaskTransformer;

class Data extends Presenter
{
    private $_taskTransformer;

    public function __construct(TaskTransformer $taskTransformer)
    {
        $this->_taskTransformer = $taskTransformer;
    }

    public function render(Response $response, $code, $data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->_checkForParsing($value);
            }
        } else {
            $data = $this->_checkForParsing($data);
        }

        return $response->withJson($data, $code);
    }

    private function _checkForParsing($data)
    {
        if ($data instanceof Task) {
            $data = $this->_taskTransformer->transformToApiObject($data);
        }
        return $data;
    }
}
