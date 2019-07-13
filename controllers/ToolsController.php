<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/3/1
 * Time: 0:56
 */

namespace app\controllers;


use yii\web\Controller;


class ToolsController extends Controller
{
    private $yb_token;
    private $yb_uid;
    private $yb_info;
    private $group_id;
    private $group_info;

    /**
     * 初始化
     * @return string
     */
    function init(){
        $session=\Yii::$app->session->get('token');
        if(!isset($session)) {
            $_SESSION['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            \Yii::$app->response->statusCode = 302;
            header("location:http://f.yiban.cn/iapp200981");
            die("滚去登陆");
        }
        $session=\Yii::$app->session;
        $this->yb_token=$session->get("token");
        $this->yb_info=$session->get("info");
        $this->yb_uid=$this->yb_info->yb_userid;
        $this->group_id=\Yii::$app->db->createCommand('SELECT `group` FROM `user` WHERE `yb_uid`=:yb_uid')->bindValue(':yb_uid',$this->yb_uid)->queryOne()['group'];
        $this->group_info=\Yii::$app->db->createCommand("SELECT * FROM `group` WHERE id=:group")->bindValue(":group",$this->group_id)->queryOne();
        return;
    }
    function actionUpload(){

        if (isset($_FILES['file'])) {
            $file=$_FILES['file'];
            if(move_uploaded_file($file['tmp_name'], "../../{$file['name']}")) {
                return $this->render("upload", ['info' => "上传成功。文件名字：" . $file['name']]);
            }else{
                return $this->render("upload", ['info' => "文件上传失败。。可能是您脸黑"]);
            }
        }

        return $this->render("upload");
    }
}