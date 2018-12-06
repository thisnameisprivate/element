<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <style>
        .container{height:500px; width:500px; position:absolute; left:0; right:0; bottom:0; top:0; margin:auto;}
        .img-container{width:300px; height:300px;}
    </style>
    <title>uploadfilemodule</title>
</head>
<body>
<div class="container">
    <button type="button" class="layui-btn" id="uploadFile">
        <i class="layui-icon">&#xe67c;</i>上传图片
    </button>
    <button type="button" class="layui-btn layui-btn-warm" id="uploadBtn">开始上传</button>
    <div class="img-container">
        <img src="" alt="" id="imageShow" style="max-width:300px;">
    </div>
</div>
</body>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/javascript">
    /*获取当前用户名*/
    var username = JSON.parse(localStorage.getItem('userAcc')).pid;
    layui.use(['upload', 'jquery'], () => {
        var upload = layui.upload;
        var $      = layui.jquery;
        upload.render({
            elem: '#uploadFile',
            url: "<?php echo U('Admin/Index/personalUpload');?>",
            accept: 'images',
            auto: false,
            bindAction: '#uploadBtn',
            before: obj => {
                obj.preview((index, file, result) => {
                    $('#imageShow').attr("src", result);
                })
            }
        })
    })
</script>
</html>