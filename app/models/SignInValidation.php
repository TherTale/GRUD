<?php

class MyValidation extends Validation 
{
public function initialize() 
	{
		$users = new User();
		$users = User::Find();
		foreach($users as $user){
    $arrayUserName[] = $user->name;
}
if(empty($arrayUserName)==true){
  $arrayUserName[]=null;
}

	$this->add(
	'email',new InclusionIn(
                [
                	'message' => 'Пользователь с таким именем не зарегистрирован',
                    "domain" => $arrayUserName
                ]
            )
        );

	}
}
?>