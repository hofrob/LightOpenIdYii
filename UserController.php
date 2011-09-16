<?php

class UserController extends Controller {

	public $defaultAction = 'login';

	public function beforeAction($action) {
		if(!Yii::app()->user->isGuest && $action->id == 'login') {
			$this->redirect(array('loggedin'));
			return false;
		}
		return parent::beforeAction($action);
	}

	public function actionLogin() {

		$user = new UserIdentity;
		$user->openid = $_GET;
		$auth = $user->authenticate();

		if($auth === UserIdentity::ERROR_CANCELLED) {
			$this->redirect(array('cancelled'));
			return;
		} elseif($auth === UserIdentity::ERROR_NONE) {
			$this->redirect(array('loggedin'));
			return;
		}

		Yii::app()->openid->identity = 'https://www.google.com/accounts/o8/id';
		Yii::app()->openid->required = array('namePerson/friendly', 'contact/email');
		Yii::app()->openid->realm = (!empty($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
		Yii::app()->openid->returnUrl = Yii::app()->openid->realm.$_SERVER['REQUEST_URI'];
		$url = Yii::app()->openid->authUrl();
		$this->redirect($url);
	}

	public function actionLoggedin() {
		$this->render('loggedin');
	}

	public function actionCancelled() {
		$this->render('cancelled');
	}

	public function actionLogout() {
		Yii::app()->user->logout();
		$this->render('logout');
	}
}