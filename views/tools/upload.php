<h2>懒得收你们的文件，，，以后就给这上传吧</h2>
<h2><?php
    if (isset($info)){
        echo $info;
    }
    ?></h2>
<form action="?r=tools/upload" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>" />
    <input type="file" name="file" class="form-group">
    <button class="btn">上传</button>
</form>