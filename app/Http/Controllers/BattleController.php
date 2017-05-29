<?php
namespace App\Http\Controllers;

// Lib
use App\Libs\BattleLib;

class battleController extends BaseGameController
{
	/*
	 *  戦うキャラの選択をする
	 */
	public function selectBattleChara()
	{
		// ユーザーIDを取得する
		$userId = $this->user['id'];

		if($this->user['battleTicket'] <= 0)
		{
			return viewWrap('notBattleTicket');
		}

		// 継続中の戦闘があったらbattleLogへリダイレクトする
		$battleInfo = $this->Model->exec('BattleInfo', 'getBattleData', $userId);
		if(isset($battleInfo))
		{
			$this->Lib->redirect('battle', 'battleLog');
		}

		// DBのキャラクターデータを取得する
		$alluChara = $this->Model->exec('Chara', 'getUserChara', $userId);

		// 金枠か銀枠かを判定する
		foreach ($alluChara as $key => $chara)
		{
			if($chara['rare'] >= 4)
			{
				$alluChara[$key]['iconFrame'] = 2;
			}
			else
			{
				$alluChara[$key]['iconFrame'] = 1;
			}
		}

		// DBからキャラクターを取得できたかを確認する
		if(!isset($alluChara))
		{
			// マイページへリダイレクトする
			$this->Lib->redirect('mypage', 'index');
		}
		// viewDataへ取得したキャラクターを送る
		$this->viewData['charaList'] = $alluChara;

		// ビューへデータを渡す
		return viewWrap('battleCharaSelect', $this->viewData);
	}

	/*
	 *  闘技場の選択をする
	 */
	public function selectArena()
	{
		// ユーザーキャラクターのIDを取得する
		$selectedCharaId = $_GET['uCharaId'];

		// 難易度を取得する
		$difficulty = \Config::get('battle.difficultyStr');

		// 対戦の難易度とキャラIDをビューへ渡す
		$this->viewData['difficultyList'] = $difficulty;
		$this->viewData['selectedCharaId'] = $selectedCharaId;

		// ビューへデータを渡す
		return viewWrap('arenaSelect', $this->viewData);
	}

	/*
	 *  戦闘準備(データの取得と構築)をする
	 */
	public function preparationBattle()
	{
		// 大会の情報を取得する(ユーザーキャラIDと難易度)
		$arenaData = $_GET;

		// 大会情報の取得に成功したか確認する
		if(!isset($arenaData))
		{
			// マイページへリダイレクトする
			$this->Lib->redirect('mypage', 'index');
		}

		// IDと一致するキャラクターをDBから取得する
		$selectedChara = $this->Model->exec('Chara', 'getById', $arenaData["selectedCharaId"]);

		// 正常に取得したかを確認する
		if(!isset($selectedChara))
		{
			// マイページへリダイレクトする
			$this->Lib->redirect('mypage', 'index');
		}

		// エネミーの外見を取得する
		$enemyApp				= $this->Lib->exec('EnemyCreate','getEnemyAppearance');
		// エネミーの名前を生成する
		$enemyName				= $this->Lib->exec('EnemyCreate','creatEnemyName',[$enemyApp['sex']]);
		// エネミー作成のための素材
		$enemyStatusMaterial	= array($selectedChara['hp'],$arenaData["arenaDifficulty"]);
		// エネミーの能力値を取得する
		$enemyStatus			= $this->Lib->exec('EnemyCreate','createEnemyStatus',$enemyStatusMaterial);

		// 対戦データの作成をする
		$matchData = BattleLib::createMatchData($arenaData,$selectedChara,$enemyApp,$enemyName,$enemyStatus);

		// データのインサートを行う
		$this->insertMatchData($matchData);

		// チケットの消費処理を行う
		$this->lossTicket();

		// 戦闘処理へリダイレクトする
		$this->Lib->redirect('battle', 'battleLog');
	}

