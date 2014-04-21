<?php
/**
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name/
 * @copyright Copyright (c) 2013-2014 Vitaliy Syrchikov
 * @since 18.12.13
 */

namespace maddoger\user;

use Yii;
use maddoger\core\Module;
use yii\rbac\Item;
use yii\web\User;

class UserModule extends Module
{
	public $authBackendLogo = '';
	public $authBackendLayout = 'auth';
	public $passwordResetTokenEmail = '@maddoger/user/mails/passwordResetToken';

	public $autoLogin = true;

	public $registrationUrl = array('/user/registration');
	public $requestPasswordResetUrl = array('/user/request-password-reset');
	public $loginUrl = array('/user/login');
	public $logoutUrl = array('/user/logout');
	public $profileUrl = array('/user/profile');
	public $returnUrl = array('/');
	public $returnLogoutUrl = array('/user/login');

	/**
	 * @var string[] login list of superusers
	 */
	public $superUsers = [];
	
	public $avatarWidth = 150;
	public $avatarHeight = 150;
	public $avatarDefault = null;
	

	protected $hasBackend = true;
	protected $hasFrontend = true;

	/**
	 * Translation category for Yii::t function
	 * @var string
	 */
	public $translationCategory = 'maddoger/user';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		/*Yii::$app->on(User::EVENT_AFTER_LOGIN, function ($event) {
			if ($event->identity) {
				$event->identity->updateLastVisitTime();
			}
		});*/

		//register translation messages from module
		//so no need do add to config/main.php
		Yii::$app->getI18n()->translations[$this->translationCategory] =
			array(
				'class' => 'yii\i18n\PhpMessageSource',
				'basePath' => '@maddoger/user/messages',
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return Yii::t('maddoger/user', '_module_name_');
	}

	/**
	 * @inheritdoc
	 */
	public function getDescription()
	{
		return Yii::t('maddoger/user', '_module_description_');
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
	public function getFaIcon()
	{
		return 'users';
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
		$model = parent::getConfigurationModel();
		$model->addAttributes([

			'avatarWidth' => ['label' => Yii::t('maddoger/user', 'Avatar width in pixels'),
				'rules' => [
					['avatarWidth', 'integer'],
					['avatarWidth', 'filter', 'filter'=>'intval'],
				]
			],
			'avatarHeight' => ['label' => Yii::t('maddoger/user', 'Avatar height in pixels'),
				'rules' => [
					['avatarWidth', 'integer'],
					['avatarWidth', 'filter', 'filter'=>'intval'],
				]
			],
			'avatarDefault' => ['type' => 'image', 'label' => Yii::t('maddoger/user', 'Default avatar')],

		]);
		return $model;
	}

	/**
	 * @inheritdoc
	 */
	public function getDefaultRoutes()
	{
		return [
			[
				['user/<action:(login|logout|captcha|request-password-reset|reset-password)>' => 'user/backend-auth/<action>'],
				Yii::t('maddoger/user', 'Backend authorization route'),
				Yii::t('maddoger/user', 'Provides authorization and password reset (with captcha) for backend application.')
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function getRbacRoles()
	{
		return [
			'user.create' => ['type'=>Item::TYPE_PERMISSION, 'description' => Yii::t('maddoger/user', 'Create new users')],
			'user.read' => ['type'=>Item::TYPE_PERMISSION, 'description' => Yii::t('maddoger/user', 'View users')],
			'user.update' => ['type'=>Item::TYPE_PERMISSION, 'description' => Yii::t('maddoger/user', 'Update users')],
			'user.delete' => ['type'=>Item::TYPE_PERMISSION, 'description' => Yii::t('maddoger/user', 'Delete users')],
			'user.manager' => [
				'type' => Item::TYPE_ROLE,
				'description' => Yii::t('maddoger/user', 'Users manager'),
				'children' => [ 'user.create','user.read','user.update','user.delete' ]
			],

			'role.create' => ['type'=>Item::TYPE_PERMISSION, 'description' => Yii::t('maddoger/user', 'Create new roles')],
			'role.read' => ['type'=>Item::TYPE_PERMISSION, 'description' => Yii::t('maddoger/user', 'View roles')],
			'role.update' => ['type'=>Item::TYPE_PERMISSION, 'description' => Yii::t('maddoger/user', 'Update roles')],
			'role.delete' => ['type'=>Item::TYPE_PERMISSION, 'description' => Yii::t('maddoger/user', 'Delete roles')],
			'role.manager' => [
				'type' => Item::TYPE_ROLE,
				'description' => Yii::t('maddoger/user', 'Roles manager'),
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
				'label' => Yii::t('maddoger/user', 'Users'), 'fa' => 'users',
				'roles' => ['user.read', 'role.read'],
				//'url' => 'user/user-backend/index',
				'items' => [
					['label' => Yii::t('maddoger/user', 'Users'), 'fa'=>'user', 'url'=> ['/user/users/index'],
						'activeUrl'=> ['/user/users/*'], 'roles' => ['user.read'],],
					['label' => Yii::t('maddoger/user', 'Roles'), 'fa'=>'group', 'url'=> ['/user/roles/index'],
						'activeUrl'=> ['/user/roles/*'], 'roles' => ['role.read']],
				],
			]
		];
	}
}