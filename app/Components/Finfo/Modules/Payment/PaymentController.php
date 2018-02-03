<?php
namespace App\Components\Finfo\Modules\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Components\Finfo\Modules\Clients\User;
use App\Components\Finfo\Modules\Clients\Company;
use App\Components\Finfo\Modules\Clients\CompanySubscription;
use App\Components\Finfo\Modules\Registers\Package;
use App\Components\Finfo\Modules\Currency\Currency as Currency;
use Session;


class PaymentController extends Controller
{
    private $apiKey;
    private $apiPassword;
    private $apiEndpoint;
    private $client;

    public function __construct()
    {
        $this->apiKey = "44DD7Cr0EtveArWzjFbp/AsUHclwWyuPryiHBlj8wsZkqZ2xUBzfGHTAg1lWgsjbIrAwEb";
        $this->apiPassword = "rnvhUWeT";
        // REMOVE HTTPS BECAUSE OUR WEBSITE NOT YET SETUP
        //$this->apiEndpoint = \Eway\Rapid\Client::MODE_SANDBOX;
        $this->apiEndpoint = 'http://api.sandbox.ewaypayments.com/';
        $this->client = \Eway\Rapid::createClient($this->apiKey, $this->apiPassword, $this->apiEndpoint);
    }

    public function checkTest()
    {
        $transaction = [
                        'Customer' => [
                            'Reference' => 'A12345',
                            'Title' => 'Mr.',
                            'FirstName' => 'John',
                            'LastName' => 'Smith',
                            'CompanyName' => 'Demo Shop 123',
                            'JobDescription' => 'PHP Developer',
                            'Street1' => 'Level 5',
                            'Street2' => '369 Queen Street',
                            'City' => 'Sydney',
                            'State' => 'NSW',
                            'PostalCode' => '2000',
                            'Country' => 'au',
                            'Phone' => '09 889 0986',
                            'Mobile' => '09 889 6542',
                            'Email' => 'demo@example.org',
                            "Url" => "http://www.ewaypayments.com",
                            'CardDetails' => [
                                'Name' => 'cheayden123',
                                'Number' => '5105105105105100',
                                'ExpiryMonth' => '12',
                                'ExpiryYear' => '15',
                                'CVN' => '111',
                            ]
                        ],
                        'ShippingAddress' => [
                            'ShippingMethod' => 'tomorrow',
                            'FirstName' => 'John',
                            'LastName' => 'Smith',
                            'Street1' => 'Level 5',
                            'Street2' => '369 Queen Street',
                            'City' => 'Sydney',
                            'State' => 'NSW',
                            'Country' => 'au',
                            'PostalCode' => '2000',
                            'Phone' => '09 889 0986',
                        ],
                        'Payment' => [
                            'TotalAmount' => 1000,
                            'InvoiceNumber' => 'Inv 21540',
                            'InvoiceDescription' => 'Individual Invoice Description',
                            'InvoiceReference' => '513456',
                            'CurrencyCode' => 'AUD',
                        ],
                        'RedirectUrl' => 'http://www.eway.com.au',
                        'TransactionType' => \Eway\Rapid\Enum\TransactionType::PURCHASE,
                    ];
        $response = $this->client->createTransaction(\Eway\Rapid\Enum\ApiMethod::DIRECT, $transaction);
        $this->debug_transaction_data($response);
        if (!$response->getErrors()) {
            echo 'Payment successful! ID: '.$response->TransactionID;
        } else {
            if ($response->getErrors()) {
                foreach ($response->getErrors() as $error) {
                    echo "Error: ".\Eway\Rapid::getMessage($error)."<br>";
                }
            }
        }
    }
    private function debug_transaction_data($response) {
        echo "<pre>";
            echo "DEFAULT INFORMATION<br/>";
            var_dump($response);
            // echo "==============";
            // echo "<br/>VERIFICATION INFORMATION<br/>";
            // var_dump($response->Verification);
            // echo "==============";
            // echo "<br/>PAYMENT INFORMATION<br/>";
            // var_dump($response->Payment);
            // echo "==============";
            // echo "<br/>CUSTOMER INFORMATION<br/>";
            // var_dump($response->Customer);
            // echo "==============";
            // echo "<br/>CARD DEFAULT INFORMATION<br/>";
            // var_dump($response->Customer->CardDetails);
            // echo "==============";
        echo "</pre>";
    }
    private function setCheckoutTransactionUserInformation($userdata) {



        $getUserData = User::where('company_id', '=', $userdata['hide_company'])->get()->first();
        $getCompanyData = Company::where('id', '=', $userdata['hide_company'])->get()->first();
        $package_subscribed = Package::where('id', '=', \Session::get('data')['package_id'])->get()->first();
        $expiry_month = $userdata['expiry_month'];

        $currency_infor = Currency::find(Session::get('data')['package_currency_type']);
        if(count($currency_infor)){
            $cur_symbal = $currency_infor->code;
            if($cur_symbal == 'AUD'){
                $price = $package_subscribed->price_aud;
            }else{
                $price = str_replace(',','',number_format(round($package_subscribed->price)));
            }
            
        }else{
            //$cur_symbal = 'USD';
            $price = str_replace(',','',number_format(round($package_subscribed->price)));
        }

        if ($userdata['expiry_month'] < 10){
            $expiry_month = "0".$userdata['expiry_month'];
        }
        $transaction = [
                        'Customer' => [
                            'FirstName' => $getUserData['first_name'],
                            'LastName' => $getUserData['last_name'],
                            'CompanyName' => $getCompanyData['company_name'],
                            'Street1' => str_replace(',', '', $userdata['street']),
                            'City' => $userdata['city'],
                            'State' => $userdata['state'],
                            'PostalCode' => $userdata['zip_code'],
                            'Country' => $this->getContryCodeByContryName($userdata['country']),
                            'Phone' => $userdata['phone'],
                            'Email' => $getUserData['email_address'],
                            'CardDetails' => [
                                'Name' => $userdata['card_holder_name'],
                                'Number' => $userdata['card_number'],
                                'ExpiryMonth' => $expiry_month,
                                'ExpiryYear' => $userdata['expiry_year'],
                                'CVN' => $userdata['cvv_number'],
                            ]
                        ],
                        'Payment' => [
                            'TotalAmount' => $price * 100,
                            'InvoiceReference' => $getUserData['id'],
                            'CurrencyCode' => 'AUD',
                            'InvoiceDescription' => $package_subscribed->name,
                        ],
                        'RedirectUrl' => 'http://www.eway.com.au',
                        'TransactionType' => \Eway\Rapid\Enum\TransactionType::PURCHASE,
                    ];
                    //dd($transaction);
        return $transaction;
    }
    public function CheckoutTransaction($request) {
        $trasactionData = $this->setCheckoutTransactionUserInformation($request);       
        $response = $this->client->createTransaction(\Eway\Rapid\Enum\ApiMethod::DIRECT, $trasactionData);
        return $response;
    }
    public function getContryCodeByContryName($countryName) {
        $countries = array( 'AF'=>'AFGHANISTAN', 'AL'=>'ALBANIA', 'DZ'=>'ALGERIA', 'AS'=>'AMERICAN SAMOA', 'AD'=>'ANDORRA', 'AO'=>'ANGOLA', 'AI'=>'ANGUILLA', 'AQ'=>'ANTARCTICA', 'AG'=>'ANTIGUA AND BARBUDA', 'AR'=>'ARGENTINA', 'AM'=>'ARMENIA', 'AW'=>'ARUBA', 'AU'=>'AUSTRALIA', 'AT'=>'AUSTRIA', 'AZ'=>'AZERBAIJAN', 'BS'=>'BAHAMAS', 'BH'=>'BAHRAIN', 'BD'=>'BANGLADESH', 'BB'=>'BARBADOS', 'BY'=>'BELARUS', 'BE'=>'BELGIUM', 'BZ'=>'BELIZE', 'BJ'=>'BENIN', 'BM'=>'BERMUDA', 'BT'=>'BHUTAN', 'BO'=>'BOLIVIA', 'BA'=>'BOSNIA AND HERZEGOVINA', 'BW'=>'BOTSWANA', 'BV'=>'BOUVET ISLAND', 'BR'=>'BRAZIL', 'IO'=>'BRITISH INDIAN OCEAN TERRITORY', 'BN'=>'BRUNEI DARUSSALAM', 'BG'=>'BULGARIA', 'BF'=>'BURKINA FASO', 'BI'=>'BURUNDI', 'KH'=>'CAMBODIA', 'CM'=>'CAMEROON', 'CA'=>'CANADA', 'CV'=>'CAPE VERDE', 'KY'=>'CAYMAN ISLANDS', 'CF'=>'CENTRAL AFRICAN REPUBLIC', 'TD'=>'CHAD', 'CL'=>'CHILE', 'CN'=>'CHINA', 'CX'=>'CHRISTMAS ISLAND', 'CC'=>'COCOS (KEELING) ISLANDS', 'CO'=>'COLOMBIA', 'KM'=>'COMOROS', 'CG'=>'CONGO', 'CD'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'CK'=>'COOK ISLANDS', 'CR'=>'COSTA RICA', 'CI'=>'COTE D IVOIRE', 'HR'=>'CROATIA', 'CU'=>'CUBA', 'CY'=>'CYPRUS', 'CZ'=>'CZECH REPUBLIC', 'DK'=>'DENMARK', 'DJ'=>'DJIBOUTI', 'DM'=>'DOMINICA', 'DO'=>'DOMINICAN REPUBLIC', 'TP'=>'EAST TIMOR', 'EC'=>'ECUADOR', 'EG'=>'EGYPT', 'SV'=>'EL SALVADOR', 'GQ'=>'EQUATORIAL GUINEA', 'ER'=>'ERITREA', 'EE'=>'ESTONIA', 'ET'=>'ETHIOPIA', 'FK'=>'FALKLAND ISLANDS (MALVINAS)', 'FO'=>'FAROE ISLANDS', 'FJ'=>'FIJI', 'FI'=>'FINLAND', 'FR'=>'FRANCE', 'GF'=>'FRENCH GUIANA', 'PF'=>'FRENCH POLYNESIA', 'TF'=>'FRENCH SOUTHERN TERRITORIES', 'GA'=>'GABON', 'GM'=>'GAMBIA', 'GE'=>'GEORGIA', 'DE'=>'GERMANY', 'GH'=>'GHANA', 'GI'=>'GIBRALTAR', 'GR'=>'GREECE', 'GL'=>'GREENLAND', 'GD'=>'GRENADA', 'GP'=>'GUADELOUPE', 'GU'=>'GUAM', 'GT'=>'GUATEMALA', 'GN'=>'GUINEA', 'GW'=>'GUINEA-BISSAU', 'GY'=>'GUYANA', 'HT'=>'HAITI', 'HM'=>'HEARD ISLAND AND MCDONALD ISLANDS', 'VA'=>'HOLY SEE (VATICAN CITY STATE)', 'HN'=>'HONDURAS', 'HK'=>'HONG KONG', 'HU'=>'HUNGARY', 'IS'=>'ICELAND', 'IN'=>'INDIA', 'ID'=>'INDONESIA', 'IR'=>'IRAN, ISLAMIC REPUBLIC OF', 'IQ'=>'IRAQ', 'IE'=>'IRELAND', 'IL'=>'ISRAEL', 'IT'=>'ITALY', 'JM'=>'JAMAICA', 'JP'=>'JAPAN', 'JO'=>'JORDAN', 'KZ'=>'KAZAKSTAN', 'KE'=>'KENYA', 'KI'=>'KIRIBATI', 'KP'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF', 'KR'=>'KOREA REPUBLIC OF', 'KW'=>'KUWAIT', 'KG'=>'KYRGYZSTAN', 'LA'=>'LAO PEOPLES DEMOCRATIC REPUBLIC', 'LV'=>'LATVIA', 'LB'=>'LEBANON', 'LS'=>'LESOTHO', 'LR'=>'LIBERIA', 'LY'=>'LIBYAN ARAB JAMAHIRIYA', 'LI'=>'LIECHTENSTEIN', 'LT'=>'LITHUANIA', 'LU'=>'LUXEMBOURG', 'MO'=>'MACAU', 'MK'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'MG'=>'MADAGASCAR', 'MW'=>'MALAWI', 'MY'=>'MALAYSIA', 'MV'=>'MALDIVES', 'ML'=>'MALI', 'MT'=>'MALTA', 'MH'=>'MARSHALL ISLANDS', 'MQ'=>'MARTINIQUE', 'MR'=>'MAURITANIA', 'MU'=>'MAURITIUS', 'YT'=>'MAYOTTE', 'MX'=>'MEXICO', 'FM'=>'MICRONESIA, FEDERATED STATES OF', 'MD'=>'MOLDOVA, REPUBLIC OF', 'MC'=>'MONACO', 'MN'=>'MONGOLIA', 'MS'=>'MONTSERRAT', 'MA'=>'MOROCCO', 'MZ'=>'MOZAMBIQUE', 'MM'=>'MYANMAR', 'NA'=>'NAMIBIA', 'NR'=>'NAURU', 'NP'=>'NEPAL', 'NL'=>'NETHERLANDS', 'AN'=>'NETHERLANDS ANTILLES', 'NC'=>'NEW CALEDONIA', 'NZ'=>'NEW ZEALAND', 'NI'=>'NICARAGUA', 'NE'=>'NIGER', 'NG'=>'NIGERIA', 'NU'=>'NIUE', 'NF'=>'NORFOLK ISLAND', 'MP'=>'NORTHERN MARIANA ISLANDS', 'NO'=>'NORWAY', 'OM'=>'OMAN', 'PK'=>'PAKISTAN', 'PW'=>'PALAU', 'PS'=>'PALESTINIAN TERRITORY, OCCUPIED', 'PA'=>'PANAMA', 'PG'=>'PAPUA NEW GUINEA', 'PY'=>'PARAGUAY', 'PE'=>'PERU', 'PH'=>'PHILIPPINES', 'PN'=>'PITCAIRN', 'PL'=>'POLAND', 'PT'=>'PORTUGAL', 'PR'=>'PUERTO RICO', 'QA'=>'QATAR', 'RE'=>'REUNION', 'RO'=>'ROMANIA', 'RU'=>'RUSSIAN FEDERATION', 'RW'=>'RWANDA', 'SH'=>'SAINT HELENA', 'KN'=>'SAINT KITTS AND NEVIS', 'LC'=>'SAINT LUCIA', 'PM'=>'SAINT PIERRE AND MIQUELON', 'VC'=>'SAINT VINCENT AND THE GRENADINES', 'WS'=>'SAMOA', 'SM'=>'SAN MARINO', 'ST'=>'SAO TOME AND PRINCIPE', 'SA'=>'SAUDI ARABIA', 'SN'=>'SENEGAL', 'SC'=>'SEYCHELLES', 'SL'=>'SIERRA LEONE', 'SG'=>'SINGAPORE', 'SK'=>'SLOVAKIA', 'SI'=>'SLOVENIA', 'SB'=>'SOLOMON ISLANDS', 'SO'=>'SOMALIA', 'ZA'=>'SOUTH AFRICA', 'GS'=>'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'ES'=>'SPAIN', 'LK'=>'SRI LANKA', 'SD'=>'SUDAN', 'SR'=>'SURINAME', 'SJ'=>'SVALBARD AND JAN MAYEN', 'SZ'=>'SWAZILAND', 'SE'=>'SWEDEN', 'CH'=>'SWITZERLAND', 'SY'=>'SYRIAN ARAB REPUBLIC', 'TW'=>'TAIWAN, PROVINCE OF CHINA', 'TJ'=>'TAJIKISTAN', 'TZ'=>'TANZANIA, UNITED REPUBLIC OF', 'TH'=>'THAILAND', 'TG'=>'TOGO', 'TK'=>'TOKELAU', 'TO'=>'TONGA', 'TT'=>'TRINIDAD AND TOBAGO', 'TN'=>'TUNISIA', 'TR'=>'TURKEY', 'TM'=>'TURKMENISTAN', 'TC'=>'TURKS AND CAICOS ISLANDS', 'TV'=>'TUVALU', 'UG'=>'UGANDA', 'UA'=>'UKRAINE', 'AE'=>'UNITED ARAB EMIRATES', 'GB'=>'UNITED KINGDOM', 'US'=>'UNITED STATES', 'UM'=>'UNITED STATES MINOR OUTLYING ISLANDS', 'UY'=>'URUGUAY', 'UZ'=>'UZBEKISTAN', 'VU'=>'VANUATU', 'VE'=>'VENEZUELA', 'VN'=>'VIET NAM', 'VG'=>'VIRGIN ISLANDS, BRITISH', 'VI'=>'VIRGIN ISLANDS, U.S.', 'WF'=>'WALLIS AND FUTUNA', 'EH'=>'WESTERN SAHARA', 'YE'=>'YEMEN', 'YU'=>'YUGOSLAVIA', 'ZM'=>'ZAMBIA', 'ZW'=>'ZIMBABWE');
        return array_search(strtoupper($countryName), $countries);        
    }
}