	/*
	 * 対戦データをDBへ入れる
	 */
	public function insertMatchData($argMatchData)
	{
		// ユーザーIDを取得する
		$userId = $this->user['id'];

		// infoデータを取得する
		$battleInfo = $this->Model->exec('BattleInfo', 'getBattleData', $userId);

		// 対戦データの取得をする
		$matchData = $argMatchData;

		// デリートフラグが立っていない、同じIDのデータが登録されていなければインサートを行う
		if(!isset($battleInfo))
		{
			// プレイヤーキャラのデータをインサートする
			$uBattleCharaId = $this->Model->exec('BattleChara','insertBattleCharaData',array($matchData['uCharaId'],$matchData['uHp']
					,$matchData['uGooAtk'],$matchData['uChoAtk'],$matchData['uPaaAtk']));

			// エネミーのデータをインサートする
			$uBattleEnemyId = $this->Model->exec('BattleEnemy','insertEnemyData',array($matchData['eImgId'],$matchData['difficulty']
					,$matchData['eFirstName'],$matchData['eLastName'],$matchData['eHp'],$matchData['eGooAtk'],$matchData['eChoAtk'],$matchData['ePaaAtk']));

			// バトルインフォにデータをインサートする
			$this->Model->exec('BattleInfo','insertBattleData',array($userId,$uBattleCharaId,$uBattleEnemyId));

			return true;
		}
		else
		{
			// データが入っていたらfalseを返す
			return false;
		}
	}

	/*
	 * チケットを消費させるファンクション
	 */
	public function lossTicket()
	{
		$this->Lib->exec('Ticket','lossTicket',array($this->user,$this->nowTime));
	}

	/*
	 * バトルログを表示するファンクション
	 */
	public function battleLog()
	{
		// setData関数を呼び出し、データをセット
		$this->getBattleData();

		// バトルデータがなかった場合、エラー画面を表示しホームへ戻す
		if(is_null($this->BattleData))
		{
			return view('error');
		}

		// どちらかのHPが0以下になったらバトル終了フラグを立てる
		if ($this->EnemyData['battleHp'] <= 0 || $this->CharaData['battleHp'] <= 0)
		{
			// BattleData の 'delFlag' を立てる
			$this->BattleData['delFlag'] = 1;
		}

		// 降参費用額計算
		$surrenderCost = $this->Lib->exec('Battle', 'surrenderCostCalc', array($this->CharaData, $this->Commission, $this->DifficultyData, $this->EnemyData));

		// 全てのデータを viewData に渡す
		$this->viewData['BattleData']	= $this->BattleData;
		$this->viewData['CharaData']	= $this->CharaData;
		$this->viewData['EnemyData']	= $this->EnemyData;
		$this->viewData['Type']			= $this->TypeData;
		$this->viewData['Result']		= $this->ResultData;
		$this->viewData['SurrenderCost']= $surrenderCost;

		return view('battle', ['viewData' => $this->viewData]);
	}

	// リザルト画面を表示するファンクション
	public function battleResult()
	{

		//リダイレクト元からデータをゲットする
		$prize = filter_input(INPUT_GET, "prize");

		if($prize > 0)
		{
			$charaData['hp']			= filter_input(INPUT_GET, "deaultHp");
			$charaData['gooAtk']		= filter_input(INPUT_GET, "deaultGooAtk");
			$charaData['choAtk']		= filter_input(INPUT_GET, "deaultChoAtk");
			$charaData['paaAtk']		= filter_input(INPUT_GET, "deaultPaaAtk");
			$charaUpData['statusUpCnt']	= filter_input(INPUT_GET, "statusUpCnt");
			$charaUpData['gooUpCnt']	= filter_input(INPUT_GET, "gooAtkUpCnt");
			$charaUpData['choUpCnt']	= filter_input(INPUT_GET, "choAtkUpCnt");
			$charaUpData['paaUpCnt']	= filter_input(INPUT_GET, "paaAtkUpCnt");

			$this->viewData['CharaDefaultData']	= $charaData;
			$this->viewData['CharaUpData']	= $charaUpData;
		}

		// getRankingData ファンクションを呼び出し、ランキングデータを取得
		$this->getRankingData();

		// リザルト画面に必要なデータを viewData に渡す
		$this->viewData['Prize']		= $prize;
		$this->viewData['RankingData']	= $this->RankingData;

		return view('battleResult', ['viewData' => $this->viewData]);
	}

	// データベースからデータをそれぞれの変数に格納するファンクション
	public function getBattleData()
	{

		// config/battle で指定した三すくみの名前を読み込み
		// 1 = 'グー' 2 = 'チョキ' 3 = 'パー' で指定中
		$this->TypeData	= \Config::get('battle.typeStr');

		// config/battle で指定した勝敗結果の名前を読み込み
		// 1(勝ち) 2(負け) 3(あいこ) で指定中
		$this->ResultData = \Config::get('battle.resultStr');

		// config/battle で指定した賞金の歩合を読み込み
		// 'Commission' で指定中
		$this->Commission = \Config::get('battle.prizeStr');

		// config/battle で指定した難易度を読み込み
		// 1(初級)、2(中級)、3(上級) で指定、賞金の補正値(％)、敵の補正値(割合)で設定中
		$this->DifficultyData = \Config::get('battle.difficultyStr');

		// ユーザーIDを元にuBattleInfo(DB)からバトルデータを読み込み
		// BattleData にバトルデータを格納
		$this->BattleData = $this->Model->exec('BattleInfo', 'getBattleData', $this->user['id']);

		if(isset($this->BattleData))
		{
			// バトルデータを元にuBattleChar(DB)からキャラデータを読み込み
			// ChaaraData に自キャラデータを格納
			$this->CharaData = $this->Model->exec('BattleChara', 'getBattleCharaData', $this->BattleData['uBattleCharaId']);

			// バトルデータを元にuBattleChar(DB)から敵データを読み込み
			// EnemyData に敵キャラデータを格納
			$this->EnemyData = $this->Model->exec('BattleEnemy', 'getBattleEnemyData', $this->BattleData['uBattleEnemyId']);
		}

	}

