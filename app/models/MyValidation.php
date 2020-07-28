<?php
use Phalcon\Validation; 
use Phalcon\Validation\Validator\Email; 
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\ExclusionIn as ExclusionIn;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Regex as RegexValidator;


class MyValidation extends Validation 
{ 
	public function initialize() 
	{ 
		$users = new User();
		//Проверка имени пользователя
		$users = User::Find();
		foreach($users as $user){
    $arrayUserName[] = $user->name;
}

$this->add(
	 'name', new PresenceOf(
	  array(
	   'message' => 'Имя пользователя не введено' ) 
	) 
	);
$this->add(
	'name',new ExclusionIn(
                [
                	'message' => 'Пользователь с таким именем уже зарегистрирован',
                    "domain" => $arrayUserName
                ]
            )
        );

//Проверка Email 
$users = User::Find();
foreach($users as $user){
    $arrayUserEmail[] = $user->email;
}
$this->add(
	'email',new ExclusionIn(
                [
                	'message' => 'Пользователь с таким Email адресом уже зарегистрирован',
                    "domain" => $arrayUserEmail
                ]
            )
        );
$this->add(
 'email', new Email(
  array(
   'message' => 'Неправильно введен Email' )
    )
     );
$this->add(
 'email', new PresenceOf(
  array(
   'message' => 'Email не введен' ) 
) 
);

//Проверка пароля
$this->add(
'confirmPassword', new Confirmation(
array(
'message' => 'Пароли не совпадают',
'with' => 'password')
)
);

$this->add(
'password', new  RegexValidator(
array(
'message' => 'Неправильный вид пароля',
'pattern' => '/[a-zA-Z0-9]{6,50}/')
)
);
$this->add(
 'password', new PresenceOf(
  array(
   'message' => 'Пароль не введен' )
    )
     );

    }
}
?>
