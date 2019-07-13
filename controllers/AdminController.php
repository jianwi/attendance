<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/2/11
 * Time: 15:24
 */

namespace app\controllers;
use yii\web\Controller;
use app\models\Admin;
class AdminController extends Controller
{
    private $yb_token;
    private $yb_uid;
    private $yb_info;
    private $group_id;
    function init(){
        $session=\Yii::$app->session->get('token');
        if(!isset($session)) {
            $_SESSION['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            \Yii::$app->response->statusCode = 302;
            header("location:http://f.yiban.cn/iapp200981");
            die("去登陆");
        }
        $session=\Yii::$app->session;
        $this->yb_token=$session->get("token");
        $this->yb_info=$session->get("info");
        $this->yb_uid=$this->yb_info->yb_userid;
        $this->group_id=\Yii::$app->db->createCommand('SELECT `group` FROM `user` WHERE `yb_uid`=:yb_uid')->bindValue(':yb_uid',$this->yb_uid)->queryOne()['group'];

        $info=$this->getInfo($this->yb_uid);
        if (!isset($info)||$info==0){
            die($this->render('index',['state'=>0]));
        }
        return;
    }

    /**
     * 首页
     * @return string
     */
    public function actionIndex(){
       return $this->render('index',['info'=>$this->yb_info,'state'=>1]);
    }

    public function actionGroupList($limit,$offset){
        $limit=intval($limit);
        $offset=intval($offset);
        if ($limit==0){
            die("胡请求个锤子");
        }
        $query= Admin::groupList($limit,$offset);
        return json_encode($query);
    }

    /**
     * 账号权限
     * @return false|string|null
     * @throws \yii\db\Exception
     */
    public function getInfo(){
        return \Yii::$app->db->createCommand("SELECT `state` FROM `adminer` WHERE yb_uid=$this->yb_uid")->queryScalar();
    }

    public function actionCheckGroup($id){
        return \Yii::$app->db->createCommand()->update('group',[
            'state'=>1,
        ],[
            'id'=>$id
        ])->execute();
    }
    public function actionDelGroup($id){
        return \Yii::$app->db->createCommand("DELETE FROM `group` where id=:id")->bindValue("id",$id)->execute();
    }
    public function actionCheck(){

    }
    public function actionManage(){

    }
}