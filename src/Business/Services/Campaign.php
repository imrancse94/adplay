<?php


namespace Adplay\Business\Services;

use Adplay\Utils\Country;

class Campaign
{

    private $data;
    private $banner;

    public function __construct()
    {
        $this->data = file_get_contents(__DIR__ . '/../../../data/campaign.json');
        $this->data = json_decode($this->data, true);
    }

    public function selectBannerCampaign($bidRequest)
    {
        $campaigns = $this->data;

        // Find the best matching campaign
        $selectedCampaign = [];
        $maxBidPrice = 0;

        foreach ($campaigns as $campaign) {

            // device compatibility check
            $is_allow = $this->checkDeviceCompitability($bidRequest['device'], $campaign);

            // Geo Information check
            $is_allow *= $this->checkGeoInformation($bidRequest['device'], $campaign);

            // check bid floor
            $impInfo = $this->checkBidFloor($bidRequest['imp'], $campaign);

            $is_allow *= !empty($impInfo) ? 1 : 0;

            if ($is_allow == 1 && $campaign['price'] >= $maxBidPrice) {
                $maxBidPrice = $campaign['price'];
                $selectedCampaign = [
                    'name' => $campaign['campaignname'],
                    'advertiser' => $campaign['advertiser'],
                    'creativeType' => $campaign['creative_type'],
                    'image_url' => $campaign['image_url'],
                    'landing_page_url' => $campaign['url'],
                    'price' => $campaign['price'],
                    'creative_id' => $campaign['creative_id'],
                    'impid'=>$impInfo['impid'],
                    'w'=>$impInfo['w'],
                    'h'=>$impInfo['h'],
                    'ext'=>$impInfo['ext']
                ];
            }
        }

        return $selectedCampaign;
    }

    private function checkBidFloor($bidRequest, $campaign)
    {
        $maxBidPrice = 0;
        $finalData = [];
        foreach ($bidRequest as $imp) {
            if ($campaign['price'] > $imp['bidfloor']) {
                if ($campaign['price'] >= $maxBidPrice) {
                    $maxBidPrice = $campaign['price'];
                    $finalData['impid'] = $imp['id'];
                    $finalData['w'] = $imp['banner']['w'];
                    $finalData['h'] = $imp['banner']['h'];
                    $finalData['ext'] = $imp['ext'];
                }
            }
        }

        return $finalData;
    }

    private function checkGeoInformation($bidRequest, $campaign)
    {

        $b_country = strtolower(Country::list[strtoupper($bidRequest['geo']['country'])]);
        $b_city = strtolower($bidRequest['geo']['city']);
        $b_lat = strtolower($bidRequest['geo']['lat']);
        $b_lon = strtolower($bidRequest['geo']['lon']);

        $is_allow = false;

        if (!empty($campaign['country']) && $b_country == strtolower($campaign['country'])) {
            $is_allow = true;
        }

        if ($is_allow && !empty($campaign['city'])) {
            $is_allow = $b_city == strtolower($campaign['city']);
        }

        if (!empty($campaign['lat']) && empty($campaign['lng'])) {
            $is_allow = calculateDistance($b_lat, $b_lon, $campaign['lat'], $campaign['lng']) <= 100; // in km
        }

        return $is_allow ? 1 : 0;
    }

    private function checkDeviceCompitability($bidRequest, $campaign)
    {

        $b_device_os = strtolower($bidRequest['os']) ?? null;
        $b_device_make = strtolower($bidRequest['make']) ?? null;
        $b_device_model = strtolower($bidRequest['model']) ?? null;

        $campaign['hs_os'] = array_map(function ($os) {
            return strtolower($os);
        }, explode(",", $campaign['hs_os']));

        // device make
        $is_allow_for_all_device = false;

        if ($campaign['device_make'] == "No Filter") {
            $is_allow_for_all_device = true;
        } else {

            $device_list = array_map(function ($dm) {
                return strtolower($dm);
            }, explode(",", $campaign['device_make']));

            $is_allow_for_all_device = in_array($b_device_make, $device_list);
        }

        $is_allow_model = false;

        if (empty($campaign['hs_model'])) {
            $is_allow_model = true;
        } else {
            $is_allow_model = $b_device_model == strtolower($campaign['hs_model']);
        }

        // OS check
        $is_device_compitable = in_array($b_device_os, $campaign['hs_os']) ? 1 : 0;

        // device filtering check
        $is_device_compitable *= $is_allow_for_all_device ? 1 : 0;

        // device model check
        $is_device_compitable *= $is_allow_model ? 1 : 0;

        return $is_device_compitable;
    }
}
