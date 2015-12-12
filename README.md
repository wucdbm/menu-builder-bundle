# menu-builder-bundle
A simple, standalone Menu Builder for Symfony 2 Applications

## Upcoming / TODO

- Feature: Edit links in existing menus
- Feature: Basic Bootstrap header menu template with a basic preview page, the page should have the previewed menu of choice plus instructions on it how on how to extract the code from the bundle and use it as a starting point. Also make use of the |isRoute(route) filter for current items etc.
- Feature: Force a parameter to remain blank - useful for _locale and such, or when a parameter has a default value. Save the default value fo each parameter in its RouteParameter entity and if the field is blank, use the default value. that is, if the MenuItemParameter value is empty, use the RouteParameter default value. Also save the default value upon creation of the MenuItem and use that if the route no longer has a default value. This will prevent removing the default value and having no value on the MenuItem.
- Feature: Order the menu with something like jQuery UI draggable
- Usability: Filter for unnamed routes and parameters
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
