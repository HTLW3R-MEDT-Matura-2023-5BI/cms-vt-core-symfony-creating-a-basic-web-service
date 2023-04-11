# CMS-VT | CORE | Symfony - Creating a basic web service

## User Story 1
_As an ADMINISTRATOR I want to be able to add new time machine resources to the database, so that the public can query for them later_

### Acceptance Criteria

-   A model for a time machine entry exists
    -   Name
    -   Resource URL
-   A controller and according route which stores a new entry exists
    -   The controller accepts POST requests
    -   The controller accepts a JSON object as parameter which is auto converted to a time machine entry
-   An authentication check is not mandatory at this point in time.
### Solution
**Entity Entry**
````
$ php bin/console make:entity

Class name of the entity to create or update:
> TimeMachineEntry

New property name (press <return> to stop adding fields):
> name

Field type (enter ? to see all types) [string]:
> string

Field length [255]:
> 255

Can this field be null in the database (nullable) (yes/no) [no]:
> no

New property name (press <return> to stop adding fields):
> resourceURL

Field type (enter ? to see all types) [string]:
> string

Field length [255]:
> 255

Can this field be null in the database (nullable) (yes/no) [no]:
> no

New property name (press <return> to stop adding fields):
>
````

**Controller**
Constructor -> initalizing JSON-Encoder
````
public function __construct()  
{  
  // https://symfony.com/doc/current/components/serializer.html#usage  
  $encoders = [new JsonEncoder()];  
  $normalizers = [new ObjectNormalizer()];  
  $this->serializer = new Serializer($normalizers, $encoders);  
}
````
POST Function to create new TimeMachine entries from JSON Request
````
#[Route('/new', name: 'app_time_machine_post', methods: ['POST'])]  
public function post(Request $request, TimeMachineRepository $timeMachineRepository): Response  
{  
  //Decode $request data to an associative array
  $data = json_decode(  
	  $request->getContent(),
	  true  
  );  
  
  //Create a TimeMachine entry using the denormalize method from the serializer object
  $timeMachine = $this->serializer->denormalize($data, TimeMachineEntry::class);  
  
  //Save the TimeMachine Object using the Repositoy with a dependency injection
  $timeMachineRepository->save($timeMachine, true);  
  
 //Returning a Response to inform the client about the status (200 OK) 
 return new Response('Time Machine created', 200, array('Content-Type' => 'text/plain;charset=UTF-8'));  
}
````

## User Story 2
_As an ADMINISTRATOR I want to add test entries to the persistence layer, so that I can test querying entries later._

### Acceptance Criteria

-   Two demonstration entries exist in the database
    -   XKCD Comic – Kill Hitler: [https://xkcd.com/1063/](https://xkcd.com/1063/)
    -   YouTube Song – Time Machine: [https://www.youtube.com/watch?v=8zwEnNJumQ4](https://www.youtube.com/watch?v=8zwEnNJumQ4)
 
### Solution
**Adding Data Fixtures**
In the namespace ``namespace App\DataFixtures;`` add the fixtures you want to persist:
````
class AppFixtures extends Fixture  
{  
  public function load(ObjectManager $manager)  
  {  
	   $timeMachineData = array(  
		   array('name' => 'XKCD Comic – Kill Hitler', 'resourceURL' => 'https://xkcd.com/1063/'),  
           array('name' => 'YouTube Song – Time Machine', 'resourceURL' => 'https://www.youtube.com/watch?v=8zwEnNJumQ4')  
	   );  
 
	   foreach ($timeMachineData as $data) {  
	       $timeMachine = new TimeMachine();  
           $timeMachine->setName($data['name']);  
           $timeMachine->setResourceURL($data['resourceURL']);  
           $manager->persist($timeMachine);  
       }  
  
       $manager->flush();  
  }  
}
````

## User Story 3
_As a USER I want to retrieve a random entry from the database, so that I can start a philosophical discussion on it._

### Acceptance Criteria

-   A controller and according route which retrieve a random time machine entry exists
-   The response is returned as JSON object
### Solution
**Getting a random Time Machine**
Create a method in the Repository which selects a random Time Machine
````
public function getRandomTimeMachine(): object  
{  
    $TimeMachineIds = $this->createQueryBuilder('tm')->select('tm.id')->getQuery()->getSingleColumnResult();  
    if (count($TimeMachineIds) == 0) {  
        return new Object_();  
    }  
  
    $randomQuoteId = $TimeMachineIds[array_rand($TimeMachineIds)];  
  
    return $this->createQueryBuilder('tm')  
    ->where('tm.id = :id')  
    ->setParameter('id', $randomQuoteId)  
    ->getQuery()  
    ->execute()[0];  
}
````
**Call the Method ``getRandomTimeMachine()`` in the Controller**
````
#[Route('/random', name: 'app_time_machine_random', methods: ['GET'])]  
public function randomTimeMachine(TimeMachineRepository $timeMachineRepository): Response {  
    $timeMachines = $timeMachineRepository->getRandomTimeMachine();  
    $jsonContent = $this->serializer->serialize($timeMachines, 'json');  
    return new Response($jsonContent, 200, array('Content-Type' => 'application/json;charset=UTF-8'));  
}
````
