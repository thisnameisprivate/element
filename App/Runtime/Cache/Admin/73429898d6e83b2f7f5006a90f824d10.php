<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <title>resources</title>
    <style>
        .container{position:relative; width:900px; height: 500px; top:0; left:0; bottom:0; right:0; margin: auto; padding-top: 50px;}
        body{overflow-y: scroll; margin-top:20px}
        .layui-form-item{text-align:center;}
    </style>
</head>
<body>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label"><span class="layui-badge">日期范围</span></label>
            <div class="layui-input-inline">
                <input type="text" id="date_min" placeholder="年-月-日" autocomplete="off" class="layui-input date time-item">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline">
                <input type="text" id="date_max" placeholder="年-月-日" autocomplete="off" class="layui-input date time-item">
            </div>
            <label class="layui-form-label"><span class="layui-badge">赴约状态</span></label>
            <div class="layui-input-inline layui-form">
                <div class="layui-input-inline">
                    <select name="status" id="status" lay-verify="required">
                        <option value="">请选择</option>
                        <option value="已到">已到</option>
                        <option value="未到">未到</option>
                    </select>
                </div>
            </div>
        </div>
        <button id="searchbtn" class="layui-btn layui-icon layui-icon-search" data-type="reload">搜索</button>
    </div>
<table id="container" lay-filter="edittable"></table>
</body>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/html" id="toolbaradd">
</script>
<script type="text/javascript">
    layui.use(['laydate', 'table', 'layer', 'laypage', 'form'], () => {
        var table = layui.table;
        var laydate = layui.laydate;
        var layer = layui.layer;
        var laypage = layui.laypage;
        var form = layui.form;
        var $ = layui.jquery;
        $('.date').each(function () {
            laydate.render({
                elem: this,
                trigger: 'click'
            })
        })
        var userAcc = JSON.parse(localStorage.getItem('userAcc'));
        var tableIns = table.render({
            text: { none: '暂无相关数据', },
            initSort: {
                field: 'id',
                type: 'desc',
            },
            elem: '#container',
            toolbar: '#toolbaradd',
            url: "<?php echo U('Admin/Index/resourcesCheck');?>",
            height: 'full-200',
            page: true,
            even: true,
            cellMinWidth: 50,
            limit: 500,
            limits: [500, 1000, 1500, 2000],
            loading: true,
            size: 'sm',
            cols:[[
                {field: 'id', title: 'No .', sort: true, hide:true},
                {field: 'name', title: '姓名', width:'6%', templet: (data) => { return data.status == '已到' ? "<span style='color:orangered;'>"+ data.name +"</span>" : "<span style='color:#5FB878;'>"+ data.name +"</span>" }},
                {field: 'sex', title: '性别', width:'4%', align:'center'},
                {field: 'old', title: '年龄', width:'5%', align:'center', sort: true},
                {field: 'phone', title: '电话', width:'8%'},
                {field: 'qq', title: "QQ", width:'4%'},
                {field: 'expert', title: '专家号'},
                {field: 'desc1', title: '咨询内容'},
                {field: 'oldDate', title: '预约时间'},
                {field: 'diseases', title: '病患类型'},
                {field: 'fromAddress', title: '媒体来源'},
                {field: 'switch', title: '地区', hide:true},
                {field: 'desc2', title: '备注'},
                {field: 'custService', title: '客服', width:'5%'},
                {field: 'newDate', title: '回访时间'},
                {field: 'status', title: '赴约状态', templet: (data) => { return data.status == '已到' ? "<span style='color:orangered;'>"+ data.status +"</span>" : "<span style='color:#5FB878;'>"+ data.status +"</span>" }},
                {field: 'currentTime', title: '添加时间', hide:true},
            ]],
            id: 'edittable',
            done: function (res, curr, count) {
                // Request OK to do.
            }
        });
        /* search */
        document.getElementById('searchbtn').onclick = () => {
            var date_min = document.getElementById('date_min').value;
            var date_max = document.getElementById('date_max').value;
            var status = document.getElementById('status').value;
            if (date_min == '' || date_max == '') {
                layer.msg("请选择时间范围", {icon: 16});
                tableIns.reload({
                    where: {null: null},
                });
                layer.close(layer.index);
            }
            if (date_min != '' && date_max != '') {
                layer.msg("数据请求中...", {icon: 16});
                tableIns.reload({
                    page: {curr: 1},
                    where: {
                        date_min: date_min,
                        date_max: date_max,
                        status: status
                    },
                })
                layer.close(layer.index);
            }
        }
    });
</script>
</html>