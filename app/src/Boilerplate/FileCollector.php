<?php

namespace App\Boilerplate;

use App\Boilerplate\GraphQL\Exception\FileNotFoundException;

class FileCollector
{

    /** @var  */
    protected $lookupDirectories;
    /** @var array  */
    protected $lookupExtensions;
    /** @var array  */
    protected $lookupExclusionFiles;
    /** @var array  */
    protected $lookupExclusionDirectories;
    /** @var bool  */
    protected $isLookupRecursive = true;
    /** @var string  */
    protected $dirSeparator;
    /** @var array  */
    protected $fileList = [];

    /**
     * FileCollector constructor.
     * @param $lookupDirectories
     * @param array $lookupExtensions
     * @param bool $isLookupRecursive
     */
    public function __construct($lookupDirectories, array $lookupExtensions = ['php'], bool $isLookupRecursive = true)
    {
        $this->lookupDirectories = $lookupDirectories;
        $this->isLookupRecursive = $isLookupRecursive;
        $this->lookupExtensions = $lookupExtensions;
        $this->lookupExclusionFiles = [];
        $this->lookupExclusionDirectories = [];
        $this->dirSeparator = DIRECTORY_SEPARATOR;
        $this->fileList = [];
    }

    public function getLookupHash ()
    {
        return hash('sha256', serialize($this));
    }

    /**
     * @return array
     */
    public function getFileList ()
    {
        return $this->fileList;
    }

    /**
     * Excludes files ot directories from being looked-up
     *
     * @param array $excludePaths array of file path or directory path. Directories are automatically recursive in their exclusion and will not include any file under it.
     * @return $this
     */
    public function addLookupExclusions(array $excludePaths) {
        foreach ($excludePaths as $exclude) {
            $exclude = realpath(rtrim($exclude, ''));
            if (is_dir($exclude)) {
                $this->lookupExclusionDirectories[md5($exclude)] = $exclude;
            }
            else {
                $this->lookupExclusionFiles[md5($exclude)] = $exclude;
            }
        }
        return $this;
    }

    /**
     * Synchronously Start looking up for files that are matching our lookup criterias.
     * Results found in $this->fileList
     * @todo cache filestructure
     * @return $this
     * @throws FileNotFoundException
     */
    public function lookup () {
        if (is_array($this->lookupDirectories)) {
            foreach ($this->lookupDirectories as $lookupDir) {
                $realPath = realpath($lookupDir);
                if (!file_exists($realPath)) {
                    throw new FileNotFoundException($lookupDir, 'Lookup Directory Not Found');
                }
                $this->listFilesInPath($realPath);
            }
        }
        return $this;
    }


    /**
     * @param $fileInfo
     * @return bool
     */
    protected function isFileExcluded ($fileInfo) : bool
    {
        $isExcluded = false;

        // Directories exclusions...
        foreach ($this->lookupExclusionDirectories as $excludeDir) {
            if (!$isExcluded && strpos($fileInfo['fullPath'], $excludeDir.$this->dirSeparator) === 0) {
                // Starts with an excluded directory !
                $isExcluded = true;
                break;
            }
        }

        // File exclusions...
        if (!$isExcluded) {
            $isExcluded = in_array($fileInfo['fullPath'], $this->lookupExclusionFiles);
        }

        return $isExcluded;
    }

    /**
     * @param $filepath
     * @return array
     */
    protected function getFileInfo ($filepath) : array
    {
        $filepath = realpath($filepath);
        $pathinfo = pathinfo($filepath);
        $result = [
            'fullPath' => $filepath,
            'fullPathMd5' => md5($filepath),
            'isDir' => is_dir($filepath),
            'isPHP' => (bool) (isset($pathinfo['extension']) && strtolower($pathinfo['extension']) === 'php'),
            'dirName' => $pathinfo['dirname'],
            'fileName' => $pathinfo['basename'],
            'fileNameWithoutExtension' => explode('.', $pathinfo['basename'])[0],
            'extension' => null,
        ];
        
        if (isset($pathinfo['extension']) && $pathinfo['extension'] !== '') {
            $result['extension'] = substr($result['fileName'], strpos($result['fileName'], '.') + 1);
        }
        
        return $result;
    }

    /**
     * Building a file list, recursivly or not.
     *
     * @param string $path
     * @return $this
     * @throws FileNotFoundException
     */
    protected function listFilesInPath (string $path)
    {
        $realpath = realpath($path);
        
        if ($this->isLookupRecursive && !is_dir($realpath)) {
            throw new FileNotFoundException($realpath);
        }

        $files = scandir($realpath);
        foreach ($files as $filepath) {
            if (in_array($filepath, ['.', '..'])) {
                continue;
            }
            $filePathInfo = $this->getFileInfo($realpath.$this->dirSeparator.$filepath);
            if (!$filePathInfo['isDir']) {
                if (
                        in_array($filePathInfo['extension'], $this->lookupExtensions)
                    &&  !$this->isFileExcluded($filePathInfo)
                ) {
                    $this->fileList[$filePathInfo['fullPathMd5']] = $filePathInfo;
                }
            } elseif ($this->isLookupRecursive) {
                $this->listFilesInPath($filePathInfo['fullPath']);
            }
        }
        
        return $this;
    }


}
