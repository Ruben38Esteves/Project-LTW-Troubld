<?php 
    function fetch_user_by_username($username) {
        require_once('connect.php');
        require_once('classes.php');
        require_once('departmentsSelection.php');
        $db = getDBConnection();
    
        $stmt = $db->prepare("SELECT users.id, users.name_, users.username, users.password_hash, users.birthday, users.email, agent.id AS agent_id, administrator.id AS administrator_id
        FROM users
        LEFT JOIN agent ON users.id = agent.id
        LEFT JOIN administrator ON agent.id = administrator_id
        WHERE users.username = ?");
        $stmt->execute([$username]);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $user = new user();
            $user->id = $result['id'];
            $user->name = $result['name_'];
            $user->username = $result['username'];
            $user->birthday = $result['birthday'];
            $user->email = $result['email'];  
            if ($result['administrator_id'] != null) {
                $user->hierarchy = 2;
            } else if ($result['agent_id'] != null) {
                $user->hierarchy = 1;
            } else {
                $user->hierarchy = 0;
            }
            $department = new department();
            $department = departmentSelection($user->id);
            $user->department = $department->id;
            return $user;
        }
        


        return null;
    }
    function get_username_by_id($user_id){
        require_once('connect.php');
        require_once('classes.php');
        $db = getDBConnection();
        
        $stmt = $db->prepare("SELECT users.username
        FROM users
        WHERE users.id = ?");
        $stmt->execute([$user_id]);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return ($result['username']);
        }

        return null;
    }
?>

