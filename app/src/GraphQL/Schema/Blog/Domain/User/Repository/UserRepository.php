<?php


namespace App\GraphQL\Schema\Blog\Domain\Repository\User;


use App\GraphQL\Schema\Blog\Domain\Repository\User\UserData;
use App\GraphQL\Schema\Blog\Domain\Repository\DataRepository;
use Overblog\DataLoader\Option;
use Overblog\PromiseAdapter\Adapter\WebonyxGraphQLSyncPromiseAdapter;

class UserRepository extends DataRepository
{

    public function __construct(WebonyxGraphQLSyncPromiseAdapter $promiseFactory, Option $options = null)
    {
        parent::__construct($this->loadByKey(), $promiseFactory, $options);
    }


    /**
     * @param Integer $userId
     * @return UserData|null
     */
    protected function fetchById($userId) {
        $data = $this->fetchAll();
        return in_array($data[$userId]) ? $data[$userId] : null;
    }


    /**
     * @param String $userEmail
     * @return UserData|null
     */
    protected function fetchByEmail($userEmail) {
        $res = array_filter($this->fetchAll(), function($v, $k) use ($userEmail){
            return $v->email === $userEmail;
        }, ARRAY_FILTER_USE_BOTH);
        return count($res) > 0 ? $res[0] : null;
    }


    public function fetchAll ()
    {
        $results = [];
        $users =  json_decode(file_get_contents(realpath(__DIR__.'/../Data/Users.json')), true); // don't do this in real life, file system is usually not really fast
        foreach ($users as $userId => $user) {
            $results[$userId] = new UserData($user);
        }
        return $results;
    }
}