<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index($p = ''){
        if (IS_AJAX) {
            echo '这是第'.$p.'页';
            return;
        }

        $this->display();
    }
}