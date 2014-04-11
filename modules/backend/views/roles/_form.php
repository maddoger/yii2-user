<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\rbac\Item;
use maddoger\user\modules\backend\models\AuthItem;

/**
 * @var yii\web\View $this
 * @var maddoger\user\modules\backend\models\AuthItem $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="auth-item-form">

	<?php $form = ActiveForm::begin(); ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('maddoger/user', 'Create') : Yii::t('maddoger/user', 'Save'),
				['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

		<?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

		<?= $form->field($model, 'type')->dropDownList(AuthItem::getTypeValues(), ['prompt' => Yii::t('maddoger/user', 'Choose item type')]); ?>

		<?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>

		<?php
		$all = AuthItem::find()->select(['name', 'description'])->orderBy('name')->asArray()->all();
		$items = [];
		foreach ($all as $ar)
		{
			if ($ar['name'] != $model->name) {
				$items[$ar['name']] = $ar['description'];
			}
		}
		echo $form->field($model, 'children')->listBox($items, ['class'=>'form-control select2', 'multiple'=> true, 'prompt' => Yii::t('maddoger/user', 'No children')]);

		?>

		<?= $form->field($model, 'rule_name')->textInput(['rows' => 2]) ?>

		<?= $form->field($model, 'data')->textarea(['rows' => 2]) ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('maddoger/user', 'Create') : Yii::t('maddoger/user', 'Save'),
				['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
