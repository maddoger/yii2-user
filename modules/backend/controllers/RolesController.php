<?php

namespace maddoger\user\modules\backend\controllers;

use maddoger\user\modules\backend\models\AuthItem;
use maddoger\user\modules\backend\models\AuthItemChild;
use maddoger\user\modules\backend\models\AuthItemSearch;
use maddoger\core\BackendController;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use maddoger\core\Module;

/**
 * RolesController implements the CRUD actions for AuthItem model.
 */
class RolesController extends BackendController
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					[
						'actions' => ['index', 'view'],
						'allow' => true,
						'roles' => ['role.read'],
					],
					[
						'actions' => ['create', 'update-from-modules'],
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
				$children = @$_POST[$model->formName()]['children'];

                if ($children)
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
				$children = @$_POST[$model->formName()]['children'];

				AuthItemChild::deleteAll(['parent'=>$model->name]);
                if ($children)
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

		foreach ($allModules as $id=>$module) {
			if (is_array($module)) {
				$module = Yii::$app->getModule($id);
			}
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

				foreach ($roles as $name => $item) {
					if (!isset($item['data'])) $item['data'] = null;
					if (!isset($item['rule_name'])) $item['rule_name'] = null;
					if (!isset($item['description'])) $item['data'] = null;

                    if ($item['type'] == Item::TYPE_PERMISSION) {
                        if ($man->getPermission($name) !== null) {
                            //Item exists
                            $r = AuthItem::findOne($name);
                            $r->setAttributes($item);
                            $r->save(false);
                        } else {
                            $permission = $man->createPermission($name);
                            //$permission->ruleName = $item['rule_name'];
                            $permission->data = $item['data'];
                            $permission->description = $item['description'];
                            $man->add($permission);
                        }
                    } else if ($item['type'] == Item::TYPE_ROLE) {
                        
                        if (($role = $man->getRole($name)) === null) {
                            $role = $man->createRole($name);
                        }

                        $role->ruleName = $item['rule_name'];
                        $role->data = $item['data'];
                        $role->description = $item['description'];
                        $man->add($role);

                        if (isset($item['children']) && is_array($item['children'])) {
                            foreach ($item['children'] as $child) {
                                $child  = $man->getPermission($child);
                                if (!$child) $child = $man->getRole($child);
                                if ($child) {
                                    $man->addChild($role, $child);
                                }
                            }
                        }
                    }

				}

				//Delete old
				foreach ($oldItems as $item) {
					if (!isset($roles[$item['name']])) {
                        $obj = $man->getPermission($item['name']);
                        if (!$obj) $obj = $man->getRole($item['name']);
						if ($obj) $man->remove($obj);
					}
				}
				AuthItem::updateAll(['module'=>$module->id], ['name' => array_keys($roles)]);
			}

			\Yii::$app->getSession()->setFlash('success', \Yii::t('maddoger/user', 'Roles was update successfully!'));
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
		if (($model = AuthItem::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
