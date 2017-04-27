{{-- css --}}
@include('common/css', ['file' => 'admin'])
{{-- js --}}
@include('common/js', ['file' => 'admin'])


{{-- 画像 --}}
<div>
    <img class="admin_title" src="{{IMG_URL}}title.png">
</div>

{{-- ユーザエディット  --}}
<div class="admin_userEdit">
    <form action="{{APP_URL}}admin/editUser" method="get">
	{{-- ユーザID入力 --}}
	<div>
	    ユーザID：<input type="text" name="userId">
	</div>

	{{-- 名前変更 --}}
	<div>
	    <input type="text" name="newName">
	    <input type="submit" name="rename" value="名前変更" onclick="return renameCheck();">
	</div>

	{{-- ユーザ削除 --}}
	<div>
	    <input type="submit" name="delete" value="ユーザ削除" onclick="return deleteCheck();">
	</div>
    </form>
</div>

{{-- 戻る  --}}
<div class="admin_returnLink">
    <a href="{{APP_URL}}top">
	⇒TOPに戻る
    </a>
</div>


{{-- viewParts  --}}
@include('element/memberList')




{{-- popupボタン --}}
<div class="modal_container">
    <span class="md_btn my">Show modal</span>
</div>

{{-- popupウインドウ --}}
@include('popup/wrap', ['template' => 'my'])



{{-- 前のページ --}}
<a href="javascript:history.back();">前のページに戻る</a>
