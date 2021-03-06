<?php

namespace Relax\Loader\Contracts;

/**
 * Interface TemplateFileLoader.
 *
 * A TemplateFileLoader is just a FileLoader that is used to load template files.
 *
 * Although it add nothing to FileLoader, it is useful to have a separate class+interface for this:
 * - Classes can express their dependencies in a clearer way,
 *   especially when they need multiple FileLoaders, eg. both a TemplateFileLoader and a ConfigurationFileLoader.
 * - IOC containers can bind this specific interface to a TemplateFileLoader instance which makes
 *   automatic dependency injection much easier.
 */
interface TemplateFileLoader extends FileLoader
{
}
