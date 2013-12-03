<?php
class UserIdentity extends CUserIdentity
{
    private $_id;
    public function authenticate()
    {
        $record=User::model()->findByAttributes(array('uname'=>  strtoupper($this->username)));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($record->upswd!==  strtoupper($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
			//successful login
            $this->_id=$record->userid;
            $this->username = $record->uname;
            $this->errorCode=self::ERROR_NONE;
			//Yii::app()->db->close();
        }
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}
?>
