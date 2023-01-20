<?php

namespace App\Service\Core\General;

use App\Service\Core\Settings\SettingsService;
use Symfony\Component\Form\Form;

class GlobalService
{

    protected $settings;

    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
    }

  

    public function serializeFormErrors(\Symfony\Component\Form\Form $form, $flat_array = false, $add_form_name = false, $glue_keys = '_') {
        $errors = array();
        $errors['global'] = array();
        $errors['fields'] = array();

        foreach ($form->getErrors() as $error) {
            $errors['global'][] = $error->getMessage();
        }

        $errors['fields'] = $this->serialize($form);

        if ($flat_array) {
            $errors['fields'] = $this->arrayFlatten($errors['fields'], $glue_keys, (($add_form_name) ? $form->getName() : ''));
        }

        $error_arr = array();
        foreach ($errors as $error_row) {
            if (!empty($error_row)) {
                foreach ($error_row as $key => $e) {
                    $error_arr [] = $key . ": " . $e;
                }
            }
        }

        return $error_arr;
    }

    private function arrayFlatten($array, $separator = "_", $flattened_key = '') {
        $flattenedArray = array();
        foreach ($array as $key => $value) {

            if (is_array($value)) {

                $flattenedArray = array_merge($flattenedArray, $this->arrayFlatten($value, $separator, (strlen($flattened_key) > 0 ? $flattened_key . $separator : "") . $key)
                );
            } else {
                $flattenedArray[(strlen($flattened_key) > 0 ? $flattened_key . $separator : "") . $key] = $value;
            }
        }
        return $flattenedArray;
    }

    private function serialize(Form $form) {
        $local_errors = array();
        foreach ($form->getIterator() as $key => $child) {

            foreach ($child->getErrors() as $error) {
                $local_errors[$key] = $error->getMessage();
            }

            if (method_exists($child, "getIterator")) {
                $local_errors[$key] = $this->serialize($child);
            }
        }

        return $local_errors;
    }
    
}
