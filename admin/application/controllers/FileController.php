<?php

class FileController extends Zend_Controller_Action {
	
	public function managerAction() {
		$this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
        require_once(APPLICATION_PATH . '/../library/Admin/elFinder.php');
        
        /*class elFinderLogger implements elFinderILogger {
			public function log($cmd, $ok, $context, $err='', $errorData = array()) {
				if (false != ($fp = fopen('./log.txt', 'a'))) {
					if ($ok) {
						$str = "cmd: $cmd; OK; context: ".str_replace("\n", '', var_export($context, true))."; \n";
					} else {
						$str = "cmd: $cmd; FAILED; context: ".str_replace("\n", '', var_export($context, true))."; error: $err; errorData: ".str_replace("\n", '', var_export($errorData, true))."\n";
					}
					fwrite($fp, $str);
					fclose($fp);
				}
			}
			
		}*/
		
		$opts = array(
			'root'            => APPLICATION_PATH . '/../../public/img/data/',                       // path to root directory
			'URL'             => Admin_Config::get('site_url') . 'img/data/', // root directory URL
			'rootAlias'       => 'data',       // display this instead of root directory name
			'uploadAllow'   => array('image'),
			'uploadDeny'    => array('all'),
			'uploadOrder'   => 'deny,allow',
			// 'disabled'     => array(),      // list of not allowed commands
			// 'dotFiles'     => false,        // display dot files
			// 'dirSize'      => true,         // count total directories sizes
			// 'fileMode'     => 0666,         // new files mode
			// 'dirMode'      => 0777,         // new folders mode
			// 'mimeDetect'   => 'internal',       // files mimetypes detection method (finfo, mime_content_type, linux (file -ib), bsd (file -Ib), internal (by extensions))
			//'imgLib'       => 'mogrify',       // image manipulation library (imagick, mogrify, gd)
			 //'tmbDir'       => '.tmb',       // directory name for image thumbnails. Set to "" to avoid thumbnails generation
			// 'tmbCleanProb' => 1,            // how frequiently clean thumbnails dir (0 - never, 100 - every init request)
			// 'tmbAtOnce'    => 5,            // number of thumbnails to generate per request
			// 'tmbSize'      => 48,           // images thumbnails size (px)
			// 'fileURL'      => true,         // display file URL in "get info"
			// 'dateFormat'   => 'j M Y H:i',  // file modification date format
			// 'logger'       => null,         // object logger
			// 'defaults'     => array(        // default permisions
			// 	'read'   => true,
			// 	'write'  => true,
			// 	'rm'     => true
			// 	),
			// 'perms'        => array(),      // individual folders/files permisions    
			// 'debug'        => true,         // send debug to client
			// 'archiveMimes' => array(),      // allowed archive's mimetypes to create. Leave empty for all available types.
			// 'archivers'    => array()       // info about archivers to use. See example below. Leave empty for auto detect
			// 'archivers' => array(
			// 	'create' => array(
			// 		'application/x-gzip' => array(
			// 			'cmd' => 'tar',
			// 			'argc' => '-czf',
			// 			'ext'  => 'tar.gz'
			// 			)
			// 		),
			// 	'extract' => array(
			// 		'application/x-gzip' => array(
			// 			'cmd'  => 'tar',
			// 			'argc' => '-xzf',
			// 			'ext'  => 'tar.gz'
			// 			),
			// 		'application/x-bzip2' => array(
			// 			'cmd'  => 'tar',
			// 			'argc' => '-xjf',
			// 			'ext'  => 'tar.bz'
			// 			)
			// 		)
			// 	)
		);
		
		$fm = new elFinder($opts); 
		$fm->run();
	}
}