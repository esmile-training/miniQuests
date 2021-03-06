<?php
namespace App\Http\Controllers;

class BelongingsController extends BaseGameController
{
	/*
	 * 商品を表示するファンクション
	 */
	public function index()
	{
		// setData関数を呼び出し、データをセット
		$this->getItemData();

		// 全てのデータを viewData に渡す
		$this->viewData['userData']			= $this->user;
		$this->viewData['ticketData']		= $this->Ticket;
		$this->viewData['belongingsData']	= $this->belongingsData;
		$this->viewData['itemData']			= $this->itemData;

		return viewWrap('belongings', $this->viewData);
	}
	
	/*
	 * 所持アイテムや商品データを読み込むファンクション
	 */
	public function getItemData()
	{
		// アイテムデータ取得
		$this->itemData			= \Config::get('item.itemStr');
		
		// 所持アイテムデータ取得
		$this->belongingsData	= $this->Model->exec('Item', 'getItemData', $this->user['id']);
		
		// 所持アイテムデータがなければ作成
		if(!isset($this->belongingsData))
		{
			// uItemにデータを追加
			$this->Model->exec('Item','insertItemData',$this->user['id']);
			
			// 所持アイテムデータ取得
			$this->belongingsData	= $this->Model->exec('Item', 'getItemData', $this->user['id']);
		}
	}
}
