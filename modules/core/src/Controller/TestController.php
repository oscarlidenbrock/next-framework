<?php

namespace Core\Controller;

use Core\Class\Extend\Controller;
class TestController extends Controller
{
    public function default() {
        print "hello from DEFAULT action in TestController";
    }

    public function edit($var2, $var1) {
        $data = [
            'nomre'=>'oscar',
            'apellidos' => 'gonzalez garcia',
            'edad' => 44
        ];

        core()->service('cache')->set('core', 'test', $data, 0);

        $this->layout("html")->render('test/edit', ['var1' => $var1, 'var2' => $var2]);
    }
}