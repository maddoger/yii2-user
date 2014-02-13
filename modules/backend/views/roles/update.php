<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var maddoger\user\modules\backend\models\AuthItem $model
 */

$this->title = Yii::t('maddoger/user', 'Update role: {name}', ['name'=>$model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/user', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('maddoger/user', 'Update');
?>
<div class="auth-item-update">
	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
