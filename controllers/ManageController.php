<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/2/20
 * Time: 19:22
 */

namespace app\controllers;
use yii\web\Controller;
use app\models\Manage;

class ManageController extends Controller
{
    private $yb_token;
    private $yb_uid;
    private $yb_info;
    private $group_id;
    private $state;
    private $branch;
    function init()
    {
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
        @$this->group_id=Manage::GetGroupInfo($this->yb_uid)['id'];
        $info=Manage::GetGroupInfo($this->yb_uid);
        if (!isset($info)||$info['state']==0){
            $dataobj=\Yii::$app->db->createCommand("SELECT state,group_id from `group_admin` where yb_uid={$this->yb_uid}")->queryOne();
            if ($dataobj['state']==2){
                $this->state=2;
                $this->group_id=$dataobj['group_id'];
                header("Location:?r=manage2");
                die("xx");
            }
            die($this->render('index',['state'=>0]));
        }
        $this->branch=\Yii::$app->db->createCommand("select branch from user where yb_uid={$this->yb_uid}")->queryScalar();
        $this->state=1;
        return;
    }

    /**
     * 首页
     * @return string
     * @throws \yii\db\Exception
     */
    function actionIndex(){
        $info = \Yii::$app->db->createCommand("select * from `group` where id={$this->group_id}")->queryOne();
        if ($this->state==1) {
            return $this->render("index", ['info' => $info, 'state' => 1]);
        }
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
                    $command = \Yii::$app->db->createCommand("UPDATE `user` SET `state`=1 WHERE `id`=:id  AND `group`={$this->group_id}");
                    break;
                case "checkN":
                    $command = \Yii::$app->db->createCommand("
DELETE FROM group_admin WHERE yb_uid=(SELECT yb_uid FROM user WHERE user.id=:id);
DELETE FROM `user` WHERE `id`=:id AND `group`={$this->group_id}}'");
//                    $command = \Yii::$app->db->createCommand("DELETE FROM `user` WHERE `id`=:id AND `group`={$this->group_id}");
                    break;
                case "setAdmin":
                    $command = \Yii::$app->db->createCommand("INSERT  ignore INTO `group_admin` (yb_uid,group_id,state) select yb_uid,{$this->group_id},2 from user where id=:id");
                    break;
                case "calAdmin":
                    $command =  \Yii::$app->db->createCommand("DELETE FROM `group_admin` WHERE `yb_uid`=:id AND `group_id`={$this->group_id}");
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
        $info=Manage::getGrouper($this->group_id,1);
        $adminer=Manage::getGrouper($this->group_id,2);
        return $this->render('groupManage',['info'=>$info,'adminer'=>$adminer]);
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
            return json_encode(Manage::LeaveListAll(Manage::GetGroupInfo($this->yb_uid)['id'],$offset,$limit));
        }
        $state=intval($state);
             return json_encode(Manage::LeaveList(Manage::GetGroupInfo($this->yb_uid)['id'],$offset,$limit,$state));
    }

    /**
     * 审核请假
     * @param $id
     * @param $state
     * @return int
     */
    function actionCheckLeave($id,$state){
        $id=intval($id);
        return Manage::checkLeave($this->group_id,$id,$state);
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
        return json_encode(Manage::signList($this->group_id,$offset,$limit));
    }

    /**
     * 分支
     * @return int|string
     * @throws \yii\db\Exception
     */
    function actionBranch(){
        @$post_data=\Yii::$app->request->post()['branches'];
        if (isset($post_data)){
            $branches=json_encode($post_data,JSON_UNESCAPED_UNICODE);
           return \Yii::$app->db->createCommand("UPDATE `group` SET branches=:branches WHERE id={$this->group_id}")
                ->bindValue(':branches',$branches)->execute();
        }
        $branches=Manage::getBranches($this->group_id);
        return $this->render('branch',[
            'branches'=>$branches
        ]);
    }

    /**
     * 修改组信息
     */
    function actionModifyGroupInfo(){
        $data=\Yii::$app->request->post();
        return \Yii::$app->db->createCommand()->update("group",[
            "name"=>$data['name'],
            "school"=>$data['school'],
            "detail"=>$data['detail'],
            "location"=>$data['location'],
        ],[
            "id"=>$data['id']
        ])->execute();
    }

}