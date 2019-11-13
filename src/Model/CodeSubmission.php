<?php declare(strict_types=1);


namespace App\Model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CodeSubmission
 * @package App\Model
 */
class CodeSubmission
{
    /**
     * @var string|null
     *
     * @Assert\NotNull(message="invalid code")
     * @Assert\Regex("/^\d{12}$/", message="invalid code")
     */
    private $code;

    /**
     * @var string|null
     *
     * @Assert\NotNull(message="invalid name")
     * @Assert\Regex("/^[a-zA-Z0-9\.\-_ ]{1,20}$/", message="invalid name")
     */
    private $nick;

    /**
     * @param Request $request
     * @return CodeSubmission
     */
    public static function fromPost(Request $request): CodeSubmission
    {
        return (new CodeSubmission())
            ->setCode($request->request->get("code"))
            ->setNick($request->request->get("nick"));
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return CodeSubmission
     */
    public function setCode(?string $code): CodeSubmission
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * @param string $nick
     * @return CodeSubmission
     */
    public function setNick(?string $nick): CodeSubmission
    {
        $this->nick = $nick;
        return $this;
    }
}