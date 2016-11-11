<?php

/**
 * FormErrorsExtension - extension to list all errors from form.
 *
 * @author Maciej Szkamruk <ex3v@ex3v.com>
 */

namespace Ex3v\FormErrorsBundle\Twig;

use Symfony\Component\Form\Form;
use Ex3v\FormErrorsBundle\Services\FormErrorsParser;
use Symfony\Component\Translation\TranslatorInterface;

class FormErrorsExtension extends \Twig_Extension
{

    /**
     *
     * @var \Ex3v\FormErrorsBundle\Services\FormErrorsParser
     */
    private $parser;
    /**
     * @var TranslatorInterface 
     */
    private $trans ;
	
    public function __construct(FormErrorsParser $parser, TranslatorInterface $trans)
    {
        $this->parser = $parser;
		$this->trans =  $trans ;
    }

    public function getFunctions()
    {
        return array(
            'all_form_errors' => new \Twig_Function_Method($this, 'allFormErrors', array("is_safe" => array("html")))
        );
    }

    /**
     * Main Twig extension. Call this in Twig to get formatted output of your form errors.
     * Note that you have to provide form as Form object, not FormView.
     * 
     * @param \Symfony\Component\Form\Form $form
     * @param string $tag html tag, in which all errors will be packed. If you will provide 'li', 'ul' wrapper will be added
     * @param type $class class of each error. Default is none
     * @return string
     */
    public function allFormErrors(Form $form, $tag = 'li', $class = '')
    {
        $errorsList = $this->parser->parseErrors($form);

        $return = '';
        if (count($errorsList)) {
            if ($tag == 'li') {
                $return.='<ul>';
            }

            foreach ($errorsList as $item) {
                $return.=$this->handleErrors($item, $tag, $class);
            }

            if ($tag == 'li') {
                $return.='</ul>';
            }
        }

        return $return;
    }

    /**
     * Handle single error creation
     * @param type $item
     * @param type $tag
     * @param type $class
     * @return string
     */
    private function handleErrors($item, $tag, $class)
    {

        $return = '';

        $errors = $item['errors'];

        if (count($errors)) {
            /* @var $error \Symfony\Component\Form\FormError */
            foreach ($errors as $error) {
                $return.='<' . $tag . ' class="'.$class.'">';
                $return .= $this->trans->trans($item['label'], array(), $item['translation']);
                $return.=': ';
                $return .= $error->getMessage();  // The translator has already translated any validation error.
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
