<?php
$branches=json_decode($branches['branches']);
if (!$branches){
    $branches=[];
}
?>
<div>
    <h4>所有分支</h4>
    <form action="" method="get" id="form">
    <div id="show_branch" style="padding-top: 30px; padding-bottom: 30px">
    <?php foreach ($branches as $val):?>
        <div class="input-group">
        <input type="text" name="branches" class="form-control" value="<?=$val?>">
        <div class="input-group-addon"><nav onclick="del(this)" class="glyphicon glyphicon-remove"></nav></div>
        </div>
    <?php endforeach;?>
    </div>

    <button  class="btn btn-default" onclick="add();return false;">添加分支</button>
    <button class="btn btn-primary" onclick="Sub();return false" >提交</button>
    </form>
</div>
<script>
    function add() {
        container=document.createElement("div");
        container.innerHTML=`
        <input type="text" name="branches" class="form-control" value="">
        <div class="input-group-addon"><nav class="glyphicon glyphicon-remove" onclick="del(this)"></nav></div>
        `
        container.setAttribute("class",'input-group')
        show_branch.append(container)
    }
    function del(ele) {
        ele.parentElement.parentElement.remove()
    }
    function Sub() {
        state=confirm("您确定要更新分支吗？")
        if(!state){
            alert("已取消操作")
            return;
        }
        values=[]
        $("input[name='branches']").map(function(){ values.push(this.value);})
        $.post("?r=manage/branch",{"branches":values},function (data) {
            console.log(data);
            if (data==0){
                alert("更新失败，可能你并没有填写新的数据")
            }
            if (data>0){
                alert("更新成功了。")
            }
        })
    }


</script>