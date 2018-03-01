<?php
App::uses('AppController', 'Controller');
App::uses('ProgressBar', 'Lib');

class IndexController extends AppController {

	public $uses = array('TwitterList','Influencer','User');

	const NUM_MAX_INFLUENCERS = 12;

	public function index() {

		if (isset($this->request->query['not_authorized'])) {
			$this->Session->destroy();
		}

		$this->_setUsernameFromSession();
		$this->_setAuthenticated();

		// DEFAULT INFLUENCERS
		$influencers = $this->Influencer->find('all',array(
			'conditions' => array(
				'default' => true
			)
		));
		//shuffle($influencers);
		//$influencers = array_slice($influencers,0,self::NUM_MAX_INFLUENCERS);
		$this->set('influencers',$influencers);

	}

	public function view() {

		$viewInfluencer = $this->request->params['slug'];
		$this->_setUsernameFromSession();
		$this->_setAuthenticated();

		// DEFAULT INFLUENCERS
		$influencers = $this->Influencer->find('all',array(
			'conditions' => array(
				'default' => true
			)
		));
		//shuffle($influencers);
		//$influencers = array_slice($influencers,0,self::NUM_MAX_INFLUENCERS);
		$this->set('influencers',$influencers);
		$this->set('viewInfluencer',str_replace("@", "", $viewInfluencer));
		$this->render('index');

	}

	private function _setUsernameFromSession() {
		$username = $this->Session->read('username');
		if ($username) {
			$username = str_replace("@", "", $username);
			$this->set('username',$username);

			$visibility = $this->Session->read('visibility');
			$optimization = $this->Session->read('optimization');
			$this->set('visibility',$visibility);
			$this->set('optimization',$optimization);
			$this->Session->delete('username');
			$this->Session->delete('visibility');
			$this->Session->delete('optimization');
		}
	}

	private function _setAuthenticated() {
		$connection = $this->TwitterList->getConnection();
		if ($connection) {
			$this->set('authenticated',true);
			$user = $this->Session->read('user');
			$this->set('user',$user);
		} else {
			$this->set('authenticated',false);
		}
	}

	public function logout() {
		$this->Session->destroy();
		$this->redirect(Router::url("/",true));
	}

	// CREDENTIALS
	public function authorize() {

		$username = $this->request->data['username'];
		$visibility = $this->request->data['visibility'];
		$optimization = $this->request->data['optimization'];
		$this->Session->write('username',$username);
		$this->Session->write('visibility',$visibility);
		$this->Session->write('optimization',$optimization);

		$connection = $this->TwitterList->getTmpConnection();
		$callbackUrl = Router::url('/api/callback',true);
		$request_token = $connection->getRequestToken($callbackUrl);

		$token = $request_token['oauth_token'];
		$this->Session->write('oauth_token',$token);
		$this->Session->write('oauth_token_secret',$request_token['oauth_token_secret']);

		/* If last connection failed don't display authorization link. */
		switch ($connection->http_code) {
			case 200:
				/* Build authorize URL and redirect user to Twitter. */
				$url = $connection->getAuthorizeURL($token);
				$this->redirect($url);
				break;
			default:
				/* Show notification if something went wrong. */
				echo 'Could not connect to Twitter. Refresh the page or try again later.';
		}
	}

	public function callback() {

		if (!isset($_REQUEST['oauth_token']) || isset($_REQUEST['oauth_token']) && $this->Session->read('oauth_token') !== $_REQUEST['oauth_token']) {
			header('Location: '.Router::url('/?not_authorized'));
		}

		$params['oauth_token'] = $this->Session->read('oauth_token');
		$params['oauth_token_secret'] = $this->Session->read('oauth_token_secret');
		$connection = $this->TwitterList->getOauthConnection($params);

		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

		/* Save the access tokens. Normally these would be saved in a database for future use. */
		$this->Session->write('access_token',$access_token);

		$this->Session->delete('oauth_token');
		$this->Session->delete('oauth_token_secret');

		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $connection->http_code) {
			$username = $this->Session->read('username');

			$user = $connection->get('account/verify_credentials');
			$this->loadModel('User');
			$previousUser = $this->User->findByUserId($user->id);
			if ($previousUser) {
				$this->User->id = $previousUser['User']['id'];
			} else {
				$this->User->create();
			}

			$data = array(
				'user_id' => $user->id,
				'username' => $user->screen_name,
				'oauth_token' => $access_token['oauth_token'],
				'oauth_token_secret' => $access_token['oauth_token_secret'],
			);

			$this->User->save($data);
			$userDb = $this->User->findById($this->User->id);
			$this->Session->write('user',$userDb);
			$this->redirect(Router::url("/",true));
		} else {
			/* Save HTTP status for error dialog on connnect page.*/
			$this->redirect(Router::url("/"));
		}

	}

	// CREATE list
	public function createlist() {

		$username = $this->request->data['username'];
		$userId = $this->request->data['user_id'];
		$visibility = $this->request->data['visibility'];
		$optimization = $this->request->data['optimization'];

		$this->set('userId',$userId);
		$this->set('username',$username);
		$this->set('visibility',$visibility);
		$this->set('optimization',$optimization);
		$this->autoLayout = false;
	}

	public function getprogress() {

		$data['message'] = $this->Session->read('progress');
		$data['end'] = $this->Session->read('end');

		$this->setAjaxResponse($data);

	}

	public function checklistusers() {
		$this->TwitterList->checkListUsers();
	}

	/**
	public function defaultinfluencers() {

		$influencerScreenNames = "billgates,cristiano,oprah,aaronpaul_8,tomhanks,taylorswift13,jack,kevin,KingJames,SteveMartinToGo,justdemi,LeoDiCaprio,neymarjr";

		$connection = $this->TwitterList->getConnection();
		$influencers = $connection->get('users/lookup', array('screen_name' => $influencerScreenNames));

		foreach ($influencers as $influencer) {
			$this->Influencer->create();
			$data = array();
			$data['user_id'] = $influencer->id;
			$data['screen_name'] = $influencer->screen_name;
			$data['name'] = $influencer->name;
			$data['image'] = $influencer->profile_image_url;
			$data['friends_count'] = $influencer->friends_count;
			$data['followers_count'] = $influencer->followers_count;
			$data['json'] = json_encode((array)$influencer);
			$data['default'] = true;
			$this->Influencer->save($data);
		}

	}
	**/


}
