<?php

namespace Ibtikar\GoogleServicesBundle\APIResponse\Device;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Mahmoud Mostafa <mahmoud.mostafa@ibtikar.net.sa>
 */
class Register
{

    /**
     * @Assert\Type("scalar")
     * @Assert\Length(max=3072)
     * @Assert\NotBlank
     */
    public $token;

    /**
     * @Assert\Type("scalar")
     * @Assert\Length(max=255)
     * @Assert\NotBlank
     */
    public $identifier;

    /**
     * @Assert\Type("numeric")
     */
    public $badgeNumber;

}
