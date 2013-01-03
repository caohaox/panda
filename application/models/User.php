<?php 

class Application_Model_User extends Site_Db {	
	
	public function getUser($user_id) {
		$query = $this->db->select();
		
		
		$query->from(array('u' => 'user'), array('u.*', 'fullname' => 'CONCAT(u.name, " ", u.lastname)', 'fullname_eng' => 'CONCAT(u.name_eng, " ", u.lastname_eng)'));
		
		
		$query->where('u.user_id = ?', $user_id)
//			  ->where('u.status = 1')
			  ->where('u.block <> 1');

		$return = $this->db->fetchRow($query);
		
		if($return) {
			if($return['birth_date'] != '0000-00-00') {
				$now = new Zend_Date();
				$birth = new Zend_Date($return['birth_date']);
				
				$age  = $now->get(Zend_Date::YEAR) - $birth->get(Zend_Date::YEAR);
				
	         	$diffMonth = $now->get(Zend_Date::MONTH) - $birth->get(Zend_Date::MONTH);
	         	$diffDay   = $now->get(Zend_Date::DAY) - $birth->get(Zend_Date::DAY);
	         	
		        if ($diffMonth < 0){
		        	$age--;
		        }elseif	(($diffMonth == 0) && ($diffDay < 0)) {
		        	$age--;
		        }
				$return['age'] = $age;
			}else $return['age'] = false;
		}
		
		$db_options = new Application_Model_Profileoption();
		
		$return['region'] = $db_options->getRegion($user_id, $return['is_man']);
		$return['partner_age'] = $db_options->getPartnerAge($user_id);
		
		unset($return['password']);
		return $return;
	}
	
	
	public function getImages($user_id) {
		$query = $this->db->select()
						  ->from('user_image')
						  ->where('user_id = ?', $user_id);


		return $this->db->fetchAll($query);
	}
	
	
	private function getRelatedOptions($options) {
		$option_values = array();
		foreach($options as $option_value) {
			$res = $this->db->fetchRow($this->db->select()
								->from('related_option_values', 'value2')
								->where('value1 = ?', $option_value));
			if($res['value2']) {		
				$option_values[] = $res['value2'];
			}
		}
		
		return $option_values;
	}
	
	
	public function getUsers($data) {
		
		if(isset($data['options']['age_from'])) {
			$age_from = $data['options']['age_from'];
			unset($data['options']['age_from']);
		}
		if(isset($data['options']['age_to'])) {
			$age_to = $data['options']['age_to'];
			unset($data['options']['age_to']);
		}
		
		if(isset($data['options']) && $data['options']) {
			$option_values = $this->getRelatedOptions($data['options']);
		}
		//var_dump($option_values);
		$query = $this->db->select();
		
		$query->from(array('u' => 'user'), array('u.*', 'fullname' => 'CONCAT(u.name_eng, " ", u.lastname_eng)'));
		$query->joinLeft(array('s' => 'sessions'), 'u.session_id = s.session_id', array('s.modified'));
		
		if(isset($data['options']) && $data['options']) {
			//$query->joinLeft(array('uov' => 'user_option_value'), 'u.user_id = uov.user_id');
			
			foreach($option_values as $key => $opt_val) {
			//$query->where('uov.profile_option_value_id IN (?)', implode(',', $option_values));
				$query->where("(SELECT COUNT(*) FROM user_option_value WHERE user_id = u.user_id AND profile_option_value_id = " . $opt_val . ")");
			}
		}
		
		//поиск по возрасту старый вариант
		if(isset($data['options'][Zend_Registry::get('partner_age')])) {
			$age = $this->db->fetchRow($this->db->select()->from('profile_option_value_description', array('name'))->where('language_id = ?', Zend_Registry::get('language_id'))->where('profile_option_value_id = ?', $data['options'][Zend_Registry::get('partner_age')]));
			$age = explode('-', $age['name']);
			
			if(isset($age[1])) {
				$from = new Zend_Date();
				$till = new Zend_Date();

				$query->where("u.birth_date BETWEEN '" . $from->subYear((int)$age[1])->toString("YYYY-MM-dd") . "' AND '" . $till->subYear((int)$age[0])->toString("YYYY-MM-dd") . "'");
			}
		}
		//новый
		if(isset($age_from) || isset($age_to)) {
			if(isset($age_from) && !isset($age_to)) {
				$from = new Zend_Date();
				$query->where("u.birth_date < '" . $from->subYear((int)$age_from)->toString("YYYY-MM-dd") . "'");
			}elseif(!isset($age_from) && isset($age_to)) {
				$till = new Zend_Date();
				$query->where("u.birth_date >= '" . $till->subYear((int)$age_to)->toString("YYYY-MM-dd") . "'");
			}else {
				$from = new Zend_Date();
				$till = new Zend_Date();
				$query->where("u.birth_date BETWEEN '" . $from->subYear((int)$age_to)->toString("YYYY-MM-dd") . "' AND '" . $till->subYear((int)$age_from)->toString("YYYY-MM-dd") . "'");
			}
		}
		
		//поиск по росту
		if(isset($data['options'][Zend_Registry::get('partner_height')])) {
			$height = $this->db->fetchRow($this->db->select()->from('profile_option_value_description', array('name'))->where('language_id = ?', Zend_Registry::get('language_id'))->where('profile_option_value_id = ?', $data['options'][Zend_Registry::get('partner_height')]));
			$height = explode('-', $height['name']);
			
			if(isset($height[1])) {
				$query->where("u.height BETWEEN '" . (int)$height[0] . "' AND '" . (int)$height[1] . "'");
			}
		}
		
		
		$query->where('u.status = 1')
			  ->where('u.step1 = 1')
			  ->where('u.step2 = 1')
			  ->where('u.step3 = 1')
			  ->where('u.block <> 1');
			  
		if(isset($data['onlyWomen']) && $data['onlyWomen']) $query->where('u.is_man = 0');
		if(isset($data['onlyMen']) && $data['onlyMen']) $query->where('u.is_man = 1');
		
		$query->order('s.modified DESC');
		
		return $query;
	}
	
