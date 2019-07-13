<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $model app\models\new_group */
/* @var $form ActiveForm */
?>
<div class="new_group">

    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'name')->label("请输入考勤组名称") ?>
        <?= $form->field($model, 'phone')->label("手机号") ?>
        <?= $form->field($model, 'school')->input("text",["value"=>$info->yb_schoolname])->label("学校") ?>
        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
        ])->label("输入验证码") ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- new_group -->
