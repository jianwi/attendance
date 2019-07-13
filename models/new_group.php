<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/2/11
 * Time: 0:18
 */

namespace app\models;
use Yii;
use yii\base\Model;
class new_group extends Model
{
    public $name;
    public $school;
    public $phone;
    public $verifyCode;
    /**
     * @return array
     */
    public function rules(){
        return [
            [['name','phone','school','verifyCode'],"required"],
            ['verifyCode', 'captcha'],
        ];
    }
    public static function newGroup($info,$yb_uid){
    if(\Yii::$app->db->createCommand()->upsert("group",[
        "name"=>$info["name"],
        "school"=>$info['school'],
        "admin"=>$yb_uid,
        "state"=>0
    ])->execute()&&
        \Yii::$app->db->createCommand("INSERT IGNORE INTO `group_admin` (yb_uid,group_id) SELECT :yb_uid,LAST_INSERT_ID()")
            ->bindValue(':yb_uid',$yb_uid)->execute()){
        return true;
    }else{
        return false;
    }
}

}