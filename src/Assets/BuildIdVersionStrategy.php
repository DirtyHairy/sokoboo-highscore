<?php declare(strict_types=1);


namespace App\Assets;


use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * Class BuildIdVersionStrategy
 * @package App\Assets
 */
class BuildIdVersionStrategy implements VersionStrategyInterface
{
    private const BUILD_ID_KEY = "BUILD_ID";

    /**
     * Returns the asset version for an asset.
     *
     * @param string $path A path
     *
     * @return string The version string
     */
    public function getVersion($path): string
    {
        return array_key_exists(self::BUILD_ID_KEY, $_ENV) ? $_ENV[self::BUILD_ID_KEY] : "";
    }

    /**
     * Applies version to the supplied path.
     *
     * @param string $path A path
     *
     * @return string The versionized path
     */
    public function applyVersion($path): string
    {
        $version = $this->getVersion($path);

        return empty($version) ? $path : sprintf("%s?v=%s", $path, $this->getVersion($path));
    }
}