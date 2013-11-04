<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	private $_id;
	public function authenticate()
	{
		/*$users=array(
			// username => password
			/*'demo'=>'demo',
			'admin'=>'admin',*/
	    /*		'GUEST'=>'GUEST',
		);*/
		$record = User::model()->findByAttributes(array('uname'=>$this->username)); //uname as username from the db
		if($record==null){
			$this->_id='user Null';
			   $this->errorCode = self::ERROR_USERNAME_INVALID;
		}else if($record->upswd!==$this->password){ //compare db password with password field
			$this->_id=$this->username;
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}else{
			$this->_id=$record['uname'];
			//$this->setState('title',$record['uname']);
			$this->errorCode = self::ERROR_NONE;
		}
		
		return !$this->errorCode;
		/*
		
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;*/
	}
	public function getId(){
		 return $this->_id;
	}
}
