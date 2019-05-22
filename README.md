![Packlink logo](https://pro.packlink.es/public-assets/common/images/icons/packlink.svg)

# Packlink Magento 2 plugin - developer instructions

## Commit procedure
**The following steps must be completed before each commit.**

### Run unit tests
Configuration for phpunit is in the `./phpunit.xml` file.

If you haven't already done so, you can setup unit tests in PHPStorm.
To do so, first go to `File > Settings > Languages & Frameworks > PHP > Test Frameworks` and 
add new PHPUnit Local configuration. Select `Use composer autoloader` and in the field below navigate to your Magento 
installation folder and select `/vendor/autoload.php` file.

Go to `Run > Edit configuration` menu and add new PHPUnit configuration. 
For Test Runner options select `Defined in configuration file` and add specific phpunit configuration 
file path to the `./phpunit.xml` file in module's root directory.

Create new file `./Packlink/PacklinkPro/Test/autoload.php` by copying the file
`./Packlink/PacklinkPro/Test/autoload-sample.php`. In the newly created file change path to the magento's root folder,
for example `/var/www/html/magento/app/bootstrap.php`.

Now test configuration is set and you can run tests by activating run command from the 
top right toolbar. 

**All tests must pass.**

### Install coding standards tool
If you haven't done so, install Magento Code Sniffer.
```
composer create-project --repository=https://repo.magento.com magento/marketplace-eqp magento-coding-standard
```

### Run code fixer
Run code fixer on base code.
```
magento-coding-standard/vendor/bin/phpcbf ./Packlink/PacklinkPro/ --standard=MEQP2

```
This will fix all common problems. 

### Run code sniffer
Run code sniffer.
```
magento-coding-standard/vendor/bin/phpcs ./Packlink/PacklinkPro/ --standard=MEQP2 --severity=10
```
If there is no output, all is fine. Otherwise, correct the reported errors.

## Release new version

### Prepare module
Make sure that version in `./Packlink/PacklinkPro/composer.json` file is set to a new version number.
Make sure DB "setup_version" in `./Packlink/PacklinkPro/etc/module.xml` file is set to a new version number.

Add change log / release notes in `./CHANGELOG.md` file.

### Validate package for release
If you haven't done so, install Magento Marketplace Tools:
```
git clone https://github.com/magento/marketplace-tools.git
```

Run checker to validate package:
```
php marketplace-tools/validate_m2_package.php -d ./PluginInstallation/[version]/PacklinkPro.zip
```
### Create module package
Run 
```
sh deploy.sh
```
This will make a proper packages in the `PluginInstallation` folder based on the current module version.
