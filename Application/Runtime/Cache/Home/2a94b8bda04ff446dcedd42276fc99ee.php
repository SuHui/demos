<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="zh-cn" class="app">
<head>  
  <meta charset="utf-8" />
</head>

<body>
              <a href="<?php echo U('/home/index/index/p/1');?>" data-pjax>第一页</a>
              <a href="<?php echo U('/home/index/index/p/2');?>" data-pjax>第二页</a>
              <a href="<?php echo U('/home/index/index/p/3');?>" data-pjax>第三页</a>
              <a href="<?php echo U('/home/index/index/p/4');?>" data-pjax>第四页</a>    
              <div id="pjax-container"></div>     
</body>
</html>

<script src="/github_demo_pjax/Public/jquery.min.js"></script>
<script src="/github_demo_pjax/Public/jquery.pjax.js"></script>
<script type="text/javascript">
    $(document).pjax('a[data-pjax]', '#pjax-container');
</script>