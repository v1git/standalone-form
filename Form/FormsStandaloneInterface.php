<?php
/**
 * @author Vadym Pylypenko<vpylypenko@corevalue.net>
 */

namespace Demo\Form;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Templating\EngineInterface;

interface FormsStandaloneInterface
{
    /**
     * Creates new FormBuilder inst
     *
     * @return FormBuilderInterface
     */
    public function getFromBuilder();

    /**
     * @return EngineInterface
     */
    public function getTemplating();
}