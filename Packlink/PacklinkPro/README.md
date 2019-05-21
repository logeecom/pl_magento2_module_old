![Packlink logo](https://pro.packlink.es/public-assets/common/images/icons/packlink.svg)

# Packlink Magento 2 plugin

## Installation instructions
Plugin can be installed directly from Magento 2 Marketplace.

### Composer installation
Magento 2 module can be installed with Composer (https://getcomposer.org/download/).
Go to your shop installation root and require integration with composer:
```
coposer require packlink/magento2
```

### Manual installation
To manually install the extension, follow these steps:

- Step 1: Extract the content of the `packlink-manual.zip` file and 
upload Packlink folder to your Magento shop `app/code/` directory 
(copy the whole folder) so you have the folders `app/code/Packlink/PacklinkPro`
- Step 2: Disable the cache in admin panel under _System > Cache Management_
- Step 3: Enter the following at the command line as magento console user (upgrade database):
  ```
  php bin/magento setup:upgrade
  ```
- Step 4: Enter the following at the command line (generate dependency map):
  ```
  php bin/magento setup:di:compile
  ```
- Step 5: Enter the following at the command line (deploy static content):
  ```
  php bin/magento setup:static-content:deploy
  ```
- Step 6: Optionally you might need to fix permissions on your Magento files if
the previous steps were ran as a `root` or other non-magento console user
- Step 7: After opening _Stores > Configuration > Advanced > Advanced_, the module will be shown in the admin panel

After installation is over, Packlink configuration can be accessed with _Sales > Packlink PRO_ menu.

## Version
dev

## Compatibility
Magento 2.1.x, 2.2.x and 2.3.x versions

## Prerequisites
- PHP 5.6 or newer
- MySQL 5.6 or newer (integration tests)
