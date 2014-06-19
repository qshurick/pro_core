pro_core
========

Some core library

application.ini

```
        autoloaderNamespaces[] = "Pro_"
        pluginPaths.Pro_Resource_ = "Pro/Resource"
    
        resources.db-plugin.ALIAS = "Some_Application_Db_Plugin"
```

Ensure in ```include_path``` directory ```vendor/qshurick/pro_core/library```, e.g.:

```
        set_include_path(implode(PATH_SEPARATOR, array(
            realpath(APPLICATION_PATH . '/../library'),
            realpath(APPLICATION_PATH . '/../vendor/qshurick/pro_core/library'),
            get_include_path(),
        )));
        require_once APPLICATION_PATH . '/../vendor/autoload.php';
```
