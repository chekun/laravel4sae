<?php namespace Lavender\Cloud\Sina\View\Engines;

use Illuminate\View\Engines\PhpEngine;
use Cache;

class SaePhpEngine extends PhpEngine {

	/**
	 * Get the evaluated contents of the view at the given path.
	 *
	 * @param  string  $__path
	 * @param  array   $__data
	 * @return string
	 */
	protected function evaluatePath($__path, $__data)
	{
		ob_start();

		extract($__data);

		// We'll evaluate the contents of the view inside a try/catch block so we can
		// flush out any stray output that might get out before an error occurs or
		// an exception is thrown. This prevents any partial views from leaking.
		try
	    {
		    $template = Cache::get('blade_'.$__path);
			eval('?>' . $template);
		}
		catch (\Exception $e)
		{
			$this->handleViewException($e);
		}

		return ob_get_clean();
	}

}