<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use rusporting\user\modules\backend\models\AuthItem;
use rusporting\user\models\User;

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

	<?php

	$all = AuthItem::find()->where(['type'=>\yii\rbac\Item::TYPE_ROLE])->select(['name', 'description'])->orderBy('name')->asArray()->all();
	$items = [];
	foreach ($all as $ar)
	{
		$items[$ar['name']] = $ar['name'].' - '.$ar['description'];
	}
	//echo $form->field($model, 'rolesNames')->listBox($items, ['class'=>'form-control select2', 'multiple'=> true, 'prompt' => Yii::t('rusporting/user', 'No roles')]);

	$roles = '<ul class="list-unstyled">';
	foreach ($model->getRolesNames() as $name) {
		$roles .= '<li>'.Html::encode($items[$name]).'</li>';
	}
	$roles .= '</ul>';

	$genders = User::getGenderItems();

	echo DetailView::widget([
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
			[
				'label' => Yii::t('rusporting/user', 'Gender'),
				'format' => 'text',
				'value' => $genders[$model->gender],
			],
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
			[
				'label' => Yii::t('rusporting/user', 'Roles'),
				'format' => 'html',
				'value' => $roles,
			],
			'last_visit_time:datetime',
			'create_time:datetime',
			'update_time:datetime',
			'avatar',
		],
	]); ?>

</div>
