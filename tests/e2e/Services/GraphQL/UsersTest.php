<?php

namespace Tests\E2E\Services\GraphQL;

use Tests\E2E\Client;
use Tests\E2E\Scopes\ProjectCustom;
use Tests\E2E\Scopes\Scope;
use Tests\E2E\Scopes\SideServer;
use Utopia\Database\ID;
use Utopia\Database\Query;

class UsersTest extends Scope
{
    use ProjectCustom;
    use SideServer;
    use Base;

    public function testCreateUser(): array
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$CREATE_USER);
        $email = 'users.service@example.com';
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => ID::unique(),
                'email' => $email,
                'password' => 'password',
                'name' => 'Project User',
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);

        $user = $user['body']['data']['usersCreate'];
        $this->assertEquals('Project User', $user['name']);
        $this->assertEquals($email, $user['email']);

        return $user;
    }

    public function testGetUsers()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$GET_USERS);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'queries' => [
                    'limit(100)',
                    'offset(0)',
                ],
            ]
        ];

        $users = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($users['body']['data']);
        $this->assertArrayNotHasKey('errors', $users['body']);
        $this->assertIsArray($users['body']['data']['usersList']);
        $this->assertGreaterThan(0, \count($users['body']['data']['usersList']));
    }

    public function testGetUser()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$GET_USER);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersGet']);
        $this->assertEquals($this->getUser()['$id'], $user['body']['data']['usersGet']['_id']);
    }

    public function testGetUserPreferences()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$GET_USER_PREFERENCES);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersGetPrefs']);
    }

    public function testGetUserSessions()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$GET_USER_SESSIONS);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersListSessions']);
    }

    public function testGetUserMemberships()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$GET_USER_MEMBERSHIPS);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersListMemberships']);
    }

    public function testGetUserLogs()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$GET_USER_LOGS);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersListLogs']);
    }

    public function testUpdateUserStatus()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$UPDATE_USER_STATUS);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
                'status' => true,
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersUpdateStatus']);
        $this->assertEquals($this->getUser()['$id'], $user['body']['data']['usersUpdateStatus']['_id']);
    }

    public function testUpdateUserEmailVerification()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$UPDATE_USER_EMAIL_VERIFICATION);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
                'emailVerification' => true,
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersUpdateEmailVerification']);
    }

    public function testUpdateUserPhoneVerification()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$UPDATE_USER_PHONE_VERIFICATION);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
                'phoneVerification' => true,
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersUpdatePhoneVerification']);
        $this->assertEquals($this->getUser()['$id'], $user['body']['data']['usersUpdatePhoneVerification']['_id']);
    }

    public function testUpdateUserName()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$UPDATE_USER_NAME);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
                'name' => 'Updated Name',
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersUpdateName']);
        $this->assertEquals('Updated Name', $user['body']['data']['usersUpdateName']['name']);
    }

    public function testUpdateUserEmail()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$UPDATE_USER_EMAIL);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
                'email' => 'newemail@appwrite.io'
            ],
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersUpdateEmail']);
        $this->assertEquals('newemail@appwrite.io', $user['body']['data']['usersUpdateEmail']['email']);
    }

    public function testUpdateUserPassword()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$UPDATE_USER_PASSWORD);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
                'password' => 'newpassword'
            ],
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersUpdatePassword']);
    }

    public function testUpdateUserPhone()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$UPDATE_USER_PHONE);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
                'number' => '+123456789'
            ],
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersUpdatePhone']);
        $this->assertEquals('+123456789', $user['body']['data']['usersUpdatePhone']['phone']);
    }

    public function testUpdateUserPrefs()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$UPDATE_USER_PREFS);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
                'prefs' => [
                    'key' => 'value'
                ]
            ],
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsArray($user['body']['data']);
        $this->assertArrayNotHasKey('errors', $user['body']);
        $this->assertIsArray($user['body']['data']['usersUpdatePrefs']);
        $this->assertEquals('{"key":"value"}', $user['body']['data']['usersUpdatePrefs']['data']);
    }

    public function testDeleteUserSessions()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$DELETE_USER_SESSIONS);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsNotArray($user['body']);
        $this->assertEquals(204, $user['headers']['status-code']);

        unset(self::$user[$this->getProject()['$id']]);
        $this->getUser();
    }

    public function testDeleteUserSession()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$DELETE_USER_SESSION);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
                'sessionId' => $this->getUser()['sessionId'],
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsNotArray($user['body']);
        $this->assertEquals(204, $user['headers']['status-code']);

        unset(self::$user[$this->getProject()['$id']]);
        $this->getUser();
    }

    public function testDeleteUser()
    {
        $projectId = $this->getProject()['$id'];
        $query = $this->getQuery(self::$DELETE_USER);
        $graphQLPayload = [
            'query' => $query,
            'variables' => [
                'userId' => $this->getUser()['$id'],
            ]
        ];

        $user = $this->client->call(Client::METHOD_POST, '/graphql', \array_merge([
            'content-type' => 'application/json',
            'x-appwrite-project' => $projectId,
        ], $this->getHeaders()), $graphQLPayload);

        $this->assertIsNotArray($user['body']);
        $this->assertEquals(204, $user['headers']['status-code']);
    }
}
