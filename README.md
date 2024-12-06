# Loft Core Cypress Selector

![Goodbye Cypress](images/hero.jpg)

Use to replace `\Drupal\loft_core\Utility\Cypress`, by providing a Cypress-compatible selector, to the [DOM Testing Selectors Drupal Module](https://github.com/aklump/drupal_dom_testing_selectors).

## Install

1. You must enable [DOM Testing Selectors Drupal Module](https://github.com/aklump/drupal_dom_testing_selectors)
2. You must have a custom Drupal module (`my_module`) for the following instructions.
1. Install this in your custom module as described by _Install with Composer_.
1. In the root _composer.json_ of your Drupal app, add the `repository` portion from _Install with Composer_ as well.

## Install with Composer

1. Because this is an unpublished package, you must define it's repository in
   your project's _composer.json_ file. Add the following to _composer.json_ in
   the `repositories` array:
   
    ```json
    {
     "type": "github",
     "url": "https://github.com/aklump/loft-core-cypress-selector"
    }
    ```
1. Require this package:
   
    ```
    composer require aklump/loft-core-cypress-selector:^0.0
    ```

## Configuration

1. Create `\Drupal\my_module\MyModuleServiceProvider` class. This will replace the default selector with the custom selector provided by this package, which will output the same markup as the Cypress class.

    ```php
    namespace Drupal\my_module;

    final class AtsCoreServiceProvider implements \Drupal\Core\DependencyInjection\ServiceModifierInterface {

      /**
       * @inheritDoc
       */
      public function alter(\Drupal\Core\DependencyInjection\ContainerBuilder $container) {
        // Switch to the Cypress-style selector.
        if ($container->hasDefinition('dom_testing_selectors.selector')) {
          $definition = $container->getDefinition('dom_testing_selectors.selector');
          $definition->setClass(\AKlump\DomTestingSelectors\Selector\LoftCoreCypressSelector::class);
        }
      }
    }
    ```
2. Now migrate your code, replacing `Cypress` with `TestingSelector` as described next.

## Code Migration

1. During the migration period, you are advised to comment out the line `$this->addHandler(new PassThroughHandler());` in `\Drupal\dom_testing_selectors\Factory\DrupalFactory`. This should help to prevent errors, by throwing exceptions.
3. Because of differences between these two libraries, in some cases you can be less specific in the element you pass. For example `$element['link']['attributes']` (as passed to `Cypress::tag()`) should be passed as `$element['link']` to `TestingSelectors::apply()`.
2. Go through your entire codebase replacing all instances of `\Drupal\loft_core\Utility\Cypress` as illustrated.

### Legacy Code

```php
$cy = \Drupal\loft_core\Utility\Cypress::create($test_group);
$cy->tag($form['revision_information'])->with('revision');
$cy->tag($form['status'])->with('publish');
```

### Upgraded Code

```php
\Drupal\dom_testing_selectors\TestingSelectors::apply($form['revision_information'], 'revision', $test_group);
\Drupal\dom_testing_selectors\TestingSelectors::apply($form['status'], 'publish', $test_group);
```
