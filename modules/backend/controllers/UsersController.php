<?php

namespace rusporting\user\modules\backend\controllers;

use rusporting\user\models\User;
use rusporting\user\modules\backend\models\AuthAssignment;
use rusporting\user\modules\backend\models\UserSearch;
use rusporting\core\BackendController;
use yii\helpers\FileHelper;
use yii\image\ImageDriver;
use yii\validators\ImageValidator;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
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

		if ($model->load($_POST)) {


			if (isset($_POST['deleteAvatar']) && $_POST['deleteAvatar']==1) {
				//Delete file
				/*if ($model->avatar) {
					$url = $model->avatar;
					$path = Yii::getAlias('@frontendPath'.$url);
					if (file_exists($path)) {
						unlink($path);
					}
				}*/
				$model->avatar = null;
			} else {

				//Checking avatar
				$avatar = UploadedFile::getInstanceByName('avatar');

				if ($avatar !== null && $avatar->size>0 && $avatar->error == 0) {

					$filename = md5($model->id.$model->auth_key).'.'.$avatar->getExtension();
					$url = Yii::getAlias('@frontendUrl/uploads/avatars/'.$filename);
					$path = Yii::getAlias('@frontendPath/uploads/avatars');

					FileHelper::createDirectory($path);
					$path = $path.'/'.$filename;

					if ($avatar->saveAs($path)) {
						$image = Yii::$app->image->load($path);
						if ($image->width>150 && $image->height>150) {
							$image->resize(150, 150, Yii\image\drivers\Image::HEIGHT);
							$image->save($path, 100);
						}
						$model->avatar = $url;
					}

				}
			}

			if ($model->save()) {

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