	public function getTotalUsers($data) {
		
		if(isset($data['options']) && $data['options']) {
			$option_values = $this->getRelatedOptions($data['options']);
		}
		
		$query = $this->db->select()
						  ->from(array('u' => 'user'), array('count' => 'COUNT(*)'));
						  
		if(isset($data['options']) && $data['options']) {
			foreach($option_values as $key => $opt_val) {
				
				$query->where("(SELECT COUNT(*) FROM user_option_value WHERE user_id = u.user_id AND profile_option_value_id = " . $opt_val . ")");
			}
		}

		//поиск по возрасту
		if(isset($data['options'][Zend_Registry::get('partner_age')])) {
			$age = $this->db->fetchRow($this->db->select()->from('profile_option_value_description', array('name'))->where('language_id = ?', Zend_Registry::get('language_id'))->where('profile_option_value_id = ?', $data['options'][Zend_Registry::get('partner_age')]));
			$age = explode('-', $age['name']);
			
			if(isset($age[1])) {
				$from = new Zend_Date();
				$till = new Zend_Date();

				$query->where("u.birth_date BETWEEN '" . $from->subYear((int)$age[1])->toString("YYYY-MM-dd") . "' AND '" . $till->subYear((int)$age[0])->toString("YYYY-MM-dd") . "'");
			}
		}
		//новый
		if(isset($age_from) || isset($age_to)) {
			if(isset($age_from) && !isset($age_to)) {
				$from = new Zend_Date();
				$query->where("u.birth_date < '" . $from->subYear((int)$age_from)->toString("YYYY-MM-dd") . "'");
			}elseif(!isset($age_from) && isset($age_to)) {
				$till = new Zend_Date();
				$query->where("u.birth_date >= '" . $till->subYear((int)$age_to)->toString("YYYY-MM-dd") . "'");
			}else {
				$from = new Zend_Date();
				$till = new Zend_Date();
				$query->where("u.birth_date BETWEEN '" . $from->subYear((int)$age_to)->toString("YYYY-MM-dd") . "' AND '" . $till->subYear((int)$age_from)->toString("YYYY-MM-dd") . "'");
			}
		}
		
		//поиск по росту
		if(isset($data['options'][Zend_Registry::get('partner_height')])) {
			$height = $this->db->fetchRow($this->db->select()->from('profile_option_value_description', array('name'))->where('language_id = ?', Zend_Registry::get('language_id'))->where('profile_option_value_id = ?', $data['options'][Zend_Registry::get('partner_height')]));
			$height = explode('-', $height['name']);
			
			if(isset($height[1])) {
				$query->where("u.height BETWEEN '" . (int)$height[0] . "' AND '" . (int)$height[1] . "'");
			}
		}
		
		 
						  
				    $query->where('u.status = 1')
				    	  ->where('u.step1 = 1')
			  			  ->where('u.step2 = 1')
			  			  ->where('u.step3 = 1')
						  ->where('u.block <> 1');
		if(isset($data['onlyWomen']) && $data['onlyWomen']) $query->where('u.is_man = 0');
		if(isset($data['onlyMen']) && $data['onlyMen']) $query->where('u.is_man = 1');
		
		$row = $this->db->fetchRow($query);
		return (int)$row['count'];
	}
	
	
	/**
	 * return true if email already exists
	 */
	public function emailValidate($email) {
		$view = Zend_Registry::get('view');
		
		$query = $this->db->select()
						  ->from('user')
						  ->where('email = ?', $email);
						  
		if($view->user) $query->where('user_id <> ?', $view->user->user_id);
						  
		if($this->db->fetchRow($query)) return true;
		return false;
	}
	
	
	public function addGenleman($data) {
		
	}
	
