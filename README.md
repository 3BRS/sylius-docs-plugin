<p align="center">
  <a href="https://www.3brs.com" target="_blank">
    <img src="https://3brs1.fra1.cdn.digitaloceanspaces.com/3brs/logo/3BRS-logo-sylius-200.png" alt="3BRS Logo"/>
  </a>
</p>

<h1 align="center">
  Sylius Docs Plugin <br />
  <a href="https://packagist.org/packages/3brs/sylius-documentation-plugin" title="License" target="_blank">
    <img src="https://img.shields.io/packagist/l/3brs/sylius-documentation-plugin" alt="License" />
  </a>
  <a href="https://packagist.org/packages/3brs/sylius-documentation-plugin" title="Version" target="_blank">
    <img src="https://img.shields.io/packagist/v/3brs/sylius-documentation-plugin" alt="Version" />
  </a>
  <a href="https://circleci.com/gh/3BRS/sylius-documentation-plugin" title="Build status" target="_blank">
    <img src="https://circleci.com/gh/3BRS/sylius-documentation-plugin.svg?style=shield" alt="Build Status" />
  </a>
</h1>

## Features

- Render Markdown-based documentation directly inside the Sylius Admin panel
- Easily add editable `.md` files inside the `/documentation` directory
- Secure access: only admins can view the docs

<p align="center">
  <img src="docs/docs_index_example.png?raw=true" alt="Admin Documentation Index" style="max-width:500px;" />
</p>

<p align="center">
  <img src="./docs/docs_item_example.png?raw=true" alt="Admin Documentation Item" style="max-width:500px;" />
</p> 

## Installation

1. Run:

    ```bash
    composer require 3brs/sylius-documentation-plugin
    ```

2. Register the bundle in your `config/bundles.php`:

    ```php
    ThreeBRS\SyliusDocumentationPlugin\ThreeBRSSyliusDocumentationPlugin::class => ['all' => true],
    ```

3. Import the plugin's routing files in `config/routes.yaml`:

    ```yaml
    threebrs_documentation_plugin_routing_file:
        resource: "@ThreeBRSSyliusDocumentationPlugin/config/routes.yaml"
        prefix: '%sylius_admin.path_name%'
    ```

4. Import the plugin's config file in `config/packages/_sylius.yaml`:

    ```yaml
    imports:
        # ...
        - { resource: "@ThreeBRSSyliusDocumentationPlugin/config/config.yaml" }
    ```
5. (Optional) Redefine the path to your documentation directory in `config/bundles/threebrs_sylius_documentation.yaml`:

    ```yaml
    threebrs_sylius_documentation:
        docs_path: '%kernel.cache_dir%/behat_docs'
    ```
## Usage

- Add a `documentation/index.md` file in the root of your Sylius project (**necessary**; acts as your table of contents).
- Add your file URLs to `documentation/index.md` like so:

    ```md
    # 🧭 Table of Contents

    Welcome to the internal documentation.

    - [Getting Started](getting-started.md)

    - [Product Import](product-import.md)

    - [Shipping Setup](shipping-setup.md)
    ```

- Access your documentation using the **Documentation** link in the admin panel sidebar.
- You will see the `documentation/index.md` you created as a table of contents for your files.
- Missing files will show **Not Found** with the name of the file not found.

## Development

### Usage

- Develop plugin logic inside `/src`
- See `Makefile` for useful dev tools

### Testing

After making changes, make sure tests and checks pass:

```bash
make ci
```
License
-------
This library is under the MIT license.

Credits
-------
Developed by [3BRS](https://3brs.com)
