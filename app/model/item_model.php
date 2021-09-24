<?php
class ItemModel
{
    private $id;
    private $title;
    private $description;
    private $price;
    private $category_id;
    private $image; // TODO
    private $status;
    private $error = [];

    const MAX_TITLE_LENGTH = 100;
    const MAX_DESCRIPTION_LENGTH = 255;

    public function __construct($id, $title, $description, $price, $image, $category_id, $status) {
        $this->id = $id;
        $this->image = $image;
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setPrice($price);
        $this->setCategoryId($category_id);
        $this->status = $status;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function setTitle($title) {
        if (strlen($title) > self::MAX_TITLE_LENGTH) {
            $this->error[] = "Item title must not be more than " . self::MAX_TITLE_LENGTH . " characters long.";
        }
        $this->title = $title;
    }

    public function setDescription($description) {
        if (strlen($description) > self::MAX_DESCRIPTION_LENGTH) {
            $this->error[] = "Item description must not be more than " . self::MAX_DESCRIPTION_LENGTH . " characters long.";
        }

        $this->description = $description;
    }

    public function setPrice($price) {
        if (!is_float($price)) {
            $this->error[] = "Given price value " . $price . " is invalid. Price value must be whole number.";
        }

        if ($price < 0) {
            $this->error[] = "Given price value " . $price . " is invalid. Price value must be non-negative.";
        }

        $this->price = $price;
    }

    public function setCategoryId($category_id) {
        if (is_null($category_id)) {
            $this->error[] = "Category ID provided is null.";
        }

        if (!is_int($category_id)) {
            $this->error[] = "Category ID provided is not an integer.";
        }
        
        if ($category_id==0) {
            $this->error[] = "No Category ID selected.";
        }

        $this->category_id = $category_id;
    }
    
    public function getErrors(){
        return $this->error;
    }
}