# Yireo EmailOverride
Magento extension for overriding email templates in your own Magento theme.

More information: https://www.yireo.com/software/magento-extensions/email-override

You can install this module in various ways:

1) Download the MagentoConnect package from our site and upload it into your own Magento
Downloader application.

2) Download the Magento source archive from our site, extract the files and upload the
files to your Magento root. Make sure to flush the Magento cache. Make sure to logout 
once you're done.

3) Use modman to install the git repository for you:

    modman init
    modman clone https://github.com/yireo/Yireo_EmailOverride
    modman update Yireo_EmailOverride

4) Use composer. See below

## Instructions for using composer

Use composer to install this extension. First make sure to initialize composer with the right settings:

    composer -n init
    composer install --no-dev

Next, modify your local composer.json file:

    {
        "require": {
            "yireo/magento1-emailoverride": "dev-master",
            "magento-hackathon/magento-composer-installer": "*"
        },    
        "repositories":[
            {
                "packagist": false
            },
            {
                "type":"composer",
                "url":"https://packages.firegento.com"
            },
            {
                "type":"composer",
                "url":"https://satis.yireo.com"
            }
        ],
        "extra":{
            "magento-root-dir":"/path/to/magento",
            "magento-deploystrategy":"copy"           
        }
    }

Make sure to set the `magento-root-dir` properly. Test this by running:

    composer update --no-dev

Done.

Bring your towel.
