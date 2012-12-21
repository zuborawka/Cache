<?php

App::uses('CacheAppController', 'Cache.Controller');

class ClearController extends CacheAppController
{
	public $components = array('Session');

	public function admin_app() {
		return $this->index('app');
	}

	public function admin_core() {
		return $this->index('core');
	}

	public function admin_index($location = 'app', $afterProcess = false) {
		switch ($location) {
			case 'core':
				$root = CAKE_CORE_INCLUDE_PATH;
				break;
			case 'app':
			default:
				$root = APP;
				break;
		}
		if ($this->_clear($root)) {
			$msg = 'All temporaly files are removed.';
		} else {
			$msg = 'Temporaly files aren\'t removed.';
		}

		if ($afterProcess){
			$this->_end($msg);
		}
	}

	protected function _clear($path) {
		$dh = opendir($path);
		while (($entry = readdir($dh)) !== false) {
			if ($entry === '.' || $entry === '..') {
				continue;
			}

			$fullPath = $path . DS . $entry;
			if (is_dir($fullPath)) {
				$this->_clear($fullPath);
			} elseif ($this->_beRemoved($path, $entry)) {
				@unlink($fullPath);
			}
		}
		return true;
	}

	protected function _beRemoved($path, $entry) {
		if (strpos($entry, '.')) {
			return false;
		}
		if ($entry == 'empty') {
			return false;
		}
		if (!strpos($path, DS . 'tmp' . DS . 'cache' . DS)) {
			return false;
		}
		return true;
	}

	protected function _end($msg, $redirect = null) {
		$this->Session->setFlash($msg);
		$redirect = $this->request->referer();
		$this->redirect($redirect);
	}
}
