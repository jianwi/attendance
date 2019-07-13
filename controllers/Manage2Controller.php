<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/3/15
 * Time: 23:51
 */

namespace app\controllers;
use yii\web\Controller;
use app\models\Manage2;

class Manage2Controller extends Controller
{
    private $yb_token;
    private $yb_uid;
    private $yb_info;
    private $group_id;
    private $state;
    private $branch;

    function init()
    {
        $session = \Yii::$app->session->get('token');
        if (!isset($session)) {
            $_SESSION['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            \Yii::$app->response->statusCode = 302;
            header("location:http://f.yiban.cn/iapp200981");
            die("滚去登陆");
        }
        $session = \Yii::$app->session;
        $this->yb_token = $session->get("token");
        $this->yb_info = $session->get("info");
        $this->yb_uid = $this->yb_info->yb_userid;
        $this->branch = \Yii::$app->db->createCommand("select branch from user where yb_uid={$this->yb_uid}")->queryScalar();
        if (!isset($info) || $info['state'] == 0) {
            $dataobj = \Yii::$app->db->createCommand("SELECT state,group_id from `group_admin` where yb_uid={$this->yb_uid}")->queryOne();
            if ($dataobj['state'] == 2) {
                $this->state = 2;
                $this->group_id = $dataobj['group_id'];
                return;
            }
            die($this->render('index', ['state' => 0]));
        }
        return;
    }
    /**
     * 首页
     * @return string
     * @throws \yii\db\Exception
     */
    function actionIndex(){
        $info = \Yii::$app->db->createCommand("select * from `group` where id={$this->group_id}")->queryOne();
            return $this->render("index", ['info' => $info, 'state' => 1]);
    }
    /**
     *成员管理
     */
    function actionGroupManage(){
        //获取组员名单，审核组员。
        $post_data=\Yii::$app->request->post();
        if (!empty($post_data)&&isset($post_data['action'])){
            switch ($post_data['action']) {
                case "checkY":
                    $command = \Yii::$app->db->createCommand("UPDATE `user` SET `state`=1 WHERE `id`=:id  AND `group`={$this->group_id} AND `branch`='{$this->branch}'");
                    break;
                case "checkN":
//                    先删掉自己的管理员，然后删了自己
                    $command = \Yii::$app->db->createCommand("
DELETE FROM group_admin WHERE yb_uid=(SELECT yb_uid FROM user WHERE user.id=:id);
DELETE FROM `user` WHERE `id`=:id AND `group`={$this->group_id} AND `branch`='{$this->branch}'");
                    break;
                default:
                    return;
            }
            if($post_data['array']=="1"){
                foreach ($post_data['id'] as $id){
                    $command->bindValue(":id",$id)->execute();
                }
                return true;
            }else{
                return $command->bindValue("id",$post_data['id'])->execute();
            }
            return;
        }
        $info=Manage2::getGrouper($this->group_id,$this->branch,1);
//        $adminer=Manage::getGrouper($this->group_id,2);
        return $this->render('groupManage',['info'=>$info]);
    }
    /**
     * 请假列表
     * @param $limit
     * @param $offset
     * @param $state
     * @return false|string
     * @throws \yii\db\Exception
     */
    function actionLeaveList($limit,$offset,$state){
        $limit=intval($limit);
        $offset=intval($offset);
        if ($limit==0){
            die("胡请求个锤子");
        }
        if($state=="All"){
            return json_encode(Manage2::LeaveListAll($this->group_id,$offset,$limit,$this->branch));
        }
        $state=intval($state);
        return json_encode(Manage2::LeaveList($this->group_id,$offset,$limit,$state,$this->branch));
    }

    /**
     *
     * 审核请假
     * @param $id
     * @param $state
     * @return int
     */
    function actionCheckLeave($id,$state){
        $id=intval($id);
        return Manage2::checkLeave($this->group_id,$id,$state);
    }

    /**
     * 签到列表
     * @param $limit
     * @param $offset
     * @return false|string
     * @throws \yii\db\Exception
     */
    function actionSignList($limit,$offset){
        $limit=intval($limit);
        $offset=intval($offset);
        if ($limit==0){
            die("胡请求个锤子");
        }
        return json_encode(Manage2::signList($this->group_id,$offset,$limit,$this->branch));
    }


}