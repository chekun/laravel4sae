<?php namespace Lavender\Cloud\Sina\View\Compilers;

use Illuminate\View\Compilers\BladeCompiler;

class SaeBladeCompiler extends BladeCompiler {
    
    /**
	 * Compile the view at the given path.
	 *
	 * @param  string  $path
	 * @return void
	 */
	public function compile($path)
	{
		$contents = $this->compileString($this->files->get($path));

		$this->cachePath->put('blade_'.md5($path), $contents, 0);
	}

	public function getCompiledPath($path)
	{
		return md5($path);
	}
    
}