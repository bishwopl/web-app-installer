<?php

namespace WebAppInstaller\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

class BaseForm extends Form {

    public function __construct($name = null, $options = array()) {
        if ($name == null) {
            $name = 'base-form';
        }
        parent::__construct($name, $options);

        $csrf = new Element\Csrf('app_installer_security');
        $csrf->getCsrfValidator()->setTimeout(600);
        $this->add($csrf);

        $submitElement = new Element\Button('app_installer_submit');
        $submitElement->setLabel('Submit')->setAttributes([
            'type' => 'submit',
            'class' => 'btn btn-sm btn-primary'
        ]);
        $this->add($submitElement);
    }

}
