<?php
/**
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name/
 * @copyright Copyright (c) 2013-2014 Rusporting Inc.
 * @since 18.12.13
 */

namespace rusporting\user;

use Yii;
use rusporting\core\components\Module;

class UserModule extends Module
{
	public $authBackendLogo = '';
	public $authBackendLayout = 'auth';
	public $passwordResetTokenEmail = '@rusporting/user/mails/passwordResetToken';

	public $autoLogin = true;

	public $registrationUrl = array('/user/registration');
	public $requestPasswordResetUrl = array('/user/request-password-reset');
	public $loginUrl = array('/user/login');
	public $logoutUrl = array('/user/logout');
	public $profileUrl = array('/user/profile');
	public $returnUrl = array('/');
	public $returnLogoutUrl = array('/user/login');

	/**
	 * Translation category for Yii::t function
	 * @var string
	 */
	public static $translationCategory = 'rusporting\user';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		//Console
		if (Yii::$app instanceof \yii\console\Application) {
			$this->controllerNamespace = 'rusporting\user\console\controllers';
		}

		Yii::$app->on(\yii\web\User::EVENT_AFTER_LOGIN, function ($event) {
			if ($event->identity) {
				$event->identity->updateLastVisitTime();
			}
		});

		//register translation messages from module
		//so no need do add to config/main.php
		Yii::$app->getI18n()->translations[static::$translationCategory] =
			array(
				'class' => 'yii\i18n\PhpMessageSource',
				'basePath' => '@rusporting/user/messages',
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return static::t('_module_name_');
	}

	/**
	 * @inheritdoc
	 */
	public function getDescription()
	{
		return static::t('_module_description_');
	}

	/**
	 * @inheritdoc
	 */
	public function getVersion()
	{
		return '0.1';
	}

	/**
	 * @inheritdoc
	 */
	public function getIcon()
	{
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function isMultiLanguage()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function hasFrontend()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function hasBackend()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function getConfigurationForm()
	{
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function getDefaultRoutes()
	{
		return [
			[
				['user/<action:(login|logout|captcha|request-password-reset|reset-password)>' => 'user/backend-auth/<action>'],
				static::t('Backend authorization route'),
				static::t('Provides authorization and password reset (with captcha) for backend application.')
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function getRights()
	{
		return null;
	}
}