FormErrorsBundle
================

[![knpbundles.com](http://knpbundles.com/Ex3v/FormErrorsBundle/badge)](http://knpbundles.com/Ex3v/FormErrorsBundle)

[![Latest Stable Version](https://poser.pugx.org/ex3v/formerrorsbundle/v/stable.svg)](https://packagist.org/packages/ex3v/formerrorsbundle) [![Total Downloads](https://poser.pugx.org/ex3v/formerrorsbundle/downloads.svg)](https://packagist.org/packages/ex3v/formerrorsbundle) [![License](https://poser.pugx.org/ex3v/formerrorsbundle/license.svg)](https://packagist.org/packages/ex3v/formerrorsbundle)

This bundle contains two things:
- a service that exports all errors from form to array
- Twig extension that helps display them as list

There already is form method getErrorsAsString(), but it does not provide you with labels. It also requires you to write boilerplate code in controllers, or writing your own service. If you will try to list all errors by yourself, you will notice that $form->getErrors() returns errors only for Form itself, not for particular fields or subforms. Why bother creatng your own parser? Use mine :)

**DISCLAIMER**
--------------
I wrote this bundle basing on Symfony 2.3.13 for my own purposes, did not tested on other versions, but having in mind what Fabien said about backwards compatibility, it should work at least on 2.4 and any future relase of Symfony2, as well as on previous versions. Remember that I don't guarantee this, so feel free to test, fork and make your changes.


**INSTALLATION**
----------------

- **Add this to *require* part of your composer.json file:**
    
        "ex3v/formerrorsbundle": "dev-master"
    
- **Add this to your AppKernel.php (under /app)**

        new Ex3v\FormErrorsBundle\Ex3vFormErrorsBundle(),
        

then run `composer update` on your project to install.

        
**USAGE**
---------

While in controller, add not only FormView, but also Form object to returning array:

        return array(
            'form' => $form->createView(), 
            'formFull' => $form
        );
        
        
Having this, you can call new method in Twig:

        {{ all_form_errors(formFull) }}
        

This method will (by default) display all of your form errors as &lt;ul&gt; list, like this:

         <ul>
             <li>Title: field cannot be empty.</li>
             <li>Website: this is not a valid URL.</li>
         </ul>



You can customize your output by adding additional parameters:

        {{ all_form_errors(formFull, "div", "myclass") }}
        
This will produce errors list in which each error is wrapped in separate div, having "myclass" as its class:

        <div class="myclass">
            Title: field cannot be empty.
        </div>
        <div class="myclass">
            Website: this is not a valid URL.
        </div>
        

If you want more control over your errors, you also can call service that parses errors directly in controller:

        $formErrorsParser = $this->get('formErrorsParser');
        $errors = $formErrorsParser->parseErrors($form);
        
$errors array will contain arrays composed of:
- field name
- field label
- Symfony\Component\Form\FormError error object, that contains error message in two formats (original and translated) as well as field value.

Any suggestions or contributions are warmly welcome. Happy coding!





