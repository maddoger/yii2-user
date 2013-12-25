<?php

namespace rusporting\user;

use yii\web\AssetBundle;

class BackendAuthAsset extends AssetBundle
{
	public $sourcePath = '@rusporting/user/assets';
	public $css = ['main.less'];
	public $js = [];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
		'yii\bootstrap\BootstrapThemeAsset',
	];
}