<?php

namespace Core;

/**
 * Base View class
 */
class View
{
	/**
	 * Render a view file
	 *
	 * @param string $view  The  view file
	 * @return void
	 */

	public static function render($view, $args = [])
	{
		extract($args, EXTR_SKIP);
		$file = ".." . DS . "App" . DS . "Views" . DS . $view;	// Relative to Core directory

		if(is_readable($file))
		{
			require $file;
		}
		else
		{
			throw new \Exception("$file not found!");
		}
	}
}
