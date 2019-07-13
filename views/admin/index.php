<?php
if($state==0){
    echo('<h1 class="text-danger">权限不够</h1>');
    return;
}
?>
<div class="panel panel-primary">
    <div class="panel-heading">考勤组审核</div>
    <div class="panel-body table-responsive">
        <table class="table table-hover">
            <thead>
            <td>id</td>
            <td>名称</td>
            <td>管理员</td>
            <td>学校</td>
            <td>提交时间</td>
            <td>审核</td>
            </thead>
            <tbody id="group_list_body">

            </tbody>
            <tfoot>
            <td colspan="7" onclick="GroupList(this)">加载更多</td>
            </tfoot>
        </table>

    </div>
</div>

<script>
    GroupList.offset=0;
    function GroupList(ele) {
        ele.textContent='加载中...';
        ele.disabled=true;
       var url=`?r=admin/group-list&limit=5&offset=${GroupList.offset}`;
        $.getJSON(url,function (data) {
            if (data.length==0){
                ele.textContent="没数据了";
                return;
            }
            for (value of data){
                group_list_body.innerHTML+=`
                  <tr>
        <td>${value.id}</td>
        <td>${value.name}</td>
        <td>${value.admin}</td>
        <td>${value.school}</td>
        <td>${value.date}</td>
        <td><button class="btn btn-primary" onclick="checkGroup(this)">审核</button></td>
        <td><button class="btn btn-primary" onclick="delGroup(this)">删除</button></td>

        </tr>
                `
            }
            GroupList.offset+=1;
            ele.disabled=false;
            ele.textContent="加载更多"
        })
    }
    function checkGroup(ele) {
        if(!confirm("are you sure 审核它？")) return;
        ele.disabled==true;
        window.xx=ele
        var id=ele.parentElement.parentElement.children[0].textContent;
        var url=`?r=admin/check-group&id=${id}`;
        $.get(url,function (data) {
            if (data>0){
                alert('审核成功');
            } else {
                alert('审核失败');
            }
        })

    }
    function delGroup(ele) {
        if(confirm("are you sure 删这个组")) if (!confirm("您可真的想好了？")) return;
        ele.disabled==true;
        window.xx=ele
        var id=ele.parentElement.parentElement.children[0].textContent;
        var url=`?r=admin/del-group&id=${id}`;
        $.get(url,function (data) {
            if (data>0){
                alert('删除成功');
            } else {
                alert('失败');
            }
        })
    }
</script>