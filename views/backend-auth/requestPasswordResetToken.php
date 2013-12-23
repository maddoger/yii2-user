<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\captcha\CaptchaAsset;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\models\User $model
 *
 */
$this->title = Yii::t('rusporting\user', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;
CaptchaAsset::register($this);
?>
<div class="site-request-password-reset row">
	<div class="col-md-6 col-md-offset-3">
		<h2><?= Html::encode($this->title) ?></h2>

		<p><?= Yii::t('rusporting\user', 'Please fill out your email. A link to reset password will be sent there.') ?></p>

		<div>
			<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
			<?= $form->field($model, 'email') ?>
			<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
				'captchaAction' => '/user/captcha',
				'options' => ['class' => 'form-control'],
				'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
			]) ?>
			<p>
				<?= Html::submitButton(Yii::t('rusporting\user', 'Send'), ['class' => 'btn btn-lg btn-block btn-success']) ?>
			</p>
			<p class="text-center"><?= Yii::t('rusporting\user', 'Remembered your password?') .' '. Html::a(Yii::t('rusporting\user', 'Login here'), ['/user/backend-auth/login']) ?></p>
			<?php ActiveForm::end(); ?>
		</div>

	</div>
</div>
