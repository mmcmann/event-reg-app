<?php

$authenticate = function ($app) {
    return function () use ($app) {
        if (!isset($_SESSION['uid'])) {
            $_SESSION['urlRedirect'] = $app->request()->getPathInfo();
            $response['status']    = "error";
            $response['message']   = "You have to be logged in";
            jsonWrite(200, $response);
        }
    };
};



$app->get('/session', function() {
    $session = AppUtil::getSession();
    $response["uid"]   = $session['uid'];
    $response["email"] = $session['email'];
    $response["name"]  = $session['name'];
    jsonWrite(200, $response);
});

$app->post('/login', function() use ($app) {
    require_once 'PasswordHash.php';
    
    $response = array();
    
    $db = new DBUtil();

    $loginData = json_decode($app->request->getBody());
    $email     = $loginData->customer->email;
    $password  = $loginData->customer->password;
    
    // Validate and sanitize data ----------------------------------------------
    // A la http://code.tutsplus.com/tutorials/sanitize-and-validate-data-with-php-filters--net-2595
    
    if (!isset($email) || strlen(trim($email)) <= 0) {
        $response['status'] = "error";
        $response['message'] = 'Please enter your email address.';
    } else {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['status'] = "error";
            $response['message'] = "$email is not a valid email address.";
        }
    }

    if (!isset($password) || strlen(trim($password)) <= 0) {
        $response['status'] = "error";
        $response['message'] = 'Please enter your password.';
    }


    if (isset($response['status']) && $response['status'] == "error") {
        // echo error json and stop the app
        $app = \Slim\Slim::getInstance();
        jsonWrite(200, $response);
        $app->stop();
    }

    // Select and validate ----------------------------------------------

    $sqlStr = "SELECT *
               FROM customer
               WHERE email=:email";
    // $db->select()
    $user = $db->selectOne($sqlStr, array(':email' => $email), PDO::FETCH_ASSOC);
    
    if ($user != NULL) {
        if (PasswordHash::check_password($user['password'], $password)) {
            $response['status']    = "success";
            $response['message']   = 'Logged in successfully.';
            $response['name']      = $user['fname'] . ' ' . $user['lname'];
            $response['uid']       = $user['uid'];
            $response['email']     = $user['email'];
            $response['createdAt'] = $user['created'];
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['uid']       = $user['uid'];
            $_SESSION['email']     = $email;
            $_SESSION['name']      = $response['name'];
        } else {
            $response['status']    = "error";
            $response['message']   = 'Login failed. Incorrect credentials';
        }
    } else {
        $response['status'] = "error";
        $response['message'] = 'No user is registered with supplied credentials.';
    }
    $db = NULL;
    jsonWrite(200, $response);
});

$app->post('/signUp', function() use ($app) {
    require_once 'PasswordHash.php';

    $response = array();
    $loginData = json_decode($app->request->getBody());

    $fname        = $loginData->customer->fname;
    $lname        = $loginData->customer->lname;
    $phone        = $loginData->customer->phone;
    $email        = $loginData->customer->email;
    $terms        = $loginData->customer->terms;
    $password     = $loginData->customer->password;

    // Validate and sanitize data ----------------------------------------------
    // TODO: Abstract all this stuff into a sanitize function.

    if (!isset($fname) || strlen(trim($fname)) <= 0) {
        $response['status'] = "error";
        $response['message'] = 'Please enter your first name.';
    } else {
        $fname = filter_var($fname, FILTER_SANITIZE_STRING);
        if (strlen(trim($fname)) <= 0) {
            $response['status'] = "error";
            $response['message'] = 'Please enter your first name.';
        }
    }

    if (!isset($lname) || strlen(trim($lname)) <= 0) {
        $response['status'] = "error";
        $response['message'] = 'Please enter your last name.';
    } else {
        $lname = filter_var($lname, FILTER_SANITIZE_STRING);
        if (strlen(trim($lname)) <= 0) {
            $response['status'] = "error";
            $response['message'] = 'Please enter your last name.';
        }
    }

    if (isset($phone) && strlen(trim($phone)) > 0) {
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        if (strlen(trim($phone)) < 10) {
            $response['status'] = "error";
            $response['message'] = 'Please enter a valid phone number.';
        }
    }

    if (!isset($email) || strlen(trim($email)) <= 0) {
        $response['status'] = "error";
        $response['message'] = 'Please enter your email address.';
    } else {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['status'] = "error";
            $response['message'] = "$email is not a valid email address.";
        }
    }

    // TODO: Add sanitizing logic to password field.
    if (!isset($password) || strlen(trim($password)) <= 0) {
        $response['status'] = "error";
        $response['message'] = 'Please enter your password.';
    }

    if (!filter_var($terms, FILTER_VALIDATE_BOOLEAN)) {
        $response['status'] = "error";
        $response['message'] = "You must accept the terms and conditions to register.";
    }

    if (isset($response['status']) && $response['status'] == "error") {
        // echo error json and stop the app
        $app = \Slim\Slim::getInstance();
        jsonWrite(200, $response);
        $app->stop();
    }

    $db = new DBUtil();

    $sqlStr = "SELECT 1
               FROM customer
               WHERE email=:email";
    $userExists = $db->selectOne($sqlStr, array(':email' => $email));

    if(!$userExists){
        $passwordHash = PasswordHash::hash($password);
        $tableName = "customer";
        $insertData = array(
                'fname' => $fname,
                'lname' => $lname,
                'phone' => $phone,
                'email' => $email,
                'password' => $passwordHash,
                'accept_terms' => 1
            );
        $result = $db->insert($tableName, $insertData);
        
        if ($result != NULL) {
            $response["status"]  = "success";
            $response["message"] = "User account created successfully";
            $response["uid"]     = $result;
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['uid']     = $response["uid"];
            $_SESSION['name']    = $fname . ' ' . $lname;
            $_SESSION['email']   = $email;
            jsonWrite(200, $response);
        } else {
            $response["status"]  = "error";
            $response["message"] = "Failed to create customer. Please try again";
            jsonWrite(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "An user with the provided email exists!";
        jsonWrite(201, $response);
    }
    $db = NULL;
});

$app->get('/logout', function() {
    AppUtil::destroySession();
    $response["status"] = "info";
    $response["message"] = "Logged out successfully";
    jsonWrite(200, $response);
});