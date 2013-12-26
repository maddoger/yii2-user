<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var rusporting\user\modules\backend\models\AuthItem $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rusporting/user', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">
	<p>
		<?= Html::a(Yii::t('rusporting/user', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
		<?php echo Html::a(Yii::t('rusporting/user', 'Delete'), ['delete', 'id' => $model->name], [
			'class' => 'btn btn-danger',
			'data-confirm' => Yii::t('app', Yii::t('rusporting/user', 'Are you sure to delete this item?')),
			'data-method' => 'post',
		]); ?>
	</p>

	<?php

	function getChildren($children)
	{
		if (!$children) return '';
		$childrenVal = '<ul class="list-unstyled">';
		foreach ($children as $child) {
			$childrenVal .= '<li><strong>'.$child->description.'</strong> (<a href="'.Yii::$app->controller->createUrl('view', ['id'=>$child->name]). '">'.
				$child->name.'</a>)</li>';
			$childrenVal .= getChildren($child->getChildren()->all());
		}
		return $childrenVal.'</ul>';
	}

	$children = $model->getChildren()->all();
	$childrenVal = getChildren($children);

	echo DetailView::widget([
		'model' => $model,
		'attributes' => [
			'name',
			'type',
			'description:ntext',
			'biz_rule:ntext',
			'data:ntext',
			[
				'label' => Yii::t('app', Yii::t('rusporting/user', 'Children roles')),
				'format' => 'html',
				'value' => $childrenVal,
			],
		],
	]); ?>
</div>
