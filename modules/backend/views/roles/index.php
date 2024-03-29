<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \maddoger\user\modules\backend\models\AuthItem;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var maddoger\user\modules\backend\models\AuthItemSearch $searchModel
 */

$this->title = Yii::t('maddoger/user', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<div>
		<div class="pull-left"><?= Html::a(Yii::t('maddoger/user', 'Create new role'), ['create'], ['class' => 'btn btn-success']) ?></div>
		<div class="pull-right"><?= Html::a(Yii::t('maddoger/user', 'Update roles from modules'), ['update-from-modules'], ['class' => 'btn btn-primary']) ?></div>
		<div class="clearfix"></div>
	</div>
	<br />

	<?php
	echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'class' => 'grid-view',
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'description:ntext',
			'name',
			[
				'value' => function ($model, $index, $widget){
						$values = AuthItem::getTypeValues();
						return isset($values[$model->type]) ? $values[$model->type] : null;
					},
				'filter' => AuthItem::getTypeValues(),
				'attribute' => 'type',
			],


			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>

</div>
