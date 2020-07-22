<?php
declare(strict_types=1);
use Phalcon\Mvc\Model\Criteria;


use Phalcon\Mvc\Model\Query;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;



class PhonebookController extends ControllerBase
{

    public function indexAction()
    {

		$types = Typephonenumber::find();
		$this->view->types = $types;
		
		$groups = Group::find();
		$this->view->groups = $groups;

    }
  
	public function searchAction()
	{

		
		$currentPage = (int) isset($_GET['page']) ? $_GET['page']:1;


		if($this->request->isPost()){
			$this->persistent->searchParams = $this->request->getPost();
		}
		
		
		$ppls = $this->modelsManager->createBuilder();
		$ppls->from('Phonenumber');
		$ppls->where('People.name LIKE :name:', ['name' => $this->persistent->searchParams['name'].'%']);
		$ppls->andWhere('Phonenumber.phoneNumber LIKE :phone:', ['phone' => $this->persistent->searchParams['phone'].'%']);
		$ppls->andWhere('People.secondName LIKE :lastName:', ['lastName' => $this->persistent->searchParams['lastName'].'%']);
		$ppls->andWhere('People.patronomic LIKE :fatherName:', ['fatherName' => $this->persistent->searchParams['fatherName'].'%']);
		$ppls->andWhere('People.mail LIKE :email:', ['email' => $this->persistent->searchParams['email'].'%']);
		$ppls->andWhere('People.organizationName LIKE :orgName:', ['orgName' => $this->persistent->searchParams['orgName'].'%']);
		$ppls->andWhere('People.city LIKE :city:', ['city' => $this->persistent->searchParams['city'].'%']);
		$ppls->andWhere('People.street LIKE :street:', ['street' => $this->persistent->searchParams['street'].'%']);
		$ppls->andWhere('People.house LIKE :house:', ['house' => $this->persistent->searchParams['house'].'%']);
		$ppls->andWhere('People.apNumber LIKE :ap:', ['ap' => $this->persistent->searchParams['ap'].'%']);
		$ppls->andWhere('People.chosen LIKE :important:', ['important' => $this->persistent->searchParams['important'].'%']);
		if($this->persistent->searchParams['typeNumber']!=0)$ppls->andWhere('Phonenumber.idTypePhoneNumber LIKE :typeNumber:', ['typeNumber' => $this->persistent->searchParams['typeNumber'].'%']);
		
		if (!empty($this->request->getPost()['birth']))$ppls->andWhere('People.birth LIKE \''.$this->persistent->searchParams['birth'].'%\'');
		
		#$ppls->andWhere('People.note LIKE \''.$this->request->getPost()['note'].'%\'');
		$ppls->join('People');
	
		$ppls->getQuery();
		
	



		
		$paginator = new Paginator(
			array(
				"builder" => $ppls,
				"limit" => 5, // Количество записей на страницу
				"page" => $currentPage // Активная страница
			)
		);

		$page = $paginator->Paginate();
		
		
		


		$this->view->page = $page;
		
		$types = Typephonenumber::find();
		$this->view->types = $types;
		
		$groups = Group::find();
		$this->view->groups = $groups;

	}

	public function newAction()
	{

		$types = Typephonenumber::find();
		$this->view->types = $types;
		
		$groups = Group::find();
		$this->view->groups = $groups;
	}

