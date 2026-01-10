<?php

declare(strict_types=1);

namespace Codediesel\RestApi;

use Codediesel\Model\Factory\Users;
use Codediesel\RestApi\Users\Login;
use Codediesel\Library\Api\RestApiAbstract;
use Codediesel\Library\Api\RestApiInterface;
use Codediesel\Library\Api\TokenFactory\ValidateDecodeToken;
use Codediesel\Library\External\EmailHandler\Sender;
use Codediesel\RestApi\Users\User;

/**
 * Authenticate class for managing user authentication and token validation.
 * 
 * This class provides methods to create authentication tokens and validate them.
 * It extends the RestApiAbstract class and implements the RestApiInterface.
 */
class Authenticate  extends RestApiAbstract implements RestApiInterface
{

    /**
     * Create a new authentication token.
     * @throws \Exception
     */
    public function create(array $data): array
    {
        if (isset($data['username'], $data['password'])) {
            $login = new Login($data['username'], $data['password']);
            return [
                'token' => $login->authentication(),
                'user_id' => $login->getUserId(),
            ];
        }
        throw new \Exception("Something went wrong");
    }

    /**
     * Validate a token and retrieve user session details.
     * @throws \Exception
     */
    public function retrieve(array $data): array
    {

        if (isset($data['token'])) {
            $validate = new ValidateDecodeToken($data['token']);
            return [
                'isValid' => $validate->validate(),
            ];
        }
        // if the token is not set, we can assume it's invalid
        return [
            'isValid' => false,
        ];
    }

    /**
     * Delete a resource (not implemented).
     * 
     */
    public function delete(array $data): array
    {
        return [];
    }

    /**
     * Update a resource (e.g., refresh token).
     */
    public function update(array $data): array
    {
        return [];
    }

    /**
     * Retrieve history (not implemented).
     * 
     */
    public function history(array $data): array
    {
        return [];
    }

    /** 
     * this method is used only to request a password change, it will send email to a user
     * once the user clicks on the link in the email, it will redirect to the password change page
     * Request a password change.
     * @throws \Exception
     */
    public function requestPasswordChange(array $data): array
    {


        if (isset($data['email'])) {

            //check if the email existing in the database
            $fetchUser = new User();
            $user = $fetchUser->findUserByEmail($data['email']);
            
            if (empty($user)) {
                throw new \Exception("Email not found");
            }
            //$date
            $date = new \DateTime();
            $date->modify('+2 hour');
            $hashUserDetails = base64_encode(json_encode(['user_id' => $user['id'] , 'date_limit' => $date->format('Y-m-d H:i:s')]));

            //hash
            $passHash = password_hash($hashUserDetails, PASSWORD_DEFAULT);
            //save on db against user table
            $user = new Users();
            $user->update([
                'hash_pass_request' => $passHash
            ] , ['id' => $user['id']]);

            $urlReset = $_ENV['APP_URL'] . '/password-change?t=' . $passHash;
            $body = sprintf(
                'Hello %s, <br> <br> You have requested to change your password. <br> <br> Please click on the link below to change your password: 
                <br> <br> <a href="%s">Change Password</a>
                if link is not clickable, please copy and paste the link in your browser: %s<br> <br>
                If you did not request this, please ignore this email. <br> <br> Thank you! <br> <br> Best regards, <br> Tico Url',
                $user['full_name'],
                $urlReset , $urlReset

            );
            //$data['email']
            $emailHandler = new Sender();
            $emailHandler->setTo($data['email']);
            $emailHandler->setToName('User');
            $emailHandler->setSubject('Password Change Request');
            $emailHandler->setBody($body);
            $emailHandler->setFrom($_ENV['EMAIL_FROM']);
            $emailHandler->setFromName($_ENV['EMAIL_FROM_NAME']);
            $emailHandler->setTemplateID('6971855');
            $result = $emailHandler->send();


            if ($result) {
                return [
                    'message' => $result,
                ];
            }
        }
        throw new \Exception("Something went wrong");
    }


    /**
     * Change the password for a user.
     * @throws \Exception
     */
    public function ChangePassword(array $data): array
    {
    
        // Assuming you have a method to handle the password change
        $password = $data['new-password'] ?? null;
        $user_id = $data['user_id'] ?? null;

        $user = new \Codediesel\RestApi\Users\User();
        $userDetails = $user->findUserById((string)$user_id);
        if(empty($userDetails)){ 
            throw new \Exception("User not found");
        }

        if($user->updateUserPassword((string) $user_id, $password)){
            // Password change successful
            return [
                'message' => 'Password changed successfully.',
            ];
        } else {
            // Password change failed
            throw new \Exception("Password change failed");
        }
    }
}
