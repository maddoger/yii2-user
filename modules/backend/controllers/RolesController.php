<?php

namespace rusporting\user\modules\backend\controllers;

use rusporting\user\modules\backend\models\AuthItem;
use rusporting\user\modules\backend\models\AuthItemChild;
use rusporting\user\modules\backend\models\AuthItemSearch;
use rusporting\core\BackendController;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;
use Yii;
use rusporting\core\Module;

/**
 * RolesController implements the CRUD actions for AuthItem model.
 */
class RolesController extends BackendController
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
						'roles' => ['role.read'],
					],
					[
						'actions' => ['create'],
						'allow' => true,
						'roles' => ['role.create'],
					],
					[
						'actions' => ['update'],
						'allow' => true,
						'roles' => ['role.update'],
					],
					[
						'actions' => ['delete'],
						'allow' => true,
						'roles' => ['role.delete'],
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
	 * Lists all AuthItem models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new AuthItemSearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single AuthItem model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new AuthItem model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new AuthItem;

		if ($model->load($_POST) && $model->save()) {
			if (isset($_POST[$model->formName()]['children'])) {
				$children = $_POST[$model->formName()]['children'];

				foreach ($children as $child) {
					$new = new AuthItemChild();
					$new->child = $child;
					$new->parent = $model->name;
					$new->save(false);
				}
			}
			return $this->redirect(['view', 'id' => $model->name]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing AuthItem model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load($_POST) && $model->save()) {
			if (isset($_POST[$model->formName()]['children'])) {
				$children = $_POST[$model->formName()]['children'];

				AuthItemChild::deleteAll(['parent'=>$model->name]);
				foreach ($children as $child) {
					$new = new AuthItemChild();
					$new->child = $child;
					$new->parent = $model->name;
					$new->save(false);
				}
			}
			return $this->redirect(['view', 'id' => $model->name]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing AuthItem model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		return $this->redirect(['index']);
	}

	public function actionUpdateFromModules()
	{
		$modules = [];
		$allModules = Yii::$app->getModules(false);

		foreach ($allModules as $module) {
			//If is rusporting module with info
			if ($module instanceof Module) {
				$roles = $module->getRbacRoles();
				if (is_array($roles)) {
					$modules[] = [$module, $roles];
				}
			}
		}

		if ($modules) {

			$man = Yii::$app->getAuthManager();

			foreach ($modules as $ar) {
				list($module, $roles) = $ar;

				//Delete old rules for module
				$oldItems = AuthItem::find()->where(['module' => $module->id])->asArray()->all();

				foreach ($roles as $name => $role) {
					if (!isset($role['data'])) $role['data'] = null;
					if (!isset($role['biz_rule'])) $role['biz_rule'] = null;
					if (!isset($role['description'])) $role['data'] = null;

					if ($man->getItem($name) !== null) {
						//Item exists
						$r = AuthItem::find($name);
						$r->setAttributes($role);
						$r->save(false);

						if (isset($role['children']) && is_array($role['children'])) {
							foreach ($role['children'] as $child) {
								if (!$man->hasItemChild($name, $child)) {
									$man->addItemChild($name, $child);
								}
							}
						}
					} else {
						//New
						$man->createItem($name, $role['type'], $role['description'], $role['biz_rule'], $role['data']);
						if (isset($role['children']) && is_array($role['children'])) {
							foreach ($role['children'] as $child) {
								$man->addItemChild($name, $child);
							}
						}
					}
				}

				//Delete old
				foreach ($oldItems as $item) {
					if (!isset($roles[$item['name']])) {
						$man->removeItem($item['name']);
					}
				}
				AuthItem::updateAll(['module'=>$module->id], ['name' => array_keys($roles)]);
			}

			\Yii::$app->getSession()->setFlash('success', \Yii::t('rusporting/user', 'Roles was update successfully!'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the AuthItem model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return AuthItem the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = AuthItem::find($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
