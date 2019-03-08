# Scandiweb_Menumanager

This module is used to create customized navigation for Magento 2.

## Example on how-to add menu to the Magento store

```
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <move element="custom.topnav" destination="store.menu" after=""/>
        <referenceContainer name="page.top">
            <block class="ScandiPWA\MenuOrganizer\Block\Menu" name="custom.navigation" template="html/menu.phtml" ttl="3600">
                <arguments>
                    <argument name="identifier" xsi:type="string">main_navigation</argument>
                </arguments>
            </block>
        </referenceContainer>
    </body>
</page>
```
