<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/3/15
 * Time: 19:00
 */
if ($state==0){
    echo "<h3 class='text-danger'>权限不够，可能你的考勤组还未通过审核</h3>";
    return;
}
if ($info['location']==""){
    $info['location']="{}";
}
?>
<div class="panel panel-primary">
    <div class="panel-heading">基本操作</div>
    <div class="panel-body">
        <a href="?r=manage2/group-manage" class="btn btn-primary">组员管理</a>
        <a href="#leave_div" class="btn btn-primary">请假审核</a>
        <a href="#sign_div" class="btn btn-primary">签到数据</a>
    </div>
</div>

<div class="panel panel-primary" id="leave_div">
    <div class="panel-heading">
        请假审核
    </div>
    <div class="panel-body">
    <ul class="nav nav-tabs">
        <li role="presentation" class="active">
            <a href="#leave0" role="tab" aria-expanded="true" data-toggle="tab">待审核</a>
        </li>
        <li role="presentation">
            <a href="#leave1" role="tab" aria-expanded="true" data-toggle="tab">已批准</a>
        </li>
        <li role="presentation">
            <a href="#leave-1" role="tab" aria-expanded="true" data-toggle="tab">不批的</a>
        </li>
        <li role="presentation">
            <a href="#leaveAll" role="tab" aria-expanded="true" data-toggle="tab">所有的</a>
        </li>
    </ul>
    <!--Tab pane-->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active table-responsive" id="leave0">
            <table class="table table-hover">
                <thead>
                <td>id</td><td>姓名</td><td>原因</td><td>请假时间</td><td>截止时间</td><td>操作</td>
                </thead>
                <tbody id="leave0_body">

                </tbody>
                <tfoot>
                <tr><td colspan="6" "><button class="btn btn-info" onclick="loadLeave(this,0)">点一下巴拉拉小魔仙，给你加载数据呢</button></td></tr>
                </tfoot>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane table-responsive"" id="leave1">
            <table class="table table-hover">
                <thead>
                <td>id</td><td>姓名</td><td>原因</td><td>请假时间</td><td>截止时间</td><td>操作</td>
                </thead>
                <tbody id="leave1_body">

                </tbody>
                <tfoot>
                <tr><td colspan="6" ><button class="btn btn-info" onclick="loadLeave(this,1)">点一下巴拉拉小魔仙，给你加载数据呢</button></td></tr>
                </tfoot>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane table-responsive" id="leave-1">
            <table class="table table-hover">
                <thead>
                <td>id</td><td>姓名</td><td>原因</td><td>请假时间</td><td>截止时间</td><td>操作</td>
                </thead>
                <tbody id="leave-1_body">

                </tbody>
                <tfoot>
                <tr><td colspan="6" ><button class="btn btn-info" onclick="loadLeave(this,-1)">点一下巴拉拉小魔仙，给你加载数据呢</button></td></tr>
                </tfoot>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane table-responsive" id="leaveAll">
            <table class="table table-hover">
                <thead>
                <td>id</td><td>姓名</td><td>原因</td><td>请假时间</td><td>截止时间</td><td>操作</td>
                </thead>
                <tbody id="leaveAll_body">

                </tbody>
                <tfoot>
                <tr><td colspan="6" ><button class="btn btn-info" onclick="loadLeave(this,'All')">点一下巴拉拉小魔仙，给你加载数据呢</button></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</div>
<!--请假审批面板-->
<div class="modal fade" id="check_leave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">请假审批</h4>
            </div>
            <div class="modal-body">
                <div id="check_state"></div>
                <table class="table table-hover">
                    <tr>
                        <td>id</td>
                        <td id="leave_id"></td>
                    </tr>
                    <tr>
                        <td>姓名</td>
                        <td id="leave_name"></td>
                    </tr>
                    <tr>
                        <td>原因</td>
                        <td id="leave_reason"></td>
                    </tr>
                    <tr>
                        <td>请假时间</td>
                        <td id="leave_start_t"></td>
                    </tr>
                    <tr>
                        <td>截止时间</td>
                        <td id="leave_end_t"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="checkLeaveClear()">关闭</button>
                <button type="button" class="btn btn-primary" onclick="checkLeave(this,1)">批准</button>
                <button type="button" class="btn btn-primary" onclick="checkLeave(this,-1)">不准</button>
            </div>
        </div>
    </div>
