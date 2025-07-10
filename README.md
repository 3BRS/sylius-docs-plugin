<p align="center">
  <a href="https://www.3brs.com" target="_blank">
    <img src="https://3brs1.fra1.cdn.digitaloceanspaces.com/3brs/logo/3BRS-logo-sylius-200.png" alt="3BRS Logo"/>
  </a>
</p>

<h1 align="center">
  Sylius Docs Plugin <br />
  <a href="https://packagist.org/packages/3brs/sylius-docs-plugin" title="License" target="_blank">
    <img src="https://img.shields.io/packagist/l/3brs/sylius-docs-plugin" alt="License" />
  </a>
  <a href="https://packagist.org/packages/3brs/sylius-docs-plugin" title="Version" target="_blank">
    <img src="https://img.shields.io/packagist/v/3brs/sylius-docs-plugin" alt="Version" />
  </a>
  <a href="https://circleci.com/gh/3BRS/sylius-docs-plugin" title="Build status" target="_blank">
    <img src="https://circleci.com/gh/3BRS/sylius-docs-plugin.svg?style=shield" alt="Build Status" />
  </a>
</h1>

## Features

- Render Markdown-based documentation directly inside the Sylius Admin panel
- Easily add editable `.md` files inside the `/docs` directory
- Secure access: only admins can view the docs

<p align="center">
  <img src="https://github.com/3BRS/sylius-docs-plugin/blob/SLS-28-Sylius-docs-plugin/doc/documentation_menu.png?raw=true" alt="Admin Screenshot" />
</p> 

<p align="center">
  <img src="https://github.com/3BRS/sylius-docs-plugin/blob/SLS-28-Sylius-docs-plugin/doc/doc_index.png?raw=true" alt="Admin Screenshot" />
</p>

## Installation

1. Run:

    ```bash
    composer require 3brs/sylius-docs-plugin
    ```

2. Register the bundle in your `config/bundles.php`:

    ```php
    ThreeBRS\SyliusDocsPlugin\ThreeBRSSyliusDocsPlugin::class => ['all' => true],
    ```

3. Import the plugin's routing files in `config/routes.yaml`:

    ```yaml
    threebrs_docs_plugin_routing_file:
        resource: "@ThreeBRSSyliusDocsPlugin/config/routes.yaml"
        prefix: '%sylius_admin.path_name%'
    ```

4. Import the plugin's config file in `config/packages/_sylius.yaml`:

    ```yaml
    imports:
        ...
        - { resource: "@ThreeBRSSyliusDocsPlugin/config/config.yaml" }
    ```

5. Generate and run Doctrine migrations:

    ```bash
    bin/console doctrine:migrations:diff 
    bin/console doctrine:migrations:migrate
    ```

## Usage

- Add a `docs/index.md` file in the root of your Sylius project (**necessary**; acts as your table of contents).
- Add your file URLs to `docs/index.md` like so:

    ```md
    # 🧭 Table of Contents

    Welcome to the internal documentation.

    - [Getting Started](getting-started)
    - [Product Import](product-import)
    - [Shipping Setup](shipping-setup)
    - [Markdown Text](md)
    - [README](README)
    - [Empty](empty)
    ```

- Access your documentation using the **Documentation** link in the admin panel sidebar.
- You will see the `docs/index.md` you created as a table of contents for your files.
- Missing files will show **Not Found** with the name of the file not found.

## Development

### Usage

- Develop your plugin logic inside `/src`
- See `bin/` for useful dev tools

### Testing

After making changes, make sure tests and checks pass:

```bash
composer install
bin/phpstan.sh
bin/ecs.sh
```
License
-------
This library is under the MIT license.

Credits
-------
Developed by [3BRS](https://3brs.com)
