<?php if(!defined('IN_APP')) die();
/** 
 * yFood
 * /includes/template.class.php - Template parser class
 *
 * @author Joshua Piccari, Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
class template {
	public  $activelink;
	public  $ptitle;

	private $title;
	private $content;
	private $page;
	private $template_web_dir;
	private $disabled = false;

	public function __construct() {
		global $config;

		$this->template_web_dir = str_replace($_SERVER['DOCUMENT_ROOT'], '', $config['template_folder']);
		ob_start();
	}
	
	public function __destruct() {
		if ($this->disabled === true) {
			print($this->content);
			return;
		}
		
		$this->content = ob_get_clean();
		
		$this->populate();

		echo $this->page;
	}

	public function disable() {
		$this->disabled = true;
	}

	private function populate() {
		global $config, $DB, $User;

		$this->title = !empty($this->ptitle) ? $this->title = $config['site_name'] . ' | ' . $this->ptitle : $this->title = $config['site_name'];

		$links = $this->createlinks($config['template_folder'] . './links.tpl');

		// Start up our template:
		ob_start();
		require($config['template_folder'] . '/template.tpl');
		$tmp = ob_get_clean();
		//$tmp   = file_get_contents($config['template_folder'] . '/template.tpl');

		$tmp = str_replace("<!--PAGE_TITLE-->", htmlentities($this->title), $tmp);
		$tmp = str_replace("<!--PAGE_LINKS-->", preg_replace("/([\r\n]+)/", "\\1" . preg_replace("/.*[\r\n]+(\t+)\<!--PAGE_LINKS-->.*/ms", "\\1", $tmp), $links), $tmp);
		$tmp = str_replace("<!--TEMPLATE_WEB_PATH-->", htmlentities($this->template_web_dir), $tmp);
		$tmp = str_replace("<!--PAGE_CONTENT-->", preg_replace("/([\r\n]+)/", "\\1" . preg_replace("/.*[\r\n]+(\t+)\<\!\-\-PAGE\_CONTENT\-\-\>.*/ms", "\\1", $tmp), $this->content), $tmp);
		$tmp = str_replace("<!--SITE_URL-->", htmlentities($config['site_url']), $tmp);

		$this->page = $tmp;
		unset($tmp);
	}

	private function createlinks($linkfile) {
		global $config;

		$links = '';

		foreach(file($linkfile) as $line) {
			if(substr($line, 0, 1) != '#') {
				list($link['name'], $link['url']) = explode('::', $line, 2);

				$link['name'] = htmlentities(trim($link['name']));
				$link['url']  = htmlentities(trim($link['url']));

				if($links != '') {
					$links .= "\n";
				}

				$links .= (strtolower($this->activelink) == strtolower($link['name'])) ? ("<a class=\"rounded\" href=\"{$config['site_url']}{$link['url']}\" id=\"active_nav\">{$link['name']}</a>") : ("<a class=\"rounded\" href=\"{$config['site_url']}{$link['url']}\">{$link['name']}</a>");
			}
		}

		return $links;
	}
}

?>