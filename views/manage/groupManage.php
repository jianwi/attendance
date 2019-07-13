<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/3/7
 * Time: 23:59
 */
use yii\helpers\Html;
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        所有用户
    </div>
    <div class="panel-body table-responsive" style="padding-bottom: 39px">
    <table class="table table-hover">
            <thead>
            <td>

            </td>
            <td>姓名</td>
            <td>部门</td>
            <td>状态</td>
            <td>操作</td>
            </thead>
            <tbody>
            <?php foreach ($info as $value): ?>
                <?php
                switch ($value['state']){
                    case "0":
                        $state="待审核";
                        break;
                    case "-1":
                        $state="未通过";
                        break;
                    case "1":
                        $state="已审核";
                        break;
                }
                ?>
                <tr>
                    <td><input type="checkbox" name="rlist" value="<?=$value['id']?>">
                    </td>
                    <td><?= $value['name'] ?></td>
                    <td><?= $value['branch'] ?></td>
                    <td><?= $state ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                操作
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <li><a href="#" onclick="Deal(<?=$value['id']?>,1,'checkY')">审核</a></li>
                                <li><a href="#" onclick="Deal(<?=$value['id']?>,1,'checkN')">删了Ta</a></li>
                                <li><a href="#" onclick="Deal(<?=$value['id']?>,1,'setAdmin')">设管理员</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td>
                    <span class="glyphicon glyphicon-check" onclick='$("input[name=\"rlist\"]").map(function(){this.checked=true})'></span>
                </td>
                <td>
                    <span class="glyphicon glyphicon-remove" onclick='$("input[name=\"rlist\"]").map(function(){this.checked=false})'></span>
                </td>
                <td><button class="btn btn-success btn-sm" onclick="Deal('',2,'checkY')">审核</button></td>
                <td><button class="btn btn-sm btn-danger" onclick="Deal('',2,'checkN')">删他们</button></td>
                <td><button class="btn btn-danger btn-sm" onclick="Deal('',2,'setAdmin')">设管理员</button></td>
                <td>

                </td>
            </tr>
            </tfoot>
    </table>
</div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">管理员</div>
    <div class="panel-body table-responsive">
        <table class="table table-hover">
            <thead>
            <td>

            </td>
            <td>姓名</td>
            <td>部门</td>
            <td>身份</td>
            <td>操作</td>
            </thead>
            <tbody>
            <?php foreach ($adminer as $value): ?>
                <?php
                switch ($value['state']){
                    case "2":
                        $state="管理员(部长)";
                        break;
                }
                ?>
                <tr>
                    <td><input type="checkbox" name="admin_list" value="<?=$value['id']?>">
                    </td>
                    <td><?= $value['name'] ?></td>
                    <td><?= $value['branch'] ?></td>
                    <td><?= $state ?></td>
                    <td>
                        <nav class="glyphicon glyphicon-remove" onclick="Deal(<?=$value['id']?>,1,'calAdmin')"></nav>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td>
                    <span class="glyphicon glyphicon-check" onclick='$("input[name=\"admin_list\"]").map(function(){this.checked=true})'></span>
                </td>
                <td>
                    <span class="glyphicon glyphicon-remove" onclick='$("input[name=\"admin_list\"]").map(function(){this.checked=false})'></span>
                </td>
                <td colspan="3"><button class="btn btn-danger btn-sm" onclick="DealAdmin('',2,'calAdmin')">删除选中的这些管理员</button></td>

                <td>

                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<script>
    function Deal(id,state,action) {
    var url = "?r=manage/group-manage"
    switch (action) {
        case "checkY":
            action="checkY"
            if (!confirm("确认审核ta？")) return;
            break
        case "checkN":
            action="checkN"
            if (!confirm("确定要删除ta吗？")) return;
            break
        case "setAdmin":
            action="setAdmin"
            if (!confirm("你真的要设置ta为管理员吗？")) return;
            break
        case "calAdmin":
            action="calAdmin"
            if (!confirm("确定要删除此管理员")){
                return;
            }
            break
        default :
            return
    }

    switch (state) {
        case 1:
            array = 0
            break
        case 2:
            array = 1
            id=[]
            $("input[name='rlist']:checkbox").map(function () {
                if (this.checked==true) {
                    id.push(this.value)
                }
            })
            if (id.length==0){
                alert("emmm,你没有选择")
                return;
            }
            console.log(id)
            break
        default:
            return;
    }
    $.post(url, {
        "action": action,
        "array":array,
        "id":id
    }, function (data) {
        console.log(data)
        if (data==1){
            alert("处理成功");
        } else {
            alert("处理失败")
        }
    })
}
    function DealAdmin(id,state,action) {
        var url = "?r=manage/group-manage"
        switch (action) {
            case "checkY":
                action="checkY"
                if (!confirm("确认审核ta？")) return;
                break
            case "checkN":
                action="checkN"
                if (!confirm("确定要删除ta吗？")) return;
                break
            case "setAdmin":
                action="setAdmin"
                if (!confirm("你真的要设置ta为管理员吗？")) return;
                break
            case "calAdmin":
                action="calAdmin"
                if (!confirm("确定要删除此管理员")){
                    return;
                }
                break
            default :
                return
        }

        switch (state) {
            case 1:
                array = 0
                break
            case 2:
                array = 1
                id=[]
                $("input[name='admin_list']:checkbox").map(function () {
                    if (this.checked==true) {
                        id.push(this.value)
                    }
                })
                if (id.length==0){
                    alert("emmm,你没有选择")
                    return;
                }
                console.log(id)
                break
            default:
                return;
        }
        $.post(url, {
            "action": action,
            "array":array,
            "id":id
        }, function (data) {
            console.log(data)
            if (data==1){
                alert("处理成功");
            } else {
                alert("处理失败")
            }
        })
    }


</script>
