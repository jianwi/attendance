<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/2/10
 * Time: 18:43
 */

namespace app\controllers;
use app\models\new_group;
use app\models\RegisterForm;
use app\models\User_tb;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use yii\web\Controller;
class UserController extends Controller
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


    /**
     * 主页
     * @return string
     */
    public function actionIndex(){
//        die("系统升级");
        //处理post请求
        if (\Yii::$app->request->post()){
            $post=\Yii::$app->request->post();
        switch (\Yii::$app->request->post()['method']){
            case "sign":
                $position=\Yii::$app->request->post()['position'];
                $state=\Yii::$app->request->post()['state'];
                $position=json_encode($position);
                $state=User_tb::sign($this->yb_uid,$position,$this->group_id,$state);
                if($state){
                    echo "true";
                }else{
                    echo "false";
                }
                return;
                break;
            case "sign_e":
                $position=\Yii::$app->request->post()['position'];
                $state=\Yii::$app->request->post()['state'];
                $position=json_encode($position);
                $state=User_tb::sign_e($this->yb_uid,$position,$state);
                if($state){
                    echo "true";
                }else{
                    echo "false";
                }
                return;
                break;
            case "leave"://请假
//                var_dump($post);
                $state=User_tb::leave($this->yb_uid,$post);
//                var_dump($state);
                if($state){
                    echo "true";
                }else{
                    echo "false";
                }
                return;
            default:
                die(403);
        }}
//        var_dump(User_tb::is_write($this->yb_uid));
       if(User_tb::is_write($this->yb_uid)){
           if (User_tb::isChecked($this->yb_uid)==="1"){
               return $this->render('index', ['info' => $this->yb_info, 'group' => $this->group_info]);
           }else {
               return $this->render('result',['data'=>'已提交了加组请求，正在等待签到组审核']);
           }
       }else{
           return $this->render('first',['info'=>$this->yb_info]);
       }
    }

    /**
     * 注册
     * @return string
     */
    public function actionSignUp(){
        $model=new RegisterForm();
        $groups=\Yii::$app->db->createCommand("SELECT `id`,`name` FROM `group` WHERE `state`=1 AND `school`=:school")->bindValue(":school",$this->yb_info->yb_schoolname)->queryAll();
        $key=array_column($groups,'id');//考勤组的id
        $value=array_column($groups,"name");//考勤组的名称
        $groups=array_combine($key,$value);//拼接
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $state=User_tb::save_user_info(\Yii::$app->request->post()['RegisterForm'],$this->yb_info);
            return $this->render("result",['state'=>$state]);
        }
        return $this->render("signUp",['model'=>$model,'groups'=>$groups,'info'=>$this->yb_info]);
    }

    /**
     * 创建组
     */
    public function actionCreateGroup(){
        if(\Yii::$app->db->createCommand("SELECT `name` FROM `group` WHERE admin=:yb_uid")->bindValue(':yb_uid',$this->yb_uid)->queryScalar()){
            return $this->render('result',['state'=>0,'data'=>'你已经申请过考勤组的啦,如果还进不去管理页面可能是你还没有通过审核，请耐心等待吧']);
        }
        $model=new new_group();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
           $state=$model->newGroup(\Yii::$app->request->post()['new_group'],$this->yb_uid);
           return $this->render("result",['state'=>$state]);
        }
        return $this->render("new_group",['model'=>$model,'info'=>$this->yb_info]);
    }

    /**
     * 成为管理员
     */
    public function actionToBeAdmin(){
        $post=\Yii::$app->request->post();
        if (!empty($post)){
           if(User_tb::beAdmin($this->yb_uid,$post)){
                return $this->render('result',['state'=>1,'data'=>'申请成功，请耐心等待申请结果']);
           }
        }
        if (User_tb::isAdmin($this->yb_uid)){
            return $this->render('result',['state'=>0,'data'=>'不能申请管理员了，一生只能申请一次']);
        }
        return $this->render('be_admin');
    }

    public function actionHelp(){
        return $this->render('help');
    }

    /**
     * 签到历史
     * @param $limit
     * @param $offset
     * @return false|string
     */
    public function actionSignHistory($limit,$offset){
        $limit=intval($limit);
        $offset=intval($offset);
        if ($limit==0){
            die("胡请求个锤子");
        }
        $query=User_tb::signHistory($this->yb_uid,$limit,$offset);
        return json_encode($query);
    }

    public function actionLeaveHistory($limit,$offset){
        $limit=intval($limit);
        $offset=intval($offset);
        if ($limit==0){
            die("胡请求个锤子");
        }
        $query=User_tb::leaveHistory($this->yb_uid,$limit,$offset);
        return json_encode($query);
    }
    /**
     * 请假
     * @return string|void
     */
    public function actionLeave(){
        if(\Yii::$app->request->post()){//如果有post请求
        return;
        }
        return $this->render("Leave");

    }
    public function actionCheckIn(){

    }
    public function actionGetBranch($group){
        $branches=\Yii::$app->db->createCommand("SELECT `branches` from `group` where id=:id")->bindValue(':id',$group)->queryScalar();
        echo $branches;
        return;
    }

}