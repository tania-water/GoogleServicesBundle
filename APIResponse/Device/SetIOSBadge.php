<?php

namespace Ibtikar\GoogleServicesBundle\APIResponse\Device;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Mahmoud Mostafa <mahmoud.mostafa@ibtikar.net.sa>
 */
class SetIOSBadge
{

    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=0)
     * @Assert\NotBlank
     */
    public $badgeNumber;

    /**
     * @Assert\Type("scalar")
     * @Assert\Length(max=190)
     * @Assert\NotBlank
     */
    public $identifier;

}
