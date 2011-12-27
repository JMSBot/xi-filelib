<?php

namespace Xi\Filelib\Storage;

use \Xi\Filelib\FileLibrary,
    \Xi\Filelib\FilelibException,
    \Xi\Filelib\Storage\Storage,
    \Xi\Filelib\Storage\AbstractStorage,
    \Xi\Filelib\File\File,
    \Xi\Filelib\Plugin\VersionProvider\VersionProvider
    ;

class MultiStorage extends AbstractStorage implements Storage
{
    /**
     * @var array
     */
    private $storages = array();
    
    /**
     * @var integer Session storage id for fetch operations
     */
    private $sessionStorageId;
    
    public function addStorage(Storage $storage)
    {
        if ($storage instanceof MultiStorage) {
            throw new FilelibException('MultiStorage cannot contain a MultiStorage');
        }
        
        $this->storages[] = $storage;
    }
    
    /**
     * Returns an array of inner storages
     * 
     * @return array 
     */
    public function getStorages()
    {
        return $this->storages;
    }
        
    /**
     *
     * @param int $sessionStorage Set session storage 
     */
    public function setSessionStorageId($sessionStorageId)
    {
        $this->sessionStorageId = $sessionStorageId;
    }
    
    public function getSessionStorageId()
    {
        return $this->sessionStorageId;
    }
    
    
    /**
     * Returns session storage 
     * 
     * @return Storage
     */
    public function getSessionStorage()
    {
        if(!$this->storages) {
            throw new FilelibException('MultiStorage has no inner storages. Can not get session storage.');
        }
        
        if(!$sessionStorageId = $this->getSessionStorageId()) {
            $sessionStorageId = array_rand($this->storages);
            $this->setSessionStorageId($sessionStorageId);
        }
        
        return $this->storages[$this->getSessionStorageId()];
    }
    
    
    
    public function store(File $file, $tempFile)
    {
        foreach ($this->getStorages() as $storage) {
            $storage->store($file, $tempFile);
        }        
    }
    
    public function storeVersion(File $file, VersionProvider $version, $tempFile)
    {
        foreach ($this->getStorages() as $storage) {
            $storage->storeVersion($file, $version, $tempFile);
        }        
    }
    
    public function retrieve(File $file)
    {
        return $this->getSessionStorage()->retrieve($file);
    }
    
    public function retrieveVersion(File $file, VersionProvider $version)
    {
        return $this->getSessionStorage()->retrieveVersion($file, $version);
    }
    
    public function delete(File $file)
    {
        foreach ($this->getStorages() as $storage) {
            $storage->delete($file);
        }        
    }
    
    public function deleteVersion(File $file, VersionProvider $version)
    {
        foreach ($this->getStorages() as $storage) {
            $storage->deleteVersion($file, $version);
        }        
    }
    
}