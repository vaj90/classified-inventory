<?php
class ItemController extends Controller {
    private $db_manager;
    function __construct() {
        parent::__construct();
        // loadModel is a function that allows the program to reference the category model class
        parent::loadModel('item');
        /*  db manager use as main brain of the project where data are stored in the database*/
        $this->db_manager = new DbManager(DBHOST,DBUSER,DBPASS,DBNAME);
    }
    public function index() {
        $model = $this->db_manager->getAllShowItems();
        if(isset($_GET['category'])){
            $model =$this->db_manager->getAllShowItemsByCategoryId(intVal($_GET['category']));
        }
        $page_number = 1;
        $total_item = count($model);
        $page_count = $total_item < 3 ? 1 : ceil($total_item/3);
        if(isset($_GET['page'])){
            $page_number = intVal($_GET['page']);
        }
        $this->view->pagination = [
            'page_number' => $page_number,
            'page_count' => $page_count,
            'total_item' => $total_item
        ];
        $this->view->model = $model;
        $this->view->db_manager=$this->db_manager;
        $this->view->categories = $this->db_manager->getAllShowCategories();
        $this->view->render('item/index');
    }
    public function info($id){
        $model = $this->db_manager->getItemById($id);
        $this->view->model= $model;
        $this->view->db_manager=$this->db_manager;
        $this->view->category = $this->db_manager->getCategoryById($model["cat_id"]);
        $this->view->render('item/info');
    }
    public function list(){
        $this->isAuthenticated();
        $model = $this->db_manager->getAllItems();
        if(isset($_GET['category'])){
            $model =$this->db_manager->getAllItemsByCategoryId(intVal($_GET['category']));
        }
        $page_number = 1;
        $total_item = count($model);
        $page_count = $total_item < 3 ? 1 : ceil($total_item/3);
        if(isset($_GET['page'])){
            $page_number = intVal($_GET['page']);
        }
        $this->view->pagination = [
            'page_number' => $page_number,
            'page_count' => $page_count,
            'total_item' => $total_item
        ];
        $this->view->model= $model;
        $this->view->db_manager=$this->db_manager;
        $this->view->categories = $this->db_manager->getAllCategories();
        $this->view->render('item/list');
    }
    public function add(){
        $this->isAuthenticated();
        $model = [
            'id' => 0,
            'title' => '',
            'description' => '',
            'price' => '',
            'picture' => '',
            'cat_id' => '',
            'status' => 'SHOW'
        ];
        $this->view->title= "Add new item";
        $this->view->task= "add";
        $this->view->model= $model;
        $this->view->categories = $this->db_manager->getAllCategories();
        $this->view->render('item/addedit');
    }
    public function edit($id){
        $this->isAuthenticated();
        $data = $this->db_manager->getItemById($id);
        $model = [
            'id' => $data["id"],
            'title' => $data["title"],
            'description' => $data["description"],
            'price' => $data["price"],
            'image' => $data["image"],
            'cat_id' => $data["cat_id"],
            'status'=> $data["status"]
        ];
        $this->view->title= "Modify category";
        $this->view->task= "edit";
        $this->view->model= $model;
        $this->view->categories = $this->db_manager->getAllCategories();
        $this->view->render('item/addedit');
    }
    public function delete($id){
        $this->isAuthenticated();
        global $root_dir;
        $data = $this->db_manager->getItemById($id);
        $deleteItem = $this->db_manager->deleteItem($id);
        if($deleteItem){
            $old_image = "$root_dir/" . $data["image"];
            unlink($old_image);
            header('Location: '  . URL . 'item/list');
            exit;
        }
        $result = [
            'IsSuccess' => false,
            'Message' => []
        ];
        $result['Message'][] = "Some error occurs while deleting item";
        $this->view->link = "item/list";
        $this->view->title = "Error in deleting item";
        $this->view->model = $result['Message'];
        $this->view->render('shared/output');
    }
    public function savesubmit(){
        $this->isAuthenticated();
        global $config;
        global $root_dir;
        $upload_dir = $config['IMAGES_PATH'];
        $errors = []; 
        $file_ext_allowed = ['jpeg','jpg','png']; 
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = floatval($_POST['price']);
        $cat_id = intval($_POST['cat_id']);
        $status = $_POST['status'];
        $task = $_POST['task'];

        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_temporary_name  = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_error = $_FILES['image']['error'];
        $tmp = explode('.',$file_name);
        $file_ext = end($tmp);

        $result = [
            'IsSuccess' => false,
            'Message' => []
        ];
        if($file_name=="" && $task=="add"){
            $errors[] = "No file selected.";
        }
        else{
            if($file_name!=""){
                if (!in_array($file_ext, $file_ext_allowed)) {
                    $errors[] = "This file extension is not allowed. Please upload " . implode(" ",$file_ext_allowed) . " file";
                }
                if ($file_size > 2000000) {
                    $errors[] = "File exceeds maximum size (2MB)";
                }
            }
        }
        if (empty($errors)) {
            $old_image ="";
            $file_destination = "";
            $image = "";
            if($file_name!=""){
                $file_new_name = uniqid('',true). "."."$file_ext";
                $file_destination = $upload_dir . $file_name;
                $image =  substr(str_replace("\\","/", $file_destination),1);
                $file_destination =  $root_dir . $file_destination;
            }
            if($task=="add"){
                $result = $this->db_manager->addItem($title,$description,$price,$image,$cat_id,$status);
            }
            else{
                $data = $this->db_manager->getItemById($id);
                $old_image = $data["image"];
                if($image == ""){
                    $image = $old_image;
                }
                $result = $this->db_manager->updateItem($id,$title,$description,$price,$image,$cat_id,$status);
            }
            if($result['IsSuccess']){
                if($task=="add"){
                    $is_uploaded = move_uploaded_file($file_temporary_name, $file_destination);
                    if (!$is_uploaded) {
                        $result['IsSuccess'] = false;
                        $result['Message'][] = "An error occurred. Please contact the administrator.";
                    }
                }
                else{
                    $is_uploaded = true;
                    if($old_image!=$image){
                        $old_image = "$root_dir/$old_image";
                        unlink($old_image);
                        $is_uploaded = move_uploaded_file($file_temporary_name, $file_destination);
                        if (!$is_uploaded) {
                            $result['IsSuccess'] = false;
                            $result['Message'][] = "An error occurred. Please contact the administrator.";
                        }
                    }
                }
                if($is_uploaded){
                    header('Location: '  . URL . 'item/list');
                    exit;
                }
            }
        } else {
            foreach ($errors as $error) {
                $result['Message'][]= $error ;
            }
        }
        $this->view->link = $task == "add" ? "item/add" : "item/edit/" . $id;
        $this->view->title = "Error in " . ($task == "add" ? "creating" : "modifying") . " item";
        $this->view->model = $result['Message'];
        $this->view->render('shared/output');
    }
}