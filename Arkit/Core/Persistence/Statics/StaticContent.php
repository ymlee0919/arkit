<?PHP

namespace Arkit\Core\Persistence\Statics;

/**
 * Simple manager for static html pages.
 * Implements the singleton pattern
 */
class StaticContent 
{

    private static ?StaticContent $instance = null;

    private string $cacheDir;

    private int $cacheTime;

    /**
     * Constructor of the class. Initialize the directory for cache
     */
    private function __construct()
    {
        $this->cacheTime = 18000;
        $this->cacheDir = \Arkit\App::fullPath('resources/statics/');
        
        if(!is_dir($this->cacheDir))
            mkdir($this->cacheDir);

    }

    /**
     * Get the unique instance of the class
     *
     * @return StaticContent Unique instance of the class
     */
    public static function getInstance() : StaticContent
    {
        if(is_null(self::$instance))
            self::$instance = new StaticContent();

        return self::$instance;
    }

    /**
     * Send the current output to cache
     *
     * @return void
     */
    public function outputToCache()
    {
        $request = &\Arkit\App::$Request;

        $targetDir = self::getCacheDir($request->getRequestedDomain());
        if(!$targetDir)
            return;

        // Write content to cache
        $cacheFile = $this->getCacheFileName();
        $cached = fopen($cacheFile, 'w');
        fwrite($cached, ob_get_contents());
        fclose($cached);
    }

    /**
     * Send a cached response according the current request
     *
     * @return boolean
     */
    public function cacheToOutput() : bool
    {
        $targetDir = self::getCacheDir(\Arkit\App::$Request->getRequestedDomain());
        if(!$targetDir)
            return false;

        $cacheFile = $this->getCacheFileName();
        if(!file_exists($cacheFile))
            return false;

        if(time() - $this->cacheTime < filemtime($cacheFile))
        {
            echo "<!-- Cached copy, generated " . date('H:i', filemtime($cacheFile)) . " -->n";
            readfile($cacheFile);
            return true;
        }

        return false;
    }

    private function getCacheDir(string $domainName) : ?string
    {
        $targetDir = \Arkit\App::fullPath('resources/statics/' . strtolower($domainName));
        if (is_dir($targetDir))
            return $targetDir;

        // Create the target dir
        $success = @mkdir($targetDir);

        return (!!$success) ? $targetDir : null;
    }

    private function getCacheFileName() : string
    {
        $request = &\Arkit\App::$Request;

        $fileName = ($request->isAJAX()) ? '[AJAX]::' : '';
        $fileName = sha1($fileName . $request->getRequestUrl()) . '.html';

        return $fileName;
    }
}