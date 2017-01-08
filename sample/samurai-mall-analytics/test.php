<?php
// ライブラリの読み込み
require_once 'google-api-php-client/src/Google/autoload.php';

// サービスアカウントのメールアドレス
$service_account_email = 'samurai-analytics@samurai-mall-analytics-project.iam.gserviceaccount.com';

// 秘密キーファイルの読み込み
$key = file_get_contents('samurai-mall-analytics-project-04b9806ab180.p12');


// プロファイル(ビュー)ID
$profile = '136127922';

// Googleクライアントのインスタンスを作成
$client = new Google_Client();
$analytics = new Google_Service_Analytics($client);

// クレデンシャルの作成
$cred = new Google_Auth_AssertionCredentials(
				$service_account_email,
				array(Google_Service_Analytics::ANALYTICS_READONLY),
				$key
				);
$client->setAssertionCredentials($cred);
if($client->getAuth()->isAccessTokenExpired()) {
		$client->getAuth()->refreshTokenWithAssertion($cred);
}

$result = $analytics->data_ga->get(
				'ga:' . $profile, // アナリティクス ビュー ID
				'2016-11-01',       // データの取得を開始する日付は7日前
				'2017-01-03',      // データの取得を終了する日付は昨日
				'ga:sessions, ga:pageviews, ga:bounces',     // セッション数を取得する
				[
					'dimensions'  => 'ga:pagePath, ga:pageTitle'
				]

				);

// 結果を出力
//echo $result -> rows[0][0];
var_dump($result);

?>
