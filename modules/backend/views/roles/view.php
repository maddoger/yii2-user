<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var maddoger\user\modules\backend\models\AuthItem $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/user', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">
	<p>
		<?= Html::a(Yii::t('maddoger/user', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
		<?php echo Html::a(Yii::t('maddoger/user', 'Delete'), ['delete', 'id' => $model->name], [
			'class' => 'btn btn-danger',
			'data-confirm' => Yii::t('maddoger/user', 'Are you sure to delete this item?'),
			'data-method' => 'post',
		]); ?>
	</p>

	<?php

	function getChildren($children)
	{
		if (!$children) return '';
		$childrenVal = '<ul class="list-unstyled">';
		foreach ($children as $child) {
			$childrenVal .= '<li><strong>'.$child->description.'</strong> (<a href="'.\yii\helpers\Url::to(['view', 'id'=>$child->name]). '">'.
				$child->name.'</a>)</li>';
			$childrenVal .= getChildren($child->getChildren()->orderBy(['type'=>SORT_ASC, 'name'=>SORT_ASC])->all());
		}
		return $childrenVal.'</ul><br />';
	}

	$children = $model->getChildren()->orderBy(['type'=>SORT_ASC, 'name'=>SORT_ASC])->all();
	$childrenVal = getChildren($children);

	$types = $model->getTypeValues();

	echo DetailView::widget([
		'model' => $model,
		'attributes' => [
			'name',
			[
				'label' => Yii::t('maddoger/user', 'Type'),
				'value' => $types[$model->type],
			],
			'description:ntext',
			'rule_name',
			'data:ntext',
			[
				'label' => Yii::t('maddoger/user', 'Children roles'),
				'format' => 'html',
				'value' => $childrenVal,
			],
		],
	]); ?>
</div>
