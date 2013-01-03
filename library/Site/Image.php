<?php 
class Site_Image {
	
	public function resize($filename, $width, $height, $watermark = false, $default_image = 'no_image.jpg') {
		defined('DIR_IMAGE')
    		|| define('DIR_IMAGE', realpath(APPLICATION_PATH . '/../public/img/data') . '/');
		defined('HTTP_IMAGE')
    		|| define('HTTP_IMAGE', Site_Config::get('site_url') . 'img/data/');
		
    	if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename) || !filesize(DIR_IMAGE . $filename) || (!getimagesize(DIR_IMAGE . $filename))) {
			$filename = $default_image;
		}
    		
		$info = pathinfo($filename);
		$extension = $info['extension'];
		
		$old_image = $filename;
		$new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;
		
		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}
			
			$image = new Site_Image_Library(DIR_IMAGE . $old_image);
			$image->resize($width, $height);
			$image->save(DIR_IMAGE . $new_image);
		}
		
		return HTTP_IMAGE . $new_image;
			
	}
}