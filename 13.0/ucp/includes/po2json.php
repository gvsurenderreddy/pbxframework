<?php
/**
 * translate po file to je file obejct
 * @author Anakeen
 * @license http://creativecommons.org/licenses/by-nc-sa/2.0/fr/ Anakeen - licence CC
 */

class po2json {
	public $pofile = "";
	public $entry = array();
	private $encoding = 'utf-8';
	private $lang = 'en';
	private $pluralForms = 'nplurals=2; plural=n != 1;';

	function __construct($pofile) {
		$this->pofile=$pofile;
	}

	private function parseEntry(&$out) {
		$out = preg_replace("/(?m)\[BLOCK\s*([^\]]*)\](.*?)\[ENDBLOCK\s*\\1\]/se", "\$this->memoEntry('\\1','\\2')", $out);
	}

	private function trimquote($s) {
		return trim($s,'"');
	}

	public function memoEntry($key,$text) {
		$tkey=explode("\n",$key);
		$ttext=explode("\n","$text");
		$key=trim(implode("\n",array_map(array($this,"trimquote"),$tkey)));
		$text=trim(implode("\n",array_map(array($this,"trimquote"),$ttext)));
		if ($key && $text) {
			$this->entry[$key]=array(null, $text);
		} else if ($key=="") {
			if (stristr($text,"charset=ISO-8859") !== false) {
				$this->encoding='iso';
			}
			if(preg_match('/Language: (.*)/m',$text,$matches)) {
				$this->lang = trim(str_replace("\\n","",$matches[1]));
			}
			if(preg_match('/Plural-Forms: (.*)/m',$text,$matches)) {
				$this->pluralForms = trim(str_replace("\\n","",$matches[1]));
			}
		}
	}

	private function process() {
		if (file_exists($this->pofile)) {
			$pocontent=file_get_contents($this->pofile);
			if ($pocontent) {
				$pocontent.="\n\n";
				preg_match_all('/^msgid (?P<msgid>".*?)msgstr (?P<msgstr>".*?")\n\n/ms', $pocontent, $matches, PREG_SET_ORDER);
				foreach($matches as $m) {
					$this->memoEntry($m['msgid'], $m['msgstr']);
				}
			}
		}
	}

	private function finalize() {
		dbug("finalize");
		$this->process();
		$this->entry[""] = array(
			"domain" => "messages",
			"lang" => $this->lang,
			"plural_forms" => $this->pluralForms
		);
	}

	public function po2json() {
		if (count($this->entry) > 0) {
			$this->finalize();
			$js=json_encode($this->entry);
			if ($this->encoding=="iso") {
				$js=utf8_encode($js);
			}
			return $js;
		} else {
			return "";
		}
	}

	public function po2array() {
		return json_decode($this->po2json(),true);
	}
}
