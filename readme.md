Yii2 User Management Module by maddoger

## Installation

1) clone
2) migrate: ##yii migrate --migrationPath=@maddoger/user/migrations##
3) modules:

'modules' => [
		...
		'user' => 'maddoger\user\UserModule',
		...
	],

4) components:

'components' => [
		...
		'user' => [
			'identityClass' => 'maddoger\user\models\User',
			'loginUrl' => ['user/login'],
		],
		...
	],


