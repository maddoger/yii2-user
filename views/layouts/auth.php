<?php
use maddoger\user\BackendAuthAsset;
use yii\helpers\Html;
use maddoger\user\widgets\Alert;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
BackendAuthAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<title><?= Html::encode($this->title) ?></title>
    <?= Html::csrfMetaTags() ?>
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
