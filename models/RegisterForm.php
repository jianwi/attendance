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

class RegisterForm extends Model
{
    public $branch;
    public $phone;
    public $group;
    public $verifyCode;
    /**
     * @return array
     */
    public function rules(){
        return [
            [['branch','phone','group'],"required"],
            ['verifyCode', 'captcha'],
        ];
}

}