<?php

namespace Adplay\Business\Services;

class Bidder
{

    // Function to check if a campaign matches bid request parameters
    private function isCampaignMatch($campaign, $bidRequest)
    {
        dd($bidRequest['device']);
        if ($campaign['country'] !== $bidRequest['geo']['country']) {
            return false;
        }

        $bannerWidth = $bidRequest['imp'][0]['banner']['w'];
        $bannerHeight = $bidRequest['imp'][0]['banner']['h'];
        $campaignDimensions = explode('x', $campaign['dimension']);
        if ($bannerWidth > $campaignDimensions[0] || $bannerHeight > $campaignDimensions[1]) {
            return false;
        }

        if ($campaign['price'] < $bidRequest['imp'][0]['bidfloor']) {
            return false;
        }

        return true;
    }

    public function generateCampaignBanner($campaigns,$bidRequest){
        // Find suitable campaign
        $selectedCampaign = 'Not found';
        foreach ($campaigns as $campaign) {
            if ($this->isCampaignMatch($campaign, $bidRequest)) {
                if (!$selectedCampaign || $campaign['price'] > $selectedCampaign['price']) {
                    $selectedCampaign = $campaign;
                }
            }
        }

        return $selectedCampaign;
    }
}