	// データベースからランキングデータを RankingData に格納するファンクション
	public function getRankingData()
	{
		// ユーザーIDを元に週間のランキングデータを読み込み
		$this->RankingData = $this->Model->exec('Ranking', 'getRankingData', $this->user['id']);

		// ランキングデータがなければ、新しくランキングデータを作成してから読み込み
		if(is_null($this->RankingData))
		{
			$this->Model->exec('Ranking','insertRankingData',$this->user['id']);
			$this->RankingData = $this->Model->exec('Ranking', 'getRankingData', $this->user['id']);
		}
	}

	// バトルデータを更新するファンクション
	public function updateBattleData()
	{
		// setData関数を呼び出し、データをセット
		$this->getBattleData();

		// どちらかのHPが既に0の状態なら、ダメージ処理を行わずリザルト画面へ飛ばす
		if($this->CharaData['battleHp'] <= 0 || $this->EnemyData['battleHp'] <= 0)
		{
			return $this->Lib->redirect('Battle', 'makeResultData');
		}

		// 押されたボタンのデータを Chara の 'hand' に格納
		// 1(グー) / 2(チョキ) / 3(パー) のどれかが入る
		$this->CharaData['hand'] = $_GET["hand"];

		// 敵キャラデータを元に、Enemy の 'hand' を格納
		// 1(グー) / 2(チョキ) / 3(パー) のどれかが入る
		$this->EnemyData['hand'] = BattleLib::setEnmHand($this->EnemyData);

		// 勝敗処理
		// 'win' / 'lose' / 'draw' のどれかが入る
		$this->CharaData['result'] = BattleLib::AtackResult($this->CharaData['hand'], $this->EnemyData['hand']);

		// ダメージ処理
		// CharaData の 'result' によって処理を行う
		switch ($this->CharaData['result'])
		{
			// 1(勝ち) の場合
			case 1:
				// 自キャラデータを元にダメージ量を計算
				$this->CharaData = BattleLib::damageCalc($this->CharaData);
				// 変動したダメージ量を元にダメージ処理後の敵キャラHPを計算
				$this->EnemyData['battleHp'] = BattleLib::hpCalc($this->CharaData, $this->EnemyData);
				break;

			// 2(負け) の場合
			case 2:
				// 敵キャラデータを元にダメージ量を計算
				$this->EnemyData = BattleLib::damageCalc($this->EnemyData);
				// 変動したダメージ量を元にダメージ処理後の自キャラHPを計算
				$this->CharaData['battleHp'] = BattleLib::hpCalc($this->EnemyData, $this->CharaData);
				break;

			// 3(あいこ) の場合
			case 3:
				// ダメージ処理を行わず抜ける
				break;

			default;
				exit;
		}

		// バトルキャラデータの更新処理
		// 自キャラデータの更新処理
		$this->Model->exec('BattleChara', 'UpdateBattleCharaData', array($this->CharaData));
		// 敵キャラデータの更新処理
		$this->Model->exec('BattleEnemy', 'UpdateBattleEnemyData', array($this->EnemyData));

		return $this->Lib->redirect('Battle', 'battleLog');
	}

