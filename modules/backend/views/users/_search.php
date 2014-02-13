<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use maddoger\user\models\User;
use maddoger\user\modules\backend\models\AuthItem;

/**
 * @var yii\web\View $this
 * @var maddoger\user\modules\backend\models\UserSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => array('class' => 'form-horizontal'),
		'fieldConfig' => array(
			'labelOptions' => ['class' => 'control-label col-lg-4'],
			'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}\n{hint}</div>",
		),
	]); ?>

	<div class="panel-group" id="accordion">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						<?= Yii::t('maddoger/user', 'Search') ?>
						<span class="caret"></span>
					</a>
				</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse <?php if (isset($_GET['UserSearch'])) echo 'in'; ?>">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-3">
							<?= $form->field($model, 'id')->textInput() ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'email_confirmed')->dropDownList([
								'' => Yii::t('maddoger/user', 'Anything'),
								0 => Yii::t('maddoger/user', 'No'),
								1 => Yii::t('maddoger/user', 'Yes'),
							]) ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3">
							<?= $form->field($model, 'last_name')->textInput(['maxlength' => 50]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'first_name')->textInput(['maxlength' => 50]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'patronymic')->textInput(['maxlength' => 50]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'nick_name')->textInput(['maxlength' => 50]) ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3">
							<?= $form->field($model, 'date_of_birth')->textInput(['class' => 'form-control date-editor']) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'gender')->dropDownList(User::getGenderItems(), ['prompt'=> Yii::t('maddoger/user', 'Anything')]) ?>
						</div>
						<div class="col-lg-6"></div>
					</div>
					<div class="row">
						<div class="col-lg-3">
							<?= $form->field($model, 'facebook_uid')->textInput(['maxlength' => 255]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'facebook_name')->textInput(['maxlength' => 255]) ?>

							<?php //$form->field($model, 'facebook_data')->textarea(['rows' => 6]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'twitter_uid')->textInput(['maxlength' => 255]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'twitter_name')->textInput(['maxlength' => 255]) ?>

							<?php // $form->field($model, 'twitter_data')->textarea(['rows' => 6]) ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3">
							<?= $form->field($model, 'gplus_uid')->textInput(['maxlength' => 255]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'gplus_name')->textInput(['maxlength' => 255]) ?>

							<?php //$form->field($model, 'gplus_data')->textarea(['rows' => 6]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'vk_uid')->textInput(['maxlength' => 255]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'vk_name')->textInput(['maxlength' => 255]) ?>

							<?php // $form->field($model, 'vk_data')->textarea(['rows' => 6]) ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<?= Html::submitButton(Yii::t('maddoger/user', 'Search'), ['class' => 'btn btn-primary']) ?>
							<?= Html::a(Yii::t('maddoger/user', 'Reset'), ['index'], ['class' => 'btn btn-default']) ?>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<?php ActiveForm::end(); ?>

</div>
