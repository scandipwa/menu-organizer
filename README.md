# ScandiPWA_MenuOrganizer

This repository is a module for Magento 2. This module is used to create customized navigation.

## Menu
1. <a href="#supported">Supported features</a>
2. <a href="#install">Installation</a>
3. <a href="#how-to">How to use</a><br/>
    3.1. <a href="#form-menu">Creating first menu</a><br/>
    3.2. <a href="#form-item">Adding menu items</a><br/>
    3.3. <a href="#render">Structure for ScandiPWA</a><br/>
    3.4. <a href="#per-store">How to enable/disable menu per store</a><br/>
4. <a href="#dev">For development</a><br/>
    4.1.  <a href="#dev-db">DB structure</a><br/>
    4.2.  <a href="#dev-graphql">GraphQl structure</a><br/>
    4.3.  <a href="#dev-quick">Quick file access</a><br/>
--------
<div id="supported"></div>

## 1. Supported features
* Multilevel structured menu
* Magento categories
* Magento CMS pages
* Custom URLs
* Icons for menu items
* Custom CSS classes
-----
<div id="install"></div>

## 2. Installation
Installation via composer:
```
composer require scandipwa/menu-organizer
```
-----
<div id="how-to"></div>

## 3. How to use
<div id="form-menu"></div>

### 3.1. Creating first menu
1. Open admin panel
2. Locate `admin/scandipwa_menuorganizer/menu` via URL or by using side panel menu: `Scandiweb -> Navigation -> MenuManager`
3. Find `Add New Menu` button in the right top corner, click it.<div id="step"></div>
4. Fill out form fields:
    1. Required:
        * _Title_
        * _Menu identifier (used in graphql requests)_
        * _Menu status (enabled / disabled)_
    2. Optional:
        * _Custom Menu CSS Class_
        * _Store (to which store-view this menu is assigned to)_ 
5. Click `Save`.

![image](https://user-images.githubusercontent.com/82165392/128774925-e1c39338-84ed-4add-9140-13fe647b6692.png)
<div id="form-item"></div>

### 3.2. Adding menu items
1. Open menu.
2. Find the `Assigned Menu Items` in the navigation on the left. click it.
3. Click `Add item` in the top right corner.
4. Fill out form fields:
    1. Required:
        * Menu Item Title
        * URL Type _(Custom URL / Category / CMS page)_
        * URL Type specific field: Custom URL | Category | CMS Page
        * Parent _(Parent menu item)_
        * Menu Item Status _(enabled / disabled)_
    2. Optional:
        * Menu Item CSS Class
        * URL Attributes
        * Icon
        * Icon Alt Text
        * Menu Item Sort Order _(Item with lowest number will be on top)_
5. Click `Save`.

![image](https://user-images.githubusercontent.com/82165392/128774951-2a3be10e-9930-4b8e-8e11-c9e02e0b40b3.png)
<div id="render"></div>

### 3.3. Structure for ScandiPWA
By default ScandiPWA will only render yellow elements _(check image)_ and ignore the red ones.

![image](https://user-images.githubusercontent.com/82165392/128774980-d1e07103-fc64-4398-a2d3-1912bb6e11e9.png)

The example below would result in a menu with the options Women, Men and Accessories and their respective sub-items (such as Bags and Tops).

![image](https://user-images.githubusercontent.com/82165392/128775983-575bacfa-bbf7-4506-8734-37c635fa3ea1.png)

<div id="per-store"></div>

### 3.4. How to enable/disable menu per store

To enable a menu for a specific store, make sure you have selected the store in the menu configuration on step <a href="#step">3.1.4.ii.</a><br/> Then, select the correct menu id in the configuration:

1. Open admin panel
2. Using the side panel menu, go to: `Stores -> Settings -> Configuration`
3. Find `Scope` and select the desired store view.
4. Using the menu dropdown, go to: `ScandiPWA -> Content Customization -> Header and Menu`
5. Uncheck the checkbox `Use Website`
6. Select the desired menu in `Menu to display`
7. Tap `Save Config`
8. Flush Cache Storage

![image](https://user-images.githubusercontent.com/82165392/128777137-05d918b2-94cf-403b-9898-e8ad65ccd588.png)

-----
<div id="dev"></div>

## 4. For development
<div id="dev-db"></div>

### 4.1. DB structure

![image](https://user-images.githubusercontent.com/82165392/128775005-35875db3-9b87-4077-a4fc-3855c15ca5b5.png)
<div id="dev-graphql"></div>

### 4.2. GraphQl structure

```js
type Query {
    scandiwebMenu(identifier: String!): Menu @resolver(class: "ScandiPWA\\MenuOrganizer\\Model\\Resolver\\Menu")
}

type Menu {
    menu_id: ID 
    title: String 
    is_active: Boolean 
    css_class: String 
    items: [Item]
}

type Item  {
    item_id: ID 
    icon: String
    title: String 
    item_class: String @doc(description: "CSS class of the item")
    parent_id: Int 
    url: String 
    url_type: Int @doc(description: "0 - regular link, 1 - cms page, 2 - category")
    position: Int 
    is_active: Boolean 
    cms_page_identifier: String 
    is_promo: Int @doc(description: "Boolean if category is promotional category")
    promo_image: String @doc(description: "Promo category image background")
    category_id: Int @doc(description: "Category id")
}

```
Or check: [schema.graphqls](./src/etc/schema.graphqls)
<div id="dev-quick"></div>

### 4.3. Quick file access:
* Form for creating menu: [General.php](./src/Block/Adminhtml/Menu/Edit/Tab/General.php)
* Form for creating item: [Form.php](./src/Block/Adminhtml/Item/Edit/Form.php)
* Menu model: [Item.php](./src/Model/Menu.php) | [ResourceModel/Item.php](./src/Model/ResourceModel/Menu.php)
* Item model: [Menu.php](./src/Model/Item.php) | [ResourceModel/Item.php](./src/Model/ResourceModel/Item.php)
* Menu resolver: [Menu.php](./src/Model/Resolver/Menu.php)
* DB schema: [db_schema.xml](./src/Model/Resolver/Menu.php)
