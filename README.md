# menu-builder-bundle
A simple, standalone Menu Builder for Symfony 2 Applications

## Upcoming

- Should be possible to use the builder via an iframe, which makes it convenient to be integrated into any sort of administration panels.
- There should be a button to import any new routes.
- Also save the route pattern
- The ability to get all the menus at once

## Usage

Add this bundle to your AppKernel.php `new \Wucdbm\Bundle\MenuBuilderBundle\WucdbmMenuBuilderBundle()`

In your config.yml, add `WucdbmMenuBuilderBundle` to your assetic bundles, as well as your doctrine mapping (if not automatic)

Execute `app/console doctrine:schema:update --dump-sql` and after verifying what is being executed, execute again with --force.
Alternatively, use doctrine migrations via the DoctrineMigrations bundle.

Execute `app/console wucdbm_menu_builder:import_routes` to import your current routes from your symfony application into the tables created by the bundle.

Once this has been done, you can start using the bundle. Simply register it in your routing.yml like so:

```
wucdbm_builder:
    resource: "@WucdbmMenuBuilderBundle/Resources/config/routing.yml"
    prefix: /admin/builder
```

Assuming that /admin is protected by a firewall, the builder should be secure and inaccessible to random people.

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