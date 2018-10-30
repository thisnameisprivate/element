<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <style>
        .box{width:70%; display:flex;}
        .left-box{flex:4;}
        .right-box{flex:4; display:flex;}
        .right-box-min-container{flex:1;}
        .layui-card-header{color:#5FB878;}
        a{font-size:1rem; color:#5FB878; font-weight:700;}
        a:hover{color:#FF5722;}
    </style>
    <title>overView</title>
</head>
<body>
<div class="box">
    <div class="left-box">
        <div class="layui-card min-container">
            <div class="layui-card-header">挂号数据统计</div>
            <div class="layui-card-body">
                <table class="layui-table" lay-skin="line" lay-size="sm">
                    <thead>
                    <tr class="layui-bg-green">
                        <th></th>
                        <th>总共</th>
                        <th>已到</th>
                        <th>未到</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>今日</td>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                    </tr>
                    <tr>
                        <th>昨日</th>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                    </tr>
                    <tr>
                        <th>本月</th>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                    </tr>
                    <tr>
                        <th>上月</th>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="layui-card min-container">
            <div class="layui-card-header">预约未定数据统计</div>
            <div class="layui-card-body">
                <table class="layui-table" lay-skin="line" lay-size="sm">
                    <colgroup>
                        <col width="150">
                        <col width="150">
                        <col width="150">
                        <col width="150">
                    </colgroup>
                    <thead>
                    <tr class="layui-bg-green">
                        <th>排名</th>
                        <th>名字</th>
                        <th>信息</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>今日</td>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                    </tr>
                    <tr>
                        <th>昨日</th>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                    </tr>
                    <tr>
                        <th>本月</th>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                    </tr>
                    <tr>
                        <th>上月</th>
                        <td><a href="javascript:;">1</a></td>
                        <td><a href="javascript:;">1</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="right-box">
        <div class="right-box-min-container">
            <div class="layui-card min-container">
                <div class="layui-card-header">本月到院排行榜</div>
                <div class="layui-card-body">
                    <table class="layui-table" lay-skin="line" lay-size="sm">
                        <colgroup>
                            <col width="150">
                            <col width="150">
                            <col width="150">
                            <col width="150">
                        </colgroup>
                        <thead>
                        <tr class="layui-bg-green">
                            <th>排名</th>
                            <th>名字</th>
                            <th>信息</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>今日</td>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>昨日</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>本月</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>上月</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="layui-card min-container">
                <div class="layui-card-header">上月到院排行</div>
                <div class="layui-card-body">
                    <table class="layui-table" lay-skin="line" lay-size="sm">
                        <colgroup>
                            <col width="150">
                            <col width="150">
                            <col width="150">
                            <col width="150">
                        </colgroup>
                        <thead>
                        <tr class="layui-bg-green">
                            <th>排名</th>
                            <th>名字</th>
                            <th>信息</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>今日</td>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>昨日</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>本月</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>上月</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="right-box-min-container">
            <div class="layui-card min-container">
                <div class="layui-card-header">本月预约排行</div>
                <div class="layui-card-body">
                    <table class="layui-table" lay-skin="line" lay-size="sm">
                        <colgroup>
                            <col width="150">
                            <col width="150">
                            <col width="150">
                            <col width="150">
                        </colgroup>
                        <thead>
                        <tr class="layui-bg-green">
                            <th>排名</th>
                            <th>名字</th>
                            <th>信息</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>今日</td>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>昨日</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>本月</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>上月</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="layui-card min-container">
                <div class="layui-card-header">上月预约排行</div>
                <div class="layui-card-body">
                    <table class="layui-table" lay-skin="line" lay-size="sm">
                        <colgroup>
                            <col width="150">
                            <col width="150">
                            <col width="150">
                            <col width="150">
                        </colgroup>
                        <thead>
                        <tr class="layui-bg-green">
                            <th>排名</th>
                            <th>名字</th>
                            <th>信息</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>今日</td>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>昨日</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>本月</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        <tr>
                            <th>上月</th>
                            <td><a href="javascript:;">1</a></td>
                            <td><a href="javascript:;">1</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/javascript">

</script>
</html>