	public function editAction($number)
	{
			
			$phonenumber = Phonenumber::findFirst('phoneNumber = \''.addslashes($number).'\'');
			
			$people = People::findFirst('id = \''.addslashes($phonenumber->idPeople).'\'');
			
			$people->name = addslashes($this->request->getPost()['name']);
			
			$people->secondName = addslashes($this->request->getPost()['secondName']);
			
			$people->patronomic = addslashes($this->request->getPost()['fatherName']);
			$people->mail = addslashes($this->request->getPost()['mail']);
			
			$people->organizationName = addslashes($this->request->getPost()['orgName']);
			$people->city = addslashes($this->request->getPost()['city']);
			$people->street = addslashes($this->request->getPost()['street']);
			
			$people->house = addslashes($this->request->getPost()['house']);
			$people->apNumber =addslashes( $this->request->getPost()['ap']);
			$people->birth = empty($this->request->getPost()['birth']) ? NULL:addslashes($this->request->getPost()['birth']);
			
			
			$people->peoplegroup->delete();
			$success = $people->save();
			if(isset($this->request->getPost()['groups'])){
			
			
		
		
			
			$groups = Group::find([
				'id IN ({letter:array})',
				'bind' => [
				'letter' => $this->request->getPost()['groups']
				]
			]);
			
			$modules = [];
			foreach ($groups as $gr)
				array_push($modules,$gr);
			$people->groups = $modules;
		
		}
			$success = $people->save();
		
		
		
		
		
			
				
		
			
			$phonenumber->phoneNumber = $this->request->getPost()['phone'];
			$phonenumber->idTypePhoneNumber = $this->request->getPost()['typeNumber'];
			
			$success = $phonenumber->save();
			if ($success) {
				echo "Контакт обновлён!";
				echo $this->tag->linkTo("phonebook/search", "Перейти к справочнику");
				
			}else {
				echo "Ошибка: <br/>";
				foreach ($phonenumber->getMessages() as $message) {
					echo $message->getMessage(), "<br/>";
				}
			}


	}

	


	
	

	public function createAction()
	{

			
	$people = new people();
		
		$people->name = addslashes($this->request->getPost()['name']);
		$people->email = addslashes($this->request->getPost()['email']);
		$people->secondName = addslashes($this->request->getPost()['lastName']);
		
		$people->patronomic = addslashes($this->request->getPost()['fatherName']);
		$people->mail = addslashes($this->request->getPost()['email']);
		$people->note = addslashes($this->request->getPost()['note']);
	
		$people->chosen = isset($this->request->getPost()['important'])       ? addslashes($this->request->getPost()['important']) : '0';
		
		
		
		$people->organizationName = addslashes($this->request->getPost()['orgName']);
		$people->city = addslashes($this->request->getPost()['city']);
		$people->street = addslashes($this->request->getPost()['street']);
		
		$people->house = addslashes($this->request->getPost()['house']);
		$people->apNumber = addslashes($this->request->getPost()['ap']);
		
		$people->birth = empty($this->request->getPost()['birth']) ? NULL: addslashes($this->request->getPost()['birth']);
		
		
		if(isset($this->request->getPost()['groups'])){
		$people->peoplegroup = $this->request->getPost()['groups'];
		
	
		
		
		
		
			$group = new Group();
			$group->name = 'Test';
			$groups = Group::find([
				'id IN ({letter:array})',
				'bind' => [
				'letter' => $this->request->getPost()['groups']
				]
			]);
			
			$modules = [];
			foreach ($groups as $gr)
				array_push($modules,$gr);
			$people->groups = $modules;
		
		}
		
		$success = $people->save();
		
		
		
		
		
		
		
		if ($success) {
			
			$phonenumber = new phonenumber();
	
			$phonenumber->phoneNumber = addslashes($this->request->getPost()['phone']);
			$phonenumber->idTypePhoneNumber = addslashes($this->request->getPost()['typeNumber']);
			$phonenumber->idPeople = $people->id;
			$phonenumber->idOperator = 1;
			$success2 = $phonenumber->save();
			if ($success2) {
				echo "Контакт добавлен!";
				echo $this->tag->linkTo("phonebook/search", "Перейти к справочнику");
				echo $this->tag->linkTo("phonebook/new", "Добавить еще контакт");
			}else {
				echo "Ошибка: <br/>";
				foreach ($phonenumber->getMessages() as $message) {
					echo $message->getMessage(), "<br/>";
				}
			}
		} else {
			echo "Ошибка: <br/>";
			foreach ($people->getMessages() as $message) {
				echo $message->getMessage(), "<br/>";
			}
		}

	}

	public function saveAction()
	{
			//save from edit form
	}


	public function deleteAction($number)
	{
		$Phone = Phonenumber::find('phoneNumber = \''.$number.'\'');

		if ($Phone !== false) {
			if ($Phone->delete() === false) {
				echo "К сожалению, мы не можем удалить телефон прямо сейчас: \n";

				$messages = $Phone->getMessages();

				foreach ($messages as $message) {
					echo $message, "\n";
				}
			} else {
				echo $this->tag->linkTo("phonebook/search", "Перейти к справочнику");
				echo 'Телефон был успешно удален!';
			}
		}

	}
}

