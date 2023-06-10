<?php
    class ticket{
        public $id;
        public $subtitle_;
        public $description_;
        public $department_name_;
        public $status_;
        public $priority_;
        public $department_id;
        public $client_username;
        public $agent_username;
    }

    class department{
        public $id;
        public $name;
        public $description;
        public $tickets = array();
    }

    class user{
        public $id;
        public $name;
        public $username;
        public $birthday;
        public $email;
        public $hierarchy;
        public $department;
    }
    
    class message{
    public $id;
    public $content;
    public $id_client;
    public $time_sent;
}
?>