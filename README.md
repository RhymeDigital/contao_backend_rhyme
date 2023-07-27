![Alt text](docs/logo.png?raw=true&v=3 "logo")

# Contao Back End Bundle

A Contao CMS 4.x bundle that applies a custom theme to the back end of the website.

---
## Veello Element Set Customizations
<br/>

### <u>_Configurations_</u>

To add custom element set configurations, add your .php files into the following directories (including any number of subdirectories) in your bundle.

_Examples:_

```
%kernel.project_dir%/Resources/contao/config/element_sets

%kernel.project_dir%/contao/config/element_sets
```

Alternatively, you can use an event listener that accepts the `Rhyme\ContaoBackendThemeBundle\Event\LoadElementSetsEvent` type event, with tag `rhyme_be.load_element_sets`.

<br/>

### <u>_Images_</u>

To add custom images for each element set, each image must be of type .png, and the file name must match the element set config key.

Add your custom element set images into an `element_sets` directory (including any number of subdirectories) anywhere in your bundle directory.

_Examples:_

```
src/Resources/element_sets`

src/Resources/public/assets/img/element_sets`
```

**NOTE:** In order for the images to work, you need to run the `Recreate the symlinks` command via "Maintenance" > "Purge Data" in Contao.

Alternatively, you can run the `assets:install` command in the `%kernel.project_dir%`. Be sure to replace `web` or `public` with your custom `%contao.web_dir%` if you have one.

Examples:

```
php vendor/bin/contao-console assets:install web --symlink

php vendor/bin/contao-console assets:install public --symlink
```

<br/>

### <u>_Language Entries_</u>

To add language entries for each custom element set, you need to add to the `$GLOBALS['TL_LANG']['VEE']['element_sets']` array. You may add them into your `default.php` or `default.xlf` files, but we recommend adding `element_sets.php` or `element_sets.xlf` files to your language directories to keep things organized.

Language entries for element sets must match the element set's config key.

Language entries for element set _groups_ must match the group's config key, prepended by `group_`. For example, an element set group with the config key `my_element_set_group` would need to have the language entry key `group_my_element_set_group`.

_Examples:_

```<?php
/**
 * Element set groups
 */
$GLOBALS['TL_LANG']['VEE']['element_sets']['group_my_element_set_group'] = 'My Awesome Element Sets';

/**
 * Element sets
 */
$GLOBALS['TL_LANG']['VEE']['element_sets']['my_element_set'] = 'My First Awesome Element Set';
```