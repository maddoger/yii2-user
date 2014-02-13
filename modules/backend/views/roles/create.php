<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var maddoger\user\modules\backend\models\AuthItem $model
 */

$this->title = Yii::t('maddoger/user', 'Create role');
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/user', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">
	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>
</div>