	public function addWoman($data) {
		$translate = Zend_Registry::get('translate');

		unset ($data['password_confirm']);
		unset ($data['agree']);
		unset ($data['captcha']);
		unset ($data['women']);
		unset ($data['genderG']);
		unset ($data['countryG']);
		$data['password'] = md5(strtolower($data['password']));
		$data['register_date'] = new Zend_Db_Expr('NOW()');
		$data['is_man'] = 0;
		
		$this->db->insert('user', $data);
		$user_id = $this->db->lastInsertId();
		$code = md5(mt_rand());
		$this->db->insert('confirm_code', array('user_id' => $user_id, 'code' => $code));
		
		$this->sendMail($data['email'], $code);
		
		return $user_id;
	}
	
	public function addGentleman($data) {
		$translate = Zend_Registry::get('translate');

		unset ($data['password_confirm']);
		unset ($data['agree']);
		unset ($data['captcha']);
		unset ($data['gentle']);
		unset ($data['genderG']);
		unset ($data['countryG']);
		$data['password'] = md5(strtolower($data['password']));
		$data['register_date'] = new Zend_Db_Expr('NOW()');
		$data['is_man'] = 1;
		
		$this->db->insert('user', $data);
		$user_id = $this->db->lastInsertId();
		$code = md5(mt_rand());
		$this->db->insert('confirm_code', array('user_id' => $user_id, 'code' => $code));
		
		$this->sendMail($data['email'], $code);

		
		return $user_id;
	}
	
