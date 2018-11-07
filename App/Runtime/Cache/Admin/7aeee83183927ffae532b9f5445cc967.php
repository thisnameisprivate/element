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
        a{font-size:1rem; color:#5FB878; font-weight:600;}
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
                <table class="layui-table" lay-even lay-skin="line" lay-size="sm">
                    <thead>
                    <tr class="layui-bg-green">
                        <th>时间</th>
                        <th>总共</th>
                        <th>已到</th>
                        <th>未到</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>今日</td>
                        <td><a href="javascript:;"><?php echo ($arrivalTotal); ?></a></td>
                        <td><a href="javascript:;"><?php echo ($arrival); ?></a></td>
                        <td><a href="javascript:;"><?php echo ($arrivalOut); ?></a></td>
                    </tr>
                    <tr>
                        <th>昨日</th>
                        <td><a href="javascript:;"><?php echo ($yesterTotal); ?></a></td>
                        <td><a href="javascript:;"><?php echo ($yesterArrival); ?></a></td>
                        <td><a href="javascript:;"><?php echo ($yesterArrivalOut); ?></a></td>
                    </tr>
                    <tr>
                        <th>本月</th>
                        <td><a href="javascript:;"><?php echo ($thisTotal); ?></a></td>
                        <td><a href="javascript:;"><?php echo ($thisArrival); ?></a></td>
                        <td><a href="javascript:;"><?php echo ($thisArrivalOut); ?></a></td>
                    </tr>
                    <tr>
                        <th>上月</th>
                        <td><a href="javascript:;"><?php echo ($lastTotal); ?></a></td>
                        <td><a href="javascript:;"><?php echo ($lastArrival); ?></a></td>
                        <td><a href="javascript:;"><?php echo ($lastArrivalOut); ?></a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="layui-card min-container">
            <div class="layui-card-header">预约未定数据统计</div>
            <div class="layui-card-body">
                <table class="layui-table" lay-even lay-skin="line" lay-size="sm">
                    <colgroup>
                        <col width="150">
                        <col width="150">
                        <col width="150">
                        <col width="150">
                    </colgroup>
                    <thead>
                    <tr class="layui-bg-green">
                        <th>时间</th>
                        <th>总共</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>今日</td>
                        <td><a href="javascript:;"><?php echo ($appointment['todayTotal']); ?></a></td>
                        <td><a href="javascript:;"></a></td>
                    </tr>
                    <tr>
                        <th>昨日</th>
                        <td><a href="javascript:;"><?php echo ($appointment['yesterTotal']); ?></a></td>
                        <td><a href="javascript:;"></a></td>
                    </tr>
                    <tr>
                        <th>本月</th>
                        <td><a href="javascript:;"><?php echo ($appointment['thisTotal']); ?></a></td>
                        <td><a href="javascript:;"></a></td>
                    </tr>
                    <tr>
                        <th>上月</th>
                        <td><a href="javascript:;"><?php echo ($appointment['lastTotal']); ?></a></td>
                        <td><a href="javascript:;"></a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="right-box">
        <div class="right-box-min-container">
            <div class="layui-card min-container">
                <div class="layui-card-header">本月到院排行</div>
                <div class="layui-card-body">
                    <table class="layui-table" lay-even lay-skin="line" lay-size="sm">
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
                        <?php if(is_array($thisArrivalSort)): foreach($thisArrivalSort as $k=>$vo): ?><tr>
                                <td><?php static $i = 0; echo "No ." . $i += 1; ?></td>
                                <td><a href="javascript:;"><?php echo ($k); ?></a></td>
                                <td><a href="javascript:;"><?php echo ($vo); ?></a></td>
                            </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="layui-card min-container">
                <div class="layui-card-header">上月到院排行</div>
                <div class="layui-card-body">
                    <table class="layui-table" lay-even lay-skin="line" lay-size="sm">
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
                        <?php if(is_array($lastArrivalSort)): foreach($lastArrivalSort as $k=>$vo): ?><tr>
                                <td><?php static $c = 0; echo "No ." . $c += 1; ?></td>
                                <td><a href="javascript:;"><?php echo ($k); ?></a></td>
                                <td><a href="javascript:;"><?php echo ($vo); ?></a></td>
                            </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="right-box-min-container">
            <div class="layui-card min-container">
                <div class="layui-card-header">本月预约排行</div>
                <div class="layui-card-body">
                    <table class="layui-table" lay-even lay-skin="line" lay-size="sm">
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
                        <?php if(is_array($thisAppointmentSort)): foreach($thisAppointmentSort as $k=>$vo): ?><tr>
                                <td><?php static $j = 0; echo "No ." . $j += 1; ?></td>
                                <td><a href="javascript:;"><?php echo ($k); ?></a></td>
                                <td><a href="javascript:;"><?php echo ($vo); ?></a></td>
                            </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="layui-card min-container">
                <div class="layui-card-header">上月预约排行</div>
                <div class="layui-card-body">
                    <table class="layui-table" lay-even lay-skin="line" lay-size="sm">
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
                        <?php if(is_array($lastAppointmentSort)): foreach($lastAppointmentSort as $k=>$vo): ?><tr>
                                <td><?php static $d = 0; echo "No ." . $d += 1; ?></td>
                                <td><a href="javascript:;"><?php echo ($k); ?></a></td>
                                <td><a href="javascript:;"><?php echo ($vo); ?></a></td>
                            </tr><?php endforeach; endif; ?>
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
layui.use(['layer', 'element', 'laydate', 'form'], () => {
    var table = layui.table;
    var laydate = layui.laydate;
    var layer = layui.layer;
    var form = layui.form;
    var laypage = layui.page;
    var aList = document.getElementsByTagName('a');
    for (var i = 0; i < aList.length; i++) {
        aList[i].onclick = function () {
            console.log(this);
            console.log(this.text);
        }
    }
});
</script>
</html>