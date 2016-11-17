<?php

namespace Ibtikar\GoogleServicesBundle\APIResponse\Device;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Mahmoud Mostafa <mahmoud.mostafa@ibtikar.net.sa>
 */
class SetIOSBadge extends Device
{

    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=0)
     * @Assert\NotBlank
     */
    public $badgeNumber;

}
