<?php
namespace App\Controllers;

use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuthResponseType;
use VK\Client\VKApiClient;

use App\Models\HomeModel;

class Home extends BaseController{
    protected $client_id = 2754180;
    protected $client_secret = 'CcgXk31Feir3AhcHYwTD';
    protected $redirect_uri = 'https://richpeach.grattomania.space/home/redirect_url/';
    
	public function index(){
        $model = new HomeModel();
        //check if user has a cookie
        $cookie = get_cookie('richpeach_cookie');
        
        //if user has cookie, go to database for access token vk
        if($cookie){
            
            //check for sessions in database still valid
            $response = $model->get_session($cookie);
            
            $data['getAccounts'] = $model->get_accounts($response->access_user);
            
            //User
            $vk = new VKApiClient();
            $data['getUsers'] = $vk->users()->get($response->access_token, [
                'user_ids'  => [$response->access_user],
                'fields'    => ['first_name', 'photo_50', 'photo_100'],
            ]);
            
            return view('private_full_page', $data);
        }
        else{
            //if user has no cookie, authorize it
            return view('auth_full_page');
        }
        
		//return view('welcome_message');
	}
    
    public function auth(){
        $oauth = new VKOAuth();
        $display = VKOAuthDisplay::PAGE;
        $scope = [VKOAuthUserScope::ADS];
        $state = $this->getGUIDnoHash();

        $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $this->client_id, $this->redirect_uri, $display, $scope, $state);
        header('Location: '.$browser_url);
    }
    
    public function redirect_url(){
        $model = new HomeModel();
        $data = [];
        //GETs
        $code = $_GET['code'];
        $data['state'] = $_GET['state'];
        
        $oauth = new VKOAuth();
        $response = $oauth->getAccessToken($this->client_id, $this->client_secret, $this->redirect_uri, $code);
        $data['access_token'] = $response['access_token'];
        $data['access_time'] = $response['expires_in'];
        $data['access_user'] = $response['user_id'];
        
        //if user successfull authorized, set cookie and write to database
        if($data['access_token']){
            //write to database
            $answer = $model->set_session($data);
            if($answer){
                set_cookie('richpeach_cookie', $data['state'], $data['access_time']); //access cookie
                
                //delete old
                $delete = $model->delete_accounts($data['access_user']);
                
                $vk = new VKApiClient();
                //Ads
                $data['getAccounts'] = $vk->ads()->getAccounts($data['access_token']);
                //Save it to database
                $save = $model->save_accounts($data);
                
                echo 'You are successfully logged in. ';
                echo '<a href="'.base_url().'">GO TO PRIVATE PAGE</a>';
            }
        }
        unset($data);
    }
    
    public function exit(){
        delete_cookie('richpeach_cookie');
        header('Location: '.base_url());
    }
    
    //Ads campaigns
    public function account($account_id){
        //check if user has a cookie
        $cookie = get_cookie('richpeach_cookie');
        
        //if user has cookie, go to database for access token vk
        if($cookie){
        
            $model = new HomeModel();
            $data = [];
            
            $cookie = get_cookie('richpeach_cookie');
            if($cookie){
                //check for sessions in database still valid
                $response = $model->get_session($cookie);
            }
            
            $vk = new VKApiClient();
            //Account
            //$data['getAccounts'] = $vk->ads()->getAccounts($response->access_token);
            $data['getAccounts'] = $model->get_accounts($response->access_user);
            //Campaigns
            $data['getCampaigns'] = $vk->ads()->getCampaigns($response->access_token, ['account_id' => $account_id]);
            
            return view('account_full_page', $data);
        }
        else{
            //if user has no cookie, authorize it
            return view('auth_full_page');
        }
    }
    
    //Ads ads
    public function campaign($account_id, $campaign_id){
        $cookie = get_cookie('richpeach_cookie');
        if($cookie){
            
            $model = new HomeModel();
            $data = [];
            $data['notes'] = [];
        
            //check for sessions in database still valid
            $response = $model->get_session($cookie);
        
        
            $vk = new VKApiClient();
            
            //Accounts
            $data['getAccounts'] = $model->get_accounts($response->access_user);
            //Campaigns
            $json = '['.$campaign_id.']';
            $data['getCampaigns'] = $vk->ads()->getCampaigns($response->access_token, ['account_id' => $account_id, 'campaign_ids' => $json]);
            //Ads
            $data['getAds'] = $vk->ads()->getAds($response->access_token, ['account_id' => $account_id, 'include_deleted' => 0, 'campaign_ids' => $json]);
            
            $notes = $model->get_notes($campaign_id);
            foreach($notes as $item){
                $data['notes'][$item['adid']] = $item['note'];
            }
            
            $map = ['id' => 'идентификатор объявления.', 'campaign_id' => 'идентификатор кампании.', 'ad_format' => 'формат объявления.', 'cost_type' => 'тип оплаты.', 'cpc' => 'цена за переход в копейках', 'cpm' => 'цена за 1000 показов в копейках.', 'ocpm' => 'цена за действие для oCPM в копейках.', 'goal_type' => 'тип цели.', 'impressions_limit' => 'ограничение количества показов', 'impressions_limited' => 'количество показов объявления на одного пользователя ограничено', 'ad_platform' => 'рекламные площадки', 'ad_platform_no_wall' => 'Не показывать на стенах сообществ', 'ad_platform_no_ad_network' => 'Не показывать в рекламной сети', 'all_limit' => 'общий лимит объявления в рублях', 'day_limit' => 'дневной лимит объявления в рублях.', 'autobidding' => 'автоматическое управление ценой', 'autobidding_max_cost' => 'максимальное ограничение автоматической ставки в копейках', 'category1_id' => 'ID тематики или подраздела тематики объявления', 'category2_id' => 'ID тематики или подраздела тематики объявления. Дополнительная тематика.', 'status' => 'статус объявления.', 'name' => 'название объявления.', 'approved' => 'статус модерации объявления', 'video' => 'объявление является видеорекламой', 'disclaimer_medical' => 'Есть противопоказания. Требуется консультация специалиста', 'disclaimer_specialist' => 'Необходима консультация специалистов.', 'disclaimer_supplements' => 'БАД. Не является лекарственным препаратом.', 'weekly_schedule_hours' => 'расписание показа объявления по часам', 'weekly_schedule_use_holidays' => 'использовать расписание воскресенья в праздничные дни.', 'events_retargeting_groups' => 'Описание событий, собираемых в группы ретаргетинга.', 'create_time' => 'дата создания', 'update_time' => 'дата обновления', 'start_time' => 'начало', 'stop_time' => 'конец', 'age_restriction' => 'возрастные ограничения', 'conversion_pixel_id' => 'пиксель', 'conversion_event_id' => 'идентификатор конверсии'];
            
            $ad_format = [1 => 'изображение и текст', 2 => 'большое изображение', 3 => 'эксклюзивный формат', 4 => 'продвижение сообществ или приложений, квадратное изображение', 5 => 'приложение в новостной ленте', 6 => 'мобильное приложение', 9 => 'запись в сообществе', 11 => 'адаптивный формат', 12 => 'истории'];
            $cost_type = [0 => 'оплата за переходы', 1 => 'оплата за показы', 3 => 'оптимизированная оплата за показы'];
            
            foreach($data['getAds'] as $key => $item){
                foreach($item as $origKey => $value){
                    $value = ($origKey == 'ad_format')?$ad_format[$value]:$value;
                    $value = ($origKey == 'cost_type')?$cost_type[$value]:$value;
                    
                    $data['newArray'][$key][$map[$origKey]] = $value;
                }
                $data['newArray'][$key]['id'] = $item['id'];
            }
            
            return view('campaign_full_page', $data);
        }
        else{
            //if user has no cookie, authorize it
            return view('auth_full_page');
        }
    }
    
    public function delete($account_id, $campaign_id, $ad_id){
        $vk = new VKApiClient();
        
        $json = '['.$ad_id.']';
        $data['deleteAds'] = $vk->ads()->deleteAds($response->access_token, ['account_id' => $account_id, 'ids' => $json]);
        if($data['deleteAds'] == 0){
            header('Location: '.base_url('/home/campaign/'.$account_id.'/'.$campaign_id));
        }
    }
    
    public function save(){
        $model = new HomeModel();
        
        $ad_id = $_POST['ad_id'];
        $campaign_id = $_POST['campaign_id'];
        $note = $_POST['note'];
        
        $response = $model->save_note($campaign_id, $ad_id, $note);
        if($response){
            echo json_encode(['success' => true]);
        }
    }
    
    private function getGUIDnoHash(){
        mt_srand((double)microtime()*10000);
        $charid = md5(uniqid(rand(), true));
        $c = unpack("C*",$charid);
        $c = implode("",$c);

        return substr($c,0,15);
    }
}
