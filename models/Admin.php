<?php
namespace app\models;
use yii\base\Model;

class Admin extends Model{
    public static function groupList($limit,$offset){
        $offset=$offset>0?$offset*$limit:0;
        $query=(new \yii\db\Query())
            ->select("*")
            ->from('group')
            ->where([
//                'state'=>0
            ])
            ->orderBy(['id'=>SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->all();
        return $query;
    }
}