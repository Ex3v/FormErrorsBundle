<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of safeCodeExtension
 *
 * @author ex3v
 */

namespace Ex3v\FormErrorsBundle\Twig;

use Symfony\Component\Form\Form;
use Ex3v\FormErrorsBundle\Services\FormErrorsParser;

class FormErrorsExtension extends \Twig_Extension
{

    /**
     *
     * @var \Ex3v\FormErrorsBundle\Services\FormErrorsParser
     */
    private $parser;

    public function __construct(FormErrorsParser $parser)
    {
        $this->parser = $parser;
    }

    public function getFunctions()
    {
        return array(
            'all_form_errors' => new \Twig_Function_Method($this, 'allFormErrors', array("is_safe" => array("html")))
        );
    }

    public function allFormErrors(Form $form, $tag = 'li', $class = '')
    {
        $errorsList = $this->parser->parseErrors($form);

        $return = '';
        if (count($errorsList)) {
            if ($tag == 'li') {
                $return.='<ul>';
            }

            foreach ($errorsList as $item) {
                $return.=$this->handleErrors($item, $tag);
            }

            if ($tag == 'li') {
                $return.='</ul>';
            }
        }

        return $return;
    }

    private function handleErrors($item, $tag)
    {

        $return = '';

        $errors = $item['errors'];

        if (count($errors)) {
            /* @var $error \Symfony\Component\Form\FormError */
            foreach ($errors as $error) {
                $return.='<' . $tag . '>';
                $return.=$item['label'];
                $return.=': ';
                $return.=$error->getMessage();
                $return.="</" . $tag . '>';
            }
        }

        return $return;
    }

    public function getName()
    {
        return 'all_form_errors_extension';
    }

}

?>
