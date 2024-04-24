## Adplay

### Technologies:
1. PHP
2. REST API
3. For Authentication, I have used **JWT** 
4. access token life time **5 minute**

### Project setup
1. Clone the project ``https://github.com/imrancse94/adplay.git``
2. Go to the project root
3. Run ``sudo composer install``  
4. Run ``php -S localhost:8000``


## Usage 

After running project. Go to api testing tool like postman

### To generate token

#### Request:

url: ``http://localhost:8000/token``
method: POST
content-type: application/json
body: {
  "username":"adplay",
  "password":"123456"
}

#### Response:

{
  "token": "<generated-token>",
  "username": "adplay"
}

### To bid

#### Request

url: ``http://localhost:8000/bidder``
method: POST
content-type: application/json
body: go to ``<project-root>/data/bidder.json`` you will find sample json

#### Response
````
{
  "id": "myB92gUhMdC5DUxndq3yAg",
  "seatbid": [
    {
      "bid": [
        {
          "impid": "1",
          "advertiser": "TestGP",
          "image_url": "https://s3-ap-southeast-1.amazonaws.com/elasticbeanstalk-ap-southeast-1-5410920200615/CampaignFile/20240117030213/D300x250/e63324c6f222208f1dc66d3e2daaaf06.png",
          "landing_page_url": "https://adplaytechnology.com/",
          "price": 0.2,
          "adm": "<iframe marginwidth=0 marginheight=0 height=600 frameborder=0 width=160 scrolling=no src=\"https://adplaytechnology.com/\"></iframe>",
          "adomain": [
            "adplaytechnology.com"
          ],
          "w": 320,
          "h": 50,
          "creativeType": "1"
        }
      ]
    }
  ],
  "ext": {
    "billing_id": [
      "123456789",
      "152349838468"
    ],
    "publisher_settings_list_id": [
      "10210479292634817089",
      "14735124967324597266"
    ],
    "allowed_vendor_type": [
      785,
      767,
      144
    ],
    "ampad": 2,
    "creative_enforcement_settings": {
      "policy_enforcement": 2,
      "scan_enforcement": 1,
      "publisher_blocks_enforcement": 1
    },
    "auction_environment": 0
  }
}

````

##### Note: For campaign, I have used ``<project-root>/data/campaign.json`` file as storage 



