<?php

class FilemanagerController extends Zend_Controller_Action {
    public function uploadAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		$json = array();
		
		if (isset($_FILES['imagefile']) && $_FILES['imagefile']['tmp_name']) {
			$filename = basename(html_entity_decode($_FILES['imagefile']['name'], ENT_QUOTES, 'UTF-8'));
				
			$directory = rtrim(realpath(APPLICATION_PATH . '/../public/img/data/users'));
				
				
			if ($_FILES['imagefile']['size'] > 2048000) {
				$json['error'] = $this->translate('Size error');
			}
				
			$allowed = array(
				'image/jpeg',
				'image/pjpeg',
				'image/png',
				'image/x-png',
				'image/gif'
			);
						
			if (!in_array($_FILES['imagefile']['type'], $allowed)) {
				$json['error'] = $this->translate('Type error');
			}
				
			$allowed = array(
				'.jpg',
				'.jpeg',
				'.gif',
				'.png'
			);
						
			if (!in_array(strtolower(strrchr($filename, '.')), $allowed)) {
				$json['error'] = $this->translate('Type error');
			}

			if ($_FILES['imagefile']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = 'error_upload_' . $this->request->files['image']['error'];
			}			
		} else {
			$json['error'] = $this->translate('File error');
		}
		
		
		if (!isset($json['error'])) {	
			$oldName = pathinfo($filename);
			$newName = str_ireplace(array('.', ' '), '', microtime()) . '.' . $oldName['extension'];
			if (@move_uploaded_file($_FILES['imagefile']['tmp_name'], $directory . '/' . $newName)) {	
				
				$image_id = $this->model('user')->addImage($this->view->user->user_id, 'users/' . $newName);
					
				$json['success'] = $this->translate('Image uploaded');
				$json['filename'] = 'users/' . $newName;
				
				//$json['image'] = Site_Image::resize('users/' . $newName, 196, 196);
				$json['image_small'] = Site_Image::resize('users/' . $newName, 67, 67);
				$json['image_big'] = Site_Image::resize('users/' . $newName, 271, 271);
				$json['image_id'] = $image_id;
			} else {
				$json['error'] = $this->translate('Upload error');
			}
		}
		
		$this->getResponse()->setBody(json_encode($json));
    }
}

