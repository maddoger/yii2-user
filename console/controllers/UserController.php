<?php

namespace maddoger\user\console\controllers;

use Yii;
use yii\console\Exception;
use yii\console\Controller;
use yii\db\Connection;
use maddoger\user\models\User;

class UserController extends Controller
{
	public $db = 'db';

	public function actionIndex()
	{
		$this->stdout('hello world!');
	}

	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {

			if ($action->id !== 'create') {
				if (is_string($this->db)) {
					$this->db = Yii::$app->getComponent($this->db);
				}
				if (!$this->db instanceof Connection) {
					throw new Exception("The 'db' option must refer to the application component ID of a DB connection.");
				}
				if ($this->db->schema->getTableSchema(User::tableName(), true) === null) {
					throw new Exception("The '".User::tableName()."' table does not exist.");
				}
			}

			$version = Yii::getVersion();
			echo "maddoger User Creation tool (based on Yii v{$version})\n\n";
			return true;
		} else {
			return false;
		}
	}

	public function actionCreate()
	{
		echo "User creation tool\n";

		$user = new User();
		$user->setScenario('signup');

		$user->username = $this->prompt('User name', ['default'=>'admin', 'required'=>true]);
		$user->email = $this->prompt('Email', ['default'=>'admin@admin.local', 'required'=>true]);
		$user->password = $this->prompt('Password', ['default'=>'pass', 'required'=>true]);
		$user->email_confirmed = true;

		if ($user->validate()) {
			//Create user
			if ($user->save()) {
				echo "User was successfully created. ID: ", $user->id ,"\n";
			} else {
				echo "Error while saving!\n";
			}
			return 0;
		} else {
			echo "Errors:\n";
			$errors = $user->getErrors();
			foreach ($errors as $field=>$fieldErrors) {
				foreach ($fieldErrors as $error) {
					echo "\t", $error, "\n";
				}
			}
			return 1;
		}
	}
}