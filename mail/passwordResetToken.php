<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var maddoger\user\models\User $user;
 */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl('user/reset-password', ['token' => $user->password_reset_token]);
?>

<?= Yii::t('maddoger/user', 'Hello {name},', ['name' => Html::encode($user->username)]) ?>

<?= Yii::t('maddoger/user', 'Follow the link below to reset your password:') ?>

<?= Html::a(Html::encode($resetLink), $resetLink) ?>
