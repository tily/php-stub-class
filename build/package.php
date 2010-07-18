<?php

require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$package = new PEAR_PackageFileManager2();
$package->setOptions(array(
    'filelistgenerator' => 'file',
    'packagedirectory'  => dirname(__FILE__) . '/../',
    'baseinstalldir'    => '/',
    'simpleoutput'      => true,
    'ignore'            => array('Rakefile', 'build/'),
    'exceptions'        => array('README' => 'doc'),
    'dir_roles'         => array('PHPStubClass' => 'php', 'example' => 'doc', 'doc' => 'doc', 'test' => 'test')
));
$package->setPackageType(          'php');
$package->addRelease();
$package->generateContents();
$package->setPackage(              'PHPStubClass');
$package->setChannel(              'pear.php.net');
$package->setReleaseVersion(       '0.0.1');
$package->setAPIVersion(           '0.0.1');
$package->setReleaseStability(     'beta');
$package->setAPIStability(         'beta');
$package->setSummary(              'utility to rewrite class as stub for unit test using reflection and runkit');
$package->setDescription(          'utility to rewrite class as stub for unit test using reflection and runkit');
$package->setNotes(                'Initial release');
$package->setPhpDep(               '5.1.0');
$package->setPearinstallerDep(     '1.4.0a12');
$package->addMaintainer(           'lead', 'tily', 'tily', 'tily05@gmail.com');
$package->setLicense(              'PHP License', 'http://www.php.net/license');

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    $package->writePackageFile();
} else {
    $package->debugPackageFile();
}
?>
