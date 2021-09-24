<?php
class CategoryModel
{
    private $id;
    private $name;
    private $description;
    private $status;
    private $error = [];

    const MAX_NAME_LENGTH = 60;
    const MAX_DESCRIPTION_LENGTH = 100;
    public function __construct($id, $name, $description, $status) {
        $this->id = $id;
        $this->setName($name);
        $this->setDescription($description);
        $this->status = $status;
    }
    
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function setName($name) {
        if (trim($name) === '') {
            $this->error[] = "Category name provided is empty.";
        }
        if (strlen($name) > self::MAX_NAME_LENGTH) {
            $this->error[] = "Category name must not be more than " . self::MAX_NAME_LENGTH . " characters long.";
        }
        $this->name = $name;
    }

    public function setDescription($description) {
        if (trim($description) === '') {
            $this->error[] = "Category description provided is empty.";
        }
        if (strlen($description) > self::MAX_DESCRIPTION_LENGTH) {
            $this->error[] = "Category description must not be more than " . self::MAX_DESCRIPTION_LENGTH . " characters long.";
        }
        $this->description = $description;
    }

    public function getErrors(){
        return $this->error;
    }
}
