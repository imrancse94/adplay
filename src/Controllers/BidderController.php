<?php


namespace Adplay\Controllers;

use Adplay\Business\Requests\BidRequest;
use Adplay\Business\Services\Campaign;

class BidderController{

    public function index(BidRequest $request)
    {
        $inputData = $request->all();
        
        $campaign = (new Campaign)->selectBannerCampaign($inputData);
        
        $data = $this->prepareDataRTBFormat($inputData,$campaign);

        return responseJSON($data,200);
    }


    // {
    //     "id": "KO9b4i49863AW26d1Tl9q6",
    //     "seatbid": [
    //       {
    //         "bid": [
    //           {
    //             "id": "Zc2MS8m162f920x7944",
    //             "impid": "1",
    //             "price": 0.133555,
    //             "adm": "<iframe marginwidth=0 marginheight=0 height=600 frameborder=0 width=160 scrolling=no src=\"https://test.com/ads?id=123456&curl=%%CLICK_URL_ESC%%&wprice=%%WINNING_PRICE_ESC%%\"></iframe>",
    //             "adomain": [
    //               "google.com"
    //             ],
    //             "w": 300,
    //             "h": 250,
    //             "crid": "test_creative_id_410013",
    //             "creativeType": "DISPLAY",
    //             "burl": "https://test.com/imp?id=123456",
    //             "cid": "37092002186",
    //             "adid": "test_creative_id_410013"
    //           }
    //         ],
    //         "seat": "8333:6953:689419"
    //       }
    //     ],
    //     "ext": {
    //       "pixels": "<script type='text/javascript'>var adContent = '';\nadContent += '<scr' + 'ipt type=\"text/javascript\" src=\"https://service.idsync.analytics.yahoo.com/sp/v0/pixels?pixelIds=58292,58301,58294&referrer=&limit=12&us_privacy=null&js=1&_origin=1&gdpr=0&euconsent=\"></scr' + 'ipt>' + '\\n';\ndocument.write(adContent);</script>"
    //     }
    //   }

    private function prepareDataRTBFormat($bidrequest,$campaign){

        return [
            'id'=>$bidrequest['id'],
            'seatbid'=>[
                [
                   "bid"=>[
                        [
                            "impid"=>$campaign['impid'],
                            "advertiser"=>$campaign['advertiser'],
                            "image_url"=>$campaign['image_url'],
                            "landing_page_url"=>$campaign['landing_page_url'],
                            "price"=>$campaign['price'],
                            "adm" => "<iframe marginwidth=0 marginheight=0 height=600 frameborder=0 width=160 scrolling=no src=\"{$campaign['landing_page_url']}\"></iframe>",
                            "adomain" => [
                                getUrlToDomain($campaign['landing_page_url'])
                            ],
                            "w"=>$campaign['w'],
                            "h"=>$campaign['h'],
                            "creativeType"=>$campaign['creativeType']
                        ]
                   ] 
                ]
            ],
            "ext"=>$campaign['ext']
        ];
    }
}