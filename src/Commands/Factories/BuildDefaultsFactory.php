<?php

namespace Bfg\OpenDoc\Commands\Factories;

use Bfg\OpenDoc\Traits\UI;

/**
 * Class BuildModelFactory
 * @package Bfg\OpenDoc\Commands
 */
class BuildDefaultsFactory extends Factory
{
    use UI;

    public function files(): array
    {
        $files = [
            'buildReadMe' => 'read-me',
            'buildDependencies' => 'dependencies',
        ];

        if (
            is_file(base_path('package-lock.json'))
            || is_file(base_path('yarn.lock'))
        ) {
            $files['buildNpmDependencies'] = 'npm-dependencies';
        }

        return $files;
    }

    protected function buildReadMe(): string
    {
        return $this->phpHeader('readme', 'started', 'Read me')
            . $this->markdown(file_get_contents(base_path('README.md')));
    }

    protected function buildDependencies(): string
    {
        $php = $this->phpHeader('php-dependencies', 'started', 'PHP Dependencies', 'PHP Dependencies');
        $php .= $this->markdown('A list of all the dependencies used in the project.
This list is generated from the `composer.json` and `composer.lock` files.
The table below shows the package name, version, and description of each dependency.
The description is taken from the `composer.lock` file, if available.
If not, it is left empty. The table is generated using the `composer.json` and `composer.lock` files.');
        $php .= $this->nl();

        $headers = ['Package', 'Version', 'Description'];
        $data = [];

        $composerData = json_decode(file_get_contents(base_path('composer.json')), true);
        $composerLockDataFile = json_decode(file_get_contents(base_path('composer.lock')), true);
        $composerLockData = collect($composerLockDataFile['packages']);

        foreach ($composerData['require'] as $dependency => $version) {

            $dependencyDetail = $composerLockData->firstWhere('name', $dependency);

            if ($dependencyDetail) {
                $data[] = [$dependency, $dependencyDetail['version'], $dependencyDetail['description'] ?? ''];
            } else {
                $data[] = [$dependency, $version, ''];
            }
        }

        $data2 = [];

        $composerLockData = collect($composerLockDataFile['packages-dev']);

        foreach ($composerData['require-dev'] as $dependency => $version) {

            $dependencyDetail = $composerLockData->firstWhere('name', $dependency);

            if ($dependencyDetail) {
                $data2[] = [$dependency, $dependencyDetail['version'], $dependencyDetail['description'] ?? ''];
            } else {
                $data2[] = [$dependency, $version, ''];
            }
        }

        return $php . $this->createBootstrapTabs([
            ['title' => 'Required', 'content' => $this->createBootstrapTable($data, $headers)],
            ['title' => 'Required DEV', 'content' => $this->createBootstrapTable($data2, $headers)],
        ]);
    }

    protected function buildNpmDependencies()
    {
        $php = $this->phpHeader('npm-dependencies', 'started', 'NPM Dependencies', 'NPM Dependencies');
        $php .= $this->markdown('A list of all the dependencies used in the project.
This list is generated from the `package.json` and `package-lock.json` files.
The description is taken from the `package-lock.json` file, if available.
If not, it is left empty. The table is generated using the `package.json` and `package-lock.json` files.');
        $php .= $this->nl();

        $headers = ['Package', 'Version'];
        $data = [];

        $packageData = json_decode(file_get_contents(base_path('package.json')), true);
        $packageLockData = json_decode(file_get_contents(base_path('package-lock.json')), true);

        foreach (($packageData['dependencies'] ?? []) as $dependency => $version) {

            $dependencyDetail = $packageLockData['packages']['node_modules/' . $dependency] ?? null;

            if ($dependencyDetail) {
                $data[] = [$dependency, $dependencyDetail['version']];
            } else {
                $data[] = [$dependency, $version];
            }
        }

        $data2 = [];

        foreach (($packageData['devDependencies'] ?? []) as $dependency => $version) {

            $dependencyDetail = $packageLockData['packages']['node_modules/' . $dependency] ?? null;

            if ($dependencyDetail) {
                $data2[] = [$dependency, $dependencyDetail['version']];
            } else {
                $data2[] = [$dependency, $version];
            }
        }

        $tabs = [];

        if (count($data)) {
            $tabs[] = ['title' => 'Required', 'content' => $this->createBootstrapTable($data, $headers)];
        }

        if (count($data2)) {
            $tabs[] = ['title' => 'Required DEV', 'content' => $this->createBootstrapTable($data2, $headers)];
        }

        return $php . $this->createBootstrapTabs($tabs);
    }
}
