<?php
/**
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name/
 * @copyright Copyright (c) 2013-2014 Rusporting Inc.
 * @since 18.12.13
 */

namespace rusporting\user;

use Yii;
use rusporting\core\Module;
use yii\rbac\Item;

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

	public $superUsers = ['admin2'];

	protected $hasBackend = true;
	protected $hasFrontend = true;

	/**
	 * Translation category for Yii::t function
	 * @var string
	 */
	public $translationCategory = 'rusporting/user';

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
		Yii::$app->getI18n()->translations[$this->translationCategory] =
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
		return Yii::t('rusporting/user', '_module_name_');
	}

	/**
	 * @inheritdoc
	 */
	public function getDescription()
	{
		return Yii::t('rusporting/user', '_module_description_');
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
	public function getConfigurationModel()
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
				Yii::t('rusporting/user', 'Backend authorization route'),
				Yii::t('rusporting/user', 'Provides authorization and password reset (with captcha) for backend application.')
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function getRbacRoles()
	{
		return [
			'user.create' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/user', 'Create new users')],
			'user.read' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/user', 'View users')],
			'user.update' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/user', 'Update users')],
			'user.delete' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/user', 'Delete users')],
			'user.manager' => [
				'type' => Item::TYPE_ROLE,
				'description' => Yii::t('rusporting/user', Yii::t('rusporting/user', 'Users manager')),
				'children' => [ 'user.create','user.read','user.update','user.delete' ]
			],

			'role.create' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/user', 'Create new roles')],
			'role.read' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/user', 'View roles')],
			'role.update' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/user', 'Update roles')],
			'role.delete' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/user', 'Delete roles')],
			'role.manager' => [
				'type' => Item::TYPE_ROLE,
				'description' => Yii::t('rusporting/user', Yii::t('rusporting/user', 'Roles manager')),
				'children' => [ 'role.create','role.read','role.update','role.delete' ]
			],
		];
	}

	/**
	 * Returns navigation items for backend
	 * @return array
	 */
	public function getBackendNavigation()
	{
		return [
			[
				'label' => Yii::t('rusporting/user', 'Users'),
				//'url' => 'user/user-backend/index',
				'items' => [
					['label' => Yii::t('rusporting/user', 'Users list'), 'fa'=>'user', 'url'=> ['/user/users/index'], 'activeUrl'=> ['/user/users/*']],
					['label' => Yii::t('rusporting/user', 'Roles list'), 'fa'=>'group', 'url'=> ['/user/roles/index'], 'activeUrl'=> ['/user/roles/*']],
				],
			]
		];
	}
}