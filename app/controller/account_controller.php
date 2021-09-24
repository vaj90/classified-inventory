<?php
class AccountController extends Controller {
    private $db_manager;
    function __construct() {
        parent::__construct();
        parent::loadModel('account');
        $this->db_manager = new DbManager(DBHOST,DBUSER,DBPASS,DBNAME);
    }
    public function index() {
        $this->isAuthenticated();
        $model = $this->db_manager->getAllMembers();
        $this->view->model= $model;
        $this->view->render('account/index');
    }   
    public function login($args) {
        $this->view->render('account/login');
    }
    public function loginsubmit() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $redirect = URL . 'account/login';
        $model = $this->db_manager->getMemberByUsernameAndPassword($username,$password);
        if($model!=null){
            Session::set("LoggedIn", true);
            $redirect = URL . 'dashboard';  
            header('Location: '. $redirect);
            exit;
        }
        header('Location: '  . URL . 'account/login');
    }
    public function add(){
        $this->isAuthenticated();
        $model = [
            'id' => 0,
            'first_name' => '',
            'last_name' => '',
            'username' => '',
            'password' => '',
            'email' => ''
        ];
        $this->view->title= "Add account";
        $this->view->task= "add";
        $this->view->model= $model;
        $this->view->render('account/addedit');
    }
    public function edit($id){
        $this->isAuthenticated();
        $model = $this->db_manager->getMemberById($id);
        $model = [
            'id' => $model["id"],
            'first_name' => $model["first_name"],
            'last_name' => $model["last_name"],
            'username' => $model["username"],
            'password' => $model["password"],
            'email' => $model["email"]
        ];
        $this->view->title= "Modify account";
        $this->view->task= "edit";
        $this->view->model= $model;
        $this->view->render('account/addedit');
    }
    public function delete($id){
        $this->isAuthenticated();
        $deleteMember =  $this->db_manager->deleteMember($id);
        if($deleteMember){
            header('Location: '  . URL . 'account');
            exit;
        }
        $result = [
            'IsSuccess' => false,
            'Message' => []
        ];
        $result['Message'][] = "Some error occurs while deleting category";
        $this->view->link = "category/list";
        $this->view->title = "Error in deleting category";
        $this->view->model = $result['Message'];
        $this->view->render('shared/output');
    }
    public function savesubmit(){
        $this->isAuthenticated();
        $result = [
            'IsSuccess' => true,
            'Message' => []
        ];
        $id = $_POST['id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirmpassword'];
        $task = $_POST['task'];
        if($password == $confirmpassword){
            if($task=="add"){
                $result = $this->db_manager->addMember($firstname, $lastname, $username, $password, $email);
            }
            else{
                $result = $this->db_manager->updateMember($id, $firstname, $lastname, $username, $password, $email);
            }
        }
        else{
            $result['IsSuccess'] = false;
            $model['Message'][] = "Some error occurs, Error: password didn't match";
        }
        if($result['IsSuccess']){
            header('Location: '  . URL . 'account');
            exit;
        }
        $this->view->link = $task == "add" ? "account/add" : "account/edit/" . $id;
        $this->view->title = "Error in " . ($task == "add" ? "creating" : "modifying") . " category";
        $this->view->model = $result['Message'];
        $this->view->render('shared/output');
    }
    public function logout() {
        Session::destroy(); 
        Session::unset("LoggedIn");
        header('Location: '  . URL . 'account/login');
        exit;
    }
}