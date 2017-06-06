{{--CSS--}}
@include('common/css', ['file' => 'coachSelect'])

<div class="coachSelect_signboard">
	 <img src="{{IMG_URL}}/training/signboard.png">
</div>
<?php $cnt = 0; ?>
{{--uCoach(DB)から持ってきたデータの表示--}}
<div class="coachSelect_all">
	@foreach($viewData['coachList'] as $val)
		<div class="training_coach_window">
			@if($val['state'] == 1)
				<div class ="modal_btn trainingPopup{{$cnt}} training_a_none">
					<div class="scale_img"><img src="{{IMG_URL}}/training/coachButton.png"></div>
			@else
				<div class ="modal_btn trainingPopup{{$cnt}}">
					<img src="{{IMG_URL}}/training/coachButton.png">
			@endif	
				<div class="coachSelect_text training_coach_name">
					<font>{{$val['name']}}</font>
				</div>
				<div class="training_coach_icon">
					<img src="{{IMG_URL}}/chara/icon/icon_{{$val['imgId']}}.png">
				</div>
				<div class="training_goo_icon">
					<img src="{{IMG_URL}}/chara/status/hand1.png">
				</div>
				<div class="coachSelect_text training_coach_goo_atk">
					{{$val['gooAtk']}}
				</div>
				<div class="training_cho_icon">
					<img src="{{IMG_URL}}/chara/status/hand2.png">
				</div>
				<div class="coachSelect_text training_coach_cho_atk">
					{{$val['choAtk']}}
				</div>
				<div class="training_paa_icon">
					<img src="{{IMG_URL}}/chara/status/hand3.png">
				</div>
				<div class="coachSelect_text training_coach_paa_atk">
					{{$val['paaAtk']}}
				</div>

			</div>
			@include('popup/wrap', [
				'class'		=> "trainingPopup{$cnt}", 
				'template'	=> 'coachSelect',
				'data'		=> ['uCoach' => $cnt]
			])

			<div class ="modal_btn atkUpProbabilityPopup{{$cnt}}">
				<div class="atkUpProbabilityPopup">
					<img src="{{IMG_URL}}/training/probabilityButton.png">
				</div>
			</div>
			@include('popup/wrap', [
				'class'		=> "atkUpProbabilityPopup{$cnt}", 
				'template'	=> 'atkUpProbability',
				'data'		=> ['cnt' => $cnt]
			])
		</div>
		<?php $cnt++; ?>
	@endforeach
</div>