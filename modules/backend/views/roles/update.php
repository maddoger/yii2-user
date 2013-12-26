<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var rusporting\user\modules\backend\models\AuthItem $model
 */

$this->title = Yii::t('rusporting/user', 'Update role: {name}', ['name'=>$model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('rusporting/user', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-item-update">
	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
