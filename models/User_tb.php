<?php

namespace app\models;

use Yii;
use yii\data\Pagination;
/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $yb_uid 易班uid
 * @property string $name 名字
 * @property string $phone 电话
 * @property string $group 考勤组
 * @property string $school 学校
 */
class User_tb extends \yii\db\ActiveRecord
{
    /**
     * 保存用户信息
     * @param $info
     * @return int
     * @throws \yii\db\Exception
     */
    public static function save_user_info($data,$info){
        $state=\Yii::$app->db->createCommand()->upsert("user",[
            'yb_uid'=>htmlspecialchars($info->yb_userid),
            'school'=>htmlspecialchars($info->yb_schoolname),
            'name'=>htmlspecialchars($info->yb_realname),
            'branch'=>htmlspecialchars($data['branch']),
            'phone'=>htmlspecialchars($data['phone']),
            'group'=>htmlspecialchars($data['group']),
            'state'=>0,
        ])->execute();
        return $state;
    }

    /**
     * 判断用户信息是否已经写入，判断是不是第一次登陆
     * @param $yb_uid
     * @return false|string|null
     * @throws \yii\db\Exception
     */
    public static function is_write($yb_uid){
        return \Yii::$app->db->createCommand("SELECT `group` FROM `user` WHERE `yb_uid`=:yb_uid")->bindValue(":yb_uid",$yb_uid)->queryOne();
    }

    /**
     * 判断是否通过审核
     * @param $yb_uid
     * @return false|string|null
     * @throws \yii\db\Exception
     */
    public static function isChecked($yb_uid){
        return \Yii::$app->db->createCommand("SELECT `state` FROM  `user` WHERE `yb_uid`=:yb_uid")->bindValue(":yb_uid",$yb_uid)->queryScalar();
    }

    /**
     * 签到
     * @param $yb_uid
     * @param $postion
     * @return bool|int
     * @throws \yii\db\Exception
     */
   public static function sign($yb_uid,$postion,$group_id,$state){
        if(self::isSigned($yb_uid)){//判断数据库中不存在只有开始时间的签到记录
            return false;
        }
        $sta=\Yii::$app->db->createCommand()->insert("corn",[
            'yb_uid'=>$yb_uid,
            'start_c'=>$postion,
            'group_id'=>$group_id,
            'start_s'=>$state,
        ])->execute();
        return $sta;
   }

    /**
     * 签退
     * @param $yb_uid
     * @param $position
     * @return bool|int
     * @throws \yii\db\Exception
     */
   public static function sign_e($yb_uid,$position,$state){
       if (!self::isSigned($yb_uid)) return false;
       $sta=\Yii::$app->db->createCommand("UPDATE `corn` SET `end_c`=:end_c,`end_s`=:state,`end_t`=NOW() WHERE (`yb_uid`=:yb_uid) AND (DATE(start_t)=DATE(NOW())) AND (`end_t`=0)")->bindValues([
           ':end_c'=>$position,
           ':yb_uid'=>$yb_uid,
           ':state'=>$state,
       ])->execute();
       return $sta;
   }
   /**
    * 签到检查
     * 是否有今日未签退
     *
     * {@inheritdoc}
     */
    public static function isSigned($yb_uid){
        $state=\Yii::$app->db->createCommand( "SELECT `id`,`start_t` FROM `corn` WHERE `yb_uid`=:yb_uid  AND DATE(start_t)=DATE(NOW()) AND `end_t`=0 AND `end_c`='';")->bindValue(":yb_uid",$yb_uid)->queryScalar();
        return $state;
    }
    public static function latest(){//最近一次签到记录
        $result=\Yii::$app->db->createCommand("\"SELECT `id`,`start_t`,`end_t` FROM `corn` WHERE `yb_uid`=:yb_uid  AND DATE(start_t)=DATE(NOW())");
    }

    /**
     * 请假
     * @param $yb_uid
     * @param $info
     * @return int
     * @throws \yii\db\Exception
     */
    public static function leave($yb_uid,$info){//请假
        if ($info['id']!=0){
            $state=\Yii::$app->db->createCommand()->update("leave",[
                'start_t'=>date("Y-m-d H:i:s",intval($info['start_t'])),
                'end_t'=>date("Y-m-d H:i:s",intval($info['end_t'])),
                'reason'=>$info['reason']
            ],[
               'yb_uid'=>$yb_uid,
               'id'=>$info['id'],
                'group_id'=>self::is_write($yb_uid)['group'],
            ])->execute();
            return $state;
        }
        $state=\Yii::$app->db->createCommand()->insert("leave",[
            'yb_uid'=>$yb_uid,
            'start_t'=>date("Y-m-d H:i:s",intval($info['start_t'])),
            'end_t'=>date("Y-m-d H:i:s",intval($info['end_t'])),
            'reason'=>htmlspecialchars($info['reason']),
            'group_id'=>self::is_write($yb_uid)['group'],
        ])->execute();
        return $state;
    }

    /**
     *  签到历史记录
     * @param $yb_uid
     * @param $limit
     * @param $offset
     * @return array
     */
    public static function signHistory($yb_uid,$limit,$offset){
        $offset=$offset>0?$offset*$limit:0;
        $query=\Yii::$app->db->createCommand("SELECT * ,TIMEDIFF(end_t,start_t) as time FROM `corn`
        WHERE `yb_uid`=:yb_uid ORDER BY `id` DESC LIMIT :limit OFFSET :offset")->bindValues([
            ':yb_uid'=>$yb_uid,
            ':limit'=>$limit,
            ':offset'=>$offset,
            ])->queryAll();
       /* $query=(new \yii\db\Query())
            ->select("*")
            ->from('corn')
            ->where([
                'yb_uid'=>$yb_uid
            ])
            ->orderBy(['id'=>SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->all();*/
        return $query;
    }

    /**
     * 加载请假记录
     * @param $yb_uid
     * @param $limit
     * @param $offset
     * @return array
     */
    public  static function leaveHistory($yb_uid,$limit,$offset){
        $offset=$offset>0?$offset*$limit:0;
        $query=(new \yii\db\Query())
            ->select("*")
            ->from('leave')
            ->where([
                'yb_uid'=>$yb_uid
            ])
            ->orderBy(['id'=>SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->all();
        return $query;
    }

    /**
     * 成为管理员
     * @param $yb_uid
     * @param $data
     * @return int
     * @throws \yii\db\Exception
     */
    public static function beAdmin($yb_uid,$data){
        return  \Yii::$app->db->createCommand()->insert('adminer',[
            'yb_uid'=>$yb_uid,
            'detail'=>$data['detail'],
            'phone'=>$data['phone'],
        ])->execute();

    }

    /**
     * 判断是否是管理员
     * @return false|string|null
     * @throws \yii\db\Exception
     */
    public static function isAdmin($yb_uid){
      return  \Yii::$app->db->createCommand("SELECT yb_uid FROM `adminer` WHERE yb_uid=$yb_uid")->queryScalar();
    }
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['yb_uid', 'name', 'phone', 'group', 'school'], 'required'],
            [['yb_uid', 'name'], 'string', 'max' => 30],
            [['phone'], 'string', 'max' => 11],
            [['group'], 'string', 'max' => 15],
            [['school'], 'string', 'max' => 100],
            [['yb_uid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'yb_uid' => 'Yb Uid',
            'name' => 'Name',
            'phone' => 'Phone',
            'group' => 'Group',
            'school' => 'School',
        ];
    }
}
