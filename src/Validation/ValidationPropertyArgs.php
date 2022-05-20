<?php

require_once ('src/Validation/Validation.php');

class ValidationPropertyArgs implements Validation
{
    /**
     * @param array $mode
     * @return array
     */
    public function validate(array $mode): array
    {
        $errors = [];

        if(count($mode) !== 2){
            $errors[] = 'Should give an one argument (sales or rental)';
        }

        return $errors;
    }
}