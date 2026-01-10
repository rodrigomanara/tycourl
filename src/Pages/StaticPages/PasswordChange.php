<?php


namespace Codediesel\Pages\StaticPages;

use Codediesel\Model\Factory\Users;
use Codediesel\Pages\Page;

class PasswordChange extends Page
{


    const URL = '/password-change';

    public function init(): void
    {

        $pass = $this->checkValidRequest();
        if (!$pass) {
            $this->render('password-change.twig', [
                'title' => 'Password Change Failed',
                'error' => 'Failed to change password, Please make another request.',
            ]);
            return;
        }

        $user_id = $pass->user_id;
        $this->render('password-change.twig', [
            'title' => 'Password Change',
            'user_id' => $user_id
        ]);
    }

    private function checkValidRequest()
    {
        $pass = $this->route->get('t');
        $user = new Users();
        $data = $user->fetchUser([
            'hash_pass_request' => $pass
        ]);

        if ($data['error']) return false;
        if (isset($data['error']))
            return $data;

        return false;

    }

}