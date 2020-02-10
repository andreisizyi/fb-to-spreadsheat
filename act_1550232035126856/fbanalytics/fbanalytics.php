<?php
/**
 * Copyright (c) 2015-present, Facebook, Inc. All rights reserved.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Facebook.
 *
 * As with any software that integrates with the Facebook platform, your use
 * of this software is subject to the Facebook Developer Principles and
 * Policies [http://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */

require __DIR__ . '/vendor/autoload.php';

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdsInsights;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;

$access_token = 'EAAjMImXZBdQkBAEGo8RhvBy3fjUlfH1DC0tKYPVyE0mw5Or20lhf1Af7oTefpvSi94DZBbCZCTyY5JshWKy7i1ZBfZB4RjtcMlveZBJtFPqTMNhWqmKDDlSTxmUgV8xD8vMFF3trf9pVLhP5mvGOPJihywYf0kSMm77dGPn4PYTwZDZD';
$ad_account_id = 'act_1550232035126856';
$app_secret = '4bf1d7c5a184a3ce3f8c80b3b0296c3b';
$app_id = '2476247925814537';

$api = Api::init($app_id, $app_secret, $access_token);
$api->setLogger(new CurlLogger());

$fields = array(
  'account_name',
  'account_id',
  'impressions',
  'objective',
   /*'clicks',*/
   'actions',
   'spend',
   'cost_per_conversion',
  /*'outbound_clicks',
  'cpc',
  'cost_per_conversion',*/
);
$params = array(
	'level' => 'account',
	'breakdowns' => array(),
	'time_range' => array('since' => date("y-m-d"),'until' => date("y-m-d")),
);

$json = json_encode((new AdAccount($ad_account_id))->getInsights(
  $fields,
  $params
)->getResponse()->getContent(), JSON_PRETTY_PRINT);

$obj = json_decode($json,true);
$account_name = $obj['data'][0]['account_name'];
$impressions = $obj['data'][0]['impressions'];
$objective = $obj['data'][0]['objective'];
$actions = $obj['data'][0]['actions'][2]["value"];

//var_dump(json_decode($json));

echo 'account_name: '.$account_name.', impressions: '.$impressions.', objective: '.$objective.', actions: '.$actions;


/*$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($json, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

foreach ($jsonIterator as $key => $val) {
    if(is_array($val)) {
        echo "$key:\n";
    } else {
        echo "$key => $val\n";
    }
}*/