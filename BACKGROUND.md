# Introduction
When customizing Magento emails, you have the choice of modifying the template through the **Magento Admin Panel**,
or through the system folder `app/locale/xx_XX/template/email`. The last option classifies as a core hack, and with every upgrade your changes will be gone. The first option requires you to modify code within a textarea and no syntax highlightning available.

# Override email templates
This extension allows you to create file-based overrides of the Magento email templates within your own custom Magento theme. You can simply copy templates around, and modify the files using favorite IDE.

    app/design/frontend/{PACKAGE}/{THEME}/locale/{LOCALE}/template/email

In the path, `{PACKAGE}` placeholder reflects your own theming package, `{THEME}` reflects the Magento theme you are
using and `{LOCALE}` reflects the language locale you are using - for example `en_US` or `de_DE`. Refer to the Magento documentation on how to configure theming and locales.

# Override CSV files
An additional feature of this module is that CSV translation files like `Mage_Checkout.csv` can also be overrridden in your theme. For instance:

    app/design/frontend/default/mytheme/locale/en_US/Mage_Checkout.csv

This allows you to make any changes to any language file simply by copying files.
