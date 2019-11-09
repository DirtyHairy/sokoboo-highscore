<?php


namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScoreEntryRepository")
 * @ORM\Table(
 *     uniqueConstraints={@UniqueConstraint(name="code_nick_unqiue", columns={"nick", "code"})},
 *     indexes={
 *         @Index(name="level_idx", columns={"level"}),
 *         @Index(name="nick_idx", columns={"nick"}),
 *         @index(name="moves_seconds_timestamp_idx", columns={"moves", "seconds", "timestamp"})
 *     }
 * )
 */
class ScoreEntry
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id = -1;

    /** @var int
     *
     * @ORM\Column(type="integer")
     */
    private $level = 0;

    /** @var int
     *
     * @ORM\Column(type="integer")
     */
    private $moves = 0;

    /** @var int
     *
     * @ORM\Column(type="integer")
     */
    private $seconds = 0;

    /** @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $nick = "";

    /** @var string
     *
     * @ORM\Column(type="string", length=12)
     */
    private $code = "";

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $timestamp = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $ip = null;

    /** @var string|null
     *
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private $session = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ScoreEntry
     */
    public function setId(int $id): ScoreEntry
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return ScoreEntry
     */
    public function setLevel(int $level): ScoreEntry
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return int
     */
    public function getMoves(): int
    {
        return $this->moves;
    }

    /**
     * @param int $moves
     * @return ScoreEntry
     */
    public function setMoves(int $moves): ScoreEntry
    {
        $this->moves = $moves;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeconds(): int
    {
        return $this->seconds;
    }

    /**
     * @param int $seconds
     * @return ScoreEntry
     */
    public function setSeconds(int $seconds): ScoreEntry
    {
        $this->seconds = $seconds;
        return $this;
    }

    /**
     * @return string
     */
    public function getNick(): string
    {
        return $this->nick;
    }

    /**
     * @param string $nick
     * @return ScoreEntry
     */
    public function setNick(string $nick): ScoreEntry
    {
        $this->nick = $nick;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return ScoreEntry
     */
    public function setCode(string $code): ScoreEntry
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     * @return ScoreEntry
     */
    public function setTimestamp(int $timestamp): ScoreEntry
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return ScoreEntry
     */
    public function setIp(?string $ip): ScoreEntry
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSession(): ?string
    {
        return $this->session;
    }

    /**
     * @param string|null $session
     * @return ScoreEntry
     */
    public function setSession(?string $session): ScoreEntry
    {
        $this->session = $session;
        return $this;
    }
}