</div>
<!--地图面板-->
<div class="modal fade" id="map_modal" tabindex="-1">
<div class="modal-content">
    <div class="modal-body">
        <div id="container" style="width: auto;height: 350px">
        地图加载中
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" onclick="initLoc()">关闭</button>
        <button class="btn" data-dismiss="modal">确定</button>
    </div>
</div>
</div>
<div class="panel panel-primary" id="sign_div">
    <div class="panel-heading">
        签到数据
    </div>
    <div class="panel-body table-responsive">

        <table class="table table-hover">
        <thead>
        <td>id</td>
        <td>姓名</td>
        <td>签到时间</td>
        <td>签退时间</td>
        <td>签到时长</td>
        <td>签到地点</td>
        <td>签退地点</td>
        </thead>
        <tbody id="sign_list_body">

        </tbody>
        <tfoot>
        <tr><td colspan="7" "><button class="btn btn-info" onclick="loadSign(this)">点一下巴拉拉小魔仙，给你加载数据呢</button></td></tr>
        </tfoot>
    </table>
</div>
</div>
<script>

</script>

<script>
    /**
     * 加载请假数据
     * @param ele
     * @param state
     */
    loadLeave.offset0=0;
    loadLeave.offset1=0;
    loadLeave.offset_1=0;
    loadLeave.offsetAll=0;
   function loadLeave(ele,state){
        ele.disabled=true;
        ele.textContent="加载中，君莫急呢";
       switch (state) {
           case 1:
               var offset=loadLeave.offset1;
               break;
           case 0:
               var offset=loadLeave.offset0;
               break;
           case -1:
               var offset=loadLeave.offset_1;
               break;
           case "All":
               var offset=loadLeave.offsetAll;
               break;
       }
           var url=`?r=manage2/leave-list&limit=5&offset=${offset}&state=${state}`;
            offset==null;
        $.getJSON(url,function (data) {
            if(data.length==0){
                document.getElementById(`leave${state}_body`).innerHTML+=`
                <tr><td colspan=6>没有数据鸟</td></tr>
                `;
                ele.textContent="没有数据鸟，不能加载了呢。。。"
                return;
            }
            for (value of data){
                document.getElementById(`leave${state}_body`).innerHTML += `
                <tr><td>${value.id}</td><td>${value.name}</td><td><p style="width:55px;overflow: hidden;text-overflow: ellipsis;">${value.reason}</p></td><td>${value.start_t}</td>
                    <td>${value.end_t}</td><td><button type="button" class="btn btn-primary" id="leave_button" data-toggle="modal" onclick="leave_init(this)" data-target="#check_leave">审核</button></td></tr>
               `;
            }
        })
            switch (state) {
                case 1:
                    loadLeave.offset1+=1;
                    break
                case 0:
                    loadLeave.offset0+=1;
                    break
                case -1:
                    loadLeave.offset_1+=1
                    break
                case "All":
                    loadLeave.offsetAll+=1
                    break
            }

            ele.disabled=false;
            ele.textContent="点一下能加载更多呢";
        }

    /**
     * 请假审批页面初始化
     * @param ele
     */
    function leave_init(ele) {
       var tr=ele.parentElement.parentElement.children
        leave_id.innerText=tr[0].textContent;
        leave_name.innerText=tr[1].textContent;
        leave_reason.innerText=tr[2].textContent;
        leave_start_t.innerText=tr[3].textContent;
        leave_end_t.innerText=tr[4].textContent;
   }

    /**
     * 审批请假
     * @param ele
     * @param state
     */
    function checkLeave(ele,state){
       ele.disabled=true;
        check_state.innerHTML= `
        <p class="text-primary">君莫急呢，八个大脑正在处理您的请求呢。</p>
        `;
       id=leave_id.textContent;
       var url=`?r=manage2/check-leave&id=${id}&state=${state}`;
       $.get(url,function (data) {
           if(data==0){
           check_state.innerHTML= `
        <p class="text-primary">处理失败了呢，原因你自己猜。不能把同一个请假请求审核两次，就像你不可能两次踏进同一条河里。去查查看数据吧</p>
        `;
               ele.disabled=false;
           return
           }
           if (data==1){
               check_state.innerHTML= `
        <p class="text-primary">处理成功啦，嘻嘻嘻。欢迎您下次来玩哦</p>
        `;
               ele.disabled=false;
           }

       })
    }
    /**
     * 审批取消
     */
    function checkLeaveClear(){
        console.log(1);
    }

    /**
     * 签到记录
     * @param state
     */
    loadSign.offset=0
    function loadSign(ele,state) {
        ele.disabled=true;
        ele.textContent="加载中，君莫急呢";
        $.getJSON('?r=manage2/sign-list&limit=20&offset='+loadSign.offset,function (data){
            if(data.length==0){
                document.getElementById(`sign_list_body`).innerHTML+=`
                <tr><td colspan=6>没有数据鸟</td></tr>
                `;
                ele.textContent="没有数据鸟，不能加载了呢。。。"
                return;
            }
            for(value of data){
                var sign_s,sign_es;
                switch (value.start_s) {
                    case "-1":
                        sign_s="外勤"
                        break;
                    case "1":
                        sign_s="正常"
                        break;
                    case "0":
                        sign_s="异常"
                }
                switch (value.end_s) {
                    case "-1":
                        sign_es="外勤"
                        break;
                    case "1":
                        sign_es="正常"
                        break
                    case "0":
                        sign_es="异常"
                }
                if (value.start_c!="") {
                    start_pos = JSON.parse(value.start_c)
                    var position_s = {
                        lng: start_pos[0],
                        lat: start_pos[1]
                    }
                    value.start_c="位置"
                    if (value.end_c!=""){
                        end_pos = JSON.parse(value.end_c)
                        var position_e = {
                            lng: end_pos[0],
                            lat: end_pos[1]
                        }
                        value.end_c="位置"
                    }
                }
                else {
                    var position_e={
                        lng:0,
                        lat:0
                    }
                    var position_s={
                        lng:0,
                        lat:0
                    }
                }
                document.getElementById('sign_list_body').innerHTML+=`
                <td>${value.id}</td><td>${value.name}</td><td>${value.start_t}</td><td>${value.end_t}</td>
<td>${value.time}</td>
<td data-toggle="modal" data-target="#map_modal" onclick='showMap(${JSON.stringify(position_s)})'>${sign_s}</td>

<td data-target="#map_modal" onclick='showMap(${JSON.stringify(position_e)})' data-toggle="modal">${sign_es}</td>
 `
            }
            ele.textContent="加载更多呢"
            ele.disabled=false;
            loadSign.offset+=1;
        })
    }
