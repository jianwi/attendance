<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/2/22
 * Time: 18:08
 */

namespace app\models;
use yii\base\Model;
use app\models\User_tb;

class Manage extends Model
{
    /**
     * 获取考勤组信息
     * @param $yb_uid
     * @return array
     * @throws \yii\db\Exception
     */
    public static function GetGroupInfo($yb_uid)
    {
        return \Yii::$app->db->createCommand("SELECT * FROM `group` WHERE `admin`=:yb_uid")->bindValue(':yb_uid', $yb_uid)->queryOne();
    }

    /**
     * 更新考勤表
     * @param $yb_uid
     * @param $group_id
     * @param $info
     * @return \yii\db\Command
     */
    public static function UpdateGroup($yb_uid, $group_id, $info)
    {
        return \Yii::$app->db->createCommand()->update("group", [
            'name' => $info['name'],
            'school' => $info['school'],
            'detail' => $info['detail'],
            'location' => $info['location'],
        ], [
            'yb_uid' => $yb_uid,
            'id' => User_tb::is_write($yb_uid)['group']
        ]);
    }

    /**
     * 请假列表
     * @param $group_id
     * @param $offset
     * @param $limit
     * @param $state
     * @return array
     * @throws \yii\db\Exception
     */
    public static function LeaveList($group_id, $offset, $limit, $state)
    {
        $offset = $offset > 0 ? $offset * $limit : 0;
        return \Yii::$app->db->createCommand("SELECT leave.id,user.name,leave.reason,leave.start_t,leave.end_t FROM `leave` LEFT JOIN `user` ON user.yb_uid=leave.yb_uid WHERE leave.state=:state AND group_id=:group_id ORDER BY `id` DESC LIMIT :limit OFFSET :offset")->bindValues([
            ':state' => $state,
            ':group_id' => $group_id,
            ':limit' => $limit,
            ':offset' => $offset
        ])->queryAll();
    }

    /**
     * 所有的请假列表
     * @param $group_id
     * @param $offset
     * @param $limit
     * @return array
     * @throws \yii\db\Exception
     */
    public static function LeaveListAll($group_id, $offset, $limit)
    {
        $offset = $offset > 0 ? $offset * $limit : 0;
        return \Yii::$app->db->createCommand("SELECT leave.id,user.name,leave.reason,leave.start_t,leave.end_t,leave.state FROM `leave` LEFT JOIN `user` ON user.yb_uid=leave.yb_uid WHERE  group_id=:group_id ORDER BY `id` DESC LIMIT :limit OFFSET :offset")->bindValues([
            ':group_id' => $group_id,
            ':limit' => $limit,
            ':offset' => $offset
        ])->queryAll();
    }

    /**
     * 审核请假
     * @param $group_id
     * @param $id
     * @param $state
     * @return int
     * @throws \yii\db\Exception
     */
    public static function checkLeave($group_id,$id, $state)
    {
        return \Yii::$app->db->createCommand()->update('leave',[
            'state'=>$state
        ],[
            'id'=>$id,
            'group_id'=>$group_id,
        ])->execute();
    }

    /**
     * 签到记录
     * @param $group_id
     * @param $offset
     * @param $limit
     * @return array
     * @throws \yii\db\Exception
     */
    public static function signList($group_id,$offset,$limit){
        $offset = $offset > 0 ? $offset * $limit : 0;
        return \Yii::$app->db->createCommand("SELECT corn.*,user.name,TIMEDIFF(corn.end_t,corn.start_t) AS time FROM `corn` LEFT JOIN user ON user.yb_uid=corn.yb_uid WHERE `group_id`=:group_id ORDER BY corn.id DESC LIMIT :limit OFFSET :offset")->bindValues([
            ':group_id'=>$group_id,
            ':limit'=>$limit,
            ':offset'=>$offset,
        ])->queryAll();
    }

    /**
     * 获取组成员
     * @param $group
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getGrouper($group,$state){
        switch ($state){
            case 1:
                return \Yii::$app->db->createCommand("SELECT * FROM `user` WHERE `group`=:group ORDER BY `branch`")->bindValue(':group',$group)->queryAll();
            break;
            case 2:
                return \Yii::$app->db->createCommand("SELECT  group_admin.yb_uid as id,user.name,user.branch,group_admin.state FROM `group_admin` LEFT JOIN user on user.yb_uid=group_admin.yb_uid WHERE `group_id`=:group AND group_admin.state=2")
                    ->bindValue(':group',$group)->queryAll();

        }
    }

    /**
     * 获取分支部门
     * @param $group_id
     * @return array|false
     * @throws \yii\db\Exception
     */
    public static function getBranches($group_id){
        return \Yii::$app->db->createCommand("SELECT `branches` FROM `group` WHERE id=:group_id")->bindValue(':group_id',$group_id)->queryOne();
    }

}