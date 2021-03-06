@include('common/css', ['file' => 'training'])
<div class="training_font_serif">
	{{'訓練時間を選択してください'}}
</div>
<div>
	<script type="text/javascript">
		
		var uCoachHp		= 0;
		var trainingFee		= 0;
		var userMoney		= 0;

		$(function()
		{
			uCoachHp	= <?php echo $viewData['coachList'][$uCoach]['hp']; ?>;
			trainingFee = uCoachHp * 10;
			
			// 所持金
			userMoney = <?php echo $viewData['user']['money']; ?>;
			
			trainingFee{{$cnt}}	= document.getElementById("trainingFee{{$cnt}}");
			trainingFee{{$cnt}}.innerHTML = Math.floor(trainingFee);
			
			var inputSubmitButton{{$cnt}} = document.getElementById("inputSubmitButton" + {{$cnt}});
			
			if(userMoney > trainingFee)
			{
				inputSubmitButton{{$cnt}}.innerHTML = '<input class="training_submit" type="image" src="{{IMG_URL}}popup/ok_Button.png">';
			}else
			{
				inputSubmitButton{{$cnt}}.innerHTML = '<input class="training_submit" type="hidden" src="{{IMG_URL}}popup/ok_Button.png"><div class="fee_shortage_text">{{"所持金が足りません"}}</div>';
			}
			
		});

		function feeCalcuration{{$cnt}}(select)
		{
			// input class 取得
			var inputClass = $('.training_submit');
			var time	= select.value;
			
			uCoachHp	= <?php echo $viewData['coachList'][$uCoach]['hp']; ?>;
			trainingFee = uCoachHp * 10 * time * (100 - (time - 1) * 3) / 100;
			
			userMoney = <?php echo $viewData['user']['money']; ?>;
			
			trainingFee{{$cnt}}	= document.getElementById("trainingFee{{$cnt}}");
			trainingFee{{$cnt}}.innerHTML = Math.floor(trainingFee);
			
			if(userMoney > trainingFee)
			{
				inputSubmitButton{{$cnt}}.innerHTML = '<input class="training_submit" type="image" src="{{IMG_URL}}popup/ok_Button.png">';
			}else
			{
				inputSubmitButton{{$cnt}}.innerHTML = '<input class="training_submit" type="hidden" src="{{IMG_URL}}popup/ok_Button.png"><div class="fee_shortage_text">{{"所持金が足りません"}}</div>';
			}
			
		}
	</script>
</div>

<body>

	<div class="coachSelect_text training_fee_text">
		<p id="trainingFee{{$cnt}}"></p>
		<span  class ="training_fee_img"><img src="{{IMG_URL}}/user/gold.png"></span>
	</div>
	
	<form name='trainingInfo' action='{{APP_URL}}training/infoSet' method='get'>
		<select name="trainingTime" onchange="feeCalcuration{{$cnt}}(this)">
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
			<option>6</option>
			<option>7</option>
			<option>8</option>
			<option>9</option>
			<option>10</option>
		</select>

		<p id="inputSubmitButton{{$cnt}}"></p>
		<input type = "hidden" name = "uCoachId" value = "{{$viewData['coachList'][$uCoach]['id']}}">
		<input type = "hidden" name = "uCharaId" value = "{{$viewData['uCharaId']}}">
		<input type = "hidden" name = "uCoachHp" value = "{{$viewData['coachList'][$uCoach]['hp']}}">
	</form>
</body>
