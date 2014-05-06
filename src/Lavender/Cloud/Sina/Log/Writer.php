<?php namespace Lavender\Cloud\Sina\Log;

use Illuminate\Log\Writer as IlluminateLogWriter;
use Monolog\Formatter\LineFormatter;
use Lavender\Cloud\Sina\Log\SaeLogHandler;
use Config;

class Writer extends IlluminateLogWriter {

    protected function useSaeLog($level = 'debug')
    {
        $level = $this->parseLevel($level);
        $this->monolog->pushHandler($handler = new SaeLogHandler($level));
        $handler->setFormatter(new LineFormatter(null, null, true));
    }

    public function useFiles($path, $level = 'debug')
    {
        if (Config::get('app.sae')) {
            return $this->useSaeLog($level);
        }

        parent::useFiles($path, $level);
    }

    public function useDailyFiles($path, $days = 0, $level = 'debug')
    {
        if (Config::get('app.sae')) {
            return $this->useSaeLog($level);
        }

        parent::useDailyFiles($path, $days, $level);
    }

}