	private function sendMail($email, $code) {
		
		$translate = Zend_Registry::get('translate');
		
		$sendMail = new Zend_Mail('UTF-8');
		$sendMail->setSubject($translate->_('RegisterMailTitle'));
		$sendMail->addTo($email);
		$sendMail->setFrom(Site_Config::get('admin_email'), Site_Config::get('site_name'));
		$sendMail->setBodyHtml(html_entity_decode(sprintf($translate->_('RegisterMailText'), Site_Config::get('site_name'), Site_Config::get('site_url') . 'register/confirm/code/' . $code, Site_Config::get('site_url') . 'register/confirm/' . $code), ENT_QUOTES, 'UTF-8'));
		$sendMail->send();
	}
	
	public function resendRegisterMail($user_id) {
		$user = $this->db->fetchRow($this->db->select()->from('user')->where('user_id = ?', $user_id));
		
		$code = $this->db->fetchRow($this->db->select()->from('confirm_code')->where('user_id = ?', $user_id));
		
		if($code['code']) {
			$this->sendMail($user['email'], $code['code']);
		}
	}
	
	public function confirm($code) {
		$query = $this->db->select()
						  ->from('confirm_code', 'user_id')
						  ->where('code = ?', $code);
		$result = $this->db->fetchRow($query);
		if($result) {
			$this->db->update('user', array('status' => 1), 'user_id = ' . (int)$result['user_id']);

			$this->db->delete('confirm_code', 'user_id = ' . (int)$result['user_id']);
			
			return $result['user_id'];
		}
		return false;
	}
	
	
	public function fillStep1($user_id, $data) {
		
		if((int)$data['height'] < 1) $data['height'] = '';
		//if((int)$data['weight'] < 1) $data['weight'] = '';
		
		if(!$data['image'] && isset($data['image2']) && $data['image2']) {
			$data['image'] = $data['image2'];
			unset($data['image2']);
		}
		
		$this->db->update('user', array(
			'name'			=> $data['name'],
			'lastname'		=> $data['lastname'],
			'name_eng'		=> $data['name_eng'],
			'lastname_eng'	=> $data['lastname_eng'],
			'skype'			=> $data['skype'],
			//'city'			=> $data['city'],
			'height'		=> $data['height'],
			//'weight'		=> $data['weight'],
			'telephone'		=> $data['telephone'],
			'childs'		=> $data['children']['childs'],
			'birth_date'	=> $data['year'] . '-' . (($data['month'] < 10)?'0' . $data['month']:$data['month']) . '-' . (($data['day'] < 10)?'0' . $data['day']:$data['day']) ,
			'image'			=> $data['image']?('users/' . $data['image']):'',
			'step1'			=> 1,
		), "user_id = '" . (int)$user_id . "'");
		
		if($data['image']) $this->db->insert('user_image', array('image' => 'users/' . $data['image'], 'user_id' => $user_id));
		if(isset($data['image2']) && $data['image2']) $this->db->insert('user_image', array('image' => 'users/' . $data['image2'], 'user_id' => $user_id));
		
		$this->setLanguages($user_id, $data['languages']);
		if($data['children']['childs']) {
			$this->setChildren($user_id, $data['children']);
		}else $this->db->delete('user_children', "user_id = '" . (int)$user_id . "'");
		
		$this->setProfileOptions($user_id, $data);
	}
	
	
	private function setLanguages($user_id, $languages) {
		$this->db->delete('user_to_language', "user_id = '" . (int)$user_id . "'");
		if(!empty($languages)) {
			$insert = array();
			foreach($languages as $key => $value) {
				if(strpos($key, 'language') !== false) {
					$count = explode('language', $key);
					if(isset($count[1])) {
						$insert[$count[1]]['language'] = $value;
					}
				}elseif(strpos($key, 'skill') !== false) {
					$count = explode('skill', $key);
					if(isset($count[1])) {
						$insert[$count[1]]['skill'] = $value;
					}
				}
			}

			foreach($insert as $in) {
				if(!empty($in['language']) && !empty($in['skill'])) {
					//$this->db->replace('user_to_language', array('user_id' => $user_id, 'all_language_id' => $in['language'], 'skill_id' => $in['skill']));
					$this->db->query("REPLACE INTO user_to_language SET user_id = '" . (int)$user_id . "', all_language_id = '" . (int)$in['language'] . "', skill_id = '" . (int)$in['skill'] . "'");
				}
			}
		}
	}
	
