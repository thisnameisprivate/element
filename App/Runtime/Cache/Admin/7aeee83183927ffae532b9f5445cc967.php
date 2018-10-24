<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <title>overView</title>
</head>
<body>
<div class="layui-card">
    <div class="layui-card-header">挂号数据统计</div>
    <div class="layui-card-body">
        <table id="container" lay-filter="edittable"></table>
    </div>
</div>
</body>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/javascript">
    layui.use(['table', 'layer'], () => {
        var table = layui.table;
        var layer = layui.layer;
        var tableIns = table.render({
            text: { none: '暂无相关数据' },
            elem: '#container',
            url: "<?php echo U('Admin/Index/detailReportCheck');?>",
            height: 'full-200',
            size: 'sm',
            cols: [[
                {align: 'center', title: '今日', colspan: 4},
                {align: 'center', title: '昨日', colspan: 3},
                {align: 'center', title: '本月', colspan: 3},
                {align: 'center', title: '上月', colspan: 3}
            ],[
                {field: 'custservice', title: '客服', width: 80, templet: (data) => { return "<span style='color:darkorange;'>"+ data.custservice +"</span>" }},
                {field: 'arrivalTotal', title: '总数', width: 100, templet: (data) => { return data.arrivalTotal ? data.arrivalTotal : 0 }},
                {field: 'arrival', title: '已到', width: 100, templet: (data) => { return data.arrival ? data.arrival : 0 }},
                {field: 'arrivalOut', title: '未到', width: 100, templet: (data) => { return data.arrivalOut ? data.arrivalOut : 0 }},
                {field: 'yestserTotal', title: '总数', width: 100, templet: (data) => { return data.yestserTotal ? data.yestserTotal : 0 }},
                {field: 'yesterArrival', title: '已到', width: 100, templet: (data) => { return data.yesterArrival ? data.yesterArrival : 0 }},
                {field: 'yesterArrivalOut', title: '未到', width: 100, templet: (data) => { return data.yesterArrivalOut ? data.yesterArrivalOut : 0 }},
                {field: 'thisTotal', title: '总数', width: 100, templet: (data) => { return data.thisTotal ? data.thisTotal : 0 }},
                {field: 'thisArrival', title: '已到', width: 100, templet: (data) => { return data.thisArrival ? data.thisArrival : 0 }},
                {field: 'thisArrivalOut', title: '未到', width: 100, templet: (data) => { return data.thisArrivalOut ? data.thisArrivalOut : 0}},
                {field: 'lastTotal', title: '总数', width: 100, templet: (data) => { return data.lastTotal ? data.lastTotal : 0 }},
                {field: 'lastArrival', title: '已到', width: 100, templet: (data) => { return data.lastArrival ? data.lastArrival : 0 }},
                {field: 'lastArrivalOut', title: '未到', width: 100, templet: (data) => { return data.lastArrivalOut ? data.lastArrivalOut : 0 }}
            ]]
        })
    })
</script>
</html>