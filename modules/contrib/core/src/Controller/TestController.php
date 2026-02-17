<?php

namespace Core\Controller;

use Core\Class\Controller;

class TestController extends Controller
{
    public function default() {
        print "hello from DEFAULT action in TestController";
    }

    public function edit($var2, $var1) {
        $this->render('test/edit', ['var1' => $var1, 'var2' => $var2]);
    }
}