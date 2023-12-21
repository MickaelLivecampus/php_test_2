<?php

class CarService
{

    public function isValid($fields): bool
    {
        $checksFields = $this->isEmptyOrNotSetFields($fields);

        return $this->isValidMarque($fields['marque'] ?? null) 
                && $this->isValidModele($fields['modele'] ?? null)
                && !in_array(false, $checksFields, true);
    }

    public function isEmptyOrNotSetFields($fields): array
    {
        $results = [];
        $errors = [];
    
        foreach ($fields as $key => $field) {
            if (!isset($field) || empty($field)) {
                $errors[$key] = "Field '$key' is empty or not defined";
                $results[$key] = false;
            } else {
                $results[$key] = true;
            }
        }
    
        if (!empty($errors)) {
            throw new Exception("Errors in fields: " . implode(', ', $errors));
        }
    
        return $results;
    }
    

    public function isValidMarque(?string $marque): bool
    {
        return $marque !== null && trim(strlen($marque)) <= 50;
    }

    public function isValidModele(?string $modele): bool
    {
        return $modele !== null && trim(strlen($modele)) <= 50;
    }
}

?>