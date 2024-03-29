<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var maddoger\user\modules\backend\models\AuthItemSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="auth-item-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'name') ?>

		<?= $form->field($model, 'type') ?>

		<?= $form->field($model, 'description') ?>

		<?= $form->field($model, 'data') ?>

		<div class="form-group">
			<?= Html::submitButton(Yii::t('maddoger/user', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton(Yii::t('maddoger/user', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
