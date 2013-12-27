<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use rusporting\user\modules\backend\models\AuthItem;
use rusporting\user\models\User;

/**
 * @var yii\web\View $this
 * @var rusporting\user\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-form">

	<?php $form = ActiveForm::begin(
		[
			'options' => array('class' => 'form-horizontal'),
			'fieldConfig' => array(
				'labelOptions' => ['class' => 'control-label col-lg-2'],
				'template' => "{label}\n<div class=\"col-lg-10\">{input}\n{error}\n{hint}</div>",
			),
		]
	); ?>

	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-tabs" style="margin-bottom: 15px;">
				<li class="active"><a href="#auth" data-toggle="tab"><?= Yii::t('rusporting/user', 'Authentication') ?></a></li>
				<li><a href="#main" data-toggle="tab"><?= Yii::t('rusporting/user', 'Main info') ?></a></li>
				<li><a href="#social" data-toggle="tab"><?= Yii::t('rusporting/user', 'Social') ?></a></li>
				<li><a href="#roles" data-toggle="tab"><?= Yii::t('rusporting/user', 'Roles') ?></a></li>

			</ul>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade active in" id="auth">
					<?= $form->field($model, 'id')->textInput(['readOnly' => true]) ?>

					<?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

					<?= $form->field($model, 'password')->passwordInput(['placeholder'=>'New password', 'maxlength' => 255, 'autocomplete'=>'off']) ?>

					<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

					<?= $form->field($model, 'email_confirmed')->checkbox([], false) ?>

					<?php //$form->field($model, 'status')->textInput() ?>
				</div>
				<div class="tab-pane fade" id="main">

					<?= $form->field($model, 'last_name')->textInput(['maxlength' => 50]) ?>

					<?= $form->field($model, 'first_name')->textInput(['maxlength' => 50]) ?>

					<?= $form->field($model, 'patronymic')->textInput(['maxlength' => 50]) ?>

					<?= $form->field($model, 'nick_name')->textInput(['maxlength' => 50]) ?>

					<?= $form->field($model, 'date_of_birth')->textInput(['class' => 'date-editor']) ?>

					<?= $form->field($model, 'gender')->dropDownList(User::getGenderItems()) ?>

					<?= $form->field($model, 'avatar')->fileInput(['maxlength' => 255]) ?>
				</div>
				<div class="tab-pane fade" id="social">
					<?= $form->field($model, 'facebook_uid')->textInput(['maxlength' => 255]) ?>

					<?= $form->field($model, 'facebook_name')->textInput(['maxlength' => 255]) ?>

					<?php //$form->field($model, 'facebook_data')->textarea(['rows' => 6]) ?>

					<?= $form->field($model, 'twitter_uid')->textInput(['maxlength' => 255]) ?>

					<?= $form->field($model, 'twitter_name')->textInput(['maxlength' => 255]) ?>

					<?php // $form->field($model, 'twitter_data')->textarea(['rows' => 6]) ?>

					<?= $form->field($model, 'gplus_uid')->textInput(['maxlength' => 255]) ?>

					<?= $form->field($model, 'gplus_name')->textInput(['maxlength' => 255]) ?>

					<?php //$form->field($model, 'gplus_data')->textarea(['rows' => 6]) ?>

					<?= $form->field($model, 'vk_uid')->textInput(['maxlength' => 255]) ?>

					<?= $form->field($model, 'vk_name')->textInput(['maxlength' => 255]) ?>

					<?php // $form->field($model, 'vk_data')->textarea(['rows' => 6]) ?>
				</div>
				<div class="tab-pane fade" id="roles">
					<?php
					$all = AuthItem::find()->where(['type'=>\yii\rbac\Item::TYPE_ROLE])->select(['name', 'description'])->orderBy('name')->asArray()->all();
					$items = [];
					foreach ($all as $ar)
					{
						$items[$ar['name']] = $ar['name'].' - '.$ar['description'];
					}
					echo $form->field($model, 'rolesNames')->listBox($items, ['class'=>'form-control select2', 'multiple'=> true, 'prompt' => Yii::t('rusporting/user', 'No roles')]);
					?>
				</div>
				<div class="row">
					<div class="col-lg-2"></div>
					<div class="col-lg-10">
					<?= Html::submitButton($model->isNewRecord ? Yii::t('rusporting/user', 'Create') : Yii::t('rusporting/user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>
