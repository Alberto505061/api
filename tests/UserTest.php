<?php


use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    protected $user;

    /**
     *
     * setUp method
     * set Authenticated $user via Passport
     */

    public function setUp()
    {
        parent::setUp();
        $this->user = User::inRandomOrder()->firstOrFail();
        Passport::actingAs($this->user, ['user']);
    }

    /**
     *
     * GET /api/users
     *
     */


//    public function testShowAllUsers()
//    {
//
//
//        $this->json("get", "api/users");
//
//
//        $this->assertResponseOk();
//
//        // assert that each user in the json response has below attributes
//        $this->seeJsonStructure([
//            '*' => [
//                'firstName', 'lastName', 'email', 'created_at', 'updated_at'
//            ]
//        ]);
//
//
//    }

    /**
     *
     * GET /api/users/{id}
     *
     */

//    public function testShowOneUser()
//    {
//        $user = User::inRandomOrder()->firstOrfail();
//        Passport::actingAs($user, ['user']);
//        $this->json("get", "api/users/{$user->id}");
//
//        $this->assertResponseOk();
//        $this->seeJsonStructure(
//            [
//                'firstName',
//                'lastName',
//                'email',
//                'created_at',
//                'updated_at'
//            ]
//        );
//    }

    /**
     *
     * POST /api/users
     *
     */

    public function testCreate()
    {
        $newUser = factory(User::class)->raw();
        $newUser["password"] = "pass";
        $password = $newUser["password"];
        Passport::actingAs(User::inRandomOrder()->firstOrFail(), ['admin']);
        $nbUsers = User::all()->count();

        $this->json("post", "api/users", $newUser);

        unset($newUser["password"]);
        $hashedPassword = User::findOrfail($nbUsers + 1)->password;

        $this->assertResponseStatus(201);
        $this->seeJsonStructure([
            'firstName',
            'lastName',
            'email',
            'status',
            'updated_at',
            'created_at',
            'id'
        ]);

        $this->seeInDatabase("users", $newUser);
        $this->assertTrue(Hash::check($password, $hashedPassword));

    }

    /**
     *
     * POST /api/login
     *
     */
    public function testLogin()
    {
        $user = factory(User::class)->raw();
        $user['password'] = 'pass';
        Passport::actingAs(User::inRandomOrder()->firstOrFail(), ['admin']);
        $this->json("post", "api/users", $user);
        $secret = DB::table('oauth_clients')->where('id', 2)->first()->secret;
        $loginRequest = '
        {
        "client_id" : "2", 
        "client_secret" : "' . $secret . '",
        "grant_type" : "password",
        "password" : "pass",
        "username" : "' . $user['email'] . '"
        }
        ';

        $loginRequest = json_decode($loginRequest, true);


        $this->json('post', 'api/login', $loginRequest);

        $this->assertResponseOk();
        $this->seeJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token',
            'lastname',
            'firstname',
            'mail',
            'id',
            'status'
        ]);


    }


}