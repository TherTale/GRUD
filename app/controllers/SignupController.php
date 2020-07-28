<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{
    public function indexAction()
    {

    }
	public function registerAction()
    {
    	$user = new User();
        $validation = new MyValidation();
        $this->view->name=null;
         $this->view->email=null;
          $this->view->password=null;
          $this->view->confirmPassword=null;
		#Проверка данных на валидность
       $messages = $validation->validate($_POST); 
       if (count($messages)) {
         foreach ($messages as $message) {
            switch ($message->getField()) {
                 case 'name':
                     $this->view->name=$message->getMessage();
                     break;
                case 'email':
                     $this->view->email=$message->getMessage();
                     break;
                case 'password':
                     $this->view->password=$message->getMessage();
                     break;
                     case 'confirmPassword':
                     $this->view->password=$message->getMessage();
                     break;
                 default:
                     # code...
                     break;
             } 
     }
              return $this->dispatcher->forward(
            [
                'controller' => 'signup',
                'action'     => 'index',
            ]
        );
   }else{

        $user->name = $this->request->getPost()['name'];
        $user->email = $this->request->getPost()['email'];
        $user->password = password_hash($this->request->getPost()['password'], PASSWORD_DEFAULT);
        $success = $user->save();
        if ($success) {
            $this->flash->success(
            'Акаунт создан.Для дальнейшей работы войдите в акаунт'
            );
            return $this->dispatcher->forward(
            [
                'controller' => 'index',
                'action'     => 'index',
            ]
        );
        }else{
            $this->flash->error(
            'Проблема соеденения с базой данных'
            );
            return $this->dispatcher->forward(
            [
                'controller' => 'signup',
                'action'     => 'index',
            ]
        );
        }

    } 



 }
}
