<?php 

class Mustache_MyLoader extends Mustache_Loader_FilesystemLoader implements Mustache_Loader_MutableLoader
{
	private $templates = array();

	public function load($name)
	{
		if (!isset($this->templates[$name]))
		{
			return parent::load($name);
		}

		return $this->templates[$name];
	}

	public function setTemplates(array $templates)
	{
		$this->templates = array_merge($this->templates, $templates);
	}

	public function setTemplate($name, $template)
	{
		$this->templates[$name] = $template;
	}
}