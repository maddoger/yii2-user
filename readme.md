Yii2 User Management Module by Rusporting

## Installation

1) clone
2) migrate: ##yii migrate --migrationPath=@rusporting/user/migrations##
3) modules:

'modules' => [
		...
		'user' => 'rusporting\user\UserModule',
		...
	],

4) components:

'components' => [
		...
		'user' => [
			'identityClass' => 'rusporting\user\models\User',
			'loginUrl' => ['user/login'],
		],
		...
	],