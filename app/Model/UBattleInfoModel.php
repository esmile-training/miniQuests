<?php
/*
 * uBattleInfo用のモデル
 * 製作者：松井 勇樹
 * 最終更新日:2017/05/02
 */

namespace App\Model;

class UBattleInfoModel extends \App\Model\BaseGameModel
{
	/*
	 * DBからバトルデータを取得する
	 */
	public function getBattleData($userId = false)
	{
$sql = <<< EOD
	SELECT *
	FROM uBattleInfo
	WHERE userId = {$userId}
	AND delFlag = 0
EOD;
		return $this->select($sql, 'first');
	}

	/*
	 * DBにデータをインサートする
	 */
	public function InsertBattleData($userId,$charaId,$enemyId)
	{
		$time = date('Y-m-d H:i:s', time());
$sql = <<< EOD
		INSERT INTO uBattleInfo
		VALUES (
			NULL,
			DEFAULT,
			'{$userId}',
			'{$charaId}',
			'{$enemyId}',
			'{$time}',
			'{$time}'
		);
EOD;
		// インサートしたレコードのidを取得する
		$id = $this->insert($sql);
		// インサートしたレコードのIDを返す
		return $id;
	}

	/*
	 * 'delFlag'を立てる
	 */
	public function UpdateInfoFlag($battleData)
	{
$sql = <<< EOD
	UPDATE  uBattleInfo
	SET		delFlag = 1
	WHERE   id = {$battleData['id']};
EOD;
		$this->update($sql);
	}

}