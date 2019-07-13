<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/2/12
 * Time: 23:20
 */
if ($group['location']=="") {
    $group['location'] = "{}";
}
?>
<header>
<img src="<?=$info->yb_userhead?>" class="text-center" width="50px" class="img-circle" alt="">
<p>你好啊，<?=$info->yb_username;?>，欢迎使用考勤管理系统。</p>
    <button data-toggle="modal" data-target="#map_modal" class="btn btn-primary btn-sm" onclick="dingwei()">查看位置</button>
<div id="pos" class="alert alert-info">
    <h4>考勤组规则(公告)</h4>
    <?=$group['detail']?>
</div>
</header>
<!--基本操作-->
<div class="panel panel-info">
    <div class="panel-heading">基本操作</div>
    <div class="panel-body">
        <button id="qd" class="btn btn-primary" onclick="SIGN(this)">签到</button>
        <button id="qt" class="btn btn-primary" onclick="SIGN_E(this)">签退</button>
        <button type="button" class="btn btn-primary" id="leave_button" data-toggle="modal" data-target="#leave">请假</button>
    </div>
</div>
<!--高级操作-->
<div class="panel panel-info">
    <div class="panel-heading">高级操作</div>
    <div class="panel panel-body">
        <!--nav tab-->
        <ul class="nav nav-tabs">
            <li role="presentation" class="active">
                <a href="#leave_manage" role="tab" aria-expanded="true" data-toggle="tab">请假管理</a>
            </li>
            <li role="presentation">
                <a href="#sign_history" role="tab" aria-expanded="true" data-toggle="tab">签到历史</a>
            </li>
        </ul>
        <!--Tab pane-->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active table-responsive" id="leave_manage">
                <table class="table table-hover">
                    <thead>
                    <td>id</td><td>原因</td><td>请假时间</td><td>截止时间</td><td>状态</td><td>操作</td>
                    </thead>
                    <tbody id="leave_table">

                    </tbody>
                    <tfoot>
                    <tr><td colspan="6" "><button class="btn btn-info" onclick="load_leave(this)">点一下巴拉拉小魔仙，给你加载数据呢</button></td></tr>
                    </tfoot>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane table-responsive" id="sign_history">
                <table class="table table-hover">
                    <thead>
                    <td>id</td><td>签到时间</td><td>签退时间</td><td>签到坐标</td><td>签退坐标</td><td>签到时长</td>
                    </thead>
                    <tbody id="sign_table">

                    </tbody>
                    <tfoot>
                    <tr><td colspan="6" ><button class="btn btn-info" onclick="load_sign(this)">点一下巴拉拉小魔仙，给你加载数据呢</button></td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- leave  请假  modal-->
<div class="modal fade" id="leave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">请假</h4>
            </div>
            <div class="modal-body">
                <div id="leave_state"></div>
                <form action="" method="post">
                    <input name="method" value="leave" style="display: none">
                    <input name="leave_id" id="leave_id" value="0" style="display: none">
                    <div class="form-group">
                    <label for="start_t">
                        请假:[开始时间]
                    </label>
                    <input type="datetime-local" class="form-control" id="start_t" name="start_t" required>
                        <label for="end_t">请假:[结束时间]</label>
                    <input type="datetime-local" class="form-control" id="end_t" name="end_t" required>
                        <label for="reason">请假原因</label>
                        <textarea rows="3" class="form-control" id="reason" name="reason" required></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="leaveClear()">关闭</button>
                <input CLASS="btn btn-primary" type="submit" value="提交"  onclick="leave_sm(this);return false" value="提交">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- map_modal modal 地图-->
<div class="modal fade" id="map_modal" tabindex="-1">
    <div class="modal-content">
        <div class="modal-header">地图</div>
        <div class="modal-body">
            <div id="container" style="
      width: inherit;
      height: 400px;
    ">
                <div class="text-info">正在定位中，请稍等</div>
            </div>
        </div>
        <div  class="modal-footer">
            <button class="btn" data-dismiss="modal">关闭</button>
            <button class="btn" >确定</button>
        </div>
    </div>

