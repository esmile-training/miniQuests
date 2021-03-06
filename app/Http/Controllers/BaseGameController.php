<?php
namespace App\Http\Controllers;

class BaseGameController extends Controller
{
	public $viewData;

	public function __construct()
	{
		//BaseGameLibをセット
		$this->Lib = new \App\Libs\BaseGameLib();
		//BaseGameModelをセット
		$this->Model = new \App\Model\BaseGameModel();

		//ユーザ認証しないコントローラ
		if( in_array(CONTROLLER_NAME, \Config::get('common.ignoreAuthController')))
		{
			return;
		}
		
		//ユーザー認証
		$userId = $_COOKIE['userId'];
		$commonData['user'] = $this->Lib->exec('User', 'userAuth', false, $userId);
		
		//現在時刻をセット
		$commonData['nowTime'] = (is_null($commonData['user']['debugDate']))?date('Y-m-d H:i:s', time()) : $commonData['user']['debugDate'];
		
		// チケット情報を配列化
		$ticketData = [$commonData['user']['id'],  $commonData['nowTime'], $commonData['user']['battleTicket'], $commonData['user']['ticketLossTime']];
	
		// チケットの回復処理
		$commonData['recoveryTime'] = $this->Lib->exec('Ticket', 'confirmation', $ticketData);
		
		$commonData['Ticket'] = $this->Model->exec('User', 'getTicket', $userId);
		
		// 訓練終了したキャラの確認
		$commonData['endTraining'] = $this->Model->exec('Training', 'endAlert', $userId);
		
		// フィーバータイム判定処理
		$commonData['feverTimeFlug'] = $this->Lib->exec('Battle', 'checkFeverTime');
		
		$userData = $this->Lib->exec('User', 'getUser',$userId);
	
		//汎用変数をセット
		foreach( $commonData as $key => $val )
		{
			$this->viewData[$key] = $this->$key = $val;   
		}
		if($userData['money'] < 0)
		{
			$userData['money'] = 0; 
			$this->Model->exec('user', 'updateMoney', array($userData));
			
			return $this->Lib->redirect('commonError');
		}
	}
}