<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/2/25
 * Time: 12:57
 */
use \yii\helpers;
?>
<div class="alert alert-warning">友情提示！由于管理员权限较大，除非联系过系统管理员了，不然您的请求是不会通过审核的</div>
<!--<form action="?r=user/to-be-admin" method="post">-->
<!---->
<!--    <div class="form-group">-->
<!--        <label for="name">姓名</label>-->
<!--        <input type="text" id="name" name="name" class="form-control" required>-->
<!--        <label for="phone">手机号</label>-->
<!--        <input type="number" id="phone" name="phone" class="form-control" required>-->
<!--        <label for="detail">申请理由</label>-->
<!--        <textarea id="detail" name="detail" class="form-control" required></textarea>-->
<!--    </div>-->
<!--    <button type="submit" class="btn btn-default">提交</button>-->
<!--</form>-->

<?=\yii\helpers\Html::beginForm('?r=user/to-be-admin','post',['class'=>'form-group']);?>
<div class="form-group">
    <label for="name">姓名</label>
    <input type="text" id="name" name="name" class="form-control" required>
    <label for="phone">手机号</label>
    <input type="number" id="phone" name="phone" class="form-control" required>
    <label for="detail">申请理由</label>
    <textarea id="detail" name="detail" class="form-control" required></textarea>
</div>
<?=\yii\helpers\Html::submitButton('提交',['class'=>'btn btn-default']);?>
<?=helpers\Html::endForm();?>