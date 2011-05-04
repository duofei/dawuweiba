<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
	    if ($this->isAuthenticated) return true;
	    
		$username = strtolower($this->username);
	    $user = User::model()->find('LOWER(username) = ?', array($username));
		
		if ($user === null)
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		elseif ($user->password != md5($this->password))
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else {
			$this->errorCode = self::ERROR_NONE;
			$this->_id = $user->id;
			$this->setUserStates($user);
			$user->afterLogin();
		}
		return !$this->errorCode;
	}
	
	public function getId()
	{
	    return $this->_id;
	}

	/**
	 * 设置用户资料，放入session中
	 * @param User $user
	 */
	private function setUserStates($user)
	{
	    $session = app()->session;
        $session['email'] = $user->email;
        $session['realname'] = $user->realname;
        $session['screenName'] = $user->screenName;
        $session['gender'] = $user->gender;
        $session['birthday'] = $user->birthday;
        $session['genderText'] = $user->genderText;
        $session['telphone'] = $user->telphone;
        $session['mobile'] = $user->mobile;
        $session['create_time'] = $user->create_time;
        $session['createTimeText'] = $user->createTimeText;
        $session['create_ip'] = $user->create_ip;
        $session['last_login_time'] = $user->last_login_time;
        $session['lastLoginTimeText'] = $user->lastLoginTimeText;
        $session['last_login_ip'] = $user->last_login_ip;
        $session['login_nums'] = $user->login_nums;
        $session['portrait'] = $user->portrait;
        $session['portraitUrl'] = $user->portraitUrl;
        $session['portraitHtml'] = $user->portraitHtml;
        $session['portraitLinkHtml'] = $user->portraitLinkHtml;
        $session['integral'] = $user->integral;
        $session['credit'] = $user->credit;
        $session['bcnums'] = $user->bcnums;
        $session['qq'] = $user->qq;
        $session['city_id'] = $user->city_id;
        $session['manage_city_id'] = $user->manage_city_id;
        $session['super_admin'] = $user->super_admin;
        $session['source'] = $user->source;
        $session['source_uid'] = $user->source_uid;
        $session['update_time'] = $user->update_time;
        $session['updateTimeText'] = $user->updateTimeText;
        $session['orderCompleteCount'] = $user->orderCompleteCount;
        $session['orderGoodsCount'] = $user->getOrderGoodsNums();
        $session['super_shop'] = (int)$user->super_shop;
        $session['approve_state'] = $user->approve_state;
        
		if ($user->super_shop || 1 == $user->shopCount) {
		    $session['shop'] = $user->shops[0];
		}

		$this->setState('screenName', $user->screenName);
	    
	}
}