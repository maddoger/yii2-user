<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use rusporting\user\widgets\Alert;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<title><?= Html::encode($this->title) ?></title>
	<style>
		#content {
			margin-top: 20px;
		}
	</style>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container" id="content">
	<?= Alert::widget() ?>
	<?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
