<?php
namespace In2code\Userprofile\Property;

class PrivacySettings
{
    /**
     * @var string
     */
    protected $propertyName = '';

    /**
     * @var bool
     */
    protected $public = false;

    /**
     * @var bool
     */
    protected $authenticated = false;

    /**
     * @var bool
     */
    protected $group = false;

    /**
     * @param string $propertyName
     * @param bool $public
     * @param bool $authenticated
     * @param bool $group
     */
    public function __construct(
        string $propertyName,
        bool $public = false,
        bool $authenticated = false,
        bool $group = false
    ) {
        $this->propertyName = $propertyName;
        $this->public = $public;
        $this->authenticated = $authenticated;
        $this->group = $group;
    }

    /**
     * @param string $propertyName
     * @param mixed $settings
     * @return self
     */
    public static function createFromArray(string $propertyName, $settings)
    {
        return new static(
            $propertyName,
            $settings['public'] ?: false,
            $settings['authenticated'] ?: false,
            $settings['groups'] ?: false
        );
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @return bool
     */
    public function getPublic(): bool
    {
        return $this->public;
    }

    /**
     * @return bool
     */
    public function getAuthenticated(): bool
    {
        return $this->authenticated;
    }

    /**
     * @return bool
     */
    public function getGroup(): bool
    {
        return $this->group;
    }
}
