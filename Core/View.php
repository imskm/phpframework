<?php

namespace Core;

/**
 * Base View class
 */
class View
{
	private $views_path;
	private $content;
	private $section;
	private $template = "";
	private $template_args;

	/**
	 * Constructor to instantiate class
	 *
	 * @param string  Views path
	 */
	public function __construct($view_path)
	{
		$this->views_path = $view_path;
	}

	/**
	 * Render a view file
	 *
	 * @param string $view  The  view file
	 * @return void
	 */

	public function render($view, $args = [])
	{
		$this->extractArgs($args);
		$file = $this->views_path . DS . $view;	

		if(is_readable($file))
		{
			$this->setContent($file);
			if ($this->isTemplateUsed()) {
				$this->renderTemplate();
			}
		}
		else
		{
			throw new \Exception("$file not found!");
		}
	}

	public function use($template, array $args = array())
	{
		if (! is_readable($this->views_path . DS . $template)) {
			throw new \Exception("Template file $template not found!");
		}

		$this->template = $this->views_path . DS . $template;
		$this->template_args = $args;
	}

	protected function renderTemplate()
	{
		extract($this->template_args);
		// $this->extractArgs($this->template_args);
		// echo $this->template; exit;
		ob_start();
		// var_dump($this->template_args);
		require $this->template;

		////////////////////////
		// NEED TO WORK ON IT.
		// MAKE IT EFFICIENT IN MEMORY USEAGE.
		// LEARN BUFFER STACKING FOR RESPONDING HTML
		////////////////////////

		$content = ob_get_contents();
		ob_end_clean();

		echo $content;
	}

	protected function setContent($file)
	{
		ob_start();
		require $file;
		$this->content = ob_get_contents();
		ob_end_clean();
	}

	public function content()
	{
		echo $this->content;
		return;
	}

	public function section($section)
	{

	}

	protected function extractArgs(& $args)
	{
		extract($args, EXTR_SKIP);
	}

	public function escape($data = "")
	{
		if (! $data) {
			return "";
		}

		return e($data);
	}

	public function e($data = "")
	{
		return $this->escape($data);
	}

	protected function isTemplateUsed()
	{
		return $this->template !== "";
	}
}
