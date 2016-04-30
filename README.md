# menu-builder-bundle
A simple, standalone Menu Builder for Symfony 2 Applications

## Upcoming / TODO

- Remove missing routes if not updated via a migration beforehand
- Feature: Make it possible to completely ignore route parameters such as `_locale`, `_scheme` (maybe `_host`?) and use those from the current environment - a valid use case for that is if a user is browsing the french version of the website, but I want all links on my menu to link to pages in french and not the default fallback to english for instance.
- UX: Better handling of route requirements such as asd|(ffs|xd)|dasdf - strip the parenthesis for the time being or process it somehow
- Feature: Force a parameter to remain blank - useful for _locale and such, or when a parameter has a default value. Save the default value fo each parameter in its RouteParameter entity and if the field is blank, use the default value. that is, if the MenuItemParameter value is empty, use the RouteParameter default value. Also save the default value upon creation of the MenuItem and use that if the route no longer has a default value. This will prevent removing the default value and having no value on the MenuItem.
- Feature: Order the menu with something like jQuery UI draggable
- TODO: Error pages for missing items - instead of type hinting the doctrine entities in the controllers, take their IDs and show an error page if any entity was not found.

## Usage

Having properly configured uglifycss and uglifyjs is a requirement for production.

Add this bundle to your AppKernel.php `new \Wucdbm\Bundle\MenuBuilderBundle\WucdbmMenuBuilderBundle()`

In your config.yml, add `WucdbmMenuBuilderBundle` to your assetic bundles, as well as your doctrine mapping (if not automatic)

Execute `app/console doctrine:schema:update --dump-sql` and after verifying what is being executed, execute again with --force.
Alternatively, use doctrine migrations via the DoctrineMigrations bundle.

Execute `app/console wucdbm_menu_builder:import_routes` to import your current routes from your symfony application into the tables created by the bundle.

Alternatively, add `Wucdbm\\Bundle\\MenuBuilderBundle\\Composer\\ScriptHandler::importRoutes` to your composer.json's `post-install-cmd` or `post-update-cmd` command list and this will be executed after every install or update

Once this has been done, you can start using the bundle. Simply register it in your routing.yml like so:

```
wucdbm_builder:
    resource: "@WucdbmMenuBuilderBundle/Resources/config/routing.yml"
    prefix: /admin/builder
```

Assuming that /admin is protected by a firewall, the builder should be secure and inaccessible to random people.

You can create a link to the builder using `{{ path('wucdbm_menu_builder_dashboard') }}`, or embed it into your admin UI via an iframe like so `<iframe src="{{ path('wucdbm_menu_builder_dashboard') }}" style="border: 0; width: 100%; height: 100%;"></iframe>`

The User Interface is pretty self-explanatory. Once you have created a menu, you can access it in your application by calling the `getMenu` 
twig function, which will return `Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu` or `null`. A menu contains `Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem`s.
Menu items can be a parent of another MenuItem and have children. A good idea when listing the top-level menu is to only list items whose parent is null:

```
{# New: You can use the menuTopLevelItems filter to get all top-level items: #}
{% for item in getMenu(1)|menuTopLevelItems %}
```


```
{% if getMenu(1) %} {# You could also use any constant with the constant() function or any other way of referencing the menu #}
    {% for item in getMenu(1).items if item.parent is null %}
        {# You could recursively include this template to list the sub-menus #}
        {% include '@Some/location/template.html.twig' with {items: item.children} %} 
    {% endfor %}
{% endif %}
```

Another idea is to build pages using the menu builder like so:

```
{% if getMenu(1) %}
    {% for item in getMenu(1).items if item.parent is null %}
        <li>
            <a href="{{ path('admin_menu_view', {menuId: item.menu.id, itemId: item.id}) }}">
                {{ item.name }}
            </a>
        </li>
    {% endfor %}
{% endif %}
```

Printing a link for a menu is done via the `menuItemPath` twig filter/function, like so:

```
<a href="{{ item|menuItemPath }}">
    {{ item.name }}
</a>
```

Or for absolute links

```
<a href="{{ item|menuItemUrl }}">
    {{ item.name }}
</a>
```

You can also use the second (optional) parameter for `menuItemUrl` and set the type of address (one of the `Symfony\Component\Routing\Generator\UrlGeneratorInterface` constants)

## Dynamic Default Parameters

If you want to have a dynamic default parameter for some of your routes, for instance, routes with a dynamic locale:

In config.yml:
```
parameters:
    locale:           en
framework:
    default_locale:  %locale%
```

In routing.yml:
```
some_resource:
    resource: "@SomeBundle/Resources/config/routing.yml"
    prefix: /{_locale}
    schemes:  [https]
    requirements:
        _locale: "en|de|ru"
    defaults:
        _locale: %locale%
```
Generally, you do NOT need the `defaults: {_locale: %locale%}` part because you already have the default locale configured in your framework bundle config.
However, with this approach the default value for the `_locale` route parameter will be available to the menu builder when importing routes.
When building a link, you may choose to leave the field blank if there is a default parameter. 
This will allow you to change the default value for that parameter at a later point, WITHOUT having to update menu items. 
When routes are updated during the deployment of your application, the default value for that parameter of your route will also be updated.
The current value of the default parameter will always be saved upon menu item edit anyway, but the menu builder will always try to use the current default value for that route parameter.
If the default value for any parameter has been removed, it will fallback to the route default parameter as has been on the last menu item save.

## Not to be confused with symfony internal parameters such as `_locale` that may have another default value in the current context
An example would be a site with default locale of "en", but the user is browsing the "fr" version. You want your links to always point to the current locale and not to a pre-selected one or the default for your site.
Which is a feature that has not yet been developed, but this would allow you to completely ignore a parameter and not provide it if it already exists in the router context? To be researched.
See TODOs for more information on this.