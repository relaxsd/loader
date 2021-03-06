<?php

namespace Relax\Loader\FileLoader;

use Relax\Loader\Contracts\ConfigurationFileLoader as ConfigurationFileLoaderInterface;

/**
 * Class ConfigurationFileLoader.
 *
 * A ConfigurationFileLoader is just a FileLoader that is used to load configuration files.
 *
 * Although it add nothing to FileLoader, it is useful to have a separate class+interface for this:
 * - Classes can express their dependencies in a clearer way,
 *   especially when they need multiple FileLoaders, eg. both a ConfigurationFileLoader and a TemplateFileLoader.
 * - IOC containers can bind this specific interface to a ConfigurationFileLoader instance which makes
 *   automatic dependency injection much easier.
 */
class ConfigurationFileLoader extends FileLoader implements ConfigurationFileLoaderInterface
{
}
