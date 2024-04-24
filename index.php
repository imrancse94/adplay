<?php

require 'vendor/autoload.php';


use Adplay\Routes\Adapter;


(new Adapter)->routes()->execute();

// use Adplay\Bidder;
// use Adplay\Request;

// $bidderRequest = file_get_contents('data/bidder.json');
// $campaigns = file_get_contents('data/campaign.json');

// $campaignData = (new Request)->parseJsonToArray($campaigns);
// $bidderData = (new Request)->parseJsonToArray($bidderRequest);

// $bannerData = (new Bidder)->generateCampaignBanner($campaignData,$bidderData);

// dd($bannerData);



?>

