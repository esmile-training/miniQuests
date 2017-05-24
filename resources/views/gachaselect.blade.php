@include('common/css', ['file' => 'gachaselect'])


<div class = "gacha_all">
	<div>
		<img class="gacha_signboard" src="{{IMG_URL}}gacha/kanban.png">
	</div>
	<div class = "junban0">
		<img class="gacha_frame" src="{{IMG_URL}}gacha/wakunormal.png">
		<a href="{{APP_URL}}gacha/eventsSelect">
			<input type="submit" class = "eventebutton">
		</a>
	</div>
	{{-- popupボタン --}}
	<div class = "junban1">
		<div class="modal_container">
			<div class = "gacha_button1">
				<input type="image" class="modal_btn gacha1" src="{{IMG_URL}}gacha/gachabutton1.png" name = 'gachavalue' value = "1" width= 100% height= 100%>
				<img class= "gacha_normalgachamoney" src="{{IMG_URL}}gacha/10000.png">
			</div>
			<div class = "gacha_button2">
				<input type="image" class="modal_btn gacha2" src="{{IMG_URL}}gacha/gachabutton2.png" name = 'gachavalue' value = "2"width= 100% height= 100%>
				<img class= "gacha_raregachamoney" src="{{IMG_URL}}gacha/30000.png">
			</div>
			<div class = "gacha_button3">
				<input type="image" class="modal_btn gacha3" src="{{IMG_URL}}gacha/gachabutton3.png" name = 'gachavalue' value = "3"width= 100% height= 100%>
				<img class= "gacha_superraregachamoney" src="{{IMG_URL}}gacha/50000.png">
			</div>
		</div>
	</div>
</div>
{{-- popupウインドウ --}}
@include('popup/wrap', [
	'class'		=> 'gacha1', 
	'template'	=> 'gacha',
	'data'		=> ['gachaId' => 1]
])
@include('popup/wrap', ['class' => 'gacha2', 'template' => 'gacha', 'data' => ['gachaId' => 2]])
@include('popup/wrap', ['class' => 'gacha3', 'template' => 'gacha', 'data' => ['gachaId' => 3]])