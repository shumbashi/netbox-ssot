<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Helper;

/**
 * Class RandomStringGenerator
 *
 * Helper class for generating random strings with configurable character sets and length.
 * Supports numbers, lowercase, and uppercase letters in various combinations.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Helper
 */
class RandomStringGenerator
{
    /**
     * Combined character set used for string generation.
     *
     * @var string
     */
    protected $charSet = '';

    /**
     * Lowercase letters character set.
     *
     * @var string
     */
    protected $lowerCharSet = 'qwertyuioplkjhgfdsazxcvbnm';

    /**
     * Numbers character set.
     *
     * @var string
     */
    protected $numbersCharSet = '0123456789';

    /**
     * Length of generated random string.
     *
     * @var int
     */
    protected $stringLength = 10;

    /**
     * Uppercase letters character set.
     *
     * @var string
     */
    protected $upperCharSet = 'QWERTYUIOPLKJHGFDSAZXCVBNM';

    /**
     * Flag for including lowercase letters in random string.
     *
     * @var bool
     */
    protected $useLowercase = true;

    /**
     * Flag for including numbers in random string.
     *
     * @var bool
     */
    protected $useNumbers = true;

    /**
     * Flag for including uppercase letters in random string.
     *
     * @var bool
     */
    protected $useUppercase = false;

    /**
     * RandomStringGenerator constructor.
     *
     * Initializes generator with provided configuration parameters.
     *
     * @param int|null $stringLength Length of the generated string (default: 10)
     * @param bool $useNumbers Whether to include numbers (default: true)
     * @param bool $useLowercase Whether to include lowercase letters (default: true)
     * @param bool $useUppercase Whether to include uppercase letters (default: false)
     */
    public function __construct($stringLength = null, $useNumbers = true, $useLowercase = true, $useUppercase = false)
    {
        $this->setLength($stringLength);

        $this->setUseNumbers($useNumbers);
        $this->setUseUppercase($useUppercase);
        $this->setUseLowercase($useLowercase);
    }

    /**
     * Set the length of the generated random string.
     *
     * @param int|null $stringLength Desired length of the random string
     * @return void
     */
    public function setLength($stringLength)
    {
        if ((int)$stringLength > 0)
        {
            $this->stringLength = (int)$stringLength;
        }
    }

    /**
     * Set whether to include numbers in the generated string.
     *
     * @param bool $value Whether to include numbers
     * @return void
     */
    public function setUseNumbers($value = true)
    {
        if (is_bool($value))
        {
            $this->useNumbers = $value;
        }

        $this->loadCharSet();
    }

    /**
     * Update the character set based on current configuration.
     *
     * @return void
     */
    public function loadCharSet()
    {
        $this->charSet = '';
        if ($this->useNumbers)
        {
            $this->charSet .= $this->numbersCharSet;
        }
        if ($this->useLowercase)
        {
            $this->charSet .= $this->lowerCharSet;
        }
        if ($this->useUppercase)
        {
            $this->charSet .= $this->upperCharSet;
        }

        //use default set if someone disables all sets
        if ($this->charSet === '')
        {
            $this->charSet = $this->numbersCharSet . $this->lowerCharSet;
        }
    }

    /**
     * Set whether to include uppercase letters in the generated string.
     *
     * @param bool $value Whether to include uppercase letters
     * @return void
     */
    public function setUseUppercase($value = true)
    {
        if (is_bool($value))
        {
            $this->useUppercase = $value;
        }

        $this->loadCharSet();
    }

    /**
     * Set whether to include lowercase letters in the generated string.
     *
     * @param bool $value Whether to include lowercase letters
     * @return void
     */
    public function setUseLowercase($value = true)
    {
        if (is_bool($value))
        {
            $this->useLowercase = $value;
        }

        $this->loadCharSet();
    }

    /**
     * Generate a random string based on the configured settings.
     *
     * @param string|null $prefix Optional prefix to add to the generated string
     * @return string The generated random string
     */
    public function genRandomString(?string $prefix = null)
    {
        $randString = '';
        while (strlen($randString) < $this->stringLength)
        {
            $number     = rand(0, strlen($this->charSet) - 1);
            $randString .= $this->charSet[$number];
        }

        if ($prefix)
        {
            $randString = $prefix . '_' . $randString;
        }

        return $randString;
    }
}
