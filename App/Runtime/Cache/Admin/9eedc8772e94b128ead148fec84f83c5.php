<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <style>
        .container{position:relative; width:500px; height:200px; left:0; top:0; rigth:0; bootom:0; margin:auto; padding-top:20px;}
        body{overflow:scroll; margin-top:20px;}
    </style>
    <title>detail report</title>
</head>
<body>
<table id="container" lay-filter="edittable"></table>
</body>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/javascript">
    layui.use(['table', 'layer', 'form'], () => {
        var table = layui.table;
        var layer = layui.layer;
        var form = layui.form;
        var tableIns = table.render({
            text: { none: '暂无相关数据' },
            elem: '#container',
            url: "<?php echo U('Admin/Index/detailReportCheck');?>",
            height: 'full-200',
            size: 'sm',
            cols: [[
                {field: 'custservice', title: '客服', width: 80},
            ]],
            id: 'edittable'
        });
    })
</script>
</html>