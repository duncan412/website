<?php

namespace App\Assets;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;

class CustomVersionStrategy implements VersionStrategyInterface
{
    private $version;
    private $file_name = 'version.yaml';
    private $format;

    /**
     * VersionStrategy constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, $format = '%s?v=%s')
    {
        $enviroment = $container->getParameter('kernel.environment');
        $rootFolder = $container->getParameter('kernel.project_dir');
        $configFolder = $rootFolder . '/config/custom';

        $this->format = $format;

        if ($enviroment === 'dev') {
            $this->version = time();
        } else {
            $this->version = $this->setVersion($configFolder);
        }
    }

    /**
     * @param string $configFolder
     * @return string
     */
    private function setVersion($configFolder): string
    {
        if (!\is_dir($configFolder)) {
            mkdir($configFolder);
        }

        $fileLocator = new FileLocator([$configFolder]);

        try {
            $versioning = $fileLocator->locate($this->file_name);
        } catch (FileLocatorFileNotFoundException $exception) {
            $this->createFile($configFolder);
            $versioning = $fileLocator->locate($this->file_name);
        }

        $values = Yaml::parseFile($versioning);

        if (!isset($values['version']) || empty($values['version'])) {
            $this->createFile($configFolder);
            $values = Yaml::parseFile($versioning);
        }

        return $values['version'];
    }

    /**
     * @param string $configFolder
     */
    private function createFile($configFolder): void
    {
        $data = [
            'version' => time()
        ];

        $yaml = Yaml::dump($data);
        file_put_contents($configFolder . '/' . $this->file_name, $yaml);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getVersion($path): string
    {
        return $this->version;
    }

    /**
     * @param string $path
     * @return string
     */
    public function applyVersion($path): string
    {
        return sprintf($this->format, $path, $this->getVersion($path));
    }
}
