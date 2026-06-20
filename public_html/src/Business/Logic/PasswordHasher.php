<?PHP

namespace src\Business\Logic;

class PasswordHasher
{
    private const ALGORITHM = 'argon2id';
    private const OPTIONS = array(
        'memory_cost' => 65536,
        'time_cost' => 4,
        'threads' => 1,
    );

    public static function hash(string $password): string
    {
        self::assertArgon2idSupport();
        return \password_hash($password, self::ALGORITHM, self::OPTIONS);
    }

    public static function verify(string $password, string $hash): bool
    {
        if($hash === '')
            return false;

        return \password_verify($password, $hash);
    }

    public static function needsRehash(string $hash): bool
    {
        self::assertArgon2idSupport();
        return \password_needs_rehash($hash, self::ALGORITHM, self::OPTIONS);
    }

    public static function legacySha256SaltHash(string $password, string $salt): string
    {
        return \hash('sha256', $salt . \hash('sha256', $password));
    }

    public static function verifyLegacySha256Salt(string $password, string $hash, string $salt): bool
    {
        return \hash_equals($hash, self::legacySha256SaltHash($password, $salt));
    }

    private static function assertArgon2idSupport(): void
    {
        if(!\in_array(self::ALGORITHM, \password_algos(), true))
            throw new \RuntimeException('PHP password hashing must be compiled with Argon2id support.');
    }
}
