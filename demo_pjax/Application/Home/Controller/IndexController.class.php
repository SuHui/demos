<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index($p = ''){
        if ($p) { //请求第$p页的详细信息
            if (IS_AJAX) { //pjax发送过来的请求都是Ajax请求
                echo '这是第'.$p.'页';
            } else {
                echo '<head><meta charset="utf-8"></head>看到这个是因为浏览器不支持pjax，进行了跳转';
            }
            return;
        }

        $this->display();
    }
}