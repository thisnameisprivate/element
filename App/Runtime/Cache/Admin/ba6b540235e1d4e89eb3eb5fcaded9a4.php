<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>monthdata</title>
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <style>
        .container{height:800px; width:80%; position:fixed; top:0; bottom:0; right:0; margin:auto;}
        .pieBar{float:left; width:30%; height:100%;}
        .line{float:right; width:70%; height:100%;}
    </style>
</head>
<body>
<div class="container">
    <div class="pieBar">
        <div id="pie" style="width:100%; height:100%;"></div>
        <div id="bar" style="width:100%; height:100%;"></div>
    </div>
</div>
</body>
<script src="/element/Public/statics/js/echart.js"></script>
<script src="/element/Public/statics/js/macarons.js"></script>
<script>
    var myChartPie = echarts.init(document.getElementById('pie'), 'macarons');
    var date = new Date();
    // get time
    gettime = () => {
        var date = new Date();
        date.setMonth((date.getMonth()));
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        if (month < 10) {
            month = '0' + month;
        }
        return year + '-' + month;
    }
    // 使用刚指定的配置项和数据显示图表。
    myChartPie.setOption({
        title: {
            text: '本月数据'
        },
        tooltip: {},
        legend: {
            orient: 'vertical',
            x: 'right',
            data: ['预约', '等待', '已到', '未到', '全流失', '半流失', '已诊治'],
        },
        series: [
            {
                name: '本月数据',
                type: 'pie',
                radius: '55%',
                data: [
                    {value: <?php echo ($arrival['reser']); ?>, name: '预约'},
                    {value: <?php echo ($arrival['advan']); ?>, name: '等待'},
                    {value: <?php echo ($arrival['arrival']); ?>, name: '已到'},
                    {value: <?php echo ($arrival['arrivalOut']); ?>, name: '未到'},
                    {value: <?php echo ($arrival['halfTotal']); ?>, name: '全流失'},
                    {value: <?php echo ($arrival['half']); ?>, name: '半流失'},
                    {value: <?php echo ($arrival['treat']); ?>, name: '已诊治'}
                ],
            }
        ],
    });
</script>
</html>