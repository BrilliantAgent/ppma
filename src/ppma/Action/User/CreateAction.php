<?php


namespace ppma\Action\User;


use Nocarrier\Hal;
use ppma\Action\ActionImpl;
use ppma\Config;
use ppma\Service\Model\Exception\EmailIsRequiredException;
use ppma\Service\Model\Exception\PasswordIsRequiredException;
use ppma\Service\Model\Exception\PasswordNeedsToBeALengthOf64Exception;
use ppma\Service\Model\Exception\UsernameAlreadyExistsException;
use ppma\Service\Model\Exception\UsernameIsRequiredException;
use ppma\Service\Model\UserService;
use ppma\Service\Request\HttpFoundationServiceImpl;
use ppma\Service\SmtpService;

class CreateAction extends ActionImpl
{

    const USERNAME_IS_REQUIRED    = 1;
    const USERNAME_ALREADY_EXISTS = 2;
    const EMAIL_IS_REQUIRED       = 3;
    const PASSWORD_IS_REQUIRED    = 4;
    const PASSWORD_IS_INVALID     = 5;

    /**
     * @var HttpFoundationServiceImpl
     */
    protected $request;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var SmtpService
     */
    protected $mail;

    /**
     * @return array
     */
    public function services()
    {
        return array_merge(parent::services(), [
            array_merge(Config::get('services.model.user'), ['target' => 'userService']),
            array_merge(Config::get('services.request'),    ['target' => 'request']),
            array_merge(Config::get('services.smtp'),       ['target' => 'mail'])
        ]);
    }

    /**
     * @return void
     */
    public function run()
    {
        // create hal object
        $hal    = new Hal('/users');

        try
        {
            // get attributes
            $username = $this->request->post('username');
            $email    = $this->request->post('email');
            $password = $this->request->post('password');

            // create user
            $model = $this->userService->create($username, $email, $password);

            // add apikey to hal
            $hal->setData([
                'apikey' => $model->apikey
            ]);

            // get uri of user
            $uri = sprintf('/users/%s', $model->slug);

            // add link to user profile
            $hal->addLink('user', $uri);

            // add created resource to header
            $header = [
                'Content-Type' => 'application/hal+json',
                'Location'     => $uri
            ];

            // send message to user
            try {
                $this->mail->sendMessage(
                    [$model->email => $model->username],
                    'Your account for ppma was created',
                    sprintf("Hi %s,\n\nblah blah blah.\n\n---\n\npow, pow, pow!", $model->username)
                );
            } catch (\Exception $e) { }

            // send response
            $this->response->send($hal->asJson(), 201, $header);

        // no username
        } catch (UsernameIsRequiredException $e) {
            $hal->setData([
                'code'    => self::USERNAME_IS_REQUIRED,
                'message' => '`username` is required'
            ]);

            $header = ['Content-Type' => 'application/hal+json'];
            $this->response->send($hal->asJson(), 400, $header);

        } catch (UsernameAlreadyExistsException $e) {
            $hal->setData([
                'code'    => self::USERNAME_ALREADY_EXISTS,
                'message' => '`username` already exists in database'
            ]);

            $header = ['Content-Type' => 'application/hal+json'];
            $this->response->send($hal->asJson(), 400, $header);

        // no email
        } catch (EmailIsRequiredException $e) {
            $hal->setData([
                'code'    => self::EMAIL_IS_REQUIRED,
                'message' => '`email` is required'
            ]);

            $header = ['Content-Type' => 'application/hal+json'];
            $this->response->send($hal->asJson(), 400, $header);

        // no password
        } catch (PasswordIsRequiredException $e) {
            $hal->setData([
                'code'    => self::PASSWORD_IS_REQUIRED,
                'message' => '`password` is required'
            ]);

            $header = ['Content-Type' => 'application/hal+json'];
            $this->response->send($hal->asJson(), 400, $header);

        // no password
        } catch (PasswordNeedsToBeALengthOf64Exception $e) {
            $hal->setData([
                'code'    => self::PASSWORD_IS_INVALID,
                'message' => '`password` is not a sha256 hash'
            ]);

            $header = ['Content-Type' => 'application/hal+json'];
            $this->response->send($hal->asJson(), 400, $header);

        // unknown error
        } catch (\Exception $e) {
            $hal->setData([
                'code'    => 999,
                'message' => $e->getMessage()
            ]);

            $header = ['Content-Type' => 'application/hal+json'];
            $this->response->send($hal->asJson(), 400, $header);
        }
    }

} 