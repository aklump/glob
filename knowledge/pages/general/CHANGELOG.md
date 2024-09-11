<!--
id: changelog
tags: ''
-->

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

- Nothing to list

## [0.0.9] - 2024-09-11

### Changed

- `\AKlump\Glob\Glob::glob` will no longer return the same instance on subsequent calls as this was found to be unstable. If you want to leverage file-caching as previously, you should instantiate an instance and use that, e,g. `$glob = new Glob(); $glob('./*.php'); $glob('./*.html').`

### Fixed

- File changes after the first call to ` \AKlump\Glob\Glob::glob` would not be detected during subsequent calls due to caching, which led to incorrect results.
