<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

$folder = dirname(dirname(__FILE__));
require_once $folder . "/helpers/curl.php";
require_once "abstract_controller.php";

class Home extends abstract_controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

        $this->benchmark->mark('Home:index_start');

        $current = '/home/';
		$data = $this -> security($current);
		if ($data && !empty($data)) {
      //loading models

            $this->benchmark->mark('Home:index load_model_start');

            $this->load->model('cards_model', 'cards');
            $this->load->model('storyboards_model', 'storyboards');
            $this->load->model('users_model', 'users');
            $this->load->model('card_of_the_day_model', 'card_otd');
            $this->load->model('storyboard_of_the_day_model', 'storyboard_otd');

            $this->benchmark->mark('Home:index load_model_end');
            log_message('debug', "load home model " . $this->benchmark->elapsed_time('Home:index load_model_start', 'Home:index load_model_end'));

            //total cards and storyboards
            $this->benchmark->mark('Home:index total_cards_and_storyboards_start');
      $data['total_cards'] = $this->cards->count();
      $data['total_storyboards'] = $this->storyboards->count();
      if( $data['total_cards'] > 0)
        $data['cards_public_shared_percent'] = number_format((float)($this->cards->count_public_shared_cards() / $data['total_cards']), 2, '.', '')*100;
      else
        $data['cards_public_shared_percent'] = 0;
      if($data['total_storyboards'] > 0)
        $data['storyboards_public_shared_percent'] = number_format((float)($this->storyboards->count_public_shared_cards() / $data['total_storyboards']), 2, '.', '')*100;
      else
        $data['storyboards_public_shared_percent'] = 0;

            $this->benchmark->mark('Home:index total_cards_and_storyboards_end');
            log_message('debug', "load total cards " . $this->benchmark->elapsed_time('Home:index total_cards_and_storyboards_start', 'Home:index total_cards_and_storyboards_end'));

            $this->benchmark->mark('Home:index ten_day_cards_start');

            $cards_10days = $this->cards->get_cards_number_per_10days();
      $data['cards_10days'] = '';
      foreach($cards_10days as $n) {
        if($data['cards_10days'] != '')
          $data['cards_10days'] .= ','.$n['n'];
        else
          $data['cards_10days'] .= $n['n'];
      }

            $this->benchmark->mark('Home:index ten_day_cards_end');
            log_message('debug', "load ten day cards " . $this->benchmark->elapsed_time('Home:index ten_day_cards_start', 'Home:index ten_day_cards_end'));
            $this->benchmark->mark('Home:index sbs_ten_day_cards_start');

            $sbs_10days = $this->storyboards->get_sbs_number_per_10days();
            $data['sbs_10days'] = '';

            foreach($sbs_10days as $n) {
                if($data['sbs_10days'] != '')
                  $data['sbs_10days'] .= ','.$n['n'];
                else
                  $data['sbs_10days'] .= $n['n'];
           }

            $this->benchmark->mark('Home:index sbs_ten_day_cards_end');
            log_message('debug', "load sbs ten day cards " . $this->benchmark->elapsed_time('Home:index sbs_ten_day_cards_start', 'Home:index sbs_ten_day_cards_end'));
            $this->benchmark->mark('Home:index most_viewed_start');

            $data['most_viewed_cards'] = $this->cards->cards_shared_with_user($this->user_id(), '', 5);
            $data['most_viewed_storyboards'] = $this->storyboards->storyboards_shared_with_user($this->user_id(), '', 5);

            $this->benchmark->mark('Home:index most_viewed_end');
            log_message('debug', "load most viewed " . $this->benchmark->elapsed_time('Home:index most_viewed_start', 'Home:index most_viewed_end'));
            $this->benchmark->mark('Home:index recent_pub_start');

            $data['recently_published_cards'] = $this->cards->recently_published_cards($this->user_id(), 5);
            $data['recently_published_storyboards'] = $this->storyboards->recently_published_storyboards($this->user_id(), 5);

            $this->benchmark->mark('Home:index recent_pub_end');
            log_message('debug', "load recently published " . $this->benchmark->elapsed_time('Home:index recent_pub_start', 'Home:index recent_pub_end'));

            $this->benchmark->mark('Home:index otd_last_rec_start');

            $card_otd = $this->card_otd->get_last_record();

            if(!empty($card_otd[0]->card)) {
                $card_otd = $this->cards->get_by_id($card_otd[0]->card);
                $card_otd->user_obj = $this->users->get_by_id($card_otd->user);
                $data['card_otd'] = $card_otd;
            }

            $this->benchmark->mark('Home:index otd_last_rec_end');
            log_message('debug', "load otd last record " . $this->benchmark->elapsed_time('Home:index otd_last_rec_start', 'Home:index otd_last_rec_end'));

            $this->benchmark->mark('Home:index otd_storyboard_last_rec_start');
            $storyboard_otd = $this->storyboard_otd->get_last_record();
            if(!empty($storyboard_otd[0]->storyboard)) {
                $storyboard_otd = $this->storyboards->get_by_id($storyboard_otd[0]->storyboard);
                $storyboard_otd->user_obj = $this->users->get_by_id($storyboard_otd->user);
                $data['storyboard_otd'] = $storyboard_otd;
            }
            $this->benchmark->mark('Home:index otd_storyboard_last_rec_end');
            log_message('debug', "load storyboard otd last record " . $this->benchmark->elapsed_time('Home:index otd_storyboard_last_rec_start', 'Home:index otd_storyboard_last_rec_end'));

			$this -> load -> view('general/header', $data);
			$this -> load -> view('home/home', $data);
			$this -> load -> view('general/footer');
		} else
			redirect('login');

        $this->benchmark->mark('Home:index_end');

        log_message('debug', "load home took " . $this->benchmark->elapsed_time('Home:index_start', 'Home:index_end'));
    }

	public function admin() {
		$current = '/home/admin/';
		$data = $this -> security($current);
		if ($data && !empty($data)) {
			$data['title'] = 'Administration ' . app_name();

            $this->load->model('sync_model', 'sync');
            $this->load->model('companies_model', 'companies');
            $this->load->model('Kpis_model', 'kpis');

            $data['companies_last_sync'] = date("m/d/Y H:ia", strtotime($this->sync->get_by_id("companies")->last_sync));
            $data['kpis_last_sync'] = date("m/d/Y H:ia", strtotime($this->sync->get_by_id("kpis")->last_sync));

            $data['count_companies'] = $this->companies->count();
            $data['count_kpis'] = $this->kpis->count();

			$this -> load -> view('general/header', $data);
			$this -> load -> view('home/admin', $data);
			$this -> load -> view('general/footer');
		}
	}

	public function sync($obj) {
		set_time_limit(2700);
		$current = '/home/admin/';
		$data = $this -> security($current);
		if ($data && !empty($data)) {
			try {
				if ($obj == "companies") {
                    $arr = array();
                    $arr['type'] = "companies";
                    $arr['last_sync'] = date("Y-m-d H:i:s");
                    @$this->load->model('sync_model', 'sync');
                    @$this->sync->edit($arr);
					echo @$this -> sync_companies();
				} else if ($obj == "kpis") {
                    $arr = array();
                    $arr['type'] = "kpis";
                    $arr['last_sync'] = date("Y-m-d H:i:s");
                    @$this->load->model('sync_model', 'sync');
                    @$this->sync->edit($arr);
					echo @$this -> sync_kpis();
				}
			} catch (Exception $e) {
				echo "ko";
				die();
			}
		}
	}

	private function sync_companies() {
		$this -> load -> model('companies_model', 'companies');

        $this->companies->sync();

		return "ok";
	}

	private function sync_kpis() {
		$this -> load -> model('Kpis_model', 'kpis');

        $this->kpis->sync();

		return "ok";
	}

  public function notification($obj) {
      $data = array();
      $data['user'] = $this->input->post('user');
      $data['circle'] = $this->input->post('circle');
      $data['status'] = ($obj == "accept") ? user_circle_status::request_accept : user_circle_status::request_reject;
      $data['modification_time'] = date("Y-m-d H:i:s");
      $this->user_circles->edit($data);
      return "ok";
  }

  public function of_the_day($submit = '') {
    $current = '/home/admin/';
    $data = $this->security($current);
    if ($data && !empty($data)) {
      $data['title'] = 'Administration ' . app_name();
      $this->load->model('card_of_the_day_model', 'card_otd');
      $this->load->model('storyboard_of_the_day_model', 'storyboard_otd');

      if(empty($submit)) {
        $this->load->model('cards_model', 'cards');
        $this->load->model('storyboards_model', 'storyboards');
        $data['public_cards'] = $this->cards->list_records(array('public' => 1));
        $data['public_storyboards'] = $this->storyboards->list_records(array('public' => 1));
        $data['card_otd'] = $this->card_otd->get_last_record();
        $data['storyboard_otd'] = $this->storyboard_otd->get_last_record();

        $this->load->view('general/header', $data);
        $this->load->view('home/of_the_day', $data);
        $this->load->view('general/footer');
      }
      else{
        $action = $this->input->post('action');
        $val = $this->input->post('val');

        if(!empty($action) && !empty($val)) {
          if($action == 'card') {
            $data = array('card' => $val, 'time' => 'now');
            $this->card_otd->add($data);
          }
          if($action == 'storyboard') {
            $data = array('storyboard' => $val, 'time' => 'now');
            $this->storyboard_otd->add($data);
          }
          echo 'ok';
        }
        else{
          echo 'ko';
        }

      }
    }
  }
}