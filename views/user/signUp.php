<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
/* @var $this yii\web\View */
/* @var $model app\models\RegisterForm */
/* @var $form ActiveForm */
?>
<div class="signup">
<div class="alert alert-warning">
    提示：加入签到组后，你的信息将会被储存到此系统的数据库中。在此之前，你的信息不会被保存。
</div>
    <?php $form = ActiveForm::begin(); ?>
        <label>姓名</label>
        <input type="text" class="form-group form-control" value="<?=$info->yb_realname?>" disabled>
        <?= $form->field($model, 'phone')->label("手机号") ?>
        <?= $form->field($model, 'group')->dropDownList($groups)->label("选择加入考勤组") ?>
        <?= $form->field($model, 'branch')->dropDownList([])->label("部门")?>
        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
        ])->label("输入验证码") ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- signup -->
<script>
    a=document.getElementById("registerform-group");
    a.addEventListener("change",function () {
        loadOptions(this)
    })
    document.getElementById("registerform-group").value=""
    function loadOptions(ele)
    {
        $("#registerform-branch")[0].options.length=0
        var opt=new Option("部门加载中，请稍等","未加组")
        $("#registerform-branch")[0].add(opt)
        group_id=ele.value
        $.get("?r=user/get-branch&group="+group_id,(data)=>{
            $("#registerform-branch")[0].options.length=0
            if (data==""){
               var opt=new Option("默认组","默认组")
               $("#registerform-branch")[0].add(opt)
               return;
           }
            data=JSON.parse(data)
            for (branch of data){
                var opt=new Option(branch,branch)
                $("#registerform-branch")[0].add(opt)
            }
        })
    }
</script>