	private function setChildren($user_id, $children) {
		$this->db->delete('user_children', "user_id = '" . (int)$user_id . "'");
		if(!empty($children)) {
			$insert = array();
			foreach($children as $key => $value) {
				
				$count = explode('child', $key);
				//такая хрень получается с подформами
				if(isset($count[1]) && $count[1] != 's' && isset($value['children'][$key])) {
					$insert[$count[1]] = $value['children'][$key];
				}
			}

			foreach($insert as $in) {
				//var_dump($in);
				if(!empty($in['name']) && !empty($in['child_age']) && isset($in['together'])) {
					
					$this->db->query("
						REPLACE INTO user_children 
						SET 
							user_id = '" . (int)$user_id . "', 
							name = " . $this->db->quote($in['name']) . ", 
							child_age = '" . (int)$in['child_age'] . "', 
							together = " . (int)$in['together'] . "");
				}
			}
		}
	}
	
	
	public function fillStep2($user_id, $data) {
		$this->db->update('user', array('step2'	=> 1), "user_id = '" . (int)$user_id . "'");
		$this->setProfileOptions($user_id, $data);
	}
	
	
	public function fillStep3($user_id, $data) {
		$this->db->update('user', array('step3'	=> 1), "user_id = '" . (int)$user_id . "'");
		$this->setProfileOptions($user_id, $data);
	}
	
	//функция для заполнения шагов
	private function setProfileOptions($user_id, $data) {
		
		//$this->db->delete('user_option_value', "user_id = '" . (int)$user_id . "'");
		
		foreach($data as $optionKey => $option_value_id) {
			if(strpos($optionKey, 'profile_select') !== false) {
				$option = explode('profile_select', $optionKey);
				if(isset($option[1])) {
					$this->db->delete('user_option_value', "user_id = '" . (int)$user_id . "' AND profile_option_id = '" . (int)$option[1] . "'");
					if(!empty($option_value_id)) {
						$this->db->insert('user_option_value', array('user_id' => $user_id, 'profile_option_id' => $option[1], 'profile_option_value_id' => $option_value_id));
					}
				}
			}
		}
		
		foreach($data as $optionKey => $option_values) {
			if(strpos($optionKey, 'profile_checkbox') !== false) {
				$option = explode('profile_checkbox', $optionKey);
				if(isset($option[1])) {
					$this->db->delete('user_option_value', "user_id = '" . (int)$user_id . "' AND profile_option_id = '" . (int)$option[1] . "'");
					if(!empty($option_values)) {
						foreach($option_values as $option_value_id) {
							$this->db->insert('user_option_value', array('user_id' => $user_id, 'profile_option_id' => $option[1], 'profile_option_value_id' => $option_value_id));
						}
					}
				}
			}
		}
	}
	
	//а эта для редактирования пользователя(все поля в одной форме)
	private function updateProfileOptions($user_id, $data) {
		
		$this->db->delete('user_option_value', "user_id = '" . (int)$user_id . "'");
		
		foreach($data as $optionKey => $option_value_id) {
			if(strpos($optionKey, 'profile_select') !== false) {
				$option = explode('profile_select', $optionKey);
				if(isset($option[1])) {
					//$this->db->delete('user_option_value', "user_id = '" . (int)$user_id . "' AND profile_option_id = '" . (int)$option[1] . "'");
					if(!empty($option_value_id)) {
						$this->db->insert('user_option_value', array('user_id' => $user_id, 'profile_option_id' => $option[1], 'profile_option_value_id' => $option_value_id));
					}
				}
			}
		}
		
		foreach($data as $optionKey => $option_values) {
			if(strpos($optionKey, 'profile_checkbox') !== false) {
				$option = explode('profile_checkbox', $optionKey);
				if(isset($option[1])) {
					//$this->db->delete('user_option_value', "user_id = '" . (int)$user_id . "' AND profile_option_id = '" . (int)$option[1] . "'");
					if(!empty($option_values)) {
						foreach($option_values as $option_value_id) {
							$this->db->insert('user_option_value', array('user_id' => $user_id, 'profile_option_id' => $option[1], 'profile_option_value_id' => $option_value_id));
						}
					}
				}
			}
		}
	}
	
	
	public function getLanguagesByUserId($user_id) {
		$sql = $this->db->select()
						->from(array('u2l' => 'user_to_language'))
						->joinLeft(array('aln' => 'all_languages_names'), 'u2l.all_language_id = aln.all_language_id', array('language_name' => 'aln.name'))
						->joinLeft(array('sn' => 'skills_names'), 'u2l.skill_id = sn.skill_id', array('skill_name' => 'sn.name'))
						->where('sn.language_id = ?', Zend_Registry::get('language_id'))
						->where('aln.language_id = ?', Zend_Registry::get('language_id'))
						->where('u2l.user_id = ?', $user_id);
						
		return $this->db->fetchAll($sql);
	}
	
	public function getChildrenByUserId($user_id) {
		$sql = $this->db->select()
						->from(array('uc' => 'user_children'))
						->where('uc.user_id = ?', $user_id);
						
		return $this->db->fetchAll($sql);
	}
	
	
	public function update($user_id, $data) {
		
		if((int)$data['step1']['height'] < 1) $data['step1']['height'] = '';
		//if((int)$data['step1']['weight'] < 1) $data['step1']['weight'] = '';
		
		$this->db->update('user', array(
			//'email' 		=> $data['step1']['email'], 
			//'telephone' 	=> $data['step1']['telephone'], 
			//'skype' 		=> $data['step1']['skype'], 
			'city'			=> $data['step1']['city'], 
			//'home'			=> $data['step1']['home'], 
			'height'		=> $data['step1']['height'], 
			//'weight'		=> $data['step1']['weight'], 
			'birth_date' 	=> $data['step1']['year'] . '-' . (($data['step1']['month'] < 10)?'0' . $data['step1']['month']:$data['step1']['month']) . '-' . (($data['step1']['day'] < 10)?'0' . $data['step1']['day']:$data['step1']['day'])
		), "user_id = '" . (int)$user_id . "'");
		
		$this->updateProfileOptions($user_id, array_merge($data['step1'], $data['step2'], $data['step3']));
		
		$this->setLanguages($user_id, $data['step1']['languages']);
	}
	
	public function updateSettings($user_id, $data) {

		$this->db->update('user', array(
			'email' 		=> $data['email'], 
			'telephone' 	=> $data['telephone'], 
			'skype' 		=> $data['skype'], 
			'home'			=> $data['home'], 
			'notification'	=> $data['notification'], 
			'notification_in_days'	=> $data['notification_in_days'], 
		), "user_id = '" . (int)$user_id . "'");
		
		
	}
	
	
	public function addImage($user_id, $image) {
		$user = $this->getUser($user_id);
		if(!$user['image']) {
			$this->db->update('user', array('image' => $image), "user_id = '" . (int)$user_id . "'");
		}
		
		$this->db->insert('user_image', array('image' => $image, 'user_id' => $user_id));
		
		return $this->db->lastInsertId();
	}
	
	public function setMainImage($user_id, $image) {
		
		$this->db->update('user', array('image' => $image), "user_id = '" . (int)$user_id . "'");
		
	}
	
	public function removeImage($user_id, $image_id) {
		$user = $this->getUser($user_id);
		$sql = $this->db->select()
						->from('user_image', 'image')
						->where('image_id = ?', $image_id);
		
		$image = $this->db->fetchRow($sql);
		
		$this->db->delete('user_image', "image_id = '" . (int)$image_id . "' AND user_id = '" . (int)$user_id . "'");
		
		//если это был главный рисунок, меняем главный рисунок на первый из списка
		if($user['image'] == $image['image']) {
			
			$sql = $this->db->select()
						->from('user_image', 'image')
						->where('user_id = ?', $user_id)
						->limit(1);
		
			$first_image = $this->db->fetchRow($sql);
			
			$this->db->update('user', array('image' => $first_image['image']), "user_id = '" . (int)$user_id . "'");
		}
	}
	
	
	public function getRandomImages($count = 10, $gender = '') {
		//берем случайный из 5-и кешей, которые в свою очередь тоже случайно берут пользователей
		$rnd = array(1,2,3,4,5);
		
		$hash = md5(http_build_query(array('count' => $count, 'gender' => $gender, 'rand' => $rnd[array_rand($rnd)])));
		
		$cache = Zend_Registry::get('cache');
		$data = $cache->load('random_faces' . $hash);
		
		if(!$data) {
			$sql = $this->db->select()
							->from('user', array('image'));
							
			if($gender == 'onlyWomen') {
				$sql->where('is_man = 0');
			}elseif($gender == 'onlyMen') {
				$sql->where('is_man = 1');
			}
			
			$sql->where("image <> ''");
			$sql->where("image IS NOT NULL");
			$sql->where("status = 1");
			$sql->where("step1 = 1");
			$sql->where("step2 = 1");
			$sql->where("step3 = 1");
			$sql->where("home = 1");
			$sql->where("block = 0");
			$sql->order('RAND()');
			$sql->limit($count);
			
			$data = $this->db->fetchAll($sql);
			$cache->save($data, 'random_faces' . $hash);
		}
		
		return $data;
	}
	
	
	//check if email exists and generate restore code
	public function forgetPasswordStep1($data) {
		$query = $this->db->select()
						  ->from('user', 'user_id')
						  ->where('email = ?', $data['email']);
						  
		$user = $this->db->fetchRow($query);
		if(!$user) return false;
		
		$code = md5(mt_rand());
		$this->db->insert('password_restore', array(
											'code' 		=> $code,
											'user_id'	=> $user['user_id'],
											'date_added'=> new Zend_Db_Expr('NOW()')
											));
											
		return $code;
	}
	
	
	public function forgetPasswordStep2($data, $user_id) {
		unset($data['password_confirm']);
		unset($data['captcha']);
		$data['password'] = md5($data['password']);
		$where = "user_id = '" . (int)$user_id . "'";
		
		$this->db->update('user', $data, $where);
		$this->db->delete('password_restore', $where);
	}
	
	
	public function checkRestoreCode($code) {
		$query = $this->db->select()
						  ->from(array('pr' => 'password_restore'), array('pr.user_id'))
						  ->joinLeft(array('u' => 'user'), 'pr.user_id = u.user_id')
						  ->where('pr.code = ?', $code)
						  ->where(new Zend_Db_Expr('(pr.date_added + INTERVAL 1 DAY) > NOW()'));
						  
		$result = $this->db->fetchRow($query);
		if($result) return $result;
		return false;
	}
	
	
	public function setSessionId($user_id, $session_id) {
		$this->db->update('user', array('session_id' => $session_id), "user_id = '" . (int)$user_id . "'");
	}
	
	
	public function getOnlineStatus($user_id) {
		$sql = $this->db->select()
						  ->from(array('u' => 'user'), array())
						  ->joinLeft(array('s' => 'sessions'), 'u.session_id = s.session_id', array('s.modified'))
						  ->where('u.user_id = ?', $user_id);

		$result = $this->db->fetchRow($sql); 
		if((time() - $result['modified']) > 70) {
			return false;
		}
		return true;
	}
}