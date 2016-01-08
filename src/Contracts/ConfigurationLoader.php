<?php

namespace Relax\Loader\Contracts;

/**
 * Interface ConfigLoader.
 *
 * A ConfigLoader is just a FileLoader that is used to load configuration files.
 *
 * It is useful to have a separate interface for this:
 * - Classes can express their dependencies in a clearer way,
 *   especially when they need multiple FileLoaders, eg. both a ConfigLoader and a TemplateLoader.
 * - IOC containers can bind this specific interface to the corresponding (configuration loading)
 *   singleton which makes automatic dependency injection much easier.
 */
interface ConfigurationLoader extends FileLoader
{
}
