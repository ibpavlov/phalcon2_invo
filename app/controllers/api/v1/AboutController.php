<?php

namespace App\Controllers\Api\V1;

use App\Controllers\ControllerBase;

class AboutController extends ControllerBase
{
    public function indexAction()
    {
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setJsonContent(array('success' => 'true', 'messages' => 'Pellentesque vehicula, ex in ultricies tincidunt, justo urna rutrum libero, at gravida felis quam vel nisi. Vestibulum mollis ornare est.'));
        return $this->response;
    }
}
