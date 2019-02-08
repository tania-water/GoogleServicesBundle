<?php

namespace Ibtikar\GoogleServicesBundle\APIResponse\Device;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Mahmoud Mostafa <mahmoud.mostafa@ibtikar.net.sa>
 */
class Register extends Device
{

    /**
     * @Assert\Type("scalar")
     * @Assert\Length(max=3072)
     * @Assert\NotBlank
     */
    public $token;

}
