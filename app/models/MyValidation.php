<?php
use Phalcon\Validation; 
use Phalcon\Validation\Validator\Email; 
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\ExclusionIn as ExclusionIn;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Validation\Validator\StringLength as StringLength;



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
if(empty($arrayUserName)==true){
  $arrayUserName[]=null;
}
$this->add(
 'name', new StringLength(
  array(
    'max' => 25,
     'min' => 6,
   'messageMaximum' => 'Имя пользователя должно быть меньше 25 символов',
   'messageMinimum' => 'Имя пользователя должно быть больше 6 символов' )
    )
     );
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
if(empty($arrayUserEmail)==true){
  $arrayUserEmail[]=null;
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
   'message' => 'Некоректный Email адрес')
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
'password', new  RegexValidator(
array(
'message' => 'В пароле допускаются только цифры и латинские символы',
'pattern' => '/[a-zA-Z0-9]{6,50}/')
)
);
$this->add(
 'password', new StringLength(
  array(
    'max' => 25,
     'min' => 6,
   'messageMaximum' => 'Пароль должен быть меньше 25 символов',
   'messageMinimum' => 'Пароль должен быть больше 6 символов' )
    )
     );
$this->add(
 'password', new PresenceOf(
  array(
   'message' => 'Пароль не введен' )
    )
     );

$this->add(
'confirmPassword', new Confirmation(
array(
'message' => 'Пароли не совпадают',
'with' => 'password')
)
);

    }
}
?>
