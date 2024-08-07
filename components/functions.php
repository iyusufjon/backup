<?php

function minutes() {
	$arr = ['*' => '*'];

	for ($i=0; $i < 60; $i++) { 
		$arr[$i] = $i;
	}

	return $arr;
}

function hours() {
	$arr = ['*' => '*'];

	for ($i=0; $i < 24; $i++) { 
		$arr[$i] = $i;
	}

	return $arr;
}

function dayOfMonth() {
	return [
		'*' => '*',
		1 => 1,
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5,
		6 => 6,
		7 => 7,
		8 => 8,
		9 => 9,
		10 => 10,
		11 => 11,
		12 => 12,
		13 => 13,
		14 => 14,
		15 => 15,
		16 => 16,
		17 => 17,
		18 => 18,
		19 => 19,
		20 => 20,
		21 => 21,
		22 => 22,
		23 => 23,
		24 => 24,
		25 => 25,
		26 => 26,
		27 => 27,
		28 => 28,
		29 => 29,
		30 => 30,
		31 => 31
	];
}

function months() {
	return [
		'*' => '*',
        1 => 'Январь',
        2 => 'Февраль',
        3 => 'Март',
        4 => 'Апрель',
        5 => 'Май',
        6 => 'Июнь',
        7 => 'Июль',
        8 => 'Август',
        9 => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь',
    ];
}

function dayOfWeek() {
	return ['*' => '*', 1 => 'Dushanba', 2 => 'Seshanba', 3 => 'Chorshanba', 4 => 'Payshanba', 5 => 'Juma', 6 => 'Shanba', 7 => 'Yakshanba'];
}

function sendMessageBot($text, $chat_id = -1002174643856)
{
    $client = new \yii\httpclient\Client();

    $response = $client->createRequest()
        ->setMethod('POST')
        ->setUrl('https://api.telegram.org/bot6174619802:AAH6o8ruq6muoeIJbVBrBz9SBEYXbFBz5Ag/sendMessage')
        ->setData(['text' => json_encode($text), 'chat_id' => $chat_id])
        ->setOptions([
            //'proxy' => 'tcp://proxy.example.com:5100', // use a Proxy
            'timeout' => 2, // set timeout to 5 seconds for the case server is not responding
        ])
        ->send();
}
?>