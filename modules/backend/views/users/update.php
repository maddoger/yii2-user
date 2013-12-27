<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var rusporting\user\models\User $model
 */

$this->title = Yii::t('rusporting/user', 'Update User: {name}', ['name'=>$model->full_name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('rusporting/user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->full_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rusporting/user', 'Update');
?>
<div class="user-update">
	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
