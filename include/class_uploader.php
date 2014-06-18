<?php
/**
 * StreamPHP Pager 文件上传助手
 */

class Uploader {
	private $controlName;						// 控件名称
	private $allowedMimeTypes = array();		// 允许上传文件的mime类型
	public $uploadPath;							// 文件上传指向路径
	private $isMulti = false;					// 是否是批量上传
	private $maxSize = -1;						// 允许上传的单个文件的最大字节限制
	private $allowedOverwrite = true;			// 允许同名覆盖文件
	public $files = array();					// 相应控件的全局变量$_FILES[]数组
	public $isUniqname = true;					// 是否生成唯一文件名
	public $uploaded;							// 已上传文件的文件路径

	/**
	 * 构造函数，传入HTML的文件上传控件名、上传路径、合法的mime类型
	 *
	 * @param string $controlName
	 * @param string $uploadPath
	 * @param array $allowedMimeTypes
	 */
	public function __construct($controlName, $uploadPath, $maxSize = -1, $allowedMimeTypes = array()) {
		$this->controlName = $controlName;
		$this->uploadPath = $uploadPath;
		$this->allowedMimeTypes = $allowedMimeTypes;
		$this->files = $this->controlName;
		$this->_checkIsMulti();
		$this->maxSize = $maxSize;
	}

	/**
	 * 判断是否是多文件上传
	 */
	private function _checkIsMulti() {
		if(is_array($this->files['name'])) {
			$this->isMulti = true;
		}
	}

	/**
	 * 返回相应控件的$_FILES[]全局变量数组
	 */
	public function getFilesInfo() {
		return $this->files;
	}

	/**
	 * 进行文件上传，成功则返回$_FILES[]全局变量数组
	 */
	public function upload() {
		if($this->isMulti == false) {
			if($this->files['error'] == UPLOAD_ERR_OK && is_uploaded_file($this->files['tmp_name'])) {
				$tmpName = $this->files['tmp_name'];
				$name = $this->isUniqname == false ? $this->files['name'] : $this->makeUniqname($this->files['name'],$this->getExt($this->files['name']));
				$desPath = $this->uploadPath . $name;
				$handle = move_uploaded_file($tmpName, $desPath);
				$this->uploaded = basename($desPath);
			}
		} else if($this->isMulti == true) {
			$handle = true;
			foreach($this->files['error'] as $k => $error) {
				if($error == UPLOAD_ERR_OK && is_uploaded_file($this->files['tmp_name'][$k])) {
					$tmpName = $this->files['tmp_name'][$k];
					$name = $this->isUniqname == false ? $this->files['name'][$k] : $this->makeUniqname($this->files['name'][$k],$this->getExt($this->files['name'][$k]));
					$desPath = $this->uploadPath . $name;
					$handle = $handle && move_uploaded_file($tmpName, $desPath);
					$this->uploaded[] = basename($desPath);
				}
			}
		}
		return $handle ? $this->uploaded : false;
	}

	/**
	 * 用MD5加密uniqid() + time() + $_SERVER['REMOTE_ADDR']生成HASH文件名
	 *
	 * @param string $ext
	 * @return string $filename
	 */
	public function makeUniqname($filename,$ext) {
		$filename = md5($filename.(time() + rand(0,999999))) . '.' . $ext;
		return $filename;
	}

	public function isUniqname($switch) {
		$this->isUniqname = $switch;
	}

	public function getExt($path, $includingDot = NULL) {
		$ext = $includingDot ? '.' . pathinfo($path, PATHINFO_EXTENSION) : pathinfo($path, PATHINFO_EXTENSION);
		return $ext;
	}

	public function chkSize() {
		if(! $this->isMulti) {
			return $this->files['size'] <= $this->maxSize;
		} else {
			$flag = true;
			foreach($this->files['size'] as $size) {
				$flag = $flag && $size <= $this->maxSize;
				if(! $flag) break;
			}
			return $flag;
		}
	}

	public function chkMimetype() {
		if(! $this->isMulti) {
		 	return in_array($this->files['type'], $this->allowedMimeTypes);
		} else {
			$flag = true;
			foreach($this->files['type'] as $type) {
				$flag = $flag && in_array($type, $this->allowedMimeTypes);
				if(! $flag) break;
			}
			return $flag;
		}
	}
}