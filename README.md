# ScandiPWA_MenuOrganizer

This repository is a module for Magento 2. This module is used to create customized navigation.

## How to use?

### In admin panel

1. Find the `Scandiweb` logo in the left navigation bar.
2. Go to Navigation > Menu Manager.
3. Find `Add New Menu` button in the right top corner, click it.
4. Enter the required fields (Menu title, identifier, status).
5. Find the `Save and Continue Edit` in the navigation on the top right corner, click it.
5. Find the `Assigned Menu Items` in the navigation on the left. click it.
6. Click `Add item` in the top right corner.
7. Enter the required fields (Menu item title, URL type, URL).
8. Choose URL parent item, opening type and status, click `Save`.

Congratulations! You have successfully created your first ScandiPWA menu! 🎉

### Via GraphQL endpoint

Please refer to [schema.graphqls](./src/etc/schema.graphqls) to see all available fields to query.
