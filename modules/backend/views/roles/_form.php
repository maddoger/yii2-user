<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\rbac\Item;
use rusporting\user\modules\backend\models\AuthItem;

/**
 * @var yii\web\View $this
 * @var rusporting\user\modules\backend\models\AuthItem $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="auth-item-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

		<?= $form->field($model, 'type')->dropDownList(AuthItem::getTypeValues(), ['prompt' => Yii::t('rusporting/user', 'Choose item type')]); ?>

		<?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>

		<?php
		$all = AuthItem::find()->select(['name', 'description'])->orderBy('name')->asArray()->all();
		$items = [];
		foreach ($all as $ar)
		{
			if ($ar['name'] != $model->name) {
				$items[$ar['name']] = $ar['name'].' - '.$ar['description'];
			}
		}
		echo $form->field($model, 'children')->listBox($items, ['class'=>'form-control select2', 'multiple'=> true, 'prompt' => Yii::t('rusporting/user', 'No childs')]);

		?>

		<?= $form->field($model, 'biz_rule')->textarea(['rows' => 2]) ?>

		<?= $form->field($model, 'data')->textarea(['rows' => 2]) ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('rusporting/user', 'Create') : Yii::t('rusporting/user', 'Update'),
				['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
