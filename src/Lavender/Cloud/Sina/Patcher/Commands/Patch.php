<?php namespace Lavender\Cloud\Sina\Patcher\Commands;

use Illuminate\Console\Command;

class Patch extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sae:patch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Optimize the framework for better performance";

    /**
     * Files need to Patch
     * @var array
     */
    private $files = array(
        'vendor/patchwork/utf8/class/Patchwork/Utf8/Bootup.php',
        'vendor/symfony/http-foundation/Symfony/Component/HttpFoundation/Session/Storage/NativeSessionStorage.php'
    );

    public function fire()
    {
        $this->call('dump');

        $this->call('clear-compiled');

        $this->info('Patching files for sae.');
        
        foreach ($this->files as $file) {

            $code = file_get_contents($file);

            $code = str_replace('ini_set', '@ini_set', $code);

            file_put_contents($file, $code);

        }


    }

}