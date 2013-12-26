<?php

namespace rusporting\user\components;

use Yii;
use yii\web\User as BaseWebUser;
use yii\db\Expression;

class WebUser extends BaseWebUser {

	public $identityClass = 'rusporting\user\models\User';
	//public $loginUrl = ['/user/login'];

	public function init()
	{
		Yii::$app->setComponents([
			'session' => [
				'class' => 'rusporting\user\components\Session',
			],
			'authManager' => [
				'class' => 'rusporting\user\components\AuthManager',
				'defaultRoles' => ['guest'],
			],
		]);

		parent::init();

		/**
		 * @var \rusporting\user\UserModule $module
		 */
		$module = Yii::$app->getModule('user');
		if ($module) {
			$this->loginUrl = $module->loginUrl;
			$this->enableAutoLogin = $module->autoLogin;
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function afterLogin($identity, $cookieBased)
	{
		parent::afterLogin($identity, $cookieBased);
		$this->identity->setScenario(self::EVENT_AFTER_LOGIN);
		$this->identity->setAttribute('last_visit_time', time());
		// $this->identity->setAttribute('login_ip', ip2long(\Yii::$app->getRequest()->getUserIP()));
		$this->identity->save(false);
	}

	/**
	 * Performs access check for this user.
	 * @param string $operation the name of the operation that need access check.
	 * @param array $params name-value pairs that would be passed to business rules associated
	 * with the tasks and roles assigned to the user. A param with name 'userId' is added to
	 * this array, which holds the value of [[id]] when [[DbAuthManager]] or
	 * [[PhpAuthManager]] is used.
	 * @param boolean $allowCaching whether to allow caching the result of access check.
	 * When this parameter is true (default), if the access check of an operation was performed
	 * before, its result will be directly returned when calling this method to check the same
	 * operation. If this parameter is false, this method will always call
	 * [[AuthManager::checkAccess()]] to obtain the up-to-date access result. Note that this
	 * caching is effective only within the same request and only works when `$params = []`.
	 * @return boolean whether the operations can be performed by this user.
	 */
	public function checkAccess($operation, $params = [], $allowCaching = true)
	{
		//Check superadmin
		if (!$this->isGuest) {
			$module = Yii::$app->getModule('user');
			if (in_array($this->identity->username, $module->superUsers)) {
				return true;
			}
		}
		return parent::checkAccess($operation, $params, $allowCaching);
	}
}