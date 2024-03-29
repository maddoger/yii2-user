<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use maddoger\user\modules\backend\models\AuthItem;
use maddoger\user\models\User;

/**
 * @var yii\web\View $this
 * @var maddoger\user\models\User $model
 * @var yii\widgets\ActiveForm $form
 */

$module = Yii::$app->getModule('user');
?>

<div class="user-form">

	<?php $form = ActiveForm::begin(
		[
			'options' => array('class' => 'form-horizontal', 'enctype'=>'multipart/form-data'),
			'fieldConfig' => array(
				'labelOptions' => ['class' => 'control-label col-lg-2'],
				'template' => "{label}\n<div class=\"col-lg-10\">{input}\n{error}\n{hint}</div>",
			),
		]
	); ?>

	<p>
		<?= Html::submitButton($model->isNewRecord ? Yii::t('maddoger/user', 'Create') : Yii::t('maddoger/user', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</p>

	<div class="row">

		<div class="col-md-12">
			<ul class="nav nav-tabs" style="margin-bottom: 15px;">
				<li class="active"><a href="#auth" data-toggle="tab"><?= Yii::t('maddoger/user', 'Authentication') ?></a></li>
				<li><a href="#main" data-toggle="tab"><?= Yii::t('maddoger/user', 'Main info') ?></a></li>
				<li><a href="#social" data-toggle="tab"><?= Yii::t('maddoger/user', 'Social') ?></a></li>
				<li><a href="#roles" data-toggle="tab"><?= Yii::t('maddoger/user', 'Roles') ?></a></li>

			</ul>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade active in" id="auth">
					<?= $form->field($model, 'id')->textInput(['readOnly' => true]) ?>

					<?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

					<?= $form->field($model, 'password')->passwordInput(['placeholder'=> Yii::t('maddoger/user', 'New password'), 'maxlength' => 255, 'autocomplete'=>'off']) ?>

					<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

					<?= $form->field($model, 'email_confirmed')->checkbox([], false) ?>

					<?php //$form->field($model, 'status')->textInput() ?>
				</div>
				<div class="tab-pane fade" id="main">

					<?= $form->field($model, 'last_name')->textInput(['maxlength' => 50]) ?>

					<?= $form->field($model, 'first_name')->textInput(['maxlength' => 50]) ?>

					<?= $form->field($model, 'patronymic')->textInput(['maxlength' => 50]) ?>

					<?= $form->field($model, 'nick_name')->textInput(['maxlength' => 50]) ?>

					<?= $form->field($model, 'date_of_birth')->widget('maddoger\admin\widgets\DateEditor') ?>

					<?= $form->field($model, 'gender')->dropDownList(User::getGenderItems()) ?>

					<?php
						echo $form->field($model, 'avatar')->widget('maddoger\admin\widgets\ImageInput', [
							'width' => $module->avatarWidth,
							'height' => $module->avatarHeight,
							'preview' => $model->getAvatarSrc(),
						]);
					?>


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
						$items[$ar['name']] = $ar['description'];
					}

					if ($items) {
						echo $form->field($model, 'rolesNames')->widget('maddoger\admin\widgets\Select2', [
							'data' => $items,
							'options' => [
								'multiple' => true,
								'prompt' => Yii::t('maddoger/user', 'Choose roles'),
							],
							'pluginOptions' => [
								'allowClear' => true,
								'placeholder' => Yii::t('maddoger/user', 'Choose roles'),
							],
						]);
					} else {
						$f = $form->field($model, 'rolesNames');
						$f->parts['{input}'] = '<p class="form-control-static">'.Yii::t('maddoger/user', 'No roles').'</p>';
						echo $f;
					}
					?>
				</div>
			</div>
		</div>
	</div>

	<p>
		<?= Html::submitButton($model->isNewRecord ? Yii::t('maddoger/user', 'Create') : Yii::t('maddoger/user', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</p>


	<?php ActiveForm::end(); ?>

</div>
