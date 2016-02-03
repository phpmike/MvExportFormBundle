# MvExportFormBundle
Symfony2 bundle to export data with form logic.  
This approach is not the best for performance, but, sometime we need to keep the same logic.  
Embedded forms and labels translation are supported.

INSTALLATION with COMPOSER
--------------------------

```bash
php composer.phar require mv/export-form-bundle:"~1.0"
```

###1)  Add to your AppKernel.php

```php
new Mv\ExportFormBundle\MvExportFormBundle(),
```

###2)  Use it!

[see documentation](Resources/doc/index.md)