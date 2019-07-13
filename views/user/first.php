<img src="<?=$info->yb_userhead?>" class="text-center" width="50px" class="img-circle" alt="">
<h4>你好啊，<?=$info->yb_username;?>，欢迎使用考勤管理系统。</h4>
<h4>您是第一次使用此系统，先从加入一个考勤组开始吧</h4>
<!--<p class="info">如果你是签到者，请选择加入考勤组。</p>-->
<div class="align-items-center text-center">
    <nav class="">
        <ul class="nav nav-pills nav-stacked">
            <li><a href="?r=user/sign-up" class="btn btn-primary btn-lg">加入考勤组</a></li>
            <li><a href="?r=user/create-group" class="btn btn-success btn-lg">创建考勤组</a></li>
            <li><a href="?r=user/to-be-admin" class="btn btn-danger btn-lg">申请管理员</a></li>
            <li><a href="?r=user/help" class="btn btn-info btn-lg">看帮助手册</a></li>
        </ul>
    </nav>
</div>