<?php

class CacheStr {
   /**
    * $dir : 缓存文件存放目录
    * $lifetime : 缓存文件有效期,单位为秒
    * $cacheid : 缓存文件路径,包含文件名
    * $ext : 缓存文件扩展名(可以不用),这里使用是为了查看文件方便
   */
   private $dir;
   private $lifetime;
   private $cacheid;
   private $filename;
   /**
    * 析构函数,检查缓存目录是否有效,默认赋值
   */
   function __construct($dir='',$filename='',$lifetime=1800) {
       if ($this->dir_isvalid($dir)) {
           $this->dir = $dir;
           $this->filename = $filename;
           $this->lifetime = $lifetime;
           $this->cacheid = $this->dir.$this->filename;
       }
   }
   /**
    * 检查缓存是否有效
   */
   private function isvalid() {
       if (!file_exists($this->cacheid)) return false;
       if (!(@$mtime = filemtime($this->cacheid))) return false;
       if (mktime() - $mtime > $this->lifetime) return false;
       return true;
   }

   /**
    * 加载缓存
    * exit() 载入缓存后终止原页面程序的执行,缓存无效则运行原页面程序生成缓存
    * ob_start() 开启浏览器缓存用于在页面结尾处取得页面内容
   */
   public function load() {
       if ($this->isvalid()) {
           echo "<span style='display:none;'>This is Cache.</span> ";
           return file_get_contents($this->cacheid);
           exit();
       }
       else {
           ob_start();
       }
   }
      /**
    * 写入缓存
    * $mode == 0 , 以浏览器缓存的方式取得页面内容
    * $mode == 1 , 以直接赋值(通过$content参数接收)的方式取得页面内容
    * $mode == 2 , 以本地读取(fopen ile_get_contents)的方式取得页面内容(似乎这种方式没什么必要)
   */
   public function write($mode=0,$content='') {
   	   if ($this->isvalid()) { exit();}
       switch ($mode) {
           case 0:
               $content = ob_get_contents();
               break;
           default:
               break;
       }
       ob_end_flush();
       try {
           file_put_contents($this->cacheid,$content);
       }
       catch (Exception $e) {
           $this->error('写入缓存失败!请检查目录权限!');
       }
   }
   /**
    * 检查目录是否存在或是否可创建
    */
   private function dir_isvalid($dir) {
       if (is_dir($dir)) return true;
       try {
           mkdir($dir,0777);
       }
       catch (Exception $e) {
             $this->error('所设定缓存目录不存在并且创建失败!请检查目录权限!');
             return false;
       }
       return true;
   }

   /**
    * 输出错误信息
   */
   private function error($str) {
       echo '<div style="color:red;">'.$str.'</div>';
   }
}
?>
