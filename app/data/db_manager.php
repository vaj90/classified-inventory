<?php
    class DbManager {
        private $server;
        private $username;
        private $password;
        private $db_name;
        private $db_con;
        public function __construct($server,$username,$password,$dbname){
            $this->server = $server;
            $this->username = $username;
            $this->password = $password;
            $this->db_name = $dbname;
        }
        public function connect(){
            $this->db_con = new mysqli($this->server, $this->username,$this->password,$this->db_name);
        }

        public function close(){
        }

        public function addCategory($title, $description, $status) {
            $this->connect();
            $model = [
                'IsSuccess' => true,
                'Message' => []
            ];
            $category_obj = new CategoryModel(0, $title, $description, $status);
            $error = $category_obj->getErrors();
            if(count($error)>0){
                $model['IsSuccess'] = false;
                $model['Message'] = $error;
            }
            else{
                $query = "INSERT INTO category (`title`, `description`, `status`) values('$title','$description','$status')";
                if(mysqli_query($this->db_con,$query)){
                    $model['IsSuccess'] = true;
                }
                else{
                    $model['IsSuccess'] = false;
                    $model['Message'][] = "Some error occurs, Error: " . mysqli_error($this->db_con);
                }
            }
            $this->close();
            return $model;
        }

        public function updateCategory($id, $title, $description, $status) {
            $this->connect();
            $model = [
                'IsSuccess' => true,
                'Message' => []
            ];
            $category_obj = new CategoryModel($id, $title, $description, $status);
            $error = $category_obj->getErrors();
            if(count($error)>0){
                $model['IsSuccess'] = false;
                $model['Message'] = $error;
            }
            else{
                $query = "Update `category` SET
                `title`='$title',
                `description`='$description',
                `status`='$status'
                WHERE id='$id'";
                if(mysqli_query($this->db_con,$query)){
                    $model['IsSuccess'] = true;
                }
                else{
                    $model['IsSuccess'] = false;
                    $model['Message'][] = "Some error occurs, Error: " . mysqli_error($this->db_con);
                }
            }
            $this->close();
            return $model;
        }

        public function deleteCategory($id) {
            $this->connect();
            $result = $this->deleteAllItemsByCategoryId($id);
            $query = "DELETE FROM category WHERE id='$id'";
            $result = $this->db_con->query($query);
            $this->close();
            return $result;
        }

        public function getCategoryById($id) {
            $this->connect();
            $query = "SELECT * FROM category WHERE id='". $id . "'";
            $result = $this->db_con->query($query);
            $data = $result->fetch_assoc();
            $this->close();
            return $data;
        }

        public function getAllCategories() {
            $this->connect();
            $query = "SELECT * FROM category";
            $data = [];
            $result = mysqli_query($this->db_con,$query);
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            $this->close();
            return $data;
        }

        public function getAllShowCategories() {
            $this->connect();
            $query = "SELECT * FROM category WHERE status='SHOW'";
            $data = [];
            $result = mysqli_query($this->db_con,$query);
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            $this->close();
            return $data;
        }

        public function addItem($title, $description, $price, $image, $cat_id, $status) {
            $this->connect();
            $model = [
                'IsSuccess' => true,
                'Message' => []
            ];
            $item_obj = new ItemModel(0, $title, $description, $price, $image, $cat_id, $status);
            $error = $item_obj->getErrors();
            if(count($error)>0){
                $model['IsSuccess'] = false;
                $model['Message'] = $error;
            }
            else{
                $query = "INSERT INTO items (`title`, `description`, `price`, `image`, `cat_id`, `status`) values('$title', '$description', $price, '$image', $cat_id, '$status')";
                echo $query;
                if(mysqli_query($this->db_con,$query)){
                    $model['IsSuccess'] = true;
                }
                else{
                    $model['IsSuccess'] = false;
                    $model['Message'][] = "Some error occurs, Error: " . mysqli_error($this->db_con);
                }
            }
            $this->close();
            return $model;
        }

        public function updateItem($id, $title, $description, $price, $image, $cat_id, $status) {
            $this->connect();
            $model = [
                'IsSuccess' => true,
                'Message' => []
            ];
            $item_obj = new ItemModel($id, $title, $description, $price, $image, $cat_id, $status);
            $error = $item_obj->getErrors();
            if(count($error)>0){
                $model['IsSuccess'] = false;
                $model['Message'] = $error;
            }
            else{
                //$description = str_replace('"', "",$description);
                //$description = str_replace("'", "",$description);
                $description = mysqli_real_escape_string($this->db_con,$description);
                $query = "Update `items` SET `title`='$title', `description`='$description', `price`=$price, `image`='$image', `cat_id`=$cat_id, `status`='$status' WHERE id='$id'";
                if(mysqli_query($this->db_con,$query)){
                    $model['IsSuccess'] = true;
                }
                else{
                    $model['IsSuccess'] = false;
                    $model['Message'][] = "Some error occurs, Error: " . mysqli_error($this->db_con);
                }
            }
            $this->close();
            return $model;
        }

        public function deleteItem($id) {
            $this->connect();
            $query = "DELETE FROM items WHERE id='$id'";
            $result = $this->db_con->query($query);
            $this->db_con->query("DELETE FROM track WHERE item_id='$id'");
            $this->close();
            return $result;
        }

        public function getItemById($id) {
            $this->connect();
            $query = "SELECT * FROM items WHERE id='$id'";
            $result = $this->db_con->query($query);
            $data = $result->fetch_assoc();
            $this->close();
            return $data;
        }

        public function getAllItems() {
            $this->connect();
            $query = "SELECT * FROM items";
            $data = [];
            $result = mysqli_query($this->db_con,$query);
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            $this->close();
            return $data;
        }

        public function getAllShowItems() {
            $this->connect();
            $query = "SELECT * FROM items WHERE status='SHOW'";
            $data = [];
            $result = mysqli_query($this->db_con,$query);
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            $this->close();
            return $data;
        }

        public function getAllItemsByCategoryId($id) {
            $this->connect();
            $query = "SELECT * FROM items WHERE cat_id='$id'";
            $data = [];
            $result = mysqli_query($this->db_con,$query);
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            $this->close();
            return $data;
        }

        public function getAllShowItemsByCategoryId($id) {
            $this->connect();
            $query = "SELECT * FROM items WHERE cat_id='$id' AND status='SHOW'";
            $data = [];
            $result = mysqli_query($this->db_con,$query);
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            $this->close();
            return $data;
        }

        public function deleteAllItemsByCategoryId($id) {
            $this->connect();
            $query = "DELETE FROM items WHERE cat_id='$id'";
            $result = $this->db_con->query($query);
            $this->close();
            return $result;
        }

        public function findItems($keyword,$cat_id) {
            $this->connect();
            $query = "SELECT * FROM items WHERE (title LIKE '%$keyword%' OR description LIKE '%$keyword%')  AND status='SHOW'";
            if(!empty($cat_id)){
                $query = "SELECT * FROM items WHERE (title LIKE '%$keyword%' OR description LIKE '%$keyword%')  AND status='SHOW' AND cat_id='$cat_id'";
            }
            $data = [];
            $result = mysqli_query($this->db_con,$query);
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            $this->close();
            return $data;
        }

        public function addMember($first_name, $last_name, $username, $password, $email) {
            $this->connect();
            $model = [
                'IsSuccess' => true,
                'Message' => []
            ];
                
            $query = "INSERT INTO members (`first_name`, `last_name`, `username`, `password`, `email`) values('$first_name','$last_name','$username','$password','$email')";
            if(mysqli_query($this->db_con,$query)){
                $model['IsSuccess'] = true;
            }
            else{
                $model['IsSuccess'] = false;
                $model['Message'][] = "Some error occurs, Error: " . mysqli_error($this->db_con);
            }

            return $model;
        }

        public function updateMember($id, $first_name, $last_name, $username, $password, $email){
            $this->connect();
            $model = [
                'IsSuccess' => true,
                'Message' => []
            ];
            $query = "Update `members` SET
            `first_name`='$first_name',
            `last_name`='$last_name',
            `username`='$username',
            `password`='$password',
            `email`='$email'
            WHERE id='$id'";
            if(mysqli_query($this->db_con,$query)){
                $model['IsSuccess'] = true;
            }
            else{
                $model['IsSuccess'] = false;
                $model['Message'][] = "Some error occurs, Error: " . mysqli_error($this->db_con);
            }
            $this->close();
            return $model;
        }

        public function deleteMember($id) {
            $this->connect();
            $query = "DELETE FROM members WHERE id='$id'";
            $result = $this->db_con->query($query);
            $this->close();
            return $result;
        }

        public function getMemberById($id) {
            $this->connect();
            $query = "SELECT * FROM members WHERE id='". $id . "'";
            $result = $this->db_con->query($query);
            $data = $result->fetch_assoc();
            $this->close();
            return $data;
        }

        public function getAllMembers() {
            $this->connect();
            $query = "SELECT * FROM members";
            $data = [];
            $result = mysqli_query($this->db_con,$query);
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            $this->close();
            return $data;
        }
        
        public function getMemberByUsernameAndPassword($username,$password) {
            $this->connect();
            $query = "SELECT * FROM members WHERE username='$username' AND password='$password'";
            $result = $this->db_con->query($query);
            $data = $result->fetch_assoc();
            $this->close();
            return $data;
        }

        public function getAllTrack() {
            $this->connect();
            $query = "SELECT * FROM `track` ORDER BY date_view,count";
            $data = [];
            $result = mysqli_query($this->db_con,$query);

            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            $this->close();
            return $data;
        }


        public function getTrack($id){
            $this->connect();
            $date = date("Y-m-d");

            $query = "SELECT * FROM `track` WHERE item_id='$id' and date_view='$date' LIMIT 1";
            $result = $this->db_con->query($query);
            $data = $result->fetch_assoc();
            $query = "";
            if($data){
                $count =  $data["count"]+1 ;
                $query = "Update `track` SET
                `item_id`='" . $data["item_id"]. "',
                `date_view`='$date',
                `count`='" . $count . "'
                WHERE id='" . $data["id"] . "'";
                $result = mysqli_query($this->db_con,$query);
            }
            else{
                $query = "INSERT INTO track (`item_id`, `date_view`, `count`) values('$id','$date','1')";
                $result = mysqli_query($this->db_con,$query);
            }
            $this->close();
        }
    }
?>