<?php
return [
	'eRate' => [
		1 => [		 	//ノーマルガチャ
			
			'money' =>  10000,
			
			'persent'=>[
				1 => 9000,		//レア度 N 割合(90)
				2 => 900,		//レア度 R 割合(9)
				3 => 90,		//レア度 SR 割合(0.9)
				4 => 9,			//レア度 SSR 割合(0.09)
				5 => 1			//レア度 LR 割合(0.01)
			],
			'Status' => null
			
		],
		2 => [			//レアガチャ
			'money' => 30000,
			'persent'=>[
				1 => 0,
				2 => 9000,
				3 => 900,
				4 => 90,
				5 => 10
			],
			'Status' => null
		],
		3 => [			//スーパーガチャ
			'money' => 50000,
			'persent'=>[
				1 => 0,
				2 => 6000,
				3 => 3000,
				4 => 900,
				5 => 100
			],
			'Status' => null
		],
		4 => [			//日曜限定レア排出率アップ
			'money' => 30000,
			'persent' => [
				1 => 1000,
				2 => 6000,
				3 => 2700,
				4 => 270,
				5 => 30
			],
			'Status' => null
		],
		5 => [			//月曜限定男キャラのみ
			'money' => 30000,
			'persent' => [
				1 => 1000,
				2 => 8000,
				3 => 900,
				4 => 90,
				5 => 10
			],
			'Status' => null
		],
		6 => [			//火曜限定グー強化キャラのみ
			'attr' => 1,
			'money' => 30000,
			'persent' => [
				1 => 1000,
				2 => 8000,
				3 => 900,
				4 => 90,
				5 => 10
			],
				'Status' => [
					1 => [
						'sumValueMax' => 50, //基準値
						'valueMax' => 150, //最大割合
						'valueMin' => 50, //最小割合
					],
					2 => [
						'sumValueMax' => 100, //基準値
						'valueMax' => 150, //最大割合
						'valueMin' => 50, //最小割合
					],
					3 => [
						'sumValueMax' => 200, //基準値
						'valueMax' => 150, //最大割合
						'valueMin' => 50, //最小割合
					],
					4 => [
						'sumValueMax' => 300, //基準値
						'valueMax' => 150, //最大割合
						'valueMin' => 50, //最小割合
					],
					5 => [
						'sumValueMax' => 500, //基準値
						'valueMax' => 150, //最大割合
						'valueMin' => 50, //最小割合
					]
			]
		],
		7 => [			//水曜限定女キャラのみ
			'money' => 30000,
			'persent'=>[
				1 => 1000,
				2 => 8000,
				3 => 900,
				4 => 90,
				5 => 10
			],
			'Status' => null
		],
		8 => [			//木曜限定チョキ強化キャラのみ
			'attr' => 2,
			'money' => 30000,
			'persent' => [
				1 => 1000,
				2 => 8000,
				3 => 900,
				4 => 90,
				5 => 10
			],
			'Status' => [
				1 => [
					'sumValueMax' => 50, //基準値
					'valueMax' => 110, //最大割合
					'valueMin' => 90, //最小割合
				],
				2 => [
					'sumValueMax' => 100, //基準値
					'valueMax' => 110, //最大割合
					'valueMin' => 90, //最小割合
				],
				3 => [
					'sumValueMax' => 200, //基準値
					'valueMax' => 110, //最大割合
					'valueMin' => 90, //最小割合
				],
				4 => [
					'sumValueMax' => 300, //基準値
					'valueMax' => 110, //最大割合
					'valueMin' => 90, //最小割合
				],
				5 => [
					'sumValueMax' => 500, //基準値
					'valueMax' => 150, //最大割合
					'valueMin' => 50, //最小割合
				]
			]
		],
		9 => [			//金曜限定コスト半額
			'money' => 15000,
			'persent' => [
				1 => 1000,
				2 => 8000,
				3 => 900,
				4 => 90,
				5 => 10
			],
			'Status' => null
		],
		10 => [			//土曜限定パー強化キャラのみ
			'attr' => 3,
			'money' => 30000,
			'persent' => [
				1 => 1000,
				2 => 8000,
				3 => 900,
				4 => 90,
				5 => 10
			],
			'Status' => [
				1 => [
					'sumValueMax' => 50, //基準値
					'valueMax' => 110, //最大割合
					'valueMin' => 90, //最小割合
				],
				2 => [
					'sumValueMax' => 100, //基準値
					'valueMax' => 150, //最大割合
					'valueMin' => 50, //最小割合
				],
				3 => [
					'sumValueMax' => 200, //基準値
					'valueMax' => 110, //最大割合
					'valueMin' => 90, //最小割合
				],
				4 => [
					'sumValueMax' => 300, //基準値
					'valueMax' => 110, //最大割合
					'valueMin' => 90, //最小割合
				],
				5 => [
					'sumValueMax' => 500, //基準値
					'valueMax' => 110, //最大割合
					'valueMin' => 90, //最小割合
				]
			]
		],
		11 => [			//一日一回無料ガチャ
			'money' => 0,
			'persent' => [
				1 => 9000,
				2 => 900,
				3 => 90,
				4 => 9,
				5 => 1
			],
			'Status' => null
		],
		//新規登録時、自動的に引くガチャ
		12 => [
			'money' => 0,
			'persent' => [
				1 => 10000,
				2 => 0,
				3 => 0,
				4 => 0,
				5 => 0
			],
			'Status' => null
		],
		//新規登録時、自動的に引くガチャ
		13 => [
			'money' => 0,
			'persent' => [
				1 => 0,
				2 => 0,
				3 => 10000,
				4 => 0,
				5 => 0
			],
			'Status' => null
		],
		//BOXガチャ用コンフィグ
		14 => [
			'money' => 20000,
			'persent' => [
				1 => 10000,
				2 => 0,
				3 => 0,
				4 => 0,
				5 => 0
			],
			'Status' => null,
			'deck' => [
				0 => 300,	//全体数
				1 => 150,	//N
				2 => 110,	//R
				3 => 30,	//SR
				4 => 9,		//SSR
				5 => 1		//LR
			]
		]
		
	]
];
