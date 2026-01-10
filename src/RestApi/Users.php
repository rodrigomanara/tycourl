<?php
declare(strict_types=1);

namespace Codediesel\RestApi;

use Codediesel\Exception\DatabaseErrorException;
use Codediesel\Exception\DataNotFoundException;
use Codediesel\Library\Api\RestApiAbstract;
use Codediesel\Library\Api\RestApiInterface;
use Codediesel\Model\Factory\Users as UsersFActory;
use Codediesel\Type\RoleType;


/**
 * 
 * Users class for managing user-related operations.
 * 
 * This class provides methods to create, retrieve, update, delete, and fetch history of users.
 * It extends the RestApiAbstract class and implements the RestApiInterface.
 */
class Users extends RestApiAbstract implements RestApiInterface
{

    /**
     * 
     * Creates a new user in the system.
     * 
     * @param array $data User data including full_name, email, and password.
     * @return array The created user data without the password.
     * @throws \Exception If user creation fails or an error occurs.
     */
    public function create(array $data): array
    {
        // Create a new user using the UsersFactory with the provided data.
        $fetch = (new UsersFActory())->createUser([
            'full_name' => $data['full_name'],
            'username' => $data['email'],
            'password' => password_hash($data['password'], HASH_HMAC), // Hash the password for security.
            'role' => $data['role'] ?? RoleType::ANONYMOUS ,  // Assign a default role of USER.
        ]);

        // Check if the creation failed and throw an exception if necessary.
        if (isset($fetch[0]) && $fetch[0] === false)
            throw new DataNotFoundException("Failed, User not created");
        
        // Check if there was an error during the creation process.
        if (isset($fetch['error']))
            throw new DatabaseErrorException($fetch['error']);

        // Remove the password from the response for security reasons.
        unset($fetch['password']);
        return $fetch;
    }
     
    /**
     * 
      * Retrieves a user's data by their ID.
     * 
     * @param array $data Contains the user ID to fetch.
     * @return array The retrieved user data without the password.
     * @throws \Exception If an error occurs during retrieval.
     */
    public function retrieve(array $data): array
    {

        // Fetch the user data using the UsersFactory with the provided ID.
        $fetch = (new UsersFActory())->fetchUser([
           'id' => $data['id']
        ]);
        // Remove the password from the response for security reasons.
        unset($fetch['password']);
        return $fetch;
    }

    /**
     * 
      * Deletes a user from the system.
     * 
     * @param array $data Data required for deletion (e.g., user ID).
     * @return array An empty array as a placeholder for now.
     */
    public function delete(array $data): array
    {
        return [];
    }

    /**
     * 
     * Updates a user's data in the system.
     * 
     * @param array $data Data to update the user (e.g., ID and fields to modify).
     * @return array An empty array as a placeholder for now.
     */
    public function update(array $data): array
    {
        return [];
    }

    /**
     * 
     * @OA\Get(
     *    tags={"User"},
     *    path="/users/history",
     *    @OA\Response(response="200", description="Success"),
     *    @OA\Response(response="400", description="Bad Request"),
     *    @OA\Response(response="404", description="Not Found"),
     *    @OA\Response(response="500", description="Internal Server Error"),
     *    @OA\Parameter(
     *       name="id", in="query", required=true, description="The unique identifier of the user whose history is being retrieved."
     *    ),
     * )   
     * Retrieves the history of a user.
     * 
     * @param array $data Data required to fetch the user's history (e.g., user ID).
     * @return array An empty array as a placeholder for now.
     */
    public function history(array $data): array
    {
        return [];
    }
}