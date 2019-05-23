![Packlink logo](https://pro.packlink.es/public-assets/common/images/icons/packlink.svg)

# Packlink Magento 2 plugin

## Installation instructions
Plugin can be installed directly from Magento 2 Marketplace.

There are also options to install the module via composer or to perform manual installation.

### Composer installation
For composer installation you will need your Magento marketplace credentials.
Check [user guide](https://devdocs.magento.com/guides/v2.3/install-gde/prereq/connect-auth.html) 
for information on obtaining them.

Magento 2 module can be installed with [Composer](https://getcomposer.org/download/).
If module is not available on the Magento Marketplace, module location can be set
in the `composer.json` file of the Magento root. This can be done by running a command:
```bash
composer config repositories.repo-name vcs https://github.com/logeecom/pl_module_core
composer config repositories.repo-name vcs https://github.com/logeecom/pl_magento2_module
```

If this is done successfully, module can be added with this command:
```bash
composer require packlink/magento2
```

### Manual installation
To manually install the extension, follow these steps:

**Step 1:** Extract the content of the `packlink-manual.zip` file and 
upload Packlink folder to your Magento shop `app/code/` directory 
(copy the whole folder) so you have the folders `app/code/Packlink/PacklinkPro`

**Step 2:** Disable the cache in admin panel under _System > Cache Management_

**Step 3:** Enter the following at the command line as magento console user (upgrade database):
```bash
php bin/magento setup:upgrade
```

**Step 4:** Enter the following at the command line (generate dependency map):
```bash
php bin/magento setup:di:compile
```

**Step 5:** Enter the following at the command line (deploy static content):
```bash
php bin/magento setup:static-content:deploy
```
**Step 6:** Optionally you might need to fix permissions on your Magento files if
the previous steps were ran as a `root` or other non-magento console user

**Step 7:** After opening _Stores > Configuration > Advanced > Advanced_, the module will be shown in the admin panel

After installation is over, Packlink configuration can be accessed with _Sales > Packlink PRO_ menu.

## Uninstall instructions
### Marketplace installation
If module is installed through the Magento Marketplace, module can be uninstalled
from the _System > Web Setup Wizard > Extension manager_.

### Composer installation
If module is installed via composer, run this command to uninstall it:
```bash
php bin/magento module:uninstall Packlink_PacklinkPro
```

### Manual installation
In a case where module is installed manually, some manual actions are also required to remove the module.
**Step 1:** Disable module by running this command from the Magento root folder and as a magento console user:
```bash
php bin/magento module:disable Packlink_PacklinkPro
```

**Step 2:** Remove module files
```bash
rm -rf app/code/Packlink
```

**Step 3:** Delete module data from database:
```sql
DELETE FROM `setup_module` WHERE `module` = 'Packlink_PacklinkPro';
DROP TABLE `packlink_entity`;
```

**Step 4:** Lastly, rebuild Magento's code:
```bash
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

## Version
dev

## Compatibility
Magento 2.1.x, 2.2.x and 2.3.x versions

## Prerequisites
- PHP 5.6 or newer
- MySQL 5.6 or newer (integration tests)
