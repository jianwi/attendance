<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/3/16
 * Time: 0:08
 */

namespace app\models;
    use yii\base\Model;
    use app\models\User_tb;

class Manage2 extends Model
{
    /**
     * 获取考勤组信息
     * @param $yb_uid
     * @return array
     * @throws \yii\db\Exception
     */
    public static function GetGroupInfo($group_id)
    {
        return \Yii::$app->db->createCommand("SELECT * FROM `group` WHERE `id`={$group_id}")->queryOne();
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
    public static function LeaveList($group_id, $offset, $limit, $state,$branch)
    {
        $offset = $offset > 0 ? $offset * $limit : 0;
        return \Yii::$app->db->createCommand("SELECT leave.id,user.name,leave.reason,leave.start_t,leave.end_t FROM `leave` LEFT JOIN `user` ON user.yb_uid=leave.yb_uid WHERE leave.state=:state AND group_id=:group_id AND user.branch=:branch ORDER BY `id` DESC LIMIT :limit OFFSET :offset")->bindValues([
            ':state' => $state,
            ':group_id' => $group_id,
            ':limit' => $limit,
            ':offset' => $offset,
            ':branch'=>$branch,

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
    public static function LeaveListAll($group_id, $offset, $limit,$branch)
    {
        $offset = $offset > 0 ? $offset * $limit : 0;
        return \Yii::$app->db->createCommand("SELECT leave.id,user.name,leave.reason,leave.start_t,leave.end_t,leave.state FROM `leave` LEFT JOIN `user` ON user.yb_uid=leave.yb_uid WHERE  group_id=:group_id and user.branch=:branch ORDER BY `id` DESC LIMIT :limit OFFSET :offset")->bindValues([
            ':group_id' => $group_id,
            ':limit' => $limit,
            ':offset' => $offset,
            ':branch'=>$branch

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
    public static function signList($group_id,$offset,$limit,$branch){
        $offset = $offset > 0 ? $offset * $limit : 0;
        return \Yii::$app->db->createCommand("SELECT corn.*,user.name,TIMEDIFF(corn.end_t,corn.start_t) AS time FROM `corn` LEFT JOIN user ON user.yb_uid=corn.yb_uid WHERE `group_id`=:group_id AND user.branch=:branch ORDER BY corn.id DESC LIMIT :limit OFFSET :offset")->bindValues([
            ':group_id'=>$group_id,
            ':limit'=>$limit,
            ':offset'=>$offset,
            ':branch'=>$branch
        ])->queryAll();
    }

    /**
     * 获取组成员
     * @param $group
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getGrouper($group,$branch,$state){
        switch ($state){
            case 1:
                return \Yii::$app->db->createCommand("SELECT * FROM `user` WHERE `group`={$group} AND `branch`=:branch")->bindValue(':branch',$branch)->queryAll();
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