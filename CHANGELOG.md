# Changelog

## 2.1.0

### ⚠️ **BC**: Parameters and routes naming standardization
  - Route names changed from `threebrs_admin_documentation_plugin_*` to `threebrs_sylius_documentation_admin_*`
    - `threebrs_admin_documentation_plugin_index` → `threebrs_sylius_documentation_admin_index`
    - `threebrs_admin_documentation_plugin_show` → `threebrs_sylius_documentation_admin_show`
    - `threebrs_admin_documentation_plugin_image` → `threebrs_sylius_documentation_admin_image`
  - Parameter names changed from `threebrs_sylius_documentation_plugin.*` to `threebrs_sylius_documentation.*`
    - `threebrs_sylius_documentation_plugin.docs_path`  → `threebrs_sylius_documentation.docs_path`
  - Sylius Twig hook names changed from `threebrs_sylius_documentation_plugin.*` to `threebrs_sylius_documentation.*`
    - `threebrs_sylius_documentation_plugin.sylius_admin.documentation.index` → `threebrs_sylius_documentation.sylius_admin.documentation.index`
    - `threebrs_sylius_documentation_plugin.sylius_admin.documentation.index.content` → `threebrs_sylius_documentation.sylius_admin.documentation.index.content`
    - `threebrs_sylius_documentation_plugin.sylius_admin.documentation.index.content.header` → `threebrs_sylius_documentation.sylius_admin.documentation.index.content.header`
    - `threebrs_sylius_documentation_plugin.sylius_admin.documentation.index.content.header.title_block` → `threebrs_sylius_documentation.sylius_admin.documentation.index.content.header.title_block`
    - `threebrs_sylius_documentation_plugin.sylius_admin.documentation.index.content.custom` → `threebrs_sylius_documentation.sylius_admin.documentation.index.content.custom`
    - `threebrs_sylius_documentation_plugin.base#stylesheets` → `threebrs_sylius_documentation.base#stylesheets`
    - `threebrs_sylius_documentation_plugin.base#javascripts` → `threebrs_sylius_documentation.base#javascripts`
