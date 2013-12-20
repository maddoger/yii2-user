<?php
/**
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name/
 * @copyright Copyright (c) 2013-2014 Rusporting Inc.
 * @since 18.12.13
 */

namespace rusporting\user;

use Yii;
use yii\base\Module;

class UserModule extends Module {

	public $setUserComponentSettings=true;
	public $autoLogin=true;

	public $registrationUrl = array("/user/registration");
	public $recoveryUrl = array("/user/recovery/recovery");
	public $loginUrl = array("/user/login");
	public $logoutUrl = array("/user/logout");
	public $profileUrl = array("/user/profile");
	public $returnUrl = array("/user/profile");
	public $returnLogoutUrl = array("/user/login");

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		//register translation messages from module
		//so no need do add to config/main.php
		Yii::$app->getI18n()->translations['user'] =
		   array(
			   'class'=>'yii\i18n\PhpMessageSource',
			   'basePath'=>'@rusporting/user/messages',
		   );
	}
}