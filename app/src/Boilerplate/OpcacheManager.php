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

    /** @var OpcacheManager|null */
    protected static $mainInstance;

    /** @var string  */
    public $filepath;

    /**
     * OpcacheManager constructor.
     * @param string $filepath
     * @param string $fileEsension
     */
    public function __construct(string $filepath = './cache/', $fileEsension = 'opcache')
    {
        $this->filepath = $filepath;
        $this->fileExtension = $fileEsension;
    }

    /**
     * @return OpcacheManager|null
     */
    public static function getInstance () {
        if (!self::$mainInstance) {
            self::$mainInstance = new static();
        }
        return self::$mainInstance;
    }

    /**
     * @param string $key
     * @param array|object $val
     * @return bool
     * @throws \Exception
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
        return $isFilePut !== false;
    }

    /**
     * @param string $key
     * @return null
     */
    public function get(string $key) {
        @include("$this->filepath$key.$this->fileExtension");
        // $val is written in the cache file
        return isset($val) ? $val : null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key) {
        return unlink("$this->filepath$key.$this->fileExtension");
    }

    /**
     * @param bool $clearNonTemporary
     * @param bool $clearTemporary
     * @return array
     * @throws GraphQL\Exception\FileNotFoundException
     */
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

    /**
     * @return array
     * @throws GraphQL\Exception\FileNotFoundException
     */
    public function clearAll ()
    {
        return $this->removeFiles(true, true);
    }

    /**
     * @return array
     * @throws GraphQL\Exception\FileNotFoundException
     */
    public function clearTemporary ()
    {
        return $this->removeFiles(false, true);
    }

    /**
     * @return array
     * @throws GraphQL\Exception\FileNotFoundException
     */
    public function clearNonTemporary ()
    {
        return $this->removeFiles(true, false);
    }
}
