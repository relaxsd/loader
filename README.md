# loader
[![Build Status](https://travis-ci.org/relaxsd/loader.svg?branch=master)](https://travis-ci.org/relaxsd/loader)

## Filesystem, Finder and Loader classes for easy file loading.

This PHP package provides a file finder and loader implementations, similar to the Laravel 4
`Illuminate.View.Factory`, `Illuminate.Translation.Translator` and `Illuminate.Config.Repository`.

It was written to be used in Laravel but it has no dependencies on Illuminate or Symfony.
It contains a very basic Filesystem interface and implementation. You can (re)use or extend that to your needs.

## Installation

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `relax/loader`.

	"require": {
		"relax/loader": "~1.0"
	}

