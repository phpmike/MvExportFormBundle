# Override Label translation

Mv\ExportFormBundle\Export\Label class provide the translation for labels like it is on Symfony by default.  
It's good for 99% cases. But if you have a custom way to do, you can override like this:

## Create your class and implement Mv\ExportFormBundle\Export\LabelInterface

```php
use Mv\ExportFormBundle\Export\LabelInterface;

class MyCustomLabel implements LabelInterface
{
    /**
     * @param TranslatorInterface $translator
     * @param Twig_Environment    $twig
     */
    public function __construct(TranslatorInterface $translator, Twig_Environment $twig)
    {
        $this->translator = $translator;
        $this->twig = $twig;
    }

    /**
     * @param FormView $formView
     *
     * @return string
     */
     public function trans(FormView $formView)
     {
        // Have a look to original class to have an idea of the way.
     }
}
```

## Override the original class

In your config.yml, add something like this (don't forget the full namespace of your class):

```yml
parameters:
    mv_export_form.label.class: MyCustomLabel
```

Your class will be used to translate labels.

If you need to inject some custom services, you have to pass by a CompilerPass and calling setters.