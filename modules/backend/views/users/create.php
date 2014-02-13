<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var maddoger\user\models\User $model
 */

$this->title = Yii::t('maddoger/user', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