</div>
<script type="text/javascript"
        src="https://webapi.amap.com/maps?v=1.4.13&key=36460e9c265d71750a1988db3f1681ab"></script>

<script>

    LOC={}
    LOC.sign=JSON.parse(`<?=$group['location']?>`)

    /**
     * 显示地图
     * @param position
     */
    function showMap(position) {
        if("string"==typeof position) {
            position = JSON.parse(position)
        }
        // console.log(pos);
        var map = new AMap.Map("container", {
            center:[position.lng,position.lat],
            zoom: 16,
        });
        var marker = new AMap.Marker({
            position:[position.lng,position.lat]//位置
        })
        map.add(marker)
        if ("undefined"==typeof LOC.sign.radius) return;
         circle = new AMap.Circle({
            center: [LOC.sign.position.lng,LOC.sign.position.lat],
            radius: LOC.sign.radius, //半径
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
        circle.setMap(map)
        if (circle.contains([position.lng,position.lat])){
            alert("正常签到")
        } else {
            alert("外勤签到")
        }
    }
   // 定位
   function dingwei(){
        maptools(()=>{
            alert("您当前已在考勤范围内")
        },(message)=>{
            alert(message);
        })
   }
   function maptools(resolve,reject){
       // 地图
       var map = new AMap.Map("container", {
           zoom: 16,
           resizeEnable: true,
       });
       //圆圈图层
       if ("undefined"!=typeof LOC.sign.position) {
           var circle = new AMap.Circle({
               center: [LOC.sign.position.lng, LOC.sign.position.lat],
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
           if ("undefined" != LOC.sign.radius) {
               circle.setCenter(LOC.sign.position)
               circle.setRadius(LOC.sign.radius)
           }
           circle.setMap(map)
       }
       //定位
       var options = {
           'showButton': true,//是否显示定位按钮
           'buttonPosition': 'LB',//定位按钮的位置
           /* LT LB RT RB */
           'buttonOffset': new AMap.Pixel(10, 20),//定位按钮距离对应角落的距离
           'showMarker': true,//是否显示定位点
           'markerOptions':{//自定义定位点样式，同Marker的Options
               'offset': new AMap.Pixel(-18, -36),
               'content':'<img src="https://a.amap.com/jsapi_demos/static/resource/img/user.png" style="width:36px;height:36px"/>'
           },
           'showCircle': false,//是否显示定位精度圈
           'circleOptions': {//定位精度圈的样式
               'strokeColor': '#0093FF',
               'noSelect': true,
               'strokeOpacity': 0.5,
               'strokeWeight': 1,
               'fillColor': '#02B0FF',
               'fillOpacity': 0.25
           }
       }
       AMap.plugin('AMap.Geolocation', function() {
           var geolocation = new AMap.Geolocation(options);
           map.addControl(geolocation);
           geolocation.getCurrentPosition(function(status,result){
               if(status=='complete'){
                   // console.log(result)
                   LOC.position=result.position
                   if ("undefined"!=typeof LOC.sign.position) {
                       var myLngLat=new AMap.LngLat(result.position.lng,result.position.lat);
                       if (circle.contains(myLngLat)) {
                           LOC.sign.state = "1";
                           resolve(result.position)
                       } else {
                           LOC.sign.state = "-1";
                           reject("您当前不在考勤范围内")
                       }
                   }else{
                       LOC.sign.state="0";
                       resolve(result.position)
                   }
                   // LOC.getGeo(LOC.position)
               }else{
                   LOC.position={}
                   LOC.sign.state="0";
                   reject("定位失败")
               }
           });
       });

   }
   LOC.getGeo=function (position) {
       $.ajax("//restapi.amap.com/v3/geocode/regeo?key=868eab0a3b1081d77ffaf946f6b490bd&location="+position.lng+","+position.lat+"&poitype=&radius=200&extensions=base&batch=false&roadlevel=0").done(function(e){
           pos.innerHTML=`<div class='alert alert-success' role='alert'>定位成功，点击查看。<br>
            参考位置:${e.regeocode.formatted_address}</div>`
           LOC.geo=e.regeocode.formatted_address
       })
   }
    //签到---post
    function SIGN(ele){
        qt.textContent="签退";
        ele.disabled=true;
        ele.textContent="签到ing...";
        pos.innerHTML="签到中......";
        // 签到函数
        var sign=()=>{

            if("undefined" == typeof(LOC.position.lat)) {
                LOC.position.lat=0;
                LOC.position.lng=0;
            }
            $.post("",{
                method:"sign",
                position:[LOC.position.lng,LOC.position.lat],
                state:LOC.sign.state
            },function(data,erro){
                if (data=="true"){
                    ele.textContent="签到成功";
                    pos.innerHTML=`
                <div class="alert alert-success" role="alert">签到成功,嘻嘻嘻</div>
`;
                }else {
                    ele.textContent="签到失败咯"
                    pos.innerHTML=`
                <div class="alert alert-warning alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Warning!</strong> 签到失败了呢，可能是你已经签到了却没有签退欧。或者其他问题我也不知道咩。</div>
                `;
                }
                ele.disabled=false;
            });}

        var q1=new Promise(maptools);
        q1.then(function(){
            sign()
            }
        ).catch((message)=>{
            if(confirm(message+"。继续签到，会记录你的外勤签到记录和你的坐标,您还要继续签到吗")){
                sign();
            };
        })
    }

    /**
     * 签退
     */
    function SIGN_E(ele) {
        //签退
        ele.disabled=true;
        qd.textContent="签到";
        ele.textContent="签退中，君莫急"
        pos.innerHTML="正在签退中.......巴拉拉小魔仙，把签到按钮变灰";
        var sign_e=()=>{

            if("undefined" == typeof(LOC.position.lat)) {
                LOC.position.lat=0;
                LOC.position.lng=0;
            }
            $.post("",{
                method:"sign_e",
                position:[LOC.position.lng,LOC.position.lat],
                state:LOC.sign.state,
            },function(data,erro){
                if (data=="true"){
                    ele.textContent="签退成功"
                    pos.innerHTML=`
                <div class="alert alert-success" role="alert">签退成功，感谢使用。可在高级功能区查看签到历史</div>
                `;
                }else {
                    ele.textContent="签退失败"
                    pos.innerHTML=`
                <div class="alert alert-warning" role="alert">签退失败，可能你还没有签到呢，先去签到吧，或者就是你点了两下签退呢。</div>
                `;
                }
                ele.disabled=false;
            });}
        var q1=new Promise(maptools);
        q1.then(function() {
            sign_e();
        }).catch((message)=>{
            if(confirm(message+"。继续签退，会记录你的外勤签退记录和你的坐标,您还要继续签退吗")){
                sign_e();
            };
        })

    //    签到具体详情

    }
</script>
<!--请假js-->
<script>
    /**
     *  请假
     * @returns {boolean}
     */
    function leave_sm(ele) {
        ele.disabled=true;
        if(end_t.value==""||start_t.value==""||reason.value==""){
            leave_state.innerHTML=`
            <div class="alert alert-warning"> 请假失败了呢 :( 请把信息写完整再提交嘛</div>
            `;
            ele.disabled=false;
            return false;
        }
        leave_state.innerHTML=`
            <div class="alert alert-warning">请假书正在递送中，请君莫着急，再等一等就好</div>
            `;
        $.post('',{
            method:"leave",
            id:leave_id.value,
            start_t:Date.parse(start_t.value)/1000,
            end_t:Date.parse(end_t.value)/1000,
            reason:reason.value,
            _csrf:$('meta[name="csrf-token"]').attr("content"),
        },function (data) {
            // console.log(data)
            if(data=="true"){
                leave_state.innerHTML=`
                <div class="alert alert-success">:)请假信息提交成功，等待审批</div>
                `
                ele.disabled=false;
                return;
            }else {
                leave_state.innerHTML=`
                <div class="alert alert-warning">请假信息提交失败</div>
                `
                ele.disabled=false;
                return;
            }
        })
    }

    load_sign.offset=0;
    /*
    * 加载签到历史
    * */
    function load_sign(ele) {
        ele.disabled=true;
        ele.textContent="加载中，君莫急"
        $.getJSON("?r=user/sign-history&limit=5&offset="+load_sign.offset,function (data) {
            if(data.length==0){
                sign_table.innerHTML+=`
                <tr><td colspan=6>没有数据鸟</td></tr>
                `;
                ele.textContent="没有数据鸟，不能加载了。。。"
                return;
            }
            for (value of data) {
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
                }else {
                    var position_e={
                        lng:0,
                        lat:0
                    }
                    var position_s={
                        lng:0,
                        lat:0
                    }
                }
                value.end_t=(parseInt(value.end_t)==0)?"未签退":value.end_t;
                sign_table.innerHTML+= `
                <tr><td>${value.id}</td><td>${value.start_t}</td><td>${value.end_t}</td>
                  <td data-toggle="modal" data-target="#map_modal" onclick='showMap(${JSON.stringify(position_s)})'>${value.start_c}</td>
<td data-target="#map_modal" onclick='showMap(${JSON.stringify(position_e)})' data-toggle="modal">${value.end_c}</td>
<td>${value.time}</td></tr>
               `;
            }
            load_sign.offset+=1;
            ele.disabled=false;
            ele.textContent="点一下加载更多";
        })
    }
    load_leave.offset=0;//页码初始化
    /**
     *  加载请假历史
     * @param ele
     */
    function load_leave(ele) {
        ele.disabled=true;
        ele.textContent="加载中，君莫急呢"
        $.getJSON("?r=user/leave-history&limit=5&offset="+load_leave.offset,function (data) {
            if(data.length==0){
                leave_table.innerHTML+=`
                <tr><td colspan=6>没有数据鸟</td></tr>
                `;
                ele.textContent="没有数据鸟，不能加载了呢。。。"
                return;
            }
            for (value of data) {
                switch (value.state) {
                    case "0":
                        value.state="待审核";
                        break;
                    case "1":
                        value.state="已批准";
                        break;
                    case "-1":
                        value.state="不批准";
                        break;
                    default:
                        value.state="神马情况";
                }
                leave_table.innerHTML+=`
                <tr><td>${value.id}</td><td><p style="width:55px;overflow: hidden;text-overflow: ellipsis;">${value.reason}</p></td><td>${value.start_t}</td>
                    <td>${value.end_t}</td><td>${value.state}</td><td><button class="btn btn-group-sm" onclick="modifyLeave(this);return false">修改</button> </td></tr>
               `;
            }
            //    处理一下禁用按钮
            var trs=document.getElementById("leave_table").children;
            for(tr of trs){
                let state=tr.children[4].textContent;
                if(state=="已批准"||state=="不批准"){
                    tr.children[5].children[0].disabled=true
                }
            }
            load_leave.offset+=1;
            ele.disabled=false;
            ele.textContent="点一下能加载更多呢";
        })

    }

    /**
     * 修改请假
     * @param ele
     */
    function modifyLeave(ele) {
        var data=ele.parentElement.parentElement.children;
        leave_id.value=data[0].textContent;
        reason.value=data[1].textContent;
        start_t.value=data[2].textContent.replace(" ","T");
        end_t.value=data[3].textContent.replace(" ","T");
        leave_button.click();
    }
    function leaveClear(){
        leave_id.value="0";
        reason.value="";
        start_t.value="";
        end_t.value="";
    }
</script>