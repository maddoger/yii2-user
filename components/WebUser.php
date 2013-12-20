<?php

namespace rusporting\user\components;

use Yii;
use yii\web\User as BaseWebUser;

class WebUser extends BaseWebUser {

	public $identityClass = 'rusporting\user\models\User';
	//public $loginUrl = ['/user/login'];

	public function init()
	{
		parent::init();

		/**
		 * @var \rusporting\user\UserModule
		 */
		$module = Yii::$app->getModule('user');
		if ($module) {
			$this->loginUrl = $module->loginUrl;
		}
	}
}