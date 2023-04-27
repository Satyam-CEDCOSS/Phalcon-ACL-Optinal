<?php

use MyApp\Listener\Listener;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        // Redirected to View
    }



    public function loginAction()
    {
        if ($_POST['email'] && $_POST['password']) {
            $sql = 'SELECT * FROM Users WHERE email = :email: AND password = :password:';
            $query = $this->modelsManager->createQuery($sql);
            $cars = $query->execute(
                [
                    'email' => $_POST["email"],
                    'password' => $_POST["password"]
                ]
            );
            if (isset($cars[0])) {
                $this->view->message = "success";
                $this->view->name = "Hello " . $cars[0]->name;
                $this->session->set('type', $cars[0]->type);

            } else {
                $this->view->message = "error";
            }
        } else {
            $this->view->message = "error";
        }
    }
}