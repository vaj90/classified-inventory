<?php
global $lib;
require_once($lib . 'flatfile' . DS . 'flatfile.php');
class DataManager {
    private $flatDb;
    public function __construct() {
        global $config;
        $this->flatDb = new Flatfile();
        $this->flatDb->datadir = $config['DATA_PATH'];
    }
    public function addCategory($id, $title, $description) {
        $model = [
            'IsSuccess' => true,
            'Message' => []
        ];
        $category_obj = new CategoryModel($id, $title, $description, "");
        $new_category = [
            CATEGORY_ID => $id,
            CATEGORY_TITLE => $title,
            CATEGORY_DESCRIPTION => $description
        ];
        $error = $category_obj->getErrors();
        if(count($error)>0){
            $model['IsSuccess'] = false;
            $model['Message'] = $error;
        }
        else{
            $this->flatDb->insert('categories.txt', $new_category);
        }
        return $model;
    }
    public function updateCategory($id, $title, $description) {
        $model = [
            'IsSuccess' => true,
            'Message' => []
        ];
        $category_obj = new CategoryModel($id, $title, $description, "");
        $new_category = [
            CATEGORY_ID => $id,
            CATEGORY_TITLE => $title,
            CATEGORY_DESCRIPTION => $description
        ];
        $error = $category_obj->getErrors();
        if(count($error)>0){
            $model['IsSuccess'] = false;
            $model['Message'] = $error;
        }
        else{
            $this->flatDb->updateSetWhere('categories.txt', $new_category, new SimpleWhereClause(CATEGORY_ID, '=', $id));
        }
        return $model;
    }
    public function deleteCategory($id) {
        try{
            $this->flatDb->deleteWhere('categories.txt', new SimpleWhereClause(CATEGORY_ID, '=', $id));
            return true;
        }
        catch(Exception $e){
            return false;
        }
    }
    public function getCategoryById($id) {
        $category = $this->flatDb->selectUnique('categories.txt', CATEGORY_ID, $id);
        return $category;
    }
    public function getAllCategories() {
        $model = $this->flatDb->selectAll('categories.txt');
        return $model;
    }
    public function addItem($id, $title, $description, $price, $picture, $category_id) {
        $model = [
            'IsSuccess' => true,
            'Message' => []
        ];
        $item_obj = new ItemModel($id, $title, $description, $price, $picture, $category_id, "");
        $new_item = [
            ITEM_ID => $id,
            ITEM_TITLE => $title,
            ITEM_DESCRIPTION => $description,
            ITEM_PRICE => $price,
            ITEM_PICTURE => $picture,
            ITEM_CATEGORY_ID => $category_id
        ];
        $error = $item_obj->getErrors();
        if(count($error)>0){
            $model['IsSuccess'] = false;
            $model['Message'] = $error;
        }
        else{
            $this->flatDb->insert('items.txt', $new_item);
            echo "Added";
        }
        return $model;
    }
    public function updateItem($id, $title, $description, $price, $picture, $category_id) {
        $model = [
            'IsSuccess' => true,
            'Message' => []
        ];
        $item_obj = new ItemModel($id, $title, $description, $price, $picture, $category_id, "");
        $new_item = [
            ITEM_ID => $id,
            ITEM_TITLE => $title,
            ITEM_DESCRIPTION => $description,
            ITEM_PRICE => $price,
            ITEM_PICTURE => $picture,
            ITEM_CATEGORY_ID => $category_id
        ];
        $error = $item_obj->getErrors();
        if(count($error)>0){
            $model['IsSuccess'] = false;
            $model['Message'] = $error;
        }
        else{
            $this->flatDb->updateSetWhere('items.txt', $new_item, new SimpleWhereClause(ITEM_ID, '=', $id));
        }
        return $model;
    }
    public function deleteItem($id) {
        try{
            $this->flatDb->deleteWhere('items.txt', new SimpleWhereClause(ITEM_ID, '=', $id));
            return true;
        }
        catch(Exception $e){
            return false;
        }
    }
    public function getItemById($id) {
        $item = $this->flatDb->selectUnique('items.txt', ITEM_ID, $id);
        return $item;
    }
    public function getAllItems() {
        $model = $this->flatDb->selectAll('items.txt');
        return $model;
    }
    public function getAllItemsByCategoryId($id) {
        $items = $this->flatDb->selectWhere('items.txt', new SimpleWhereClause(ITEM_CATEGORY_ID, '=', $id));
        return $items;
    }
    public function deleteAllItemsByCategoryId($id) {
        $items = $this->flatDb->deleteWhere('items.txt', new SimpleWhereClause(ITEM_CATEGORY_ID, '=', $id));
        return $items;
    }
    public function findItems($keyword) {
        $compClause = new OrWhereClause();
        $compClause->add(new LikeWhereClause(ITEM_TITLE, '%'. $keyword .'%'));
        $items = $this->flatDb->selectWhere('items.txt', $compClause);
        return $items;
    }
}