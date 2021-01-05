<?

namespace App\Boilerplate;

use App\Boilerplate\FileCollector;


/**
 * This class is about leveraging the PHP engine’s in-memory file caching (opcache) to cache application data in addition to code
 * OPcache improves PHP performance by storing precompiled script bytecode in shared memory, thereby removing the need for PHP to load and parse scripts on each request.
 * 
 * @usage
 $opcache = OpcacheManager::getInstance();
 $opcache->set('foo', ['bar']);
 $opcache->set('lorem', ['ipsum']);
 $opcache->set('dolor', ['sit', 'amet']);
 $opcache->get('lorem');
 $opcache->delete('foo');
 $opcache->clearAll();
 */
class OpcacheManager
{

    protected static $mainInstance;

    public $filepath;
    public function __construct(string $filepath = './cache/', $fileEsension = 'opcache')
    {
        $this->filepath = $filepath;
        $this->fileExtension = $fileEsension;
    }

    public static function getInstance () {
        if (!self::$mainInstance) {
            self::$mainInstance = new self();
        }
        return self::$mainInstance;
    }

    /**
     * @param array|object $val
     */
    public function set(string $key, $val) {
        if (strpos($key, '.') !== false) {
            throw new \Exception('The $key parameter\'s value must not contain the dot (.) character for purpose of extension deconfliction');
        }
        $path = "$this->filepath$key";
        $contentValue = var_export($val, true);
        $contentValue = str_replace('stdClass::__set_state', '(object)', $contentValue);
        $tmp = $path.uniqid('', false).uniqid('-', false).".$this->fileExtension.tmp";
        $isFilePut = file_put_contents($tmp, '<?php $val = ' . $contentValue . ';', LOCK_EX);
        rename($tmp, $path.".$this->fileExtension");
        return $fileput !== false;
    }
    
    public function get($key) {
        @include("$this->filepath$key.$this->fileExtension");
        // $val is written in the cache file
        return isset($val) ? $val : null;
    }

    public function delete($key) {
        return unlink("$this->filepath$key.$this->fileExtension");
    }

    protected function removeFiles(bool $clearNonTemporary = true, bool $clearTemporary = true) {
        $lookupExtensions = [];
        if ($clearNonTemporary) {
            $lookupExtensions[] = $this->fileExtension;
        }
        if ($clearTemporary) {
            $lookupExtensions[] = "$this->fileExtension.tmp";
        }
        if (count($lookupExtensions) === 0) {
            throw new \Exception('clearFiles parameters for lookupExtensions must have at least one true value');
        }
        $result = [
            'total' => 0,
            'deleted' => 0,
        ];
        $fileCollector = new FileCollector(
            [$this->filepath],
            $lookupExtensions,
            false
        );
        $fileCollector->lookup();
        $result['total'] = count($fileCollector->getFileList());
        foreach ($fileCollector->getFileList() as $fileInfo) {
            if (unlink($fileInfo['fullPath'])) {
                $result['deleted']++;
            }
        }

        return $result;
    }

    public function clearAll ()
    {
        return $this->removeFiles(true, true);
    }

    public function clearTemporary ()
    {
        return $this->removeFiles(false, true);
    }

    public function clearNonTemporary ()
    {
        return $this->removeFiles(true, false);
    }
}
