<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var rusporting\user\modules\backend\models\AuthItem $model
 */

$this->title = Yii::t('rusporting/user', 'Create role');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rusporting/user', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">
	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>
</div>
