<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \maddoger\user\models\User $model
 */
$this->title = Yii::t('maddoger/user', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password row">
	<div class="col-md-6 col-md-offset-3">
		<h1><?= Html::encode($this->title) ?></h1>

		<p><?= Yii::t('maddoger/user', 'Please choose your new password:') ?></p>

		<div>
			<?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
			<?= $form->field($model, 'password')->passwordInput() ?>
			<div class="form-group">
				<?= Html::submitButton(Yii::t('maddoger/user', 'Save'), ['class' => 'btn btn-lg btn-block btn-success']) ?>
			</div>
			<p class="text-center"><?= Yii::t('maddoger/user', 'Remembered your password?') .' '. Html::a(Yii::t('maddoger/user', 'Login here'), ['/user/backend-auth/login']) ?></p>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
