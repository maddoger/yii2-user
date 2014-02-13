<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var maddoger\user\modules\backend\models\UserSearch $searchModel
 */

$this->title = Yii::t('maddoger/user', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

	<p>
		<?= Html::a(Yii::t('maddoger/user', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<br />
	<?php echo $this->render('_search', ['model' => $searchModel]); ?>
	<br />

	<?php echo GridView::widget([
		'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			'id',
			'username',
			'email:email',
			'full_name',
			'last_visit_time:datetime',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>

</div>