</script>
<script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.12&key=868eab0a3b1081d77ffaf946f6b490bd&plugin=AMap.CircleEditor"></script>
<script>
    var LOC={}
    initLoc();
    function showMap(position) {
           var map = new AMap.Map("container", {
            center: [position.lng,position.lat],
            zoom: 17,
        });
        var marker = new AMap.Marker({
            position:[position.lng,position.lat]//位置
        })
        map.add(marker)
        if ("undefined"!=LOC.sign.radius)  return
            circle = new AMap.Circle({
            center: [LOC.sign.position.lng,LOC.sign.position.lat],
            radius: 50, //半径
            borderWeight: 3,
            strokeColor: "#FF33FF",
            strokeOpacity: 1,
            strokeWeight: 6,
            strokeOpacity: 0.2,
            fillOpacity: 0.4,
            strokeStyle: 'dashed',
            strokeDasharray: [10, 10],
            // 线样式还支持 'dashed'
            fillColor: '#1791fc',
            zIndex: 50,
        })
        if ("undefined"!=LOC.sign.radius){
            circle.setCenter(LOC.sign.position)
            circle.setRadius(LOC.sign.radius)
        }
        circle.setMap(map)
    }
    LOC.getGeo=function (position) {
        $.ajax("//restapi.amap.com/v3/geocode/regeo?key=868eab0a3b1081d77ffaf946f6b490bd&location="+position.lng+","+position.lat+"&poitype=&radius=200&extensions=base&batch=false&roadlevel=0").done(function(e){
            LOC.geo=e.regeocode.formatted_address
        })
    }
    function signCircle(){
        var map = new AMap.Map("container", {
            zoom: 16,
        });
        AMap.plugin('AMap.Geolocation', function() {
            var geolocation = new AMap.Geolocation({
                    'showButton': true,//是否显示定位按钮
                    'buttonPosition': 'LB',//定位按钮的位置
                    /* LT LB RT RB */
                    'buttonOffset': new AMap.Pixel(10, 20),//定位按钮距离对应角落的距离
                    'showMarker': true,//是否显示定位点
                    'markerOptions':{//自定义定位点样式，同Marker的Options
                        'offset': new AMap.Pixel(-18, -36),
                        'content':'<img src="https://a.amap.com/jsapi_demos/static/resource/img/user.png" style="width:36px;height:36px"/>'
                    },
                    'showCircle': true,//是否显示定位精度圈
                    'circleOptions': {//定位精度圈的样式
                        'strokeColor': '#0093FF',
                        'noSelect': true,
                        'strokeOpacity': 0.5,
                        'strokeWeight': 1,
                        'fillColor': '#02B0FF',
                        'fillOpacity': 0.25
                    }
                }
            );
            map.addControl(geolocation);
            geolocation.getCurrentPosition(function(status,result){
                if(status=='complete'){
                    console.log(result)
                    LOC.position=result.position
                    LOC.Geo=LOC.getGeo(LOC.position)
                    drawCircle(map);
                }else{
                    // onError(result)
                    console.log(result);
                    alert("定位失败"+result.message)
                    LOC.position={
                        lat:0,
                        lng:0
                    }
                    if ("undefined"!=LOC.sign.radius){
                        circle.setCenter(LOC.sign.position)
                        circle.setRadius(LOC.sign.radius)

                    }
                }
            });
        });
    }

    function drawCircle(map) {
            var circle = new AMap.Circle({
            center: [LOC.position.lng, LOC.position.lat],
            radius: 50, //半径
            borderWeight: 3,
            strokeColor: "#FF33FF",
            strokeOpacity: 1,
            strokeWeight: 6,
            strokeOpacity: 0.2,
            fillOpacity: 0.4,
            strokeStyle: 'dashed',
            strokeDasharray: [10, 10],
            // 线样式还支持 'dashed'
            fillColor: '#1791fc',
            zIndex: 50,
        })
        if ("undefined"!==typeof LOC.sign.radius) {
            map.setCenter(LOC.sign.position)
            circle.setCenter([LOC.sign.position.lng,LOC.sign.position.lat])
            circle.setRadius(LOC.sign.radius)
        }else {
            map.setFitView([ circle ])        // 缩放地图到合适的视野级别
        }
        circle.setMap(map)
        var circleEditor = new AMap.CircleEditor(map, circle)
        circleEditor.open()
        circleEditor.on('adjust', function(event) {
            LOC.sign.position=event.target.getCenter()
            LOC.sign.radius=event.target.getRadius()
    })
        circleEditor.on('move', function(event) {
            LOC.sign.position=event.target.getCenter()
            LOC.sign.radius=event.target.getRadius()
        })
    }

    function signLoc() {
        signCircle()
    }
    function initLoc() {
        LOC.sign=JSON.parse('<?=$info['location']?>');
    }
    function setPos() {

    }
</script>