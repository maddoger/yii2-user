<?php

namespace rusporting\user\modules\backend\controllers;

use rusporting\user\models\User;
use rusporting\user\modules\backend\models\AuthAssignment;
use rusporting\user\modules\backend\models\UserSearch;
use rusporting\core\BackendController;
use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;
use Yii;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends BackendController
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => 'yii\web\AccessControl',
				'rules' => [
					[
						'actions' => ['index', 'view'],
						'allow' => true,
						'roles' => ['user.read'],
					],
					[
						'actions' => ['create'],
						'allow' => true,
						'roles' => ['user.create'],
					],
					[
						'actions' => ['update'],
						'allow' => true,
						'roles' => ['user.update'],
					],
					[
						'actions' => ['delete'],
						'allow' => true,
						'roles' => ['user.delete'],
					],
					[
						'allow' => false,
					]
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all User models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserSearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single User model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new User;
		$model->setScenario('backendCreate');

		if ($model->load($_POST) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$model->setScenario('backendEdit');

		if ($model->load($_POST) && $model->save()) {
			if (isset($_POST[$model->formName()]['password']) && !empty($_POST[$model->formName()]['password'])) {

				$model->setScenario('resetPassword');
				$model->password = $_POST[$model->formName()]['password'];
				if ($model->save()) {
					Yii::$app->getSession()->setFlash('success', Yii::t('rusporting/user', 'Password was changed successfully.'));
					return $this->redirect(['view', 'id' => $model->id]);
				}
			} else
				return $this->redirect(['view', 'id' => $model->id]);
		}
		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		return $this->redirect(['index']);
	}

	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = User::find($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException(\Yii::t('rusporting/user', 'The requested user does not exist.'));
		}
	}
}
