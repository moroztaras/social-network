<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 8/2/2017
 * Time: 12:36 PM.
 */

namespace App\Components\Utils;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class SchemaReader
{
    private $schema;

    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getSchema($name = null)
    {
        if (!$this->schema) {
            $this->schema = [];
            $this->discovery();
        }
        if ($name) {
            return isset($this->schema[$name]) ? $this->schema[$name] : null;
        }

        return $this->schema;
    }

    protected function discovery()
    {
        $path = $this->kernel->getRootDir().'/Resources/schema';
        $finder = new Finder();
        $finder->files()->in($path);
        foreach ($finder as $file) {
            $fileName = $file->getBasename('.yml');
            $schema = Yaml::parse(file_get_contents($path.'/'.$fileName.'.yml'));
            $convertedArray = Common::convertArrayToSubArrays(explode('.', $fileName), $schema);
            $this->schema = array_merge_recursive($this->schema, $convertedArray);
        }
    }

    public function getKernel()
    {
        return $this->kernel;
    }
}