	/*
	 * リザルト画面に必要なデータの作成、更新をするファンクション
	 */
	public function makeResultData()
	{
		// getData ファンクションを呼び出し、バトルデータをセット
		$this->getBattleData();

		// getRankingData ファンクションを呼び出し、ランキングデータをセット
		$this->getRankingData();

		// バトルデータがなかった場合、エラー画面を表示しホームへ戻す
		// リザルト画面から戻るボタンで戻り、再度ページをリザルト画面を開かれたときの対策
		if(is_null($this->BattleData))
		{
			return view('error');
		}

		// 敵のHPが0以下の場合(試合全体としてプレイヤーが勝った場合)
		if($this->EnemyData['battleHp'] <= 0)
		{
			// 賞金額計算
			$prize =  BattleLib::prizeCalc($this->EnemyData, $this->Commission, $this->DifficultyData);

			// ユーザーの所持金 'money' に賞金額を加算しデータベースに格納
			$this->Lib->exec('Money', 'addition', array($this->user, $prize));
			// ユーザーのウィークリーポイント 'weeklyAward' に賞金額を加算しデータベースに格納
			$this->Lib->exec('Ranking', 'weeklyAdd', array($this->RankingData, $prize));

			/* 自キャラ、敵キャラのステータスを元にステータスの強化処理(訓練と同じシステムを使用) */
			$gooResult = $this->Lib->exec('Training', 'atkUpProbability', array($this->EnemyData['gooAtk'],$this->CharaData['gooAtk'],$this->CharaData['gooUpCnt']));
			$choResult = $this->Lib->exec('Training', 'atkUpProbability', array($this->EnemyData['choAtk'],$this->CharaData['choAtk'],$this->CharaData['choUpCnt']));
			$paaResult = $this->Lib->exec('Training','atkUpProbability', array($this->EnemyData['paaAtk'],$this->CharaData['paaAtk'],$this->CharaData['paaUpCnt']));
			$time=1;
			$charaUpData = $this->Lib->exec('Training','atkUpJudge',array($gooResult,$choResult,$paaResult,$time));

			/* キャラの強化後の値をデータベースに格納 */
			$upDateStatus = [
				'hp'		 => $this->CharaData['hp']			 + $charaUpData['statusUpCnt'],
				'gooAtk'	 => $this->CharaData['gooAtk']		 + $charaUpData['gooAtk'],
				'choAtk'	 => $this->CharaData['choAtk']		 + $charaUpData['choAtk'],
				'paaAtk'	 => $this->CharaData['paaAtk']		 + $charaUpData['paaAtk'],
				'gooUpCnt'	 => $this->CharaData['gooUpCnt']	 + $charaUpData['gooUpCnt'],
				'choUpCnt'	 => $this->CharaData['choUpCnt']	 + $charaUpData['choUpCnt'],
				'paaUpCnt'	 => $this->CharaData['paaUpCnt']	 + $charaUpData['paaUpCnt']
			];
			$this->Model->exec('Training', 'updateStatus', array($upDateStatus, $this->CharaData['uCharaId']));

			//リダイレクト引数受け渡し
			$param = [
				'prize'			=> $prize,
				'deaultHp'		=> $this->CharaData['hp'],
				'deaultGooAtk'	=> $this->CharaData['gooAtk'],
				'deaultChoAtk'	=> $this->CharaData['choAtk'],
				'deaultPaaAtk'	=> $this->CharaData['paaAtk'],
				'statusUpCnt'	=> $charaUpData['statusUpCnt'],
				'gooAtkUpCnt'	=> $charaUpData['gooUpCnt'],
				'choAtkUpCnt'	=> $charaUpData['choUpCnt'],
				'paaAtkUpCnt'	=> $charaUpData['paaUpCnt'],
			];
		}
		// 自キャラのHPが0以下の場合(降参せずにプレイヤーが負けた場合)
		else if($this->CharaData['battleHp'] <= 0)
		{
			$param = [
				'prize' => 0,
			];

			$this->Model->exec('Chara', 'charaDelFlag', $this->CharaData['uCharaId']);
		}
		// どちらのHPも0以上の場合(降参として処理が呼ばれた場合)
		else
		{
			// 降参費用額計算
			$prize = $this->Lib->exec('Battle', 'surrenderCostCalc', array($this->CharaData, $this->Commission, $this->DifficultyData, $this->EnemyData));

			// ユーザーの所持金 'money' から降参費用を減算しデータベースに格納
			$this->Lib->exec('Money', 'Subtraction', array($this->user,	$prize));

			$prize *= -1;

			$param = [
				'prize' => $prize,
			];
		}

		// delFlagを立てる更新
		$this->standDelFlag();

		return $this->Lib->redirect('Battle','battleResult', $param);
	}

	/*
	 * delFlagを立てるファンクション
	 */
	public function standDelFlag()
	{
		// ユーザーIDを元にuBattleInfo(DB)からバトルデータを読み込み
		// BattleData にバトルデータを格納
		$this->BattleData = $this->Model->exec('BattleInfo', 'getBattleData', $this->user['id']);

		// uBattleInfo(DB) の delFlag を立てる更新
		$this->Model->exec('BattleInfo', 'UpdateInfoFlag', $this->BattleData['id']);
	}
}
