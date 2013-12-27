<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var rusporting\user\models\User $model
 */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rusporting/user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

	<p>
		<?= Html::a(Yii::t('rusporting/user', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?php echo Html::a(Yii::t('rusporting/user', 'Delete'), ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data-confirm' => Yii::t('rusporting/user', 'Are you sure to delete this item?'),
			'data-method' => 'post',
		]); ?>
	</p>

	<?php echo DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'username',
			'email:email',
			'email_confirmed:boolean',
			'last_name',
			'first_name',
			'patronymic',
			'nick_name',
			'short_name',
			'full_name',
			'date_of_birth',
			'gender',
			'facebook_uid',
			'facebook_name',
			'facebook_data:ntext',
			'twitter_uid',
			'twitter_name',
			'twitter_data:ntext',
			'gplus_uid',
			'gplus_name',
			'gplus_data:ntext',
			'vk_uid',
			'vk_name',
			'vk_data:ntext',
			'last_visit_time:datetime',
			'create_time:datetime',
			'update_time:datetime',
			'avatar',
		],
	]); ?>

</div>
