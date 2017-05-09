<?php
namespace App\Model;

class UserModel extends BaseGameModel
{
    /*
    *	ユーザ1件取得
    */
    public function getById( $userId = false )
    {
	if( !$userId && isset($this->user['id']) ){
	    $userId = $this->user['id'];
	}

$sql =  <<< EOD
	SELECT *
	FROM user
	WHERE id = {$userId}
EOD;
	return $this->select($sql, 'first');
    }
        
    /*
    *	ユーザ作成
    */
    public function createUser($teamName = null)
    {
$sql =  <<< EOD
	INSERT INTO user ( `name`, `createDate` )
	VALUES("{$teamName}", NOW());
EOD;
	$result = $this->insert($sql);
	return $result;
    }

    /*
    *	ユーザ削除
    */
    public function deleteUser( $userId )
    {
$sql =  <<< EOD
    DELETE FROM user 
    WHERE id = {$userId};
EOD;
	$this->delete($sql);
    }

    /*
    *	ユーザ名変更
    */
    public function setUserName( $userId, $newName )
    {
$sql =  <<< EOD
    UPDATE  user
    SET	    name = "{$newName}"
    WHERE   id = {$userId};
EOD;
	$this->update($sql);
    }
	    //キャラの作成
    public function createChara($uCharaId, $uCharaName, $uCharaLastName,$ratio, $narrow, $hp, $atk1, $atk2, $atk3) {
        $sql = <<< EOD
    INSERT INTO  uChara 
    VALUES (
    NULL,
    {$_COOKIE['userId']},
        '{$uCharaId}',
        '{$uCharaName}・{$uCharaLastName}',
    '1',
        '10',
    '{$ratio}',
        '{$narrow}',
        '{$hp}',
        '{$atk1}',
        '{$atk2}',
        '{$atk3}',
        '0',
        NULL,
        NULL
    );
EOD;
        $this->insert($sql);
    }
	
	//キャラの削除
	public function deleteChara( $uCharaId )
	{
$sql = <<< EOD
	DELETE FROM uChara
	WHERE id = {$uCharaId};
EOD;
	$this->delete($sql);
	}

	//コーチの追加
	public function insertCoach ( $uCharaId, $uCharaName,$ratio, $hp, $atk1, $atk2, $atk3)
	{
$sql = <<< EOD
    INSERT INTO uCoach 
    VALUES (
    NULL,
	'{$_COOKIE['userId']}',
        '{$uCharaId}',
        '{$uCharaName}',
    '{$ratio}',
        '10',
        '{$hp}',
        '{$atk1}',
        '{$atk2}',
        '{$atk3}',
        '0'
    );
EOD;
	$this->insert($sql);
	}
	
	//コーチの削除
	public function deleteCoarch($uCoachId){
$sql = <<< EOD
	DELETE FROM uCoach
	VALUES (
	WHERE id = {$uCoachId};
EOD;
	$this->delete($sql);
	}
}