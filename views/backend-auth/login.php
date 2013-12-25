<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var rusporting\user\models\LoginForm $model
 */
$this->title = Yii::t('rusporting/user', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<?php
			$loginLogo = $this->context->module->authBackendLogo;
			if (!empty($loginLogo)) {
				echo '<h2 class="logo text-center">'.Html::img($loginLogo).'</h2><hr />';
			}
			?>
			<h3 class="text-center"><?= Yii::t('rusporting/user', 'Please sign in') ?></h3>
			<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
			<?= $form->field($model, 'username') ?>
			<?= $form->field($model, 'password')->passwordInput() ?>
			<p class="pull-right"><?= Html::a(Yii::t('rusporting/user', 'Forgot Password?'), ['/user/backend-auth/request-password-reset']) ?></p>
			<?= $form->field($model, 'rememberMe')->checkbox() ?>
			<p>
				<?= Html::submitButton(Yii::t('rusporting/user', 'Login'), ['class' => 'btn btn-lg btn-block btn-success']) ?>
			</p